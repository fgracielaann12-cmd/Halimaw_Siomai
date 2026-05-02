<?php

namespace App\Controllers;

use App\Models\SalesModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class SalesController extends BaseController
{
    protected $salesModel;
    protected $productModel;
    protected $userModel;

    public function __construct()
    {
        $this->salesModel   = new SalesModel();
        $this->productModel = new ProductModel();
        $this->userModel    = new UserModel();
    }

    public function index()
    {
        // Mark sales as seen to clear the notification badge
        $this->salesModel->where('is_seen', 0)->set(['is_seen' => 1])->update();

        // Fetch all sales with product and user info
        $sales = $this->salesModel
            ->select('sales.*, products.name as product_name, users.username as user_name')
            ->join('products', 'products.id = sales.product_id', 'left')
            ->join('users', 'users.id = sales.user_id', 'left')
            ->orderBy('sales.created_at', 'DESC')
            ->findAll();

        foreach ($sales as &$sale) {
            // 🔥 FIX PRODUCT NAME: Show actual name or ID if missing
            if (empty($sale->product_name)) {
                // Try to fetch even soft-deleted products
                $product = $this->productModel->withDeleted()->find($sale->product_id);
                $sale->product_name = $product ? $product['name'] : 'Unknown Product (ID: ' . esc($sale->product_id) . ')';
            }

            // 🔥 FIX USER NAME: Show actual username or ID if missing
            if (empty($sale->user_name)) {
                $user = $this->userModel->find($sale->user_id);
                $sale->user_name = $user ? $user['username'] : 'Unknown User (ID: ' . esc($sale->user_id) . ')';
            }

            // 🔥 FIX PACK DISPLAY:
            if (!empty($sale->pack)) {
                // Keep existing pack values (e.g., "6pcs", "12pcs")
                $sale->pack = $sale->pack;
            } else {
                // Default to "1pc" for sauces, pastil, etc.
                $sale->pack = '1pc';
            }
        }

        $data = [
            'sales'       => $sales,
            'title'       => 'Sales Records',
            'currentPath' => uri_string(),
        ];

        return view('sales/list', $data);
    }
}