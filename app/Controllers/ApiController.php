<?php

namespace App\Controllers;

use App\Models\ItemModel;
use CodeIgniter\RESTful\ResourceController;

class ApiController extends ResourceController
{
    public function getProducts()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        $itemModel = new ItemModel();
        $rawProducts = $itemModel->findAll();

        $processedProducts = [];

        foreach ($rawProducts as $product) {
            $nameLower = strtolower($product['name']);
            $isSiomai = strpos($nameLower, 'siomai') !== false;

            // Normalize prices for specific items if price is 0
            $price = (float)$product['price'];
            if (!$isSiomai && $price == 0) {
                if (strpos($nameLower, 'burger patty') !== false) {
                    $price = 190.00;
                } elseif (strpos($nameLower, 'pastil') !== false) {
                    $price = 180.00;
                } elseif (strpos($nameLower, 'chili garlic') !== false) {
                    $price = 120.00;
                } elseif (strpos($nameLower, 'toyomansi') !== false) {
                    $price = 65.00;
                }
            }

            $processedProducts[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'image' => $product['image'] ?? null,
                'isSiomai' => $isSiomai,
                'price' => $price,
                'quantity' => (int)$product['quantity'],
                'pack_small_price' => (float)($product['pack_small_price'] ?? 115),
                'pack_medium_price' => (float)($product['pack_medium_price'] ?? 185),
                'pack_biggest_price' => (float)($product['pack_biggest_price'] ?? 335),
                'pack_small_qty' => (int)($product['pack_small_qty'] ?? 0),
                'pack_medium_qty' => (int)($product['pack_medium_qty'] ?? 0),
                'pack_biggest_qty' => (int)($product['pack_biggest_qty'] ?? 0),
            ];
        }

