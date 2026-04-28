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
$builder->select('stock_requests.*, items.name AS item_name, users.username AS user_name');
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

    public function approve($id)
    {
        $stockRequestModel = new StockRequestModel();
        $itemModel = new ItemModel();
        $logModel = new ItemLogModel();
        $requestLog = new StockRequestLogModel();

        // Fetch the request
        $request = $stockRequestModel->find($id);
        if (!$request) {
            return redirect()->back()->with('error', 'Request not found.');
        }

        // Prevent re-approval
        if (strtolower($request['status']) === 'approved') {
            return redirect()->back()->with('info', 'This request is already approved.');
        }

        // Fetch the item
        $item = $itemModel->find($request['item_id']);
        if (!$item) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $reason = $request['reason'] ?? '';
        $qtyField = 'quantity';
        
        if (preg_match('/\[Variation:\s*(Small|Medium|Large)\]/i', $reason, $matches)) {
            $varType = strtolower($matches[1]);
            if ($varType === 'small') {
                $qtyField = 'pack_small_qty';
            } elseif ($varType === 'medium') {
                $qtyField = 'pack_medium_qty';
            } elseif ($varType === 'large') {
                $qtyField = 'pack_biggest_qty';
            }
        }

        $oldQty = (int) ($item[$qtyField] ?? 0);
        $qty = abs((int)$request['quantity']); // ensure positive

        // Handle action: add or subtract
        $action = strtolower(trim($request['action'] ?? 'add'));

        if ($action === 'subtract') {
            $newQty = max(0, $oldQty - $qty); // decrease quantity
        } else {
            $newQty = $oldQty + $qty; // increase quantity
        }

        // Update item quantity
        $itemModel->update($item['id'], [$qtyField => $newQty]);

        // Log the update to item_logs
        $logModel->insert([
            'item_id'    => $item['id'],
            'old_data'   => json_encode([$qtyField => $oldQty]),
            'new_data'   => json_encode([$qtyField => $newQty]),
            'updated_by' => session()->get('username') ?? 'Admin',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Mark the request as approved
        $stockRequestModel->update($id, ['status' => 'approved']);

        // Log to stock_request_logs
        $requestLog->insert([
            'request_id'  => $id,
            'action'      => 'approved',
            'message'     => 'Stock request approved. Quantity updated from ' . $oldQty . ' to ' . $newQty,
            'performed_by'=> session()->get('username') ?? 'Admin',
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Stock request approved and quantity updated successfully!');
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
