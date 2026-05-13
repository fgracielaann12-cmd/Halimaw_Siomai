<?php

namespace App\Controllers;

use App\Models\OnlineOrderModel;
use App\Models\OnlineOrderItemModel;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class OnlineOrdersController extends BaseController
{
    protected $orderModel;
    protected $itemModel;

    public function __construct()
    {
        $this->orderModel = new OnlineOrderModel();
        $this->itemModel = new ItemModel();
    }

    public function index()
    {
        $orders = $this->orderModel->orderBy('created_at', 'DESC')->findAll();
        
        $data = [
            'orders'      => $orders,
            'title'       => 'Online Orders',
            'currentPath' => uri_string(),
        ];

        return view('admin/online_orders', $data);
    }

    public function view($orderId)
    {
        $order = $this->orderModel->where('order_id', $orderId)->first();
        if (!$order) {
            return $this->response->setJSON(['success' => false, 'message' => 'Order not found']);
        }

        $itemModel = new OnlineOrderItemModel();
        $items = $itemModel->where('order_id', $orderId)->findAll();

        return $this->response->setJSON(['success' => true, 'order' => $order, 'items' => $items]);
    }

    public function confirm($id)
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $this->orderModel->update($id, ['status' => 'Completed']);
        return redirect()->back()->with('success', 'Order marked as Completed');
    }

    public function cancel($id)
    {
        // Cancellation logic could also restore stock, but for now we just change status
        $this->orderModel->update($id, ['status' => 'Cancelled']);
        return redirect()->back()->with('success', 'Order cancelled');
    }
}