        return $this->respond([
            'status' => 'success',
            'data' => $processedProducts
        ], 200);
    }

    public function submitOrder()
    {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        $json = $this->request->getJSON();
        if (!$json || empty($json->items)) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid order data'], 400);
        }

        $db = \Config\Database::connect();
        $itemModel = new ItemModel();
        $salesModel = new \App\Models\SalesModel();
        $transactionModel = new \App\Models\TransactionModel();

        // 1. Validate Stock for all items in a single query
        $itemIds = array_unique(array_column($json->items, 'id'));
        $dbItems = $itemModel->whereIn('id', $itemIds)->findAll();
        $itemsMap = [];
        foreach ($dbItems as $it) {
            $itemsMap[$it['id']] = $it;
        }

        // Check availability before starting transaction
        foreach ($json->items as $item) {
            $dbItem = $itemsMap[$item->id] ?? null;
            if (!$dbItem) {
                return $this->respond(['status' => 'error', 'message' => "Product ID {$item->id} not found"], 422);
            }

            $qtyCol = 'quantity';
            $var = strtolower($item->variation ?? '');
            if (strpos($var, 'small') !== false) $qtyCol = 'pack_small_qty';
            elseif (strpos($var, 'medium') !== false) $qtyCol = 'pack_medium_qty';
            elseif (strpos($var, 'large') !== false) $qtyCol = 'pack_biggest_qty';

            if (($dbItem[$qtyCol] ?? 0) < $item->qty) {
                return $this->respond([
                    'status' => 'error', 
                    'message' => "Insufficient stock for {$dbItem['name']} ({$item->variation}). Available: " . ($dbItem[$qtyCol] ?? 0)
                ], 422);
            }
        }

        // 2. Atomic Order Processing
        $txnId = 'API-' . strtoupper(substr(uniqid(), -6));
        $totalAmount = 0;
        $salesBatch = [];
        $itemUpdates = [];
        $now = date('Y-m-d H:i:s');

        try {
            $db->transStart();

            foreach ($json->items as $item) {
                $dbItem = $itemsMap[$item->id];
                $subtotal = $item->price * $item->qty;
                $totalAmount += $subtotal;

                $salesBatch[] = [
                    'transaction_id' => $txnId,
                    'user_id'        => 0, // 0 indicates External API Order
                    'product_id'     => $item->id,
                    'quantity'       => $item->qty,
                    'price'          => $item->price,
                    'total'          => $subtotal,
                    'pack'           => $item->variation ?? '1pc',
                    'payment_method' => $json->payment_method ?? 'External API',
                    'customer_name'  => $json->customer_name ?? 'Guest',
                    'customer_email' => $json->customer_email ?? null,
                    'is_seen'        => 0,
                    'created_at'     => $now
                ];

                // Prepare Stock Deduction
                $qtyCol = 'quantity';
                $var = strtolower($item->variation ?? '');
                if (strpos($var, 'small') !== false) $qtyCol = 'pack_small_qty';
                elseif (strpos($var, 'medium') !== false) $qtyCol = 'pack_medium_qty';
                elseif (strpos($var, 'large') !== false) $qtyCol = 'pack_biggest_qty';

                if (!isset($itemUpdates[$item->id])) {
                    $itemUpdates[$item->id] = ['id' => $item->id];
                }
                
                // Cumulative deduction in case same item appears twice
                $currentNewQty = $itemUpdates[$item->id][$qtyCol] ?? $dbItem[$qtyCol];
                $itemUpdates[$item->id][$qtyCol] = $currentNewQty - $item->qty;
            }

            // Create Transaction Record
            $transactionModel->insert([
                'transaction_id' => $txnId,
                'user_id'        => 0,
                'total_amount'   => $totalAmount,
                'payment_method' => $json->payment_method ?? 'External API',
                'customer_name'  => $json->customer_name ?? 'Guest',
                'customer_email' => $json->customer_email ?? null,
                'created_at'     => $now
            ]);

            // Execute Batch Operations
            if (!empty($salesBatch)) $salesModel->insertBatch($salesBatch);
            if (!empty($itemUpdates)) $itemModel->updateBatch(array_values($itemUpdates), 'id');

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed to commit order.');
            }

            // 3. Email Notification (Optional/Best Effort)
            if (!empty($json->customer_email)) {
                $this->sendOrderEmail($txnId, $json, $totalAmount);
            }

            return $this->respond([
                'status'     => 'success', 
                'order_id'   => $txnId,
                'line_count' => count($salesBatch)
            ], 200);

        } catch (\Exception $e) {
            if ($db->transEnabled()) $db->transRollback();
            return $this->respond(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function sendOrderEmail($txnId, $json, $totalAmount)
    {
        try {
            $email = \Config\Services::email();
            $email->setTo($json->customer_email);
            $email->setSubject("Order Confirmation: {$txnId}");
            
            $itemsHtml = "";
            foreach ($json->items as $item) {
                $itemsHtml .= "<li>{$item->name} ({$item->variation}) x {$item->qty} - ₱" . number_format($item->price * $item->qty, 2) . "</li>";
            }

            $message = "
                <h2>Thank you for your order!</h2>
                <p><b>Order ID:</b> {$txnId}</p>
                <ul>{$itemsHtml}</ul>
                <p><b>Total Amount:</b> ₱" . number_format($totalAmount, 2) . "</p>
                <p>Your order has been processed and stock has been reserved.</p>
            ";

            $email->setMessage($message);
            $email->send();
        } catch (\Exception $e) {
            log_message('error', 'API Order Email Error: ' . $e->getMessage());
        }
    }

    public function getPendingOrders()
    {
        $this->ensureTablesExist();
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        $orderModel = new \App\Models\OnlineOrderModel();
        $itemModel = new \App\Models\OnlineOrderItemModel();

        $orders = $orderModel->where('status', 'Pending')->orderBy('created_at', 'DESC')->findAll();
        
        $result = [];
        foreach ($orders as $order) {
            $items = $itemModel->where('order_id', $order->order_id)->findAll();
            $orderArray = (array)$order;
            $orderArray['items'] = $items;
            $result[] = $orderArray;
        }

        return $this->respond(['status' => 'success', 'data' => $result], 200);
    }

    public function confirmOrder()
    {
        $this->ensureTablesExist();

        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $this->response->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        if ($this->request->getMethod() === 'options') {
            return $this->response->setStatusCode(200);
        }

        $json = $this->request->getJSON();
        if (!$json || empty($json->order_id)) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid order ID'], 400);
        }

        $orderModel = new \App\Models\OnlineOrderModel();
        
        $order = $orderModel->where('order_id', $json->order_id)->first();
        if (!$order) {
            return $this->respond(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $orderModel->update($order->id, ['status' => 'Completed']);

        return $this->respond(['status' => 'success', 'message' => 'Order confirmed'], 200);
    }

    private function ensureTablesExist()
    {
        $db = \Config\Database::connect();
        
        $sql1 = "CREATE TABLE IF NOT EXISTS `online_orders` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` varchar(50) NOT NULL,
            `customer_name` varchar(150) NOT NULL,
            `customer_email` varchar(150) NOT NULL,
            `customer_phone` varchar(50) NOT NULL,
            `total_amount` decimal(10,2) NOT NULL,
            `status` varchar(50) DEFAULT 'Pending',
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $sql2 = "CREATE TABLE IF NOT EXISTS `online_order_items` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` varchar(50) NOT NULL,
            `product_id` int(11) NOT NULL,
            `product_name` varchar(150) NOT NULL,
            `variation` varchar(50) DEFAULT NULL,
            `quantity` int(11) NOT NULL,
            `price` decimal(10,2) NOT NULL,
            `subtotal` decimal(10,2) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

        $db->query($sql1);
        $db->query($sql2);
    }
}
