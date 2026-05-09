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
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('pos/staff', ['products' => $products, 'items' => $items]);
    }

    private function groupProductsForPos($products)
    {
        $grouped = [];
        $variationsMap = [];

        foreach ($products as $product) {
            $isSiomai = stripos($product['name'] ?? '', 'siomai') !== false;
            
            // Siomai already has its own pack logic handling, skip grouping them here
            if ($isSiomai) {
                $grouped[$product['product_id']] = $product;
                continue;
            }

            // Detect if this is a size variation (e.g., P001-S)
            if (preg_match('/^(.*?)-([A-Za-z0-9]+)$/', $product['product_id'], $matches)) {
                $baseId = $matches[1];
                $suffix = $matches[2];
                
                $baseName = $product['name'];
                $label = $suffix;
                
                if (preg_match('/^(.*?)\s+(Small|Medium|Large|Extra Large|XL|XXL)$/i', $product['name'], $nameMatches)) {
                    $baseName = trim($nameMatches[1]);
                    $label = trim($nameMatches[2]);
                } elseif (preg_match('/^(.*?)\s*\((Small|Medium|Large|Extra Large|XL|XXL)\)$/i', $product['name'], $nameMatches)) {
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
        return $this->response->setJSON([
            'success' => false,
            'message' => 'User not logged in'
        ]);
    }

    if (!$cart || !is_array($cart) || empty($cart)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Cart is empty or invalid'
        ]);
    }

    $db = \Config\Database::connect();
    $db->transBegin();
    $totalSaleAmount = 0;
    
    // Use ItemModel for stock updates (consistent with AdminRequests)
    $itemModel = new \App\Models\ItemModel();
    $transactionModel = new \App\Models\TransactionModel();
    $transactionId = 'OUT-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

    try {
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

            $product = null;
            if (is_numeric($productIdRaw)) {
                $product = $itemModel->find((int)$productIdRaw);
            }
            if (!$product) {
                $product = $itemModel->where('product_id', $productIdRaw)->first();
            }

            if (!$product) {
                throw new \Exception("Product ID {$productIdRaw} not found");
            }

            $realId = $product['id'];

            // 🔑 CALCULATE REAL QUANTITY TO DEDUCT AND WHICH COLUMN TO DEDUCT FROM
            $deductQty = $displayQty;
            $stockColumn = 'quantity'; // default
            
            if ($type === 'siomai' && isset($item['pack'])) {
                switch ($item['pack']) {
                    case 'Small Pack':
                        $stockColumn = 'pack_small_qty';
                        break;
                    case 'Medium Pack':
                        $stockColumn = 'pack_medium_qty';
                        break;
                    case 'Large Pack':
                        $stockColumn = 'pack_biggest_qty';
                        break;
                }
            } elseif ($type === 'patty') {
                $deductQty = $displayQty * 6;
            }

            $currentStock = (int) ($product[$stockColumn] ?? 0);
            if ($currentStock < $deductQty) {
                $productName = esc($product['name'] ?? 'Unknown Item');
                $packSuffix = isset($item['pack']) ? " ({$item['pack']})" : "";
                throw new \Exception("Insufficient stock for '{$productName}{$packSuffix}'. Available: {$currentStock}, Required: {$deductQty}");
            }

            $total = $price * $displayQty;
            $totalSaleAmount += $total;

            $saleData = [
                'transaction_id' => $transactionId,
                'user_id'        => $userId,
                'product_id'     => $realId,
                'quantity'       => $displayQty, // This now reflects raw count of packs!
                'pack'           => $item['pack'] ?? ($type === 'patty' ? '6pcs' : null),
                'price'          => $price,
                'total'          => $total,
                'payment_method' => $paymentMethod,
                'customer_name'  => $customerName,
                'customer_email' => $customerEmail,
            ];

            if (!$this->salesModel->insert($saleData)) {
                throw new \Exception("Failed to record sale for item: " . ($product['name'] ?? $productIdRaw));
            }

            // Deduct from inventory
            $newStock = $currentStock - $deductQty;
            
            log_message('info', "Deducting Stock: Product ID {$realId} (Raw: {$productIdRaw}), Name: {$product['name']}, Column: {$stockColumn}, Old: {$currentStock}, Deduct: {$deductQty}, New: {$newStock}");

            if (!$itemModel->update($realId, [$stockColumn => $newStock])) {
                throw new \Exception("Failed to update inventory for: " . ($product['name'] ?? $productIdRaw));
            }
        }

        $transactionData = [
            'transaction_id' => $transactionId,
            'user_id'        => $userId,
            'total_amount'   => $totalSaleAmount,
            'payment_method' => $paymentMethod,
            'customer_name'  => $customerName,
            'customer_email' => $customerEmail,
            'vat_applied'    => $applyVat ? 1 : 0,
            'vat_type'       => $vatType,
            'created_at'     => date('Y-m-d H:i:s')
        ];

        if (!$transactionModel->insert($transactionData)) {
            throw new \Exception("Failed to record transaction summary.");
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \Exception("Database transaction failed");
        } else {
            $db->transCommit();
        }

        // --- EMAIL RECEIPT LOGIC ---
        if (!empty($customerEmail)) {
            try {
                $emailHelper = \Config\Services::email();
                
                // Manually load config to avoid CI4 .env silent mapping issues
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
                $customerGreeting = !empty($customerName) ? "Hi " . esc($customerName) . "," : "Hi Valued Customer,";
                
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
                            <p>Electronic Receipt</p>
                        </div>
                        <div class="content">
                            <div class="greeting">' . $customerGreeting . '<br><br>Thank you for your purchase! Here are the details of your order:</div>
                            <div style="margin-bottom: 15px; font-size: 13px; color: #858796;">
                                <b>Date:</b> ' . $dateFormatted . '<br>
                                <b>Payment:</b> ' . strtoupper($paymentMethod) . '
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

                $rawTotal = 0;
                foreach ($cart as $item) {
                    $productId = (int) $item['product_id'];
                    $product = $itemModel->find($productId);
                    $itemName = esc($product ? $product['name'] : ($item['name'] ?? 'Item'));
                    $pack = isset($item['pack']) ? " <small style='color:#888'>(" . esc($item['pack']) . ")</small>" : "";
                    $qty = (int)$item['qty'];
                    $basePrice = (float)$item['price'];
                    $sub = $basePrice * $qty;
                    $rawTotal += $sub;

                    $receiptHtml .= '
                                    <tr>
                                        <td>' . $itemName . $pack . '</td>
                                        <td style="text-align:center;">' . $qty . '</td>
                                        <td class="text-right">&#8369;' . number_format($basePrice, 2) . '</td>
                                        <td class="text-right">&#8369;' . number_format($sub, 2) . '</td>
                                    </tr>';
                }
                
                $vatStr = '';
                $grandTotalDisplay = $rawTotal;
                if ($applyVat) {
                    if ($vatType === 'included') {
                        $vatableSales = $rawTotal / 1.12;
                        $vat = $rawTotal - $vatableSales;
                        $vatStr = '
                                    <tr>
                                        <td colspan="3" class="text-right" style="color:#858796;">Vatable Sales:</td>
                                        <td class="text-right" style="color:#858796;">&#8369;' . number_format($vatableSales, 2) . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right" style="color:#858796;">12% VAT (Included):</td>
                                        <td class="text-right" style="color:#858796;">&#8369;' . number_format($vat, 2) . '</td>
                                    </tr>';
                    } else {
                        $vat = $rawTotal * 0.12;
                        $grandTotalDisplay = $rawTotal + $vat;
                        $vatStr = '
                                    <tr>
                                        <td colspan="3" class="text-right" style="color:#858796;">Vatable Sales:</td>
                                        <td class="text-right" style="color:#858796;">&#8369;' . number_format($rawTotal, 2) . '</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right" style="color:#858796;">12% VAT (Added):</td>
                                        <td class="text-right" style="color:#858796;">&#8369;' . number_format($vat, 2) . '</td>
                                    </tr>';
                    }
                }

                $receiptHtml .= $vatStr;
                $receiptHtml .= '
                                    <tr class="total-row">
                                        <td colspan="3" class="text-right" style="font-size: 16px;">Grand Total Due:</td>
                                        <td class="text-right" style="font-size: 16px; color: #1cc88a;">&#8369;' . number_format($grandTotalDisplay, 2) . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p style="text-align: center; margin-top: 30px; font-size: 14px; color: #5a5c69;">
                                We hope you enjoy your Halimaw Siomai! come back soon!
                            </p>
                        </div>
                    </div>
                </body>
                </html>';

                $emailHelper->setTo($customerEmail);
                
                // IMPORTANT: Set sender equal to the authenticated SMTPUser to prevent Google bounce
                $senderEmail = !empty($config['SMTPUser']) ? $config['SMTPUser'] : 'noreply@halimawsiomai.local';
                $emailHelper->setFrom($senderEmail, 'Halimaw Siomai POS');
                
                $emailHelper->setSubject('Your Electronic Receipt - Halimaw Siomai');
                $emailHelper->setMessage($receiptHtml);
                $emailHelper->setMailType('html');
                
                $emailError = '';
                if (!$emailHelper->send(false)) {
                    $emailError = $emailHelper->printDebugger(['headers']);
                    log_message('error', 'Email Sending Failed: ' . $emailError);
                }
            } catch (\Exception $e) {
                $emailError = $e->getMessage();
                log_message('error', 'Email Sending Exception: ' . $emailError);
            }
        }
        // --- END EMAIL ---

        $responseMessage = 'Sale completed successfully! Stocks updated.';
        if (!empty($emailError)) {
            $responseMessage .= " [Email failed: " . strip_tags($emailError) . "]";
        }

        return $this->response->setJSON([
            'success' => true,
            'total'   => round($totalSaleAmount, 2),
            'message' => $responseMessage
        ]);

    } catch (\Exception $e) {
        $db->transRollback();
        log_message('error', 'POS Sale Error: ' . $e->getMessage());
        return $this->response->setJSON([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}
}