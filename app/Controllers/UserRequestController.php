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
        $userId   = session()->get('user_id');

        // Validate inputs
        if (empty($itemId) || empty($quantity) || empty($reason) || empty($action)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'All fields are required.'
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
        $model = new StockRequestModel();
        $model->insert([
            'user_id'    => $userId,
            'item_id'    => $itemId,
            'quantity'   => $quantity,
            'reason'     => $reason,
            'action'     => $action, // ✅ include this
            'status'     => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Stock request submitted successfully!'
        ]);
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
