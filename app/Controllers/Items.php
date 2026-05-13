<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\DeletedItemModel;
use App\Models\ItemLogModel;

class Items extends BaseController
{
    public function index()
    {
        $model = new ItemModel();
        $deletedModel = new DeletedItemModel();

        date_default_timezone_set('Asia/Manila');

        $today = date('Y-m-d');
        $warningDays = 10;

        $updatedItems = [];
        $items = $model->orderBy('created_at', 'ASC')->findAll();

        foreach ($items as $item) {
            // ✅ Skip already deleted items
            if (in_array($item['status'], ['manually deleted', 'auto deleted'])) {
                continue;
            }

            // Handle missing expiration
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

            // ✅ AUTO-DELETE LOGIC (Separates items into Unconsumed vs Expired)
            if (
                $status === 'expired' &&
                ($item['auto_delete'] ?? 0) == 1 &&
                !in_array($item['status'], ['manually deleted', 'unconsumed'])
            ) {
                $salesModel = new \App\Models\SalesModel();
                $saleCount = $salesModel->where('product_id', $item['product_id'])->countAllResults();
                $delStatus = ($saleCount > 0) ? 'expired' : 'unconsumed';

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
                        'barcode' => $item['barcode'] ?? '',
                        'auto_delete' => $item['auto_delete'] ?? 0,
                        'status' => $delStatus,
                        'created_at' => $item['created_at'] ?? date('Y-m-d H:i:s'),
                        'deleted_at' => date('Y-m-d H:i:s'),
                    ]);
                }
                
