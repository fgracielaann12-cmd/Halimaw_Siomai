<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\SalesModel;

class UserPosController extends BaseController
{
    protected $productModel;
    protected $salesModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->salesModel = new SalesModel();
    }

    public function index()
    {
        $products = $this->productModel
            ->where('status !=', 'expired')
            ->findAll();

        $data['products'] = $this->groupProductsForPos($products);
        
        return view('pos/user', $data);
    }

    private function groupProductsForPos($products)
    {
        $grouped = [];
        $variationsMap = [];

        foreach ($products as $product) {
            // Remove the isSiomai skip to allow grouping siomai variations
            
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
        $cart = $this->request->getJSON(true)['cart'] ?? null;
        $userId = session()->get('id') ?? 1; // Replace 1 with session ID when logged in

        if (!$cart || empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cart is empty']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $transactionId = 'OUT-' . date('Ymd') . '-' . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $totalSaleAmount = 0;
        $salesBatch = [];

        try {
            foreach ($cart as $item) {
                // Use ID if available, otherwise fallback to name-based lookup
                $product = null;
                if (isset($item['product_id']) && is_numeric($item['product_id'])) {
                    $product = $this->productModel->find((int)$item['product_id']);
                }
                if (!$product) {
                    $product = $this->productModel->like('name', $item['name'], 'both')->first();
                }

                if (!$product) {
                    throw new \Exception("Product not found: ".$item['name']);
                }

                $quantity = $item['qty'];
                $price = $item['price'];
                $total = $price * $quantity;
                $totalSaleAmount += $total;

                // Prepare Sales record
                $salesBatch[] = [
                    'transaction_id' => $transactionId,
                    'user_id'        => $userId,
                    'product_id'     => $product['id'],
                    'quantity'       => $quantity,
                    'price'          => $price,
                    'total'          => $total,
                    'pack'           => $item['pack'] ?? null,
                    'created_at'     => date('Y-m-d H:i:s')
                ];

                // Update product quantity
                $currentQuantity = $product['quantity'] ?? $product['stock'] ?? 0;
                $newQuantity = max(0, $currentQuantity - $quantity);

                $updateData = ['quantity' => $newQuantity];
                if (!array_key_exists('quantity', $product)) {
                    $updateData = ['stock' => $newQuantity];
                }

                if (!$this->productModel->update($product['id'], $updateData)) {
                    $errors = $this->productModel->errors();
                    throw new \Exception("Failed to update product quantity: ".json_encode($errors));
                }
            }

            // Insert Transaction Summary
            $transactionModel = new \App\Models\TransactionModel();
            $transactionModel->insert([
                'transaction_id' => $transactionId,
                'user_id'        => $userId,
                'total_amount'   => $totalSaleAmount,
                'created_at'     => date('Y-m-d H:i:s')
            ]);

            // Batch insert sales
            $this->salesModel->insertBatch($salesBatch);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception("Database transaction failed at commit.");
            }

            return $this->response->setJSON([
                'success' => true,
                'total' => $totalSaleAmount,
                'message' => 'Sale completed successfully'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'User POS Sale Error: '.$e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
