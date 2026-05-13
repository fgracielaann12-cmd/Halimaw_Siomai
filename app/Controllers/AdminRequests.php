<?php

namespace App\Controllers;

use App\Models\StockRequestModel;
use App\Models\ItemModel;
use App\Models\ItemLogModel;
use App\Models\StockRequestLogModel;
use CodeIgniter\Controller;

class AdminRequests extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Build main query for requests
        $builder = $db->table('stock_requests');
$builder->select('stock_requests.*, items.name AS item_name, items.created_at AS item_date, items.expiration_date AS item_exp, users.username AS user_name');
$builder->join('items', 'items.id = stock_requests.item_id', 'left');
$builder->join('users', 'users.id = stock_requests.user_id', 'left');
$builder->groupBy('stock_requests.id'); // 👈 ensures each request appears only once
$builder->orderBy('stock_requests.created_at', 'DESC');

$requests = $builder->get()->getResultArray();
;

        // Fetch all items for the request modal dropdown
        $itemModel = new ItemModel();
        $items = $itemModel->findAll();

        // Counts for approved, rejected, pending
        $approvedCount = $db->table('stock_requests')->where('status', 'approved')->countAllResults();
        $rejectedCount = $db->table('stock_requests')->where('status', 'rejected')->countAllResults();
        $pendingCount  = $db->table('stock_requests')->where('status', 'pending')->countAllResults();

        return view('items/stock_requests', [
            'requests' => $requests,
            'items'    => $items,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
            'pendingCount'  => $pendingCount,
        ]);
    }

    public function approve($id = null)
    {
        $db = \Config\Database::connect();
        $stockRequestModel = new StockRequestModel();
        $itemModel = new ItemModel();
        $logModel = new ItemLogModel();
        $requestLog = new StockRequestLogModel();

        // Support both single ID from URL and bulk IDs from POST
        $ids = $id ? [$id] : $this->request->getPost('ids');
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No requests selected.');
        }

        try {
            $db->transStart();

            // 1. Fetch pending requests
            $requests = $stockRequestModel->whereIn('id', $ids)->where('status', 'pending')->findAll();
            if (empty($requests)) {
                throw new \Exception('No pending requests found.');
            }

            $itemIds = array_unique(array_column($requests, 'item_id'));

            // 2. LOCK AFFECTED ITEMS with SELECT ... FOR UPDATE to prevent race conditions
            $db->query("SELECT id FROM items WHERE id IN (" . implode(',', array_map('intval', $itemIds)) . ") FOR UPDATE");
            
            // Re-fetch items to get current quantities under lock
            $items = $itemModel->whereIn('id', $itemIds)->findAll();
            $itemsMap = [];
            foreach ($items as $item) {
                $itemsMap[$item['id']] = $item;
            }

            $itemUpdates = [];
            $itemLogs = [];
            $requestUpdates = [];
            $requestLogs = [];

            foreach ($requests as $req) {
                $itemId = $req['item_id'];
                if (!isset($itemsMap[$itemId])) continue;

                $item = $itemsMap[$itemId];
                // All items (including variation children) use their own 'quantity' column
                $qtyField = 'quantity';

                $oldQty = (int) ($item[$qtyField] ?? 0);
                $qty = abs((int)$req['quantity']);
                $action = strtolower(trim($req['action'] ?? 'add'));

                // Initialize or accumulate updates for this item
                if (!isset($itemUpdates[$itemId])) {
                    $itemUpdates[$itemId] = ['id' => $itemId];
                }

                // Calculate New Quantity
                if ($action === 'subtract') {
                    $newQty = max(0, $oldQty - $qty);
                } else {
                    $newQty = $oldQty + $qty;
                    // For additions, update timestamps and expiration
                    $itemUpdates[$itemId]['created_at'] = date('Y-m-d H:i:s');
                    $itemUpdates[$itemId]['expiration_date'] = date('Y-m-d', strtotime('+20 days'));
                }
                
                // Track cumulative qty if multiple requests for same item are in the batch
                $runningQty = isset($itemUpdates[$itemId][$qtyField]) ? $itemUpdates[$itemId][$qtyField] : $oldQty;
                $itemUpdates[$itemId][$qtyField] = ($action === 'subtract') ? max(0, $runningQty - $qty) : ($runningQty + $qty);

                $itemLogs[] = [
                    'item_id'    => $itemId,
                    'old_data'   => json_encode([$qtyField => $oldQty]),
                    'new_data'   => json_encode([$qtyField => $itemUpdates[$itemId][$qtyField]]),
                    'updated_by' => session()->get('username') ?? 'Admin',
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $requestUpdates[] = ['id' => $req['id'], 'status' => 'approved'];
                
                $requestLogs[] = [
                    'request_id'  => $req['id'],
                    'action'      => 'approved',
                    'message'     => "Stock request approved. {$qtyField} updated from {$oldQty} to " . $itemUpdates[$itemId][$qtyField],
                    'performed_by'=> session()->get('username') ?? 'Admin',
                    'created_at'  => date('Y-m-d H:i:s'),
                ];
            }

            // 3. EXECUTE BATCH OPERATIONS
            if (!empty($itemUpdates)) {
                $itemModel->updateBatch(array_values($itemUpdates), 'id');
            }
            if (!empty($requestUpdates)) {
                $stockRequestModel->updateBatch($requestUpdates, 'id');
            }
            if (!empty($itemLogs)) {
                $logModel->insertBatch($itemLogs);
            }
            if (!empty($requestLogs)) {
                $requestLog->insertBatch($requestLogs);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \Exception('Transaction failed to complete.');
            }

            return redirect()->back()->with('success', count($requests) . ' stock request(s) approved successfully!');

        } catch (\Exception $e) {
            if ($db->transEnabled()) $db->transRollback();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    public function reject($id)
    {
        $stockRequestModel = new StockRequestModel();
        $requestLog = new StockRequestLogModel();

        // Update request status
        $stockRequestModel->update($id, ['status' => 'rejected']);

        // Log the rejection
        $requestLog->insert([
            'request_id'  => $id,
            'action'      => 'rejected',
            'message'     => 'Stock request was rejected by admin.',
            'performed_by'=> session()->get('username') ?? 'Admin',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('error', 'Request rejected.');
    }

    // View all stock request logs
    public function logs()
    {
        $logModel = new StockRequestLogModel();
        $data['logs'] = $logModel->orderBy('created_at', 'DESC')->findAll();

        return view('items/stock_request_logs', $data);
    }
    public function submitStockRequest()
{
    $stockRequestModel = new StockRequestModel();

    $userId = session()->get('user_id'); // or get current user ID however you store it
    $itemId = $this->request->getPost('item_id');
    $quantity = (int)$this->request->getPost('quantity');
    $action = strtolower(trim($this->request->getPost('action')));
    $reason = $this->request->getPost('reason');

    // Prevent duplicates: check if a pending request already exists
    $existing = $stockRequestModel->where([
        'user_id' => $userId,
        'item_id' => $itemId,
        'quantity'=> $quantity,
        'action'  => $action,
        'status'  => 'pending'
    ])->first();

    if ($existing) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'You already have a pending request for this item with the same quantity/action.'
        ]);
    }

    // Insert new stock request
    $stockRequestModel->insert([
        'user_id' => $userId,
        'item_id' => $itemId,
        'quantity'=> $quantity,
        'action'  => $action,
        'reason'  => $reason,
        'status'  => 'pending',
        'created_at' => date('Y-m-d H:i:s'),
    ]);

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Stock request submitted successfully!'
    ]);
}

}