                // Do not remove from the active items table
            }

            $updatedItems[] = $item;
        }

        // ✅ Calculate total inventory value
        $totalValue = 0;
        foreach ($updatedItems as $it) {
            if (stripos($it['name'], 'siomai') !== false) {
                $p12 = (!empty($it['pack_small_price']) && $it['pack_small_price'] > 0) ? $it['pack_small_price'] : 115;
                $p20 = (!empty($it['pack_medium_price']) && $it['pack_medium_price'] > 0) ? $it['pack_medium_price'] : 185;
                $p40 = (!empty($it['pack_biggest_price']) && $it['pack_biggest_price'] > 0) ? $it['pack_biggest_price'] : 335;
                $v1 = (float)($it['pack_small_qty'] ?? 0) * (float)$p12;
                $v2 = (float)($it['pack_medium_qty'] ?? 0) * (float)$p20;
                $v3 = (float)($it['pack_biggest_qty'] ?? 0) * (float)$p40;
                $totalValue += ($v1 + $v2 + $v3);
            } else {
                $qty = (float) ($it['quantity'] ?? 0);
                $prc = (float) ($it['price'] ?? 0);
                $totalValue += $qty * $prc;
            }
        }

        $data['items'] = $updatedItems;
        $data['totalValue'] = $totalValue;
        $data['currentPath'] = service('uri')->getPath();

        $salesModel = new \App\Models\SalesModel();
        $itemModel = new \App\Models\ItemModel();
        $topSalesQuery = $salesModel->db->query("
            SELECT product_id, SUM(quantity) as total_quantity, SUM(total) as total_value
            FROM sales 
            GROUP BY product_id
            ORDER BY total_value DESC
            LIMIT 5
        ")->getResultArray();

        foreach ($topSalesQuery as &$ts) {
            $product = $itemModel->find($ts['product_id']);
            $ts['name'] = $product ? $product['name'] : 'Unknown Product (ID: '.$ts['product_id'].')';
        }
        $data['topSalesData'] = json_encode($topSalesQuery);

        return view('items/list', $data);
    }

    public function dashboard()
    {
        $model = new ItemModel();
        $deletedModel = new DeletedItemModel();

        date_default_timezone_set('Asia/Manila');

        $today = date('Y-m-d');
        $warningDays = 10;

        $updatedItems = [];
        $items = $model->orderBy('created_at', 'ASC')->findAll();

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

            // AUTO-DELETE LOGIC
            if (
                $status === 'expired' &&
                ($item['auto_delete'] ?? 0) == 1 &&
                !in_array($item['status'], ['manually deleted', 'unconsumed'])
            ) {
                $salesModel = new \App\Models\SalesModel();
                $saleCount = $salesModel->where('product_id', $item['product_id'])->countAllResults();
                $delStatus = ($saleCount > 0) ? 'expired' : 'unconsumed';

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
                        'barcode' => $item['barcode'] ?? '',
                        'auto_delete' => $item['auto_delete'] ?? 0,
                        'status' => $delStatus,
                        'created_at' => $item['created_at'] ?? date('Y-m-d H:i:s'),
                        'deleted_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            $updatedItems[] = $item;
        }

        $totalValue = 0;
        foreach ($updatedItems as $it) {
            if (stripos($it['name'], 'siomai') !== false) {
                $p12 = (!empty($it['pack_small_price']) && $it['pack_small_price'] > 0) ? $it['pack_small_price'] : 115;
                $p20 = (!empty($it['pack_medium_price']) && $it['pack_medium_price'] > 0) ? $it['pack_medium_price'] : 185;
                $p40 = (!empty($it['pack_biggest_price']) && $it['pack_biggest_price'] > 0) ? $it['pack_biggest_price'] : 335;
                $v1 = (float)($it['pack_small_qty'] ?? 0) * (float)$p12;
                $v2 = (float)($it['pack_medium_qty'] ?? 0) * (float)$p20;
                $v3 = (float)($it['pack_biggest_qty'] ?? 0) * (float)$p40;
                $totalValue += ($v1 + $v2 + $v3);
            } else {
                $qty = (float) ($it['quantity'] ?? 0);
                $prc = (float) ($it['price'] ?? 0);
                $totalValue += $qty * $prc;
            }
        }

        $data['items'] = $updatedItems;
        $data['totalValue'] = $totalValue;
        $data['currentPath'] = service('uri')->getPath();

        $salesModel = new \App\Models\SalesModel();
        $itemModel = new \App\Models\ItemModel();
        $topSalesQuery = $salesModel->db->query("
            SELECT product_id, SUM(quantity) as total_quantity, SUM(total) as total_value
            FROM sales 
            GROUP BY product_id
            ORDER BY total_value DESC
            LIMIT 5
        ")->getResultArray();

        foreach ($topSalesQuery as &$ts) {
            $product = $itemModel->find($ts['product_id']);
            $ts['name'] = $product ? $product['name'] : 'Unknown Product (ID: '.$ts['product_id'].')';
        }
        $data['topSalesData'] = json_encode($topSalesQuery);

        return view('admin/dashboard', $data);
    }

    public function getDashboardData()
    {
        $model = new ItemModel();
        $updatedItems = [];
        $items = $model->orderBy('created_at', 'ASC')->findAll();
        $totalValue = 0;
        $today = date('Y-m-d');
        $warningDays = 10;

        foreach ($items as $item) {
            if (in_array($item['status'], ['manually deleted', 'auto deleted'])) {
                continue;
            }

            if (empty($item['expiration_date'])) {
                $item['status'] = $item['status'] ?? 'unknown';
                $updatedItems[] = $item;
                continue;
            }

            $expirationDate = $item['expiration_date'];
            $daysLeft = (int) floor((strtotime($expirationDate) - strtotime($today)) / (60 * 60 * 24));

            if ($daysLeft < 0) {
                $item['status'] = 'expired';
            } elseif ($daysLeft <= $warningDays) {
                $item['status'] = 'expiring soon';
            } else {
                $item['status'] = 'active';
            }
            
            $updatedItems[] = $item;
        }

        // ✅ Calculate total inventory value (same as index)
        $totalValue = 0;
        foreach ($updatedItems as $it) {
            if (stripos($it['name'], 'siomai') !== false) {
                $p12 = (!empty($it['pack_small_price']) && $it['pack_small_price'] > 0) ? $it['pack_small_price'] : 115;
                $p20 = (!empty($it['pack_medium_price']) && $it['pack_medium_price'] > 0) ? $it['pack_medium_price'] : 185;
                $p40 = (!empty($it['pack_biggest_price']) && $it['pack_biggest_price'] > 0) ? $it['pack_biggest_price'] : 335;
                $v1 = (float)($it['pack_small_qty'] ?? 0) * (float)$p12;
                $v2 = (float)($it['pack_medium_qty'] ?? 0) * (float)$p20;
                $v3 = (float)($it['pack_biggest_qty'] ?? 0) * (float)$p40;
                $totalValue += ($v1 + $v2 + $v3);
            } else {
                $qty = (float) ($it['quantity'] ?? 0);
                $prc = (float) ($it['price'] ?? 0);
                $totalValue += $qty * $prc;
            }
        }


        $salesModel = new \App\Models\SalesModel();
        $topSalesQuery = $salesModel->db->query("
            SELECT product_id, SUM(quantity) as total_quantity, SUM(total) as total_value
            FROM sales 
            GROUP BY product_id
            ORDER BY total_value DESC
            LIMIT 5
        ")->getResultArray();

        foreach ($topSalesQuery as &$ts) {
            $product = $model->find($ts['product_id']);
            $ts['name'] = $product ? $product['name'] : 'Unknown Product (ID: '.$ts['product_id'].')';
        }

        return $this->response->setJSON([
            'items' => $updatedItems,
            'totalValue' => $totalValue,
            'topSalesData' => $topSalesQuery
        ]);
    }

    public function add()
    {
        $nextProductId = $this->getNextProductId();
        return view('items/add', ['nextProductId' => $nextProductId]);
    }

    /**
     * Reliably finds the next P-prefixed Product ID.
     * Considers variation suffixes (e.g., P001-SML -> P001) to find the true max.
     */
    private function getNextProductId(): string
    {
        $db = \Config\Database::connect();
        // Extract base ID by stripping anything after the first hyphen
        // then sort numerically by the part after 'P'
        $query = $db->query("
            SELECT product_id 
            FROM items 
            WHERE product_id REGEXP '^P[0-9]+'
            ORDER BY CAST(
                SUBSTRING(
                    product_id, 2, 
                    IF(LOCATE('-', product_id) > 0, LOCATE('-', product_id) - 2, LENGTH(product_id))
                ) AS UNSIGNED
            ) DESC 
            LIMIT 1
        ");

        $result = $query->getRow();

        if (!$result) {
            return 'P001';
        }

        // Strip suffix if any: P001-SML -> P001
        $baseId = preg_replace('/-.*$/', '', $result->product_id);
        
        // Extract number: P001 -> 1
        $num = (int) substr($baseId, 1);
        $nextNum = $num + 1;

        return 'P' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function store()
    {
        helper(['form', 'filesystem']);
        $itemModel = new ItemModel();
        $db = \Config\Database::connect();

        $file = $this->request->getFile('bulk_file');

        // --- Handle BULK upload ---
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $ext = strtolower($file->getClientExtension());
            require_once ROOTPATH . 'vendor/autoload.php';
            $rows = [];

            if (in_array($ext, ['xlsx', 'xls'])) {
                $reader = $ext === 'xlsx'
                    ? new \PhpOffice\PhpSpreadsheet\Reader\Xlsx()
                    : new \PhpOffice\PhpSpreadsheet\Reader\Xls();

                $spreadsheet = $reader->load($file->getTempName());
                $sheetRows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
                foreach ($sheetRows as $r) {
                    $rows[] = array_values($r);
                }
            } else {
                $tmp = $file->getTempName();
                if (($handle = fopen($tmp, 'r')) !== false) {
                    while (($data = fgetcsv($handle, 0, ',')) !== false) {
                        if (count(array_filter($data)) === 0) continue;
                        $rows[] = $data;
                    }
                    fclose($handle);
                }
            }

            if (!empty($rows)) {
                $header = $rows[0];
                if (isset($header[0]) && preg_match('/product|name/i', implode(' ', $header))) {
                    array_shift($rows);
                }
            }

            $count = 0;
            $skipped = 0;
            foreach ($rows as $row) {
                $product_id = trim($row[0] ?? '');
                $name = trim($row[1] ?? '');
                $quantity = (int) ($row[2] ?? 0);
                $expiration_date = trim($row[3] ?? '');
                $category = trim($row[4] ?? '');
                $subcategory = trim($row[5] ?? '');

                if ($product_id === '' || $name === '') continue;

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
                    'price' => 0.00,
                    'barcode' => '',
                    'expiration_date' => $expiration_date ?: null,
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
            if ($skipped > 0) $msg .= " $skipped duplicate Product IDs were skipped.";
            return redirect()->to(site_url('items'))->with('success', $msg);
        }

        // --- Handle SINGLE manual add ---
        $isVariation = $this->request->getPost('size_variation') === '1';

        // Validation Rules
        $rules = [
            'product_id'      => 'required|max_length[50]',
            'name'            => 'required|min_length[2]|max_length[255]',
            'sku'             => 'required|max_length[100]|is_unique[items.sku]',
            'category'        => 'required',
            'expiration_date' => 'permit_empty|valid_date',
        ];

        if (!$isVariation) {
            $rules['quantity'] = 'required|integer|greater_than_equal_to[0]';
            $rules['price']    = 'required|decimal|greater_than_equal_to[0]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Additional Manual Checks (Uniqueness for both parent and potential children)
        $product_id = $this->request->getPost('product_id');
        $base_sku = $this->request->getPost('sku');
        $name = $this->request->getPost('name');

        if (!$isVariation) {
            if ($itemModel->where('product_id', $product_id)->first()) {
                return redirect()->back()->withInput()->with('error', 'Product ID "' . $product_id . '" already exists.');
            }
        } else {
            $variationsPost = $this->request->getPost('variations') ?? [];
            if (empty($variationsPost)) {
                return redirect()->back()->withInput()->with('error', 'Please add at least one pack size variation.');
            }

            $collisions = [];
            foreach ($variationsPost as $v) {
                $suffix = $this->generateVariationSuffix($v['label'] ?? '');
                if ($suffix === '') continue;

                $childId = $product_id . '-' . $suffix;
                $childSku = $base_sku . '-' . $suffix;

                if ($itemModel->where('product_id', $childId)->first()) {
                    $collisions[] = "ID: $childId";
                }
                if ($itemModel->where('sku', $childSku)->first()) {
                    $collisions[] = "SKU: $childSku";
                }
            }

            if (!empty($collisions)) {
                return redirect()->back()->withInput()->with('error', 'These variations already exist: ' . implode(', ', $collisions) . '. Please use a different Product ID or SKU.');
            }
        }

        // Prepare Data
        $category = $this->request->getPost('category');
        $subcategory = $this->request->getPost('subcategory');
        $expiration_date = $this->request->getPost('expiration_date');
        $expiration_date = !empty($expiration_date) ? date('Y-m-d', strtotime($expiration_date)) : null;

        $auto_delete = 0;
        if (strtolower($category) === 'food' || strtolower($subcategory) === 'expirable') {
            $auto_delete = 1;
        }

        $imageFile = $this->request->getFile('product_image');
        $imagePath = null;

        // Image upload BEFORE transaction
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $ext = strtolower($imageFile->getClientExtension());
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $imageFile->move(FCPATH . 'uploads');
                $imagePath = $imageFile->getName();
            }
        }

        try {
            $db->transStart();

            if (!$isVariation) {
                $itemData = [
                    'product_id'         => $product_id,
                    'sku'                => $base_sku,
                    'name'               => $name,
                    'quantity'           => $this->request->getPost('quantity') ?? 0,
                    'price'              => $this->request->getPost('price') ?? 0.00,
                    'barcode'            => '',
                    'expiration_date'    => $expiration_date,
                    'category'           => $category,
                    'subcategory'        => $subcategory ?: null,
                    'auto_delete'        => $auto_delete,
                    'status'             => 'active',
                    'image_path'         => $imagePath,
                    'created_at'         => date('Y-m-d H:i:s'),
                    'is_variation_child' => 0,
                    'variation_group_id' => null,
                    'variation_label'    => null,
                ];

                if ($itemModel->insert($itemData) === false) {
                    throw new \Exception(implode(', ', $itemModel->errors()));
                }
            } else {
                $groupId = uniqid('VG-', true);
                $variationBatch = [];

                foreach ($variationsPost as $v) {
                    $label = trim(strip_tags((string)($v['label'] ?? '')));
                    $suffix = $this->generateVariationSuffix($label);
                    if ($suffix === '') continue;

                    $variationBatch[] = [
                        'product_id'         => $product_id . '-' . $suffix,
                        'sku'                => $base_sku . '-' . $suffix,
                        'name'               => $name, // Keep base name
                        'quantity'           => (int)($v['qty'] ?? 0),
                        'price'              => (float)($v['price'] ?? 0),
                        'barcode'            => '',
                        'expiration_date'    => $expiration_date,
                        'category'           => $category,
                        'subcategory'        => $subcategory ?: null,
                        'auto_delete'        => $auto_delete,
                        'status'             => 'active',
                        'image_path'         => $imagePath,
                        'created_at'         => date('Y-m-d H:i:s'),
                        'is_variation_child' => 1,
                        'variation_group_id' => $groupId,
                        'variation_label'    => $label,
                    ];
                }

                if (empty($variationBatch)) {
                    throw new \Exception('No valid size rows were provided.');
                }

                if ($itemModel->insertBatch($variationBatch) === false) {
                    throw new \Exception(implode(', ', $itemModel->errors()));
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                $error = $db->error();
                log_message('error', 'Items::store() transaction failed: ' . json_encode($error));
                throw new \Exception('Database transaction failed to complete. ' . ($error['message'] ?? ''));
            }

            return redirect()->to('/items')->with('success', 'Item added successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Items::store() exception: ' . $e->getMessage());
            
            $msg = (ENVIRONMENT === 'development') ? $e->getMessage() : 'Failed to add item.';
            return redirect()->back()->withInput()->with('error', $msg);
        }
    }

    public function downloadSampleTemplate()
    {
        $filename = "bulk_upload_template.csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        
        $output = fopen("php://output", "w");
        fputcsv($output, ['product_id', 'name', 'sku', 'price', 'quantity', 'category', 'expiration_date', 'auto_delete', 'image_path']);
        fputcsv($output, ['P010', 'Pork Siomai', 'PRK-SMAI-S12', '115.00', '100', 'Food', '2027-12-12', '0', '']);
        fclose($output);
        exit();
    }

    public function bulkUpload()
    {
        helper(['form']);

        $itemModel = new ItemModel();
        $file = $this->request->getFile('bulk_file');

        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return redirect()->to('/items/add')->with('error', 'No valid CSV file uploaded.');
        }

        if ($file->getClientExtension() !== 'csv') {
            return redirect()->to('/items/add')->with('error', 'Only CSV files are allowed for bulk upload.');
        }

        $path = $file->getTempName();
        $rows = [];

        if (($handle = fopen($path, 'r')) !== false) {
            while (($data = fgetcsv($handle, 0, ',')) !== false) {
                if (count(array_filter($data)) === 0) continue;
                $rows[] = $data;
            }
            fclose($handle);
        }

        if (empty($rows)) {
            return redirect()->to('/items/add')->with('error', 'Uploaded file is empty.');
        }

        $header = $rows[0];
        if (!empty($header) && preg_match('/product|name|quantity/i', implode(',', $header))) {
            array_shift($rows);
        }

        $inserted = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $product_id = trim($row[0] ?? '');
            $name = trim($row[1] ?? '');
            $sku = trim($row[2] ?? '');
            
            $priceStr = trim($row[3] ?? '');
            $priceStr = str_replace(['₱', ',', ' '], '', $priceStr);
            $price = (float) $priceStr;
            
            $quantityStr = trim($row[4] ?? '');
            $quantity = $quantityStr === '' ? 0 : (int) $quantityStr;
            
            $category = trim($row[5] ?? '');
            $expiration_date = trim($row[6] ?? '');
            $auto_delete_val = trim($row[7] ?? '0');
            $image_path = trim($row[8] ?? '');

            if ($product_id === '' || $name === '' || $quantityStr === '') {
                $errors[] = "Row " . ($index + 2) . ": Missing Product ID, Name, or Quantity.";
                continue;
            }

            if ($itemModel->where('product_id', $product_id)->first()) {
                $skipped++;
                continue;
            }

            $auto_delete = ($auto_delete_val == '1' || strtolower($auto_delete_val) == 'yes') ? 1 : 0;

            if ($expiration_date !== '' && !strtotime($expiration_date)) {
                $errors[] = "Row " . ($index + 2) . ": Invalid expiration date format.";
                continue;
            }

            $data = [
                'product_id' => $product_id,
                'name' => $name,
                'sku' => $sku,
                'quantity' => $quantity,
                'price' => $price,
                'barcode' => '',
                'expiration_date' => $expiration_date ?: null,
                'category' => $category,
                'subcategory' => null,
                'auto_delete' => $auto_delete,
                'status' => 'active',
                'image_path' => $image_path ?: null,
                'created_at' => date('Y-m-d H:i:s'),
            ];

            try {
                $itemModel->insert($data);
                $inserted++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($index + 2) . ": " . $e->getMessage();
            }
        }

        $msg = "$inserted item(s) uploaded successfully.";
        if ($skipped > 0) {
            $msg .= " $skipped duplicate(s) skipped.";
        }
        if (!empty($errors)) {
            $msg .= " " . count($errors) . " row(s) failed. " . implode(' | ', array_slice($errors, 0, 3));
        }

        return !empty($errors)
            ? redirect()->to('/items/add')->with('error', $msg)
            : redirect()->to('/items')->with('success', $msg);
    }

    public function edit($id)
    {
        $model = new ItemModel();
        $item = $model->find($id);

        if (!$item) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Item not found');
        }

        return view('items/edit', ['item' => $item]);
    }

    public function update($id)
    {
        helper(['form']);

        $rules = [
            'product_id' => 'required',
            'name' => 'required|min_length[2]',
            'quantity' => 'required|numeric|greater_than_equal_to[0]',
            'price' => 'required|numeric|greater_than_equal_to[0]',
            'category' => 'required',
        ];

        $exp = $this->request->getPost('expiration_date');
        if (!empty($exp)) {
            $rules['expiration_date'] = 'valid_date';
        }

        if (!$this->validate($rules)) {
            return redirect()->to('/items/edit/' . $id)->withInput()->with('errors', $this->validator->getErrors());
        }

        $model = new ItemModel();
        $logModel = new ItemLogModel();
        $oldData = $model->find($id);

        $prodId = $this->request->getPost('product_id');
        $name = $this->request->getPost('name');

        $newData = [
            'product_id' => $prodId,
            'name' => $name,
            'expiration_date' => $this->request->getPost('expiration_date') ?: null,
            'barcode' => $this->request->getPost('barcode'),
            'category' => $this->request->getPost('category'),
            'subcategory' => $this->request->getPost('subcategory') ?: null,
            'auto_delete' => $this->request->getPost('auto_delete') ? 1 : 0,
            'quantity' => $this->request->getPost('quantity'),
            'price' => $this->request->getPost('price'),
        ];

        // Image Update Handling
        $imageFile = $this->request->getFile('product_image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $ext = $imageFile->getClientExtension();
            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp'])) {
                $imageFile->move(FCPATH . 'uploads');
                $newData['image_path'] = $imageFile->getName();
            }
        }

        $model->update($id, $newData);

        $logOldData = $oldData;
        $logNewData = $newData;
        unset($logOldData['barcode'], $logNewData['barcode']);

        $logModel->insert([
            'item_id' => $id,
            'old_data' => json_encode($logOldData),
            'new_data' => json_encode($logNewData),
            'updated_by' => session()->get('username') ?? 'Admin',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/items')->with('success', 'Item updated successfully (changes logged)!');
    }

    public function expired()
    {
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
                $item['expired_label'] = ($expDate < $todayDate)
                    ? "Expired " . $todayDate->diff($expDate)->days . " days ago"
                    : "Expiring in " . $expDate->diff($todayDate)->days . " days";
            } else {
                $item['expired_label'] = "No expiration date";
            }
            $item['status'] = 'expired';
        }

        foreach ($deletedItems as &$dItem) {
            $dItem['expired_label'] = match ($dItem['status']) {
                'auto deleted' => "Auto deleted item",
                'manually deleted' => "Manually deleted item",
                default => ucfirst($dItem['status'] ?? 'deleted'),
            };
        }

        return view('items/expired_items', [
            'items' => $expiredItems,
            'deletedItems' => $deletedItems
        ]);
    }

    public function unconsumedHistory()
    {
        $deletedModel = new DeletedItemModel();
        
        $unconsumedItems = $deletedModel
            ->where('status', 'unconsumed')
            ->orderBy('deleted_at', 'DESC')
            ->findAll();

        return view('items/unconsumed', ['items' => $unconsumedItems]);
    }

    public function expiringSoon()
    {
        $model = new ItemModel();
        $today = date('Y-m-d');
        $warningDays = 10;
        $futureDate = date('Y-m-d', strtotime("+$warningDays days"));

        // Mark expiring soon as seen to clear notification
        $model->where('is_expiring_seen', 0)
              ->where('expiration_date !=', null)
              ->where('expiration_date !=', '0000-00-00')
              ->where('expiration_date >=', $today)
              ->where('expiration_date <=', $futureDate)
              ->set(['is_expiring_seen' => 1])
              ->update();

        $items = $model
            ->where('expiration_date >=', $today)
            ->where('expiration_date <=', $futureDate)
            ->orderBy('expiration_date', 'ASC')
            ->findAll();

        foreach ($items as &$item) {
            $item['days_left'] = (int) floor((strtotime($item['expiration_date']) - strtotime($today)) / (60 * 60 * 24));
        }

        return view('items/expiring_soon', ['items' => $items]);
    }

    public function deleted()
    {
        $itemModel = new ItemModel();
        $deletedModel = new DeletedItemModel();
        $today = date('Y-m-d');

        // Mark expired items as seen to clear notification
        $itemModel->where('is_expired_seen', 0)
                  ->where('expiration_date !=', null)
                  ->where('expiration_date !=', '0000-00-00')
                  ->where('expiration_date <', $today)
                  ->set(['is_expired_seen' => 1])
                  ->update();

        $expiredItems = [];
        $rawExpiredItems = $itemModel
            ->where('expiration_date <', $today)
            ->orderBy('expiration_date', 'ASC')
            ->findAll();

        $deletedItems = $deletedModel
            ->where('status !=', 'unconsumed')
            ->orderBy('deleted_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll();

        foreach ($rawExpiredItems as $rawItem) {
            // Check if it already exists in deleted_items to prevent duplicates or showing unconsumed items
            $inDeleted = false;
            foreach ($deletedItems as $dItem) {
                if ($dItem['product_id'] === $rawItem['product_id']) {
                    $inDeleted = true;
                    break;
                }
            }
            if (!$inDeleted) {
                // Also verify it's not in unconsumed
                $isUnconsumed = $deletedModel->where('product_id', $rawItem['product_id'])->where('status', 'unconsumed')->first();
                if (!$isUnconsumed) {
                    $expiredItems[] = $rawItem;
                }
            }
        }

        foreach ($expiredItems as &$item) {
            if (!empty($item['expiration_date'])) {
                $daysDiff = (int) floor((strtotime($today) - strtotime($item['expiration_date'])) / (60 * 60 * 24));
                $item['days_expired'] = "Expired {$daysDiff} days ago";
            } else {
                $item['days_expired'] = "-";
            }
            $item['delete_type'] = ($item['auto_delete'] ?? 0) ? 'Auto Deleted' : 'Expired';
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

        return view('items/deleted_items', [
            'items' => $expiredItems,
            'deletedItems' => $deletedItems
        ]);
    }

    public function logs()
    {
        $logModel = new ItemLogModel();
        $logModel->select('item_logs.*, items.product_id, items.name as item_name')
            ->join('items', 'items.id = item_logs.item_id', 'left');

        $search = trim((string) $this->request->getGet('search'));
        $dateFilter = $this->request->getGet('date');
        $perPage = 10;

        if ($search !== '') {
            $logModel->groupStart()
                ->like('items.product_id', $search)
                ->orLike('items.name', $search)
                ->orLike('item_logs.updated_by', $search)
                ->groupEnd();
        }

        if ($dateFilter === 'week') {
            $logModel->where('item_logs.updated_at >=', date('Y-m-d', strtotime('-7 days')));
        } elseif ($dateFilter === 'month') {
            $logModel->where('item_logs.updated_at >=', date('Y-m-01'));
        }

        return view('items/logs', [
            'logs' => $logModel->orderBy('item_logs.updated_at', 'DESC')->paginate($perPage, 'logs'),
            'pager' => $logModel->pager,
            'search' => $search,
            'dateFilter' => $dateFilter
        ]);
    }

    public function exportLogsCsv()
    {
        $logModel = new ItemLogModel();
        $logModel->select('item_logs.*, items.product_id, items.name as item_name')
            ->join('items', 'items.id = item_logs.item_id', 'left');
        $logs = $logModel->orderBy('item_logs.updated_at', 'DESC')->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="item_logs.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Product ID', 'Item Name', 'Updated By', 'Changes', 'Updated At']);

        foreach ($logs as $log) {
            $old = json_decode($log['old_data'], true);
            $new = json_decode($log['new_data'], true);
            $changes = '';

            if ($old && $new) {
                foreach ($new as $key => $value) {
                    if (strtolower($key) === 'barcode') continue;
                    $oldVal = $old[$key] ?? '';
                    if ($oldVal != $value) {
                        $changes .= "$key: $oldVal → $value\n";
                    }
                }
            }

            fputcsv($output, [
                $log['product_id'] ?? '',
                $log['item_name'] ?? '',
                $log['updated_by'] ?? '',
                trim($changes),
                $log['updated_at'] ?? '',
            ]);
        }

        fclose($output);
        exit;
    }

    public function increaseQuantity($id)
    {
        $model = new ItemModel();
        $item = $model->find($id);
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        $amount = (int) ($data['amount'] ?? 0);
        $size = $data['size'] ?? '';
        
        $column = 'quantity';
        if ($size === '-S') $column = 'pack_small_qty';
        if ($size === '-M') $column = 'pack_medium_qty';
        if ($size === '-L' || $size === '-B') $column = 'pack_biggest_qty';

        if ($amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid amount.']);
        }

        if ($item) {
            $newQty = (int) ($item[$column] ?? 0) + $amount;
            $model->update($id, [$column => $newQty]);
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
        $model = new ItemModel();
        $item = $model->find($id);
        $data = $this->request->getJSON(true) ?: $this->request->getPost();
        $amount = (int) ($data['amount'] ?? 0);
        $size = $data['size'] ?? '';
        
        $column = 'quantity';
        if ($size === '-S') $column = 'pack_small_qty';
        if ($size === '-M') $column = 'pack_medium_qty';
        if ($size === '-L' || $size === '-B') $column = 'pack_biggest_qty';

        if ($amount <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid amount.']);
        }

        if ($item && (int)($item[$column] ?? 0) >= $amount) {
            $newQty = (int) ($item[$column] ?? 0) - $amount;
            $model->update($id, [$column => $newQty]);
            return $this->response->setJSON([
                'success' => true,
                'newQty' => $newQty,
                'message' => 'Quantity decreased successfully.'
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Not enough stock to decrease.']);
    }

    public function deletePermanent($id)
    {
        $deletedModel = new DeletedItemModel();
        if (!$deletedModel->find($id)) {
            return redirect()->to(site_url('items/deleted'))->with('error', 'Item not found or already deleted.');
        }

        $deletedModel->delete($id);
        return redirect()->to(site_url('items/deleted'))->with('success', 'Item permanently deleted.');
    }

    public function delete($id)
    {
        $model = new ItemModel();
        $deletedModel = new DeletedItemModel();
        $item = $model->find($id);

        if (!$item) {
            return redirect()->to('/items')->with('error', 'Item not found or already deleted.');
        }

        if ($deletedModel->where('product_id', $item['product_id'])->first()) {
            $model->delete($id);
            return redirect()->to('/items')->with('info', 'Item already existed in Deleted Items.');
        }

        $deletedModel->insert([
            'product_id' => $item['product_id'],
            'name' => $item['name'],
            'category' => $item['category'] ?? null,
            'subcategory' => $item['subcategory'] ?? null,
            'quantity' => $item['quantity'],
            'price' => $item['price'] ?? 0.00,
            'expiration_date' => $item['expiration_date'],
            'barcode' => $item['barcode'] ?? '',
            'auto_delete' => $item['auto_delete'],
            'status' => 'manually deleted',
            'created_at' => $item['created_at'] ?? date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        $model->delete($id);
        return redirect()->to('/items')->with('success', 'Item manually deleted successfully!');
    }

    public function deleteMultiple()
    {
        $json = $this->request->getJSON(true) ?: $this->request->getPost();
        $ids = $json['ids'] ?? [];

        if (empty($ids)) {
            return $this->response->setJSON(['success' => false, 'message' => 'No items selected.']);
        }

        $itemModel = new ItemModel();
        $deletedModel = new DeletedItemModel();
        $movedCount = 0;

        foreach ($ids as $id) {
            $item = $itemModel->find($id);
            if (!$item) continue;

            if ($deletedModel->where('product_id', $item['product_id'])->first()) {
                $itemModel->delete($id);
                continue;
            }

            $deletedModel->insert([
                'product_id' => $item['product_id'],
                'name' => $item['name'],
                'category' => $item['category'] ?? null,
                'subcategory' => $item['subcategory'] ?? null,
                'quantity' => $item['quantity'],
                'price' => $item['price'] ?? 0.00,
                'expiration_date' => $item['expiration_date'],
                'barcode' => $item['barcode'] ?? '',
                'auto_delete' => $item['auto_delete'],
                'status' => 'manually deleted',
                'created_at' => $item['created_at'] ?? date('Y-m-d H:i:s'),
                'deleted_at' => date('Y-m-d H:i:s'),
            ]);

            $itemModel->delete($id);
            $movedCount++;
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "$movedCount item(s) moved to Deleted Items."
        ]);
    }

    public function updateMultipleQuantity()
    {
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
            if (!$item) continue;

            $newQuantity = ($action === 'increase')
                ? $item['quantity'] + $amount
                : max(0, $item['quantity'] - $amount);

            $itemModel->update($id, ['quantity' => $newQuantity]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Quantity successfully " . ($action === 'increase' ? 'added to' : 'reduced for') . " " . count($ids) . " item(s)."
        ]);
    }

    public function exportCsv()
    {
        $db = \Config\Database::connect();
        $itemModel = new ItemModel();
        
        $filename = 'inventory_export_' . date('Y-m-d_H-i-s') . '.csv';

        // Set headers for download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        
        // CSV Header
        fputcsv($output, [
            'ID', 'Product ID', 'Name', 'Category', 'Subcategory', 
            'Quantity', 'Price', 'Small Qty', 'Small Price', 
            'Medium Qty', 'Medium Price', 'Large Qty', 'Large Price',
            'Expiration Date', 'Status', 'Created At'
        ]);

        try {
            $db->transStart();

            $chunkSize = 200;
            $offset = 0;

            while (true) {
                // Fetch in chunks to avoid memory exhaustion
                $items = $itemModel->orderBy('id', 'ASC')
                                   ->limit($chunkSize, $offset)
                                   ->findAll();

                if (empty($items)) break;

                foreach ($items as $item) {
                    fputcsv($output, [
                        $item['id'],
                        $item['product_id'] ?? '',
                        $item['name'] ?? '',
                        $item['category'] ?? '',
                        $item['subcategory'] ?? '',
                        $item['quantity'] ?? 0,
                        $item['price'] ?? 0,
                        $item['pack_small_qty'] ?? 0,
                        $item['pack_small_price'] ?? 0,
                        $item['pack_medium_qty'] ?? 0,
                        $item['pack_medium_price'] ?? 0,
                        $item['pack_biggest_qty'] ?? 0,
                        $item['pack_biggest_price'] ?? 0,
                        $item['expiration_date'] ?? '',
                        $item['status'] ?? '',
                        $item['created_at'] ?? ''
                    ]);
                }

                // Flush the output buffer to stream data immediately
                if (ob_get_level() > 0) ob_flush();
                flush();
                
                $offset += $chunkSize;
                if (count($items) < $chunkSize) break;
            }

            $db->transComplete();
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Inventory Export Error: ' . $e->getMessage());
        }

        fclose($output);
        exit;
    }

    public function exportSalesCsv()
    {
        $db = \Config\Database::connect();
        $salesModel = new \App\Models\SalesModel();
        
        $filename = 'sales_export_' . date('Y-m-d_H-i-s') . '.csv';
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename={$filename}");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Product', 'User', 'Pack', 'Qty', 'Price', 'Total', 'Payment', 'Date']);

        try {
            $db->transStart();

            $chunkSize = 200;
            $offset = 0;

            while (true) {
                $sales = $salesModel
                    ->select('sales.*, items.name as product_name, users.username as user_name')
                    ->join('items', 'items.id = sales.product_id', 'left')
                    ->join('users', 'users.id = sales.user_id', 'left')
                    ->orderBy('sales.created_at', 'DESC')
                    ->limit($chunkSize, $offset)
                    ->get()
                    ->getResult(); // Using get()->getResult() for custom query with join

                if (empty($sales)) break;

                foreach ($sales as $sale) {
                    fputcsv($output, [
                        $sale->id,
                        $sale->product_name ?? 'N/A',
                        $sale->user_name ?? 'N/A',
                        $sale->pack ?? '',
                        $sale->quantity,
                        $sale->price,
                        $sale->total,
                        $sale->payment_method ?? 'N/A',
                        $sale->created_at
                    ]);
                }

                if (ob_get_level() > 0) ob_flush();
                flush();

                $offset += $chunkSize;
                if (count($sales) < $chunkSize) break;
            }

            $db->transComplete();

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Sales Export Error: ' . $e->getMessage());
        }

        fclose($output);
        exit;
    }

    /**
     * Generates a clean, label-based suffix for variations.
     * "Small" -> "SML", "Extra Large" -> "EL"
     */
    private function generateVariationSuffix(string $label): string
    {
        $label = trim($label);
        // Remove special characters, keep only alpha-numeric and spaces
        $cleanLabel = preg_replace('/[^A-Za-z0-9 ]/', '', $label);
        $words = preg_split('/\s+/', $cleanLabel, -1, PREG_SPLIT_NO_EMPTY);

        if (count($words) === 1) {
            $word = $words[0];
            if (strlen($word) <= 2) {
                return strtoupper($word);
            }
            // "Small" -> "SML"
            return strtoupper(substr($word, 0, 1) . substr(preg_replace('/[aeiou]/i', '', substr($word, 1)), 0, 2));
        } else {
            // "Extra Large" -> "EL"
            return strtoupper(implode('', array_map(function ($w) {
                return substr($w, 0, 1);
            }, $words)));
        }
    }
}