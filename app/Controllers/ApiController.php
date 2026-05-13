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

        // 1. Validate Items and Parse IDs
        $itemsToProcess = [];
        $totalAmount = 0;
        foreach ($json->items as $item) {
            $rawId = $item->id;
            $variation = $item->variation ?? '';
            
            // Fallback: If ID is composite (e.g., "5616-Medium"), split it
            if (strpos($rawId, '-') !== false && !is_numeric($rawId)) {
                list($pId, $vId) = explode('-', $rawId);
                $rawId = $pId;
                if (empty($variation)) $variation = $vId;
            }
            
            $dbItem = $itemModel->find($rawId);
            if (!$dbItem) {
                return $this->respond(['status' => 'error', 'message' => "Product ID {$rawId} not found"], 422);
            }

            $subtotal = $item->price * $item->qty;
            $totalAmount += $subtotal;

            $itemsToProcess[] = [
                'product_id'   => $dbItem['id'],
                'product_name' => $dbItem['name'],
                'variation'    => $variation,
                'quantity'     => $item->qty,
                'price'        => $item->price,
                'subtotal'     => $subtotal
            ];
        }

        // 2. Record Order for Approval
        $txnId = 'ORD-' . strtoupper(substr(uniqid(), -6));
        $now = date('Y-m-d H:i:s');

        try {
            $db->transStart();

            // Create Online Order Record (for management tracking)
            $onlineOrderModel = new \App\Models\OnlineOrderModel();
            $onlineOrderModel->insert([
                'order_id'       => $txnId,
                'customer_name'  => $json->customer_name ?? 'Guest',
                'customer_email' => $json->customer_email ?? '',
                'customer_phone' => $json->customer_phone ?? '',
                'total_amount'   => $totalAmount,
                'status'         => 'Pending',
                'created_at'     => $now
            ]);

            $onlineOrderItems = [];
            foreach ($itemsToProcess as $entry) {
                $onlineOrderItems[] = [
                    'order_id'     => $txnId,
                    'product_id'   => $entry['product_id'],
                    'product_name' => $entry['product_name'],
                    'variation'    => $entry['variation'],
                    'quantity'     => $entry['quantity'],
                    'price'        => $entry['price'],
                    'subtotal'     => $entry['subtotal']
                ];
            }

            if (!empty($onlineOrderItems)) {
                $onlineOrderItemModel = new \App\Models\OnlineOrderItemModel();
                $onlineOrderItemModel->insertBatch($onlineOrderItems);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->respond(['status' => 'error', 'message' => 'Failed to submit order'], 500);
            }

            return $this->respond([
                'status' => 'success', 
                'message' => 'Order submitted successfully! Please wait for staff approval.',
                'order_id' => $txnId
            ]);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed to commit order.');
            }

            // 3. Email Notification (Handled client-side via EmailJS)
            /*
            if (!empty($json->customer_email)) {
                $this->sendOrderEmail($txnId, $json, $totalAmount);
            }
            */

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
        /*
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
        */
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
