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
    $data['products'] = $this->productModel
        ->where('status !=', 'expired')
        ->findAll();
    return view('pos/user', $data); // points to app/Views/pos/user.php
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

        $totalSaleAmount = 0;

        try {
            foreach ($cart as $item) {
                $product = $this->productModel->like('name', $item['name'], 'both')->first();

                if (!$product) {
                    throw new \Exception("Product not found: ".$item['name']);
                }

                $quantity = $item['qty'];
                $price = $item['price'];
                $total = $price * $quantity;
                $totalSaleAmount += $total;

                // Insert sale record
                $saleData = [
                    'user_id'    => $userId,
                    'product_id' => $product['id'],
                    'quantity'   => $quantity,
                    'price'      => $price,
                    'total'      => $total,
                    'pack'       => $item['pack'] ?? null,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if (!$this->salesModel->insert($saleData)) {
                    $errors = $this->salesModel->errors();
                    throw new \Exception("Failed to insert sale: ".json_encode($errors));
                }

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
