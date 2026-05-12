<?php

namespace App\Controllers;

use App\Models\StockRequestModel;
use CodeIgniter\Controller;

class UserRequestController extends Controller
{
    // 🟢 Form submit version (normal POST form)
    public function submitStockRequest()
    {
        $request = service('request');

        $itemId   = $request->getPost('item_id');
        $quantity = $request->getPost('quantity');
        $reason   = $request->getPost('reason');
        $action   = $request->getPost('action'); // ← Added this
        
        file_put_contents('test_log.txt', "--- submitStockRequest POST DATA ---\n" . print_r($_POST, true) . "\n", FILE_APPEND);

        // Fix: Robust user ID retrieval
        $userId = session()->get('user_id');
        if (!$userId) {
            $userId = session()->get('id'); // Fallback
        }
        file_put_contents('test_log.txt', "userId: " . ($userId ? $userId : 'NULL') . "\n", FILE_APPEND);

        // Validate inputs
        if (empty($itemId) || empty($quantity) || empty($reason) || empty($action)) {
            file_put_contents('test_log.txt', "Validation failed: empty fields\n", FILE_APPEND);
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
            ]);
        }
        
        if (empty($userId)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'User session expired or invalid. Please log in again.'
            ]);
        }

        // Validate action type
        if (!in_array($action, ['add', 'subtract'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid action type.'
            ]);
        }

        // Save to DB
        try {
            $model = new StockRequestModel();
            
            // Fix: Prevent duplicates
            $existing = $model->where([
                'user_id' => $userId,
                'item_id' => $itemId,
                'quantity'=> $quantity,
                'action'  => $action,
                'status'  => 'pending'
            ])->first();

            if ($existing) {
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'You already have a pending request for this item with the same quantity/action.'
                    ]);
                }
                return redirect()->back()->with('error', 'You already have a pending request for this item with the same quantity/action.');
            }

            $inserted = $model->insert([
                'user_id'    => $userId,
                'item_id'    => $itemId,
                'quantity'   => $quantity,
                'reason'     => $reason,
                'action'     => $action, // ✅ include this
                'status'     => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (!$inserted) {
                file_put_contents('test_log.txt', "Insert failed: " . json_encode($model->errors()) . "\n", FILE_APPEND);
                if ($this->request->isAJAX()) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Failed to save to database. Errors: ' . json_encode($model->errors())
                    ]);
                }
                return redirect()->back()->with('error', 'Failed to save to database.');
            }

            file_put_contents('test_log.txt', "Insert success: ID $inserted\n", FILE_APPEND);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Stock request submitted successfully!'
                ]);
            }
            return redirect()->back()->with('success', 'Stock request submitted successfully!');
            
        } catch (\Exception $e) {
            file_put_contents('test_log.txt', "Exception in submitStockRequest: " . $e->getMessage() . "\n", FILE_APPEND);
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'System error: ' . $e->getMessage()
                ]);
            }
            return redirect()->back()->with('error', 'System error: ' . $e->getMessage());
        }
    }

    // 🟣 JSON fetch version (if using JS fetch with JSON)
    public function requestStockAdjustment()
    {
        $data = json_decode($this->request->getBody(), true);

        if (!$data) {
            return $this->response->setJSON(['success' => false, 'message' => 'No data received']);
        }

        $item_id  = $data['item_id'] ?? null;
        $quantity = $data['quantity'] ?? null;
        $action   = $data['action'] ?? null;
        $reason   = $data['reason'] ?? null;

        if (!$item_id || !$quantity || !$action || !$reason) {
            return $this->response->setJSON(['success' => false, 'message' => 'All fields are required']);
        }

        if (!in_array($action, ['add', 'subtract'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid action type']);
        }

        $model = new StockRequestModel();
        $model->insert([
            'user_id'    => session()->get('user_id'),
            'item_id'    => $item_id,
            'quantity'   => $quantity,
            'action'     => $action,
            'reason'     => $reason,
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON(['success' => true, 'message' => 'Request submitted successfully!']);
    }

    // 🟡 FAQs section (optional helper)
    public function getFAQs()
    {
        $faqs = [
            [
                'question' => 'How do I add a new item?',
                'answer'   => 'Only admins can add new items through the admin dashboard.'
            ],
            [
                'question' => 'How do I request stock adjustment?',
                'answer'   => 'Use the stock request form in your dashboard and wait for admin approval.'
            ],
            [
                'question' => 'Can I edit item details?',
                'answer'   => 'Only admins can edit item names, categories, or prices.'
            ]
        ];

        return $this->response->setJSON($faqs);
    }
}
