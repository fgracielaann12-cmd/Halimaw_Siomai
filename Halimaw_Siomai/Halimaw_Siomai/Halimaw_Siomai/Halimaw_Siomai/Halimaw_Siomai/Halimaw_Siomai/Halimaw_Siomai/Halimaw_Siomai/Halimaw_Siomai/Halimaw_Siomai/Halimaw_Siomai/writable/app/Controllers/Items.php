<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\DeletedItemModel;
use App\Models\ItemLogModel;

class Items extends BaseController
{
    public function index()
    {
        $this->checkLogin('admin');
        $model = new \App\Models\ItemModel();
        $perPage = 15;
        $items = $model->orderBy('created_at', 'DESC')->paginate($perPage);
        $pager = $model->pager;
        $data = [
            'items' => $items,
            'pager' => $pager,
            'title' => 'Dashboard'
        ];
        return view((session()->get('role') === 'admin' ? 'items/list' : 'user/dashboard'), $data);
    }


    public function add()
    {
        $this->checkLogin('admin');
        return view('items/add');
    }

    public function store()
    {
        $this->checkLogin('admin');
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
        return redirect()->to('/items')->with('success', 'Item added successfully!');
    }

    public function edit($id)
    {
        $this->checkLogin('admin');
        $model = new ItemModel();
        $item = $model->find($id);

        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        return view('items/edit', ['item' => $item]);
    }

    public function update($id)
    {
        $this->checkLogin('admin');
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

        // log changes
        $logModel->insert([
            'item_id' => $id,
            'old_data' => json_encode($oldData),
            'new_data' => json_encode($newData),
            'updated_by' => session()->get('username') ?? 'Admin',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/items')->with('success', 'Item updated successfully (changes logged)!');
    }

    public function expired()
    {
        $this->checkLogin('admin');
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

        return view('items/expired_items', [
            'items' => $expiredItems,
            'deletedItems' => $deletedItems
        ]);
    }

    public function expiringSoon()
    {
        $this->checkLogin('admin');
        $svc = new \App\Services\DashboardService();
        $items = $svc->getExpiringItems(30);
        $data = ['items' => $items, 'title' => 'Expiring Soon'];
        return view('items/expiring_soon', $data);
    }

    public function deleted()
    {
        $this->checkLogin('admin');

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

        return view('items/deleted_items', $data);
    }

    public function logs()
    {
        $this->checkLogin('admin');
        $logModel = new ItemLogModel();
        $data['logs'] = $logModel->orderBy('updated_at', 'DESC')->findAll();
        return view('items/logs', $data);
    }

    public function increaseQuantity($id)
    {
        $this->checkLogin('admin');
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
        $this->checkLogin('admin');
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


    // Permanently delete from deleted_items table (not from items)
    public function deletePermanent($id)
    {
        $this->checkLogin('admin');
        $deletedModel = new DeletedItemModel();

        $item = $deletedModel->find($id);
        if (!$item) {
            return redirect()->to(site_url('items/deleted'))->with('error', 'Item not found or already deleted.');
        }

        $deletedModel->delete($id);
        return redirect()->to(site_url('items/deleted'))->with('success', 'Item permanently deleted.');
    }

    public function delete($id)
    {
        $this->checkLogin('admin');

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
        $this->checkLogin('admin');
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
        $this->checkLogin('admin');
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

    public function updateMultipleQuantity()
    {
        $this->checkLogin('admin');
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
            return redirect()->to('/login')->send();
        }

        if ($requiredRole && $session->get('role') !== $requiredRole) {
            // Redirect user to their appropriate dashboard if role mismatch
            $role = $session->get('role');
            if ($role === 'admin') {
                return redirect()->to('/admin/dashboard')->send();
            } else {
                return redirect()->to('/user/dashboard')->send();
            }
        }
    }
}
