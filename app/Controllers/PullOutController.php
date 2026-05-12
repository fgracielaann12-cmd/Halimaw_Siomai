<?php

namespace App\Controllers;

use App\Models\PullOutModel;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class PullOutController extends BaseController
{
    protected $pullOutModel;
    protected $itemModel;
    protected $itemLogModel;

    public function __construct()
    {
        $this->pullOutModel = new PullOutModel();
        $this->itemModel    = new ItemModel();
        $this->itemLogModel = new \App\Models\ItemLogModel();
    }

    // --- STAFF API ---
    public function submit()
    {
        $request = service('request');

        $itemId    = $request->getPost('item_id');
        $variation = $request->getPost('variation');
        $quantity  = (int)$request->getPost('quantity');
        $reason    = $request->getPost('reason');
        
        $category  = $request->getPost('category');
        if (empty($category)) {
            $category = ($reason === 'CUSTOMER_RETURN') ? 'Customer Return' : 'Food Waste';
        }
        
        $note      = $request->getPost('note');
        $userId    = session()->get('user_id');

        if (!$itemId || !$quantity || !$reason) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item, Quantity, and Reason are required.']);
        }

        $item = $this->itemModel->find($itemId);
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found.']);
        }

        // Determine unit cost
        $unitCost = $item['price'];
        $varLower = strtolower($variation ?? '');
        if ((strpos($varLower, 'small') !== false) && isset($item['pack_small_price'])) $unitCost = $item['pack_small_price'];
        if ((strpos($varLower, 'medium') !== false) && isset($item['pack_medium_price'])) $unitCost = $item['pack_medium_price'];
        if ((strpos($varLower, 'large') !== false) && isset($item['pack_biggest_price'])) $unitCost = $item['pack_biggest_price'];

        $totalLoss = $unitCost * $quantity;

        // Handle Image Upload
        $imagePath = null;
        $file = $this->request->getFile('image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/pullouts', $newName);
            $imagePath = 'uploads/pullouts/' . $newName;
        }

        $data = [
            'product_id'      => $itemId, // Maps to items.id
            'variation'       => $variation,
            'quantity'        => $quantity,
            'unit_cost'       => $unitCost,
            'total_loss'      => $totalLoss,
            'pull_out_reason' => $reason,
            'category'        => $category,
            'reason_note'     => $note,
            'image_path'      => $imagePath,
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

        $variation = strtolower($pullOut['variation'] ?? '');
        $qtyCol = 'quantity';
        if (strpos($variation, 'small') !== false) $qtyCol = 'pack_small_qty';
        if (strpos($variation, 'medium') !== false) $qtyCol = 'pack_medium_qty';
        if (strpos($variation, 'large') !== false) $qtyCol = 'pack_biggest_qty';

        if (!isset($item[$qtyCol]) || $item[$qtyCol] < $pullOut['quantity']) {
            return redirect()->back()->with('error', 'Insufficient stock to approve this pull-out.');
        }

        // Deduct inventory
        $newQty = $item[$qtyCol] - $pullOut['quantity'];
        $this->itemModel->update($item['id'], [$qtyCol => $newQty]);

        // Log the change
        $oldData = json_encode([$qtyCol => $item[$qtyCol]]);
        $newData = json_encode([$qtyCol => $newQty]);
        $this->itemLogModel->insert([
            'item_id'    => $item['id'],
            'old_data'   => $oldData,
            'new_data'   => $newData,
            'updated_by' => 'Admin (Pull-Out Approved ID: '.$id.')'
        ]);

        // Mark as approved
        $this->pullOutModel->update($id, [
            'status' => 'APPROVED',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pull-out request approved and inventory deducted.');
    }

    public function reject($id)
    {
        $pullOut = $this->pullOutModel->find($id);
        if (!$pullOut || $pullOut['status'] !== 'PENDING') {
            return redirect()->back()->with('error', 'Invalid pull-out request or already processed.');
        }

        // Mark as rejected
        $this->pullOutModel->update($id, [
            'status' => 'REJECTED',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Pull-out request rejected.');
    }
}
