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
    public function submitPullOut()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // Support both single and multiple pull-out items
        $rawItems = $this->request->getPost('items');
        if (empty($rawItems)) {
            // Fallback for single item submission (classic form)
            $rawItems = [[
                'item_id'   => $this->request->getPost('item_id'),
                'variation' => $this->request->getPost('variation'),
                'quantity'  => (int)$this->request->getPost('quantity'),
                'reason'    => $this->request->getPost('reason'),
                'category'  => $this->request->getPost('category'),
                'note'      => $this->request->getPost('note'),
            ]];
        }

        try {
            $db->transStart();

            $pullOutBatch = [];

            foreach ($rawItems as $input) {
                $itemId = $input['item_id'] ?? null;
                $quantity = (int)($input['quantity'] ?? 0);
                $reason = $input['reason'] ?? '';
                $variation = $input['variation'] ?? '';

                if (!$itemId || !$quantity || !$reason) {
                    throw new \Exception('Item ID, Quantity, and Reason are required for all entries.');
                }

                // Standardize Reasons
                $validReasons = ['Shortage', 'Spoilage', 'Damaged Packaging'];
                if (!in_array($reason, $validReasons)) {
                    $reasonMap = [
                        'shortage' => 'Shortage', 'spoilage' => 'Spoilage', 'spoiled' => 'Spoilage',
                        'SPOILED' => 'Spoilage', 'damaged' => 'Damaged Packaging',
                        'damaged packaging' => 'Damaged Packaging', 'DAMAGED_PACKAGING' => 'Damaged Packaging'
                    ];
                    $reason = $reasonMap[$reason] ?? $reason;
                }

                $item = $this->itemModel->find($itemId);
                if (!$item) throw new \Exception("Item ID {$itemId} not found.");

                // Unit cost comes from the item's own price (it is already the specific child)
                $unitCost = $item['price'];

                $totalLoss = $unitCost * $quantity;

                // Image Handling
                $imagePath = null;
                $file = $this->request->getFile('image');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/pullouts', $newName);
                    $imagePath = 'uploads/pullouts/' . $newName;
                }

                $pullOutBatch[] = [
                    'product_id'      => $itemId,
                    'variation'       => $variation,
                    'quantity'        => $quantity,
                    'unit_cost'       => $unitCost,
                    'total_loss'      => $totalLoss,
                    'pull_out_reason' => $reason,
                    'category'        => $input['category'] ?? 'Food Waste',
                    'reason_note'     => $input['note'] ?? '',
                    'image_path'      => $imagePath,
                    'reported_by'     => $userId,
                    'status'          => 'PENDING',
                    'date_reported'   => date('Y-m-d H:i:s')
                ];
            }

            // Insert pull-out records only — NO inventory deduction yet
            // Inventory will be deducted when admin approves the request
            if (!empty($pullOutBatch)) $this->pullOutModel->insertBatch($pullOutBatch);

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception('Transaction failed to complete.');
            }

            return $this->response->setJSON(['success' => true, 'message' => count($pullOutBatch) . ' Pull-out(s) submitted for admin approval.']);

        } catch (\Exception $e) {
            if ($db->transEnabled()) $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // --- ADMIN VIEWS & ACTIONS ---
    public function index()
    {
        $pullOuts = $this->pullOutModel
            ->select('pull_outs.*, 
                CASE 
                    WHEN pull_outs.variation IS NOT NULL AND pull_outs.variation != "" 
                    THEN CONCAT(items.name, " — ", pull_outs.variation, " (", items.product_id, ")")
                    ELSE CONCAT(items.name, " (", items.product_id, ")")
                END as display_label,
                items.product_id as product_sku, 
                users.username as reporter_name')
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

        // Always deduct from the item's own quantity (sibling model)
        $qtyCol = 'quantity';

        if (($item[$qtyCol] ?? 0) < $pullOut['quantity']) {
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
