<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\DeletedItemModel;
use App\Models\ItemLogModel;

class Userdashboard extends BaseController
{
    public function index()
    {
        $this->checkLogin('user');

        $model = new ItemModel();
        $deletedModel = new DeletedItemModel();

        date_default_timezone_set('Asia/Manila');
        $today = date('Y-m-d');
        $warningDays = 10;

        $updatedItems = [];
<<<<<<< HEAD
        $items = $model->orderBy('product_id', 'ASC')->findAll();
=======
        // Use FIFO sorting by default
        $items = $model->db->query("
            SELECT *,
                CASE 
                    WHEN expiration_date IS NULL THEN 3
                    WHEN expiration_date < CURDATE() THEN 4
                    WHEN expiration_date = CURDATE() THEN 0
                    WHEN expiration_date <= DATE_ADD(CURDATE(), INTERVAL 10 DAY) THEN 1
                    ELSE 2
                END AS expiry_priority,
                CASE 
                    WHEN variation_label IS NOT NULL THEN CONCAT(name, ' — ', variation_label, ' (', product_id, ')')
                    ELSE CONCAT(name, ' (', product_id, ')')
                END AS display_label
            FROM items
            WHERE status != 'manually deleted'
            ORDER BY 
                expiry_priority ASC,
                expiration_date ASC,
                id ASC
        ")->getResultArray();
>>>>>>> 9540bbc6c32afc140d67be9ea08283a106b5b29b

        foreach ($items as $item) {
            if (in_array($item['status'], ['manually deleted', 'auto deleted'])) {
                continue;
            }

            if (empty($item['expiration_date'])) {
                $item['status'] = $item['status'] ?? 'unknown';
                $item['status_label'] = '❓ Unknown';
                $item['days_left'] = null;
                $updatedItems[] = $item;
                continue;
            }

            $expirationDate = $item['expiration_date'];
            $daysLeft = (int) floor((strtotime($expirationDate) - strtotime($today)) / (60 * 60 * 24));

            if ($daysLeft < 0) {
                $status = 'expired';
                $status_label = "❌ Expired (" . abs($daysLeft) . " days ago)";
            } elseif ($daysLeft <= $warningDays) {
                $status = 'expiring soon';
                $status_label = "⚠️ Expiring Soon ({$daysLeft} days left)";
            } else {
                $status = 'active';
                $status_label = "✅ Active ({$daysLeft} days left)";
            }

            if (!isset($item['status']) || $item['status'] !== $status) {
                $model->update($item['id'], ['status' => $status]);
            }

            $item['status_label'] = $status_label;
            $item['days_left'] = $daysLeft;

            // ✅ AUTO-DELETE LOGIC
            if ($status === 'expired' && ($item['auto_delete'] ?? 0) == 1 && $item['status'] !== 'manually deleted') {
                $exists = $deletedModel->where('product_id', $item['product_id'])->first();
                if (!$exists) {
                    $deletedModel->insert([
                        'product_id' => $item['product_id'],
                        'name' => $item['name'],
                        'category' => $item['category'] ?? null,
                        'subcategory' => $item['subcategory'] ?? null,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'] ?? 0.00,
                        'expiration_date' => $item['expiration_date'],
                        'barcode' => $item['barcode'] ?? null,
                        'auto_delete' => $item['auto_delete'] ?? 0,
                        'status' => 'expired',
                        'deleted_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                
                // Do not remove from the active items table
            }

            $updatedItems[] = $item;
        }

        $data['items'] = $updatedItems;

        return view('user/dashboard', $data);
    }



    public function add()
    {
        $this->checkLogin();
        return view('user/dashboard/add');
    }

    public function store()
    {
        $this->checkLogin();
        helper(['form', 'filesystem']);
        $itemModel = new ItemModel();
        $file = $this->request->getFile('bulk_file');

        // If a file is uploaded (CSV, XLSX, XLS)
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getClientExtension());

            // Use vendor autoload consistently:
            require_once ROOTPATH . 'vendor/autoload.php';

            $rows = [];
            if (in_array($ext, ['xlsx', 'xls'])) {
                // Excel: use PhpSpreadsheet
                if ($ext === 'xlsx') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                }

                $spreadsheet = $reader->load($file->getTempName());
                $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                // toArray with associative columns (A, B, C). We'll map by index below.
                // Convert to zero-indexed numeric rows for simplicity:
                $rowsNumeric = [];
                foreach ($rows as $r) {
                    $rowsNumeric[] = array_values($r);
                }
                $rows = $rowsNumeric;
            } else {
                // CSV: use fopen + fgetcsv to correctly parse quotes/newlines
                $tmp = $file->getTempName();
                if (($handle = fopen($tmp, 'r')) !== false) {
                    while (($data = fgetcsv($handle, 0, ',')) !== false) {
                        // skip completely empty rows
                        if (count(array_filter($data, fn($c) => $c !== '')) === 0)
                            continue;
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            }

            // Remove header row if present (common case)
            if (!empty($rows)) {
                // optional: detect header by non-numeric product id or explicit check
                $header = $rows[0];
                // If header looks like strings (product_id/name), drop it
                $isHeader = false;
                if (isset($header[0]) && preg_match('/product|id|name/i', implode(' ', $header))) {
                    $isHeader = true;
                }
                if ($isHeader) {
                    array_shift($rows);
                }
            }

            $count = 0;
            $skipped = 0;

            foreach ($rows as $row) {
                $product_id = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $quantity = (int) ($row[2] ?? 0);
                $price = (float) ($row[3] ?? 0);
                $expiration_date = trim($row[4] ?? '');
                $barcode = trim($row[5] ?? '');
                $category = trim($row[6] ?? '');
                $subcategory = trim($row[7] ?? '');

                if ($product_id === '' || $name === '')
                    continue;

                if ($itemModel->where('product_id', $product_id)->first()) {
                    $skipped++;
                    continue;
                }

                $auto_delete = 0;
                if (strtolower($category) === 'food') {
                    $auto_delete = 1;
                } elseif (strtolower($category) === 'non-food' && strtolower($subcategory) === 'expirable') {
                    $auto_delete = 1;
                }

                if (strtolower($category) === 'non-food' && strtolower($subcategory) === 'non-expirable') {
                    $expiration_date = null;
                }

                $data = [
                    'product_id' => $product_id,
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'expiration_date' => $expiration_date ?: null,
                    'barcode' => $barcode,
                    'category' => $category,
                    'subcategory' => $subcategory ?: null,
                    'auto_delete' => $auto_delete,
                    'status' => 'active',
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $itemModel->insert($data);
                $count++;
            }

            $msg = "$count items uploaded successfully.";
            if ($skipped > 0)
                $msg .= " $skipped duplicate Product IDs were skipped.";
            return redirect()->to(site_url('items'))->with('success', $msg);
        }

        // No file => single manual add
        $product_id = $this->request->getPost('product_id');

        if ($itemModel->where('product_id', $product_id)->first()) {
            return redirect()->back()->with('error', 'Product ID already exists. Please use a unique one.');
        }

        $rules = [
            'product_id' => 'required',
            'name' => 'required|min_length[2]',
            'quantity' => 'required|numeric|greater_than[0]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'category' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $category = $this->request->getPost('category');
        $subcategory = $this->request->getPost('subcategory');
        $expiration_date = $this->request->getPost('expiration_date');
        $auto_delete = 0;

        if (strtolower($category) === 'food' || strtolower($subcategory) === 'expirable') {
            $auto_delete = 1;
        }

        $data = [
            'product_id' => $product_id,
            'name' => $this->request->getPost('name'),
            'quantity' => $this->request->getPost('quantity'),
            'price' => $this->request->getPost('price'),
            'expiration_date' => $expiration_date ?: null,
            'barcode' => $this->request->getPost('barcode'),
            'category' => $category,
            'subcategory' => $subcategory ?: null,
            'auto_delete' => $auto_delete,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $itemModel->insert($data);
        return redirect()->to('user/dashboard')->with('success', 'Item added successfully!');
    }

    public function edit($id)
    {
        $this->checkLogin();
        $model = new ItemModel();
        $item = $model->find($id);

        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        return view('user/dashboard/edit', ['item' => $item]);
    }

    public function update($id)
    {
        $this->checkLogin();
        helper(['form']);

        // expiration_date may be nullable so don't force it here; validate if provided
        $rules = [
            'product_id' => 'required',
            'name' => 'required|min_length[2]',
            'quantity' => 'required|numeric|greater_than[0]',
            'price' => 'required|decimal|greater_than_equal_to[0]',
            'category' => 'required',
        ];

        // if expiration_date provided, validate format
        $exp = $this->request->getPost('expiration_date');
        if (!empty($exp)) {
            $rules['expiration_date'] = 'valid_date';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ItemModel();
        $logModel = new ItemLogModel();

        $oldData = $model->find($id);

        $newData = [
            'product_id' => $this->request->getPost('product_id'),
            'name' => $this->request->getPost('name'),
            'quantity' => $this->request->getPost('quantity'),
            'price' => $this->request->getPost('price'),
            'expiration_date' => $this->request->getPost('expiration_date') ?: null,
            'barcode' => $this->request->getPost('barcode'),
            'category' => $this->request->getPost('category'),
            'subcategory' => $this->request->getPost('subcategory') ?: null,
            'auto_delete' => $this->request->getPost('auto_delete') ? 1 : 0,
        ];

        $model->update($id, $newData);
        return redirect()->to('user/dashboard')->with('success', 'Item updated successfully (changes logged)!');
    }

    public function expired()
    {
        $this->checkLogin();
        $itemModel = new ItemModel();
        $deletedModel = new DeletedItemModel();
        $today = date('Y-m-d');

        $expiredItems = $itemModel
            ->where('expiration_date <', $today)
            ->orderBy('expiration_date', 'ASC')
            ->findAll();

        $deletedItems = $deletedModel
            ->orderBy('deleted_at', 'DESC')
            ->findAll();

        foreach ($expiredItems as &$item) {
            if (!empty($item['expiration_date'])) {
                $expDate = new \DateTime($item['expiration_date']);
                $todayDate = new \DateTime($today);
                $interval = $todayDate->diff($expDate);
                $days = $interval->days;
                $item['expired_label'] = ($expDate < $todayDate)
                    ? "Expired {$days} days ago"
                    : "Expiring in {$days} days";
            } else {
                $item['expired_label'] = "No expiration date";
            }
            $item['status'] = 'expired';
        }

        foreach ($deletedItems as &$dItem) {
            if ($dItem['status'] === 'auto deleted') {
                $dItem['expired_label'] = "Auto deleted item";
            } elseif ($dItem['status'] === 'manually deleted') {
                $dItem['expired_label'] = "Manually deleted item";
            } else {
                $dItem['expired_label'] = ucfirst($dItem['status'] ?? 'deleted');
            }
        }

        return view('user/dashboard/expired_items', [
            'items' => $expiredItems,
            'deletedItems' => $deletedItems
        ]);
    }

    public function expiringSoon()
    {
        $this->checkLogin();
        $model = new ItemModel();
        $today = date('Y-m-d');
        $warningDays = 10;
        $futureDate = date('Y-m-d', strtotime("+$warningDays days"));

        $UserDashboard = $model
            ->where('expiration_date >=', $today)
            ->where('expiration_date <=', $futureDate)
            ->orderBy('expiration_date', 'ASC')
            ->findAll();

        foreach ($UserDashboard as &$UserDashboard) {
            $daysLeft = (int) floor((strtotime($UserDashboard['expiration_date']) - strtotime($today)) / (60 * 60 * 24));
            $item['days_left'] = $daysLeft;
        }

        $data['items'] = $UserDashboard;
        return view('user/dashboard/expiring_soon', $data);
    }

    public function deleted()
    {
        $this->checkLogin();

        $itemModel = new ItemModel();
        $deletedModel = new DeletedItemModel();
        $today = date('Y-m-d');

        $expiredItems = $itemModel
            ->where('expiration_date <', $today)
            ->orderBy('expiration_date', 'ASC')
            ->findAll();

        $deletedItems = $deletedModel
            ->orderBy('deleted_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        foreach ($expiredItems as &$item) {
            if (!empty($item['expiration_date'])) {
                $daysDiff = (int) floor((strtotime($today) - strtotime($item['expiration_date'])) / (60 * 60 * 24));
                $item['days_expired'] = "Expired {$daysDiff} days ago";
            } else {
                $item['days_expired'] = "-";
            }
            $item['delete_type'] = ($item['auto_delete'] ? 'Auto Deleted' : 'Manually Deleted');
        }

        foreach ($deletedItems as &$dItem) {
            if (!empty($dItem['deleted_at'])) {
                $daysDiff = (int) floor((strtotime($today) - strtotime($dItem['deleted_at'])) / (60 * 60 * 24));
                $dItem['days_expired'] = "Deleted {$daysDiff} days ago";
            } else {
                $dItem['days_expired'] = "-";
            }
            $dItem['delete_type'] = $dItem['status'] ?? 'Deleted';
        }

        $data['items'] = $expiredItems;
        $data['deletedItems'] = $deletedItems;

        return view('user/dashboard/deleted_items', $data);
    }

    public function increaseQuantity($id)
    {
        $this->checkLogin();
        $model = new ItemModel();
        $item = $model->find($id);

        // Accept both JSON and form data
        $data = $this->request->getJSON(true);
        if (!$data) {
            $data = $this->request->getPost();
        }

        $amount = isset($data['amount']) ? (int) $data['amount'] : 0;
        if ($amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid amount.']);
        }

        if ($item) {
            $newQty = (int) $item['quantity'] + $amount;
            $model->update($id, ['quantity' => $newQty]);
            return $this->response->setJSON([
                'success' => true,
                'newQty' => $newQty,
                'message' => 'Quantity increased successfully.'
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Item not found.']);
    }


    public function decreaseQuantity($id)
    {
        $this->checkLogin();
        $model = new ItemModel();
        $item = $model->find($id);

        // Accept both JSON and form data
        $data = $this->request->getJSON(true);
        if (!$data) {
            $data = $this->request->getPost();
        }

        $amount = isset($data['amount']) ? (int) $data['amount'] : 0;
        if ($amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid amount.']);
        }

        if ($item && $item['quantity'] >= $amount) {
            $newQty = (int) $item['quantity'] - $amount;
            $model->update($id, ['quantity' => $newQty]);
            return $this->response->setJSON([
                'success' => true,
                'newQty' => $newQty,
                'message' => 'Quantity decreased successfully.'
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Not enough stock to decrease.']);
    }


    public function delete($id)
    {
        $this->checkLogin();

        $model = new ItemModel();
        $deletedModel = new DeletedItemModel();

        // Find item
        $item = $model->find($id);
        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item not found or already deleted.');
        }

        // Prevent duplicates in deleted_items
        $alreadyDeleted = $deletedModel->where('product_id', $item['product_id'])->first();
        if ($alreadyDeleted) {
            // Just remove from items if already logged
            $model->delete($id);
            return redirect()->to('/items')->with('info', 'Item already existed in Deleted Items.');
        }

        // Move to deleted_items table
        $deletedModel->insert([
            'product_id' => $item['product_id'],
            'name' => $item['name'],
            'category' => $item['category'] ?? null,
            'subcategory' => $item['subcategory'] ?? null,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'expiration_date' => $item['expiration_date'],
            'barcode' => $item['barcode'] ?? null,
            'auto_delete' => $item['auto_delete'],
            'status' => 'manually deleted',
            'created_at' => $item['created_at'] ?? date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        // Delete from active items
        $model->delete($id);

        return redirect()->to('/items')->with('success', 'Item manually deleted successfully!');
    }


    public function deleteMultiple()
    {
        $this->checkLogin();
        $json = $this->request->getJSON(true);
        $ids = $json['ids'] ?? [];

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No items selected.']);
        }

        $itemModel = new ItemModel();
        $deletedModel = new DeletedItemModel();

        $movedCount = 0;

        // Consider wrapping in DB transaction if many rows
        foreach ($ids as $id) {
            $item = $itemModel->find($id);

            if ($item) {
                $deletedModel->insert([
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'category' => $item['category'] ?? null,
                    'subcategory' => $item['subcategory'] ?? null,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'expiration_date' => $item['expiration_date'],
                    'barcode' => $item['barcode'] ?? null,
                    'auto_delete' => $item['auto_delete'],
                    'status' => 'manually deleted',
                    'created_at' => $item['created_at'] ?? date('Y-m-d H:i:s'),
                    'deleted_at' => date('Y-m-d H:i:s'),
                ]);

                $itemModel->delete($id);
                $movedCount++;
            }
        }

        return $this->response->setJSON(['success' => true, 'message' => "$movedCount item(s) moved to Deleted Items."]);
    }

    public function upload()
    {
        $this->checkLogin();
        helper(['form', 'url']);
        $session = session();
        $itemModel = new ItemModel();

        if ($this->request->getMethod() === 'post') {
            $file = $this->request->getFile('bulk_file');

            if ($file && $file->isValid()) {
                $ext = strtolower($file->getClientExtension());
                require_once ROOTPATH . 'vendor/autoload.php';

                if ($ext === 'xls') {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                } else {
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                }

                $spreadsheet = $reader->load($file->getTempName());
                $sheetData = $spreadsheet->getActiveSheet()->toArray();

                // debug: remove die() in production
                echo "<pre>";
                print_r($sheetData);
                echo "</pre>";
                die();
            }
        }
    }
    public function getItems()
    {
        $searchQuery = $this->request->getGet('q');
        $itemModel = new ItemModel();
        $items = $itemModel->like('name', $searchQuery)->findAll();

        return $this->response->setJSON($items);
    }

    public function updateMultipleQuantity()
    {
        $this->checkLogin();
        $json = $this->request->getJSON(true);
        $ids = $json['ids'] ?? [];
        $action = $json['action'] ?? '';
        $amount = (int) ($json['amount'] ?? 0);

        if (empty($ids) || $amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid selection or amount.']);
        }

        $itemModel = new ItemModel();

        foreach ($ids as $id) {
            $item = $itemModel->find($id);
            if (!$item)
                continue;

            $newQuantity = ($action === 'increase')
                ? $item['quantity'] + $amount
                : max(0, $item['quantity'] - $amount);

            $itemModel->update($id, ['quantity' => $newQuantity]);
        }

        return $this->response->setJSON(['success' => true, 'message' => "Quantity successfully " . ($action === 'increase' ? 'added to' : 'reduced for') . " " . count($ids) . " item(s)."]);
    }
    protected function checkLogin($requiredRole = null)
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('/login');
        }
    }
    public function faqs()
    {
        $this->checkLogin('user');
        return view('user/dashboard/faqs');
    }

    public function requestStockAdjustment()
    {
        $this->checkLogin('user');
        return view('user/dashboard/request_stock_adjustment');
    }
    // ================================================
// 📦 STOCK REQUEST SUBMISSION HANDLER
// ================================================
    public function submitStockRequest()
    {
        $this->checkLogin('user');
        helper(['form']);

        $userId = session()->get('id');
        $itemId = $this->request->getPost('item_id');
        $quantity = $this->request->getPost('quantity');
        $reason = trim($this->request->getPost('reason'));

        // ✅ Basic validation
        if (empty($itemId) || empty($quantity) || empty($reason)) {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        // ✅ Validate quantity
        if (!is_numeric($quantity) || $quantity <= 0) {
            return redirect()->back()->with('error', 'Quantity must be a positive number.');
        }

        // ✅ Load model
        $stockRequestModel = new \App\Models\StockRequestModel();

        // ✅ Save request
        $stockRequestModel->insert([
            'user_id' => $userId,
            'item_id' => $itemId,
            'quantity' => $quantity,
            'reason' => $reason,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // ✅ Return with success message
        return redirect()->back()->with('success', 'Stock request submitted successfully!');
    }




}

