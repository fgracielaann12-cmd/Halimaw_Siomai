<?php

namespace App\Controllers;

use App\Models\PullOutModel;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class PullOutController extends BaseController
{
    protected $pullOutModel;
    protected $itemModel;

    public function __construct()
    {
        $this->pullOutModel = new PullOutModel();
        $this->itemModel    = new ItemModel();
    }

    // --- STAFF API ---
    public function submit()
    {
        $request = service('request');

        $productId     = $request->getPost('product_id');
        $quantity      = $request->getPost('quantity');
        $pullOutReason = $request->getPost('pull_out_reason');
        $reasonNote    = $request->getPost('reason_note');
        $userId        = session()->get('user_id');

        if (!$productId || !$quantity || !$pullOutReason) {
            return $this->response->setJSON(['success' => false, 'message' => 'Product, Quantity, and Reason are required.']);
        }

        $data = [
            'product_id'      => $productId,
            'quantity'        => $quantity,
            'pull_out_reason' => $pullOutReason,
            'reason_note'     => $reasonNote,
            'reported_by'     => $userId,
            'status'          => 'PENDING'
        ];

        if ($this->pullOutModel->insert($data)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Pull-out request submitted successfully.']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to submit pull-out request.']);
        }
    }

    // --- ADMIN VIEWS & ACTIONS ---
    public function index()
    {
        $pullOuts = $this->pullOutModel
            ->select('pull_outs.*, items.name as product_name, items.product_id as product_sku, users.username as reporter_name')
            ->join('items', 'items.id = pull_outs.product_id', 'left')
            ->join('users', 'users.id = pull_outs.reported_by', 'left')
            ->orderBy('pull_outs.date_reported', 'DESC')
            ->findAll();

        $data = [
            'title'       => 'Food Waste Pull-Outs',
            'currentPath' => uri_string(),
            'pullOuts'    => $pullOuts
        ];

        return view('admin/pull_outs', $data);
    }

    public function approve($id)
    {
        $pullOut = $this->pullOutModel->find($id);
        if (!$pullOut || $pullOut['status'] !== 'PENDING') {
            return redirect()->back()->with('error', 'Invalid pull-out request or already processed.');
        }

        $item = $this->itemModel->find($pullOut['product_id']);
        if (!$item) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        if ($item['quantity'] < $pullOut['quantity']) {
            return redirect()->back()->with('error', 'Insufficient stock to approve this pull-out.');
        }

        // Deduct inventory
        $newQty = $item['quantity'] - $pullOut['quantity'];
        $this->itemModel->update($item['id'], ['quantity' => $newQty]);

        // Mark as approved
        $this->pullOutModel->update($id, ['status' => 'APPROVED']);

        return redirect()->back()->with('success', 'Pull-out request approved and inventory deducted.');
    }

    public function reject($id)
    {
        $pullOut = $this->pullOutModel->find($id);
        if (!$pullOut || $pullOut['status'] !== 'PENDING') {
            return redirect()->back()->with('error', 'Invalid pull-out request or already processed.');
        }

        // Mark as rejected
        $this->pullOutModel->update($id, ['status' => 'REJECTED']);

        return redirect()->back()->with('success', 'Pull-out request rejected.');
    }
}
