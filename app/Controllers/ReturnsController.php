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
    public function processReturn()
    {
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');
        
        // Support both single and multiple return items
        $rawItems = $this->request->getVar('items');
        if (empty($rawItems)) {
            // Fallback for single item submission (classic form)
            $rawItems = [[
                'transaction_id'   => $this->request->getVar('transaction_id'),
                'item_id'          => $this->request->getVar('item_id'),
                'variation'        => $this->request->getVar('variation'),
                'quantity'         => (int)$this->request->getVar('quantity'),
                'reason'           => $this->request->getVar('reason'),
                'condition'        => $this->request->getVar('return_condition') ?? $this->request->getVar('condition'),
                'note'             => $this->request->getVar('note'),
                'evidence_path'    => null
            ]];
        }

        try {
            $db->transStart();

            $returnBatch    = [];
            $pullOutBatch   = [];
            $restockUpdates = []; // keyed by item id
            $itemLogs       = [];

            foreach ($rawItems as $input) {
                $txnId = $input['transaction_id'] ?? null;
                $itemId = $input['item_id'] ?? null;
                $quantity = (int)($input['quantity'] ?? 0);
                $condition = $input['condition'] ?? 'NON_RESTOCKABLE';
                $variation = $input['variation'] ?? '';
                
                if (!$txnId || !$itemId || !$quantity) {
                    throw new \Exception('Transaction ID, Item ID, and Quantity are required for all entries.');
                }

                $item = $this->itemModel->find($itemId);
                if (!$item) throw new \Exception("Item ID {$itemId} not found.");

                $actionTaken = ($condition === 'RESTOCKABLE') ? 'RESTOCKED' : 'PULL_OUT';
                
                // Handle Evidence (simplified for batch; handles single file upload if present)
                $evidencePath = $input['evidence_path'] ?? null;
                $file = $this->request->getFile('evidence_file');
                if ($file && $file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'public/uploads/returns', $newName);
                    $evidencePath = 'public/uploads/returns/' . $newName;
                }

                $returnBatch[] = [
                    'transaction_id'   => $txnId,
                    'item_id'          => $itemId,
                    'variation'        => $variation,
                    'quantity'         => $quantity,
                    'reason'           => $input['reason'] ?? 'Customer Return',
                    'evidence_path'    => $evidencePath,
                    'return_condition' => $condition,
                    'action_taken'     => $actionTaken,
                    'processed_by'     => $userId,
                    'created_at'       => date('Y-m-d H:i:s')
                ];

                if ($condition === 'RESTOCKABLE') {
                    // Always restore to the item's own quantity column.
                    // item_id already points to the exact sibling child.
                    $currentQty = (int)($item['quantity'] ?? 0);
                    if (isset($restockUpdates[$itemId])) {
                        $currentQty = $restockUpdates[$itemId]['quantity'];
                    }

                    $newQty = $currentQty + $quantity;
                    $restockUpdates[$itemId] = ['id' => $itemId, 'quantity' => $newQty];

                    $itemLogs[] = [
                        'item_id'    => $itemId,
                        'old_data'   => json_encode(['quantity' => $currentQty]),
                        'new_data'   => json_encode(['quantity' => $newQty]),
                        'updated_by' => "System (Return Restock - TXN: {$txnId})",
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                } else {
                    // Non-restockable → Escalated to Pull-Out (use item's own price)
                    $unitCost = $item['price'];

                    $pullOutBatch[] = [
                        'product_id'      => $itemId,
                        'variation'       => $variation,
                        'quantity'        => $quantity,
                        'unit_cost'       => $unitCost,
                        'total_loss'      => $unitCost * $quantity,
                        'pull_out_reason' => 'Damaged Packaging', // As per requirements
                        'category'        => 'Customer Return',
                        'reason_note'     => "[Auto-generated from Returns] TXN: {$txnId}",
                        'image_path'      => $evidencePath,
                        'reported_by'     => $userId,
                        'status'          => 'PENDING'
                    ];
                }
            }

            // Commit Batch Operations
            if (!empty($returnBatch))  $this->returnModel->insertBatch($returnBatch);
            if (!empty($pullOutBatch)) $this->pullOutModel->insertBatch($pullOutBatch);
            if (!empty($restockUpdates)) {
                $this->itemModel->updateBatch(array_values($restockUpdates), 'id');
            }
            if (!empty($itemLogs)) $this->itemLogModel->insertBatch($itemLogs);

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception('Transaction failed to complete.');
            }

            return $this->response->setJSON(['success' => true, 'message' => count($returnBatch) . ' Return(s) processed successfully.']);

        } catch (\Exception $e) {
            if ($db->transEnabled()) $db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
