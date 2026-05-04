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
        if (!$json) {
            return $this->respond(['status' => 'error', 'message' => 'Invalid JSON'], 400);
        }

        $orderModel = new \App\Models\OnlineOrderModel();
        $itemModel = new \App\Models\OnlineOrderItemModel();

        $orderId = 'ORD-' . strtoupper(substr(uniqid(), -6)) . rand(100, 999);

        // Calculate total amount
        $totalAmount = 0;
        foreach ($json->items as $item) {
            $subtotal = $item->price * $item->qty;
            $totalAmount += $subtotal;
        }

        $orderData = [
            'order_id' => $orderId,
            'customer_name' => $json->customer_name,
            'customer_email' => $json->customer_email,
            'customer_phone' => $json->customer_phone,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        if ($orderModel->insert($orderData)) {
            foreach ($json->items as $item) {
                $subtotal = $item->price * $item->qty;
                $itemModel->insert([
                    'order_id' => $orderId,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'variation' => $item->variation ?? null,
                    'quantity' => $item->qty,
                    'price' => $item->price,
                    'subtotal' => $subtotal
                ]);
            }
            
            // --- SEND EMAIL NOTIFICATION ---
            if (!empty($json->customer_email)) {
                try {
                    $emailHelper = \Config\Services::email();
                    $config = [
                        'protocol'   => getenv('email.protocol') ?: 'smtp',
                        'SMTPHost'   => getenv('email.SMTPHost') ?: 'smtp.gmail.com',
                        'SMTPUser'   => getenv('email.SMTPUser'),
                        'SMTPPass'   => getenv('email.SMTPPass'),
                        'SMTPPort'   => (int)(getenv('email.SMTPPort') ?: 465),
                        'SMTPCrypto' => getenv('email.SMTPCrypto') ?: 'ssl',
                        'mailType'   => 'html',
                        'charset'    => 'utf-8',
                        'newline'    => "\r\n",
                        'CRLF'       => "\r\n"
                    ];
                    $emailHelper->initialize($config);

                    $dateFormatted = date('F j, Y, g:i A');
                    $customerGreeting = !empty($json->customer_name) ? "Hi " . esc($json->customer_name) . "," : "Hi Valued Customer,";

                    $receiptHtml = '
                    <html>
                    <head>
                        <style>
                            body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; background-color: #f4f6f9; color: #333; margin: 0; padding: 20px; }
                            .receipt-container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
                            .header { background-color: #4e73df; color: #fff; text-align: center; padding: 30px 20px; }
                            .header h1 { margin: 0; font-size: 24px; letter-spacing: 1px; }
                            .header p { margin: 5px 0 0; opacity: 0.8; font-size: 14px; }
                            .content { padding: 30px; }
                            .greeting { font-size: 18px; margin-bottom: 20px; color: #2c3e50; }
                            .table-wrapper { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                            .table-wrapper th, .table-wrapper td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; }
                            .table-wrapper th { background-color: #f8f9fa; font-weight: 600; color: #5a5c69; text-transform: uppercase; font-size: 12px; }
                            .table-wrapper td { font-size: 14px; color: #3a3b45; }
                            .text-right { text-align: right !important; }
                            .total-row { font-weight: bold; background-color: #f8f9fc; }
                            .footer { text-align: center; padding: 20px; background: #f8f9fc; font-size: 12px; color: #858796; border-top: 1px solid #eee; }
                        </style>
                    </head>
                    <body>
                        <div class="receipt-container">
                            <div class="header">
                                <h1>HALIMAW SIOMAI</h1>
                                <p>Online Order Confirmation</p>
                            </div>
                            <div class="content">
                                <div class="greeting">' . $customerGreeting . '<br><br>Thank you for your order! Your order has been placed and is currently pending.</div>
                                <div style="margin-bottom: 15px; font-size: 13px; color: #858796;">
                                    <b>Order Number:</b> ' . esc($orderId) . '<br>
                                    <b>Date:</b> ' . $dateFormatted . '
                                </div>
                                <table class="table-wrapper">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th style="text-align:center;">Qty</th>
                                            <th class="text-right">Price</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>';

                    foreach ($json->items as $item) {
                        $itemName = esc($item->name);
                        $pack = isset($item->variation) && $item->variation ? " <small style='color:#888'>(" . esc($item->variation) . ")</small>" : "";
                        $qty = (int)$item->qty;
                        $basePrice = (float)$item->price;
                        $sub = $basePrice * $qty;

                        $receiptHtml .= '
                                        <tr>
                                            <td>' . $itemName . $pack . '</td>
                                            <td style="text-align:center;">' . $qty . '</td>
                                            <td class="text-right">&#8369;' . number_format($basePrice, 2) . '</td>
                                            <td class="text-right">&#8369;' . number_format($sub, 2) . '</td>
                                        </tr>';
                    }

                    $receiptHtml .= '
                                        <tr class="total-row">
                                            <td colspan="3" class="text-right" style="font-size: 16px;">Total Amount:</td>
                                            <td class="text-right" style="font-size: 16px; color: #1cc88a;">&#8369;' . number_format($totalAmount, 2) . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <p style="text-align: center; margin-top: 30px; font-size: 14px; color: #5a5c69;">
                                    Please present your Order Number when you pick up or receive your delivery.
                                </p>
                            </div>
                        </div>
                    </body>
                    </html>';

                    $emailHelper->setTo($json->customer_email);
                    
                    $senderEmail = !empty($config['SMTPUser']) ? $config['SMTPUser'] : 'noreply@halimawsiomai.local';
                    $emailHelper->setFrom($senderEmail, 'Halimaw Siomai Online');
                    
                    $emailHelper->setSubject('Order Confirmation: ' . esc($orderId) . ' - Halimaw Siomai');
                    $emailHelper->setMessage($receiptHtml);
                    $emailHelper->setMailType('html');
                    
                    if (!$emailHelper->send(false)) {
                        log_message('error', 'Online Order Email Failed: ' . $emailHelper->printDebugger(['headers']));
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Online Order Email Exception: ' . $e->getMessage());
                }
            }
            // --- END EMAIL ---

            return $this->respond(['status' => 'success', 'order_id' => $orderId], 200);
        } else {
            return $this->respond(['status' => 'error', 'message' => 'Failed to save order'], 500);
        }
    }

    public function getPendingOrders()
    {
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
}
