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

        $items = $this->productModel
            ->where('status !=', 'expired')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('pos/staff', ['products' => $products, 'items' => $items]);
    }

public function sell()
{
    $input = $this->request->getJSON(true);
    $cart = $input['cart'] ?? null;
    $paymentMethod = $input['payment_method'] ?? 'cash';
    $customerName = $input['customer_name'] ?? null;
    $customerEmail = $input['customer_email'] ?? null;

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

    try {
        foreach ($cart as $item) {
            if (!isset($item['product_id'], $item['qty'], $item['price'])) {
                throw new \Exception("Incomplete cart item data");
            }

            $productId = (int) $item['product_id'];
            $displayQty = (int) $item['qty'];
            $price = (float) $item['price'];
            $type = $item['type'] ?? 'other';

            $product = $itemModel->find($productId);
            if (!$product) {
                throw new \Exception("Product ID {$productId} not found");
            }

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
                    case 'Biggest Pack':
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
                'user_id'        => $userId,
                'product_id'     => $productId,
                'quantity'       => $displayQty, // This now reflects raw count of packs!
                'pack'           => $item['pack'] ?? ($type === 'patty' ? '6pcs' : null),
                'price'          => $price,
                'total'          => $total,
                'payment_method' => $paymentMethod,
                'customer_name'  => $customerName,
                'customer_email' => $customerEmail,
            ];

            if (!$this->salesModel->insert($saleData)) {
                throw new \Exception("Failed to record sale for item: " . ($product['name'] ?? $productId));
            }

            // Deduct from inventory
            $newStock = $currentStock - $deductQty;
            
            log_message('info', "Deducting Stock: Product ID {$productId}, Name: {$product['name']}, Column: {$stockColumn}, Old: {$currentStock}, Deduct: {$deductQty}, New: {$newStock}");

            if (!$itemModel->update($productId, [$stockColumn => $newStock])) {
                throw new \Exception("Failed to update inventory for: " . ($product['name'] ?? $productId));
            }
        }

        if ($db->transStatus() === false) {
            $db->transRollback();
            throw new \Exception("Database transaction failed");
        } else {
            $db->transCommit();
        }

        return $this->response->setJSON([
            'success' => true,
            'total'   => round($totalSaleAmount, 2),
            'message' => 'Sale completed successfully! Stocks updated.'
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