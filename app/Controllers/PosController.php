<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SalesModel;
use CodeIgniter\Controller;

class PosController extends BaseController
{
    protected $productModel;
    protected $salesModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->salesModel   = new SalesModel();
    }

    public function adminIndex()
    {
        $products = $this->productModel
            ->where('quantity >', 0)
            ->where('status !=', 'expired')
            ->where('status !=', 'manually deleted')
            ->orderBy('name', 'ASC')
            ->findAll();

        foreach ($products as &$product) {
            if (!isset($product['image']) || empty($product['image'])) {
                $product['image'] = 'default.jpg';
            }
        }

        $products = $this->groupProductsForPos($products);

        return view('pos/index', ['products' => $products]);
    }

    public function staffIndex()
    {
        $products = $this->productModel
            ->where('quantity >', 0)
            ->where('status !=', 'expired')
            ->where('status !=', 'manually deleted')
            ->orderBy('name', 'ASC')
            ->findAll();

        foreach ($products as &$product) {
            if (!isset($product['image']) || empty($product['image'])) {
                $product['image'] = 'default.jpg';
            }
        }

        $products = $this->groupProductsForPos($products);

        $items = $this->productModel
            ->where('status !=', 'expired')
            ->where('status !=', 'manually deleted')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('pos/staff', ['products' => $products, 'items' => $items]);
    }

    private function groupProductsForPos($products)
    {
        $grouped = [];
        $variationsMap = [];

        foreach ($products as $product) {
            $isVariation = (isset($product['is_variation_child']) && $product['is_variation_child'] == 1);
            $groupId = $product['variation_group_id'] ?? null;

            if ($isVariation && $groupId) {
                // Use explicit variation grouping
                if (!isset($variationsMap[$groupId])) {
                    $variationsMap[$groupId] = [
                        'baseName' => preg_replace('/\s*(Small|Medium|Large|Extra Large|XL|XXL)$/i', '', $product['name']),
                        'category' => $product['category'] ?? '',
                        'image' => $product['image'] ?? 'default.jpg',
                        'image_path' => $product['image_path'] ?? null,
                        'expiration_date' => $product['expiration_date'] ?? null,
                        'totalStock' => 0,
                        'variations' => []
                    ];
                }
                
                $variationsMap[$groupId]['totalStock'] += (int) $product['quantity'];
                $variationsMap[$groupId]['variations'][] = [
                    'label' => $product['variation_label'] ?? $this->extractLabel($product['name']),
                    'price' => (float) $product['price'],
                    'stock' => (int) $product['quantity'],
                    'product_id' => $product['product_id'],
                    'id' => $product['id']
                ];
            } else if (preg_match('/^(.*?)-([A-Za-z0-9]+)$/', $product['product_id'], $matches)) {
                // Fallback for legacy regex-based variations
                $baseId = $matches[1];
                $suffix = $matches[2];
                
                $baseName = $product['name'];
                $label = $suffix;
                
                if (preg_match('/^(.*?)\s+(Small|Medium|Large|Extra Large|XL|XXL)$/i', $product['name'], $nameMatches)) {
                    $baseName = trim($nameMatches[1]);
                    $label = trim($nameMatches[2]);
                }

                if (!isset($variationsMap[$baseId])) {
                    $variationsMap[$baseId] = [
                        'baseName' => $baseName,
                        'category' => $product['category'] ?? '',
                        'image' => $product['image'] ?? 'default.jpg',
                        'image_path' => $product['image_path'] ?? null,
                        'expiration_date' => $product['expiration_date'] ?? null,
                        'totalStock' => 0,
                        'variations' => []
                    ];
                }
                
                $variationsMap[$baseId]['totalStock'] += (int) $product['quantity'];
                $variationsMap[$baseId]['variations'][] = [
                    'label' => $label,
                    'price' => (float) $product['price'],
                    'stock' => (int) $product['quantity'],
                    'product_id' => $product['product_id'],
                    'id' => $product['id']
                ];
            } else {
                // Regular item
                $grouped[$product['product_id']] = $product;
            }
        }

        foreach ($variationsMap as $baseId => $groupData) {
            $grouped[$baseId] = [
                'id' => $groupData['variations'][0]['id'] ?? 0, 
                'product_id' => $baseId,
                'name' => $groupData['baseName'],
                'category' => $groupData['category'],
                'image' => $groupData['image'],
                'image_path' => $groupData['image_path'],
                'expiration_date' => $groupData['expiration_date'],
                'quantity' => $groupData['totalStock'],
                'price' => $groupData['variations'][0]['price'] ?? 0,
                'is_custom_variation' => true,
                'custom_variations' => json_encode($groupData['variations'])
            ];
        }

        return array_values($grouped);
    }

    private function extractLabel($name) {
        if (preg_match('/\s*(Small|Medium|Large|Extra Large|XL|XXL)$/i', $name, $matches)) {
            return trim($matches[1]);
        }
        return 'Regular';
    }

    public function sell()
    {
        $input = $this->request->getJSON(true);
        $cart = $input['cart'] ?? null;
        $paymentMethod = $input['payment_method'] ?? 'cash';
        $customerName = $input['customer_name'] ?? null;
        $customerEmail = $input['customer_email'] ?? null;
        $applyVat = $input['apply_vat'] ?? false;
        $vatType = $input['vat_type'] ?? 'included';

        if (!$cart) {
            $cart = $this->request->getPost('cart');
            if ($cart && is_string($cart)) {
                $cart = json_decode($cart, true);
            }
            $paymentMethod = $this->request->getPost('payment_method') ?? 'cash';
        }

        $paymentMethod = trim($paymentMethod) ?: 'cash';
        $userId = session()->get('user_id');
        
        if (!$userId) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not logged in']);
        }

        if (!$cart || !is_array($cart) || empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cart is empty or invalid']);
        }

        $db = \Config\Database::connect();
        $itemModel = new \App\Models\ItemModel();
        $transactionModel = new \App\Models\TransactionModel();
        
        $transactionId = 'OUT-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $totalSaleAmount = 0;
        
        $salesBatch   = [];
        $stockUpdates = []; // keyed by item id → ['id' => ..., 'quantity' => ...]

        try {
            $db->transStart();

            // 1. PRE-PROCESSING & VALIDATION
            foreach ($cart as $item) {
                if (!isset($item['product_id'], $item['qty'], $item['price'])) {
                    throw new \Exception("Incomplete cart item data");
                }

                $productIdRaw = $item['product_id'];
                $displayQty = (int) $item['qty'];
                $price = (float) $item['price'];
                $type = $item['type'] ?? 'other';

                if ($applyVat && $vatType === 'excluded') {
                    $price = $price * 1.12;
                }

                $product = is_numeric($productIdRaw) ? $itemModel->find((int)$productIdRaw) : $itemModel->where('product_id', $productIdRaw)->first();

                if (!$product) {
                    throw new \Exception("Product ID {$productIdRaw} not found");
                }

                $realId = $product['id'];

                // All items (single or variation child) have their own quantity column.
                // No switch/case on type — always deduct from 'quantity'.
                $stockColumn = 'quantity';
                $deductQty   = $displayQty;

                // Cumulative stock check in case the same item appears multiple times in the cart
                $baseStock        = (int) ($product['quantity'] ?? 0);
                $alreadyDeducted  = isset($stockUpdates[$realId]) ? ($baseStock - $stockUpdates[$realId]['quantity']) : 0;
                $currentAvailable = $baseStock - $alreadyDeducted;

                if ($currentAvailable < $deductQty) {
                    $productName = esc($product['name'] ?? 'Unknown Item');
                    $packSuffix  = isset($item['pack']) ? " ({$item['pack']})" : "";
                    throw new \Exception("Insufficient stock for '{$productName}{$packSuffix}'. Available: {$currentAvailable}, Required: {$deductQty}");
                }

                $total = $price * $displayQty;
                $totalSaleAmount += $total;

                // Prepare Sales Batch Record
                $salesBatch[] = [
                    'transaction_id' => $transactionId,
                    'user_id'        => $userId,
                    'product_id'     => $realId,
                    'quantity'       => $displayQty,
                    'pack'           => $item['pack'] ?? null,
                    'price'          => $price,
                    'total'          => $total,
                    'payment_method' => $paymentMethod,
                    'customer_name'  => $customerName,
                    'customer_email' => $customerEmail,
                ];

                // Accumulate stock updates keyed by item id
                $stockUpdates[$realId] = [
                    'id'       => $realId,
                    'quantity' => $currentAvailable - $deductQty,
                ];
            }

            // 2. ATOMIC DB EXECUTION
            // A. Transaction Summary
            $transactionModel->insert([
                'transaction_id' => $transactionId,
                'user_id'        => $userId,
                'total_amount'   => $totalSaleAmount,
                'payment_method' => $paymentMethod,
                'customer_name'  => $customerName,
                'customer_email' => $customerEmail,
                'vat_applied'    => $applyVat ? 1 : 0,
                'vat_type'       => $vatType,
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            // B. Sales Details (Batch)
            $this->salesModel->insertBatch($salesBatch);

            // C. Inventory Adjustments (single column: quantity)
            if (!empty($stockUpdates)) {
                $itemModel->updateBatch(array_values($stockUpdates), 'id');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception("Database transaction failed to complete.");
            }

            // 3. EMAIL RECEIPT LOGIC (POST-COMMIT)
            $emailError = '';
            if (!empty($customerEmail)) {
                try {
                    $emailHelper = \Config\Services::email();
                    $config = [
                        'protocol'   => getenv('email.protocol') ?: 'smtp',
                        'SMTPHost'   => getenv('email.SMTPHost') ?: 'smtp.gmail.com',
                        'SMTPUser'   => getenv('email.SMTPUser'),
                        'SMTPPass'   => getenv('email.SMTPPass'),
                        'SMTPPort'   => (int)(getenv('email.SMTPPort') ?: 465),
                        'SMTPCrypto' => getenv('email.SMTPCrypto') ?: 'ssl',
                        'mailType'   => 'html', 'charset' => 'utf-8', 'newline' => "\r\n", 'CRLF' => "\r\n"
                    ];
                    $emailHelper->initialize($config);
                    
                    $receiptHtml = $this->renderReceiptHtml($customerName, $paymentMethod, $cart, $applyVat, $vatType, $totalSaleAmount, $itemModel);
                    
                    $emailHelper->setTo($customerEmail);
                    $senderEmail = !empty($config['SMTPUser']) ? $config['SMTPUser'] : 'noreply@halimawsiomai.local';
                    $emailHelper->setFrom($senderEmail, 'Halimaw Siomai POS');
                    $emailHelper->setSubject('Your Electronic Receipt - Halimaw Siomai');
                    $emailHelper->setMessage($receiptHtml);
                    
                    if (!$emailHelper->send(false)) {
                        $emailError = $emailHelper->printDebugger(['headers']);
                        log_message('error', 'Email Sending Failed: ' . $emailError);
                    }
                } catch (\Exception $e) {
                    $emailError = $e->getMessage();
                }
            }

            $responseMessage = 'Sale completed successfully!';
            if (!empty($emailError)) $responseMessage .= " [Email failed: " . strip_tags($emailError) . "]";

            return $this->response->setJSON([
                'success' => true,
                'total'   => round($totalSaleAmount, 2),
                'message' => $responseMessage
            ]);

        } catch (\Exception $e) {
            if ($db->transEnabled()) $db->transRollback();
            log_message('error', 'POS Sale Error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function renderReceiptHtml($customerName, $paymentMethod, $cart, $applyVat, $vatType, $rawTotal, $itemModel) {
        $dateFormatted = date('F j, Y, g:i A');
        $customerGreeting = !empty($customerName) ? "Hi " . esc($customerName) . "," : "Hi Valued Customer,";
        
        $rows = '';
        foreach ($cart as $item) {
            $product = $itemModel->find((int)$item['product_id']);
            $itemName = esc($product ? $product['name'] : ($item['name'] ?? 'Item'));
            $pack = isset($item['pack']) ? " (" . esc($item['pack']) . ")" : "";
            $sub = (float)$item['price'] * (int)$item['qty'];
            $rows .= "<tr><td>{$itemName}{$pack}</td><td style='text-align:center;'>{$item['qty']}</td><td style='text-align:right;'>₱" . number_format($item['price'], 2) . "</td><td style='text-align:right;'>₱" . number_format($sub, 2) . "</td></tr>";
        }
        
        $vatStr = '';
        $grandTotalDisplay = $rawTotal;
        if ($applyVat) {
            if ($vatType === 'included') {
                $vatableSales = $rawTotal / 1.12;
                $vatStr = "<tr><td colspan='3' style='text-align:right;'>Vatable Sales:</td><td style='text-align:right;'>₱" . number_format($vatableSales, 2) . "</td></tr><tr><td colspan='3' style='text-align:right;'>12% VAT (Incl):</td><td style='text-align:right;'>₱" . number_format($rawTotal - $vatableSales, 2) . "</td></tr>";
            } else {
                $vat = $rawTotal * 0.12;
                $grandTotalDisplay = $rawTotal + $vat;
                $vatStr = "<tr><td colspan='3' style='text-align:right;'>Vatable Sales:</td><td style='text-align:right;'>₱" . number_format($rawTotal, 2) . "</td></tr><tr><td colspan='3' style='text-align:right;'>12% VAT (Added):</td><td style='text-align:right;'>₱" . number_format($vat, 2) . "</td></tr>";
            }
        }

        return "<html><body style='font-family:sans-serif; padding:20px; color:#333;'><div style='max-width:600px; margin:auto; border:1px solid #eee; padding:20px; border-radius:8px;'><h2 style='text-align:center; color:#4e73df;'>HALIMAW SIOMAI</h2><p>{$customerGreeting}</p><p>Order Date: {$dateFormatted}<br>Payment: " . strtoupper($paymentMethod) . "</p><table style='width:100%; border-collapse:collapse;'><thead><tr style='background:#f8f9fa;'><th>Item</th><th style='text-align:center;'>Qty</th><th style='text-align:right;'>Price</th><th style='text-align:right;'>Subtotal</th></tr></thead><tbody>{$rows}{$vatStr}<tr style='font-weight:bold; background:#f8f9fc;'><td colspan='3' style='text-align:right;'>Total Due:</td><td style='text-align:right; color:#1cc88a;'>₱" . number_format($grandTotalDisplay, 2) . "</td></tr></tbody></table><p style='text-align:center; margin-top:30px;'>Thank you for your purchase!</p></div></body></html>";
    }

}
