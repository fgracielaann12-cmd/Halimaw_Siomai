<?php

namespace App\Controllers;

use App\Models\ReturnModel;
use App\Models\TransactionModel;
use App\Models\ItemModel;
use App\Models\ItemLogModel;
use App\Models\PullOutModel;
use CodeIgniter\Controller;

class ReturnsController extends BaseController
{
    protected $returnModel;
    protected $transactionModel;
    protected $itemModel;
    protected $itemLogModel;
    protected $pullOutModel;

    public function __construct()
    {
        $this->returnModel      = new ReturnModel();
        $this->transactionModel = new TransactionModel();
        $this->itemModel        = new ItemModel();
        $this->itemLogModel     = new ItemLogModel();
        $this->pullOutModel     = new PullOutModel();
    }

    // --- ADMIN VIEW ---
    public function index()
    {
        $returns = $this->returnModel
            ->select('returns.*, items.name as product_name, items.product_id as product_sku, users.username as staff_name')
            ->join('items', 'items.id = returns.item_id', 'left')
            ->join('users', 'users.id = returns.processed_by', 'left')
            ->orderBy('returns.created_at', 'DESC')
            ->findAll();

        $totalReturns = count($returns);
        $totalSales = $this->transactionModel->countAllResults();
        
        $returnRate = $totalSales > 0 ? ($totalReturns / $totalSales) * 100 : 0;
        
        $pullOutCount = 0;
        foreach ($returns as $r) {
            if ($r['action_taken'] === 'PULL_OUT') $pullOutCount++;
        }
        $pullOutRate = $totalReturns > 0 ? ($pullOutCount / $totalReturns) * 100 : 0;

        // Financial loss calculation (sum of NON-RESTOCKABLE returns)
        // Since we insert a PENDING pull-out, the loss is technically tracked in pull-outs.
        // We can sum it up here for convenience.
        $db = \Config\Database::connect();
        $lossQuery = $db->query("SELECT SUM(total_loss) as total FROM pull_outs WHERE category = 'Customer Return'")->getRow();
        $financialLoss = $lossQuery->total ?? 0;

        $data = [
            'title'         => 'Customer Returns Dashboard',
            'currentPath'   => uri_string(),
            'returns'       => $returns,
            'totalReturns'  => $totalReturns,
            'returnRate'    => number_format($returnRate, 2),
            'pullOutRate'   => number_format($pullOutRate, 2),
            'financialLoss' => $financialLoss,
            'items'         => $this->itemModel->findAll()
        ];

        return view('admin/returns', $data);
    }

    // --- STAFF API ---
    public function submit()
    {
        $request = service('request');

        // Note: We use getVar() to support both form-data and json, but with file upload it will be form-data
        $transactionId = $request->getVar('transaction_id');
        $itemId        = $request->getVar('item_id');
        $variation     = $request->getVar('variation');
        $quantity      = (int)$request->getVar('quantity');
        $reason        = $request->getVar('reason');
        $condition     = $request->getVar('return_condition') ?? $request->getVar('condition'); // Handle both names
        $userId        = session()->get('user_id');

        if (!$transactionId || !$itemId || !$quantity || !$reason || !$condition) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required.']);
        }

        // --- Handle File Upload ---
        $evidencePath = null;
        $file = $this->request->getFile('evidence_file');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'public/uploads/returns', $newName);
            $evidencePath = 'public/uploads/returns/' . $newName;
        }

        // Validate Transaction ID
        $transaction = $this->transactionModel->where('transaction_id', $transactionId)->first();
        if (!$transaction) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid Transaction ID.']);
        }

        $item = $this->itemModel->find($itemId);
        if (!$item) {
            return $this->response->setJSON(['success' => false, 'message' => 'Item not found.']);
        }

        $actionTaken = 'RESTOCKED';

        if ($condition === 'RESTOCKABLE') {
            // Restock logic
            $qtyCol = 'quantity';
            if ($variation === 'small') $qtyCol = 'pack_small_qty';
            if ($variation === 'medium') $qtyCol = 'pack_medium_qty';
            if ($variation === 'large') $qtyCol = 'pack_biggest_qty';

            $newQty = $item[$qtyCol] + $quantity;
            $this->itemModel->update($item['id'], [$qtyCol => $newQty]);

            $this->itemLogModel->insert([
                'item_id'    => $item['id'],
                'old_data'   => json_encode([$qtyCol => $item[$qtyCol]]),
                'new_data'   => json_encode([$qtyCol => $newQty]),
                'updated_by' => 'Staff (RETURN - RESTOCK: TXN ' . $transactionId . ')'
            ]);

        } else {
            // NON-RESTOCKABLE (Escalate to Pull-Out)
            $actionTaken = 'PULL_OUT';
            
            $unitCost = $item['price'];
            if ($variation === 'small' && isset($item['pack_small_price'])) $unitCost = $item['pack_small_price'];
            if ($variation === 'medium' && isset($item['pack_medium_price'])) $unitCost = $item['pack_medium_price'];
            if ($variation === 'large' && isset($item['pack_biggest_price'])) $unitCost = $item['pack_biggest_price'];

            $totalLoss = $unitCost * $quantity;

            $this->pullOutModel->insert([
                'product_id'      => $itemId,
                'variation'       => $variation,
                'quantity'        => $quantity,
                'unit_cost'       => $unitCost,
                'total_loss'      => $totalLoss,
                'pull_out_reason' => 'CUSTOMER_RETURN',
                'category'        => 'Customer Return',
                'reason_note'     => '[Auto-generated from Returns] TXN: ' . $transactionId . ' - Reason: ' . $reason,
                'image_path'      => $evidencePath, // Pass the evidence to pull-outs
                'reported_by'     => $userId,
                'status'          => 'PENDING'
            ]);
        }

        // Save Return Record
        $this->returnModel->insert([
            'transaction_id'   => $transactionId,
            'item_id'          => $itemId,
            'variation'        => $variation,
            'quantity'         => $quantity,
            'reason'           => $reason,
            'evidence_path'    => $evidencePath,
            'return_condition' => $condition,
            'action_taken'     => $actionTaken,
            'processed_by'     => $userId,
            'created_at'       => date('Y-m-d H:i:s')
        ]);

        $msg = $condition === 'RESTOCKABLE' ? 'Return processed and item restocked successfully.' : 'Return processed and escalated to Pull-Outs successfully.';
        return $this->response->setJSON(['success' => true, 'message' => $msg]);
    }
}
