<?php

namespace App\Controllers;
use App\Models\ItemModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();

        // 🧱 1️⃣ Prevent unauthorized access
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Access denied');
        }

        // Optional: if you want only admin to see this page
        if ($session->get('role') !== 'admin') {
            return redirect()->to('/user/dashboard')->with('error', 'Access denied');
        }

        $model = new ItemModel();
        $today = date('Y-m-d');
        $soon = date('Y-m-d', strtotime('+10 days'));

        // 🔁 Automatically update expired items
        $model->set('status', 'expired')
              ->where('expiration_date <', $today)
              ->where('status !=', 'expired')
              ->update();

        // Count total items (not deleted)
        $total_items = $model->where('deleted_at', null)->countAllResults(false);

        // Count expiring soon (within 10 days but not yet expired)
        $expiring_soon = $model->where('expiration_date <=', $soon)
                              ->where('expiration_date >', $today)
                              ->where('status', 'active')
                              ->where('deleted_at', null)
                              ->countAllResults(false);

        // Count expired items
        $expired = $model->where('status', 'expired')
                         ->where('deleted_at', null)
                         ->countAllResults(false);

        // Count deleted items (soft deleted)
        $deleted = $model->onlyDeleted()->countAllResults(false);

        // Example username (replace with session value)
        $data = [
            'username' => $session->get('username') ?? 'Guest',
            'total_items' => $total_items,
            'expiring_soon' => $expiring_soon,
            'expired' => $expired,
            'deleted' => $deleted
        ];

        return view('dashboard', $data);
    }

    public function expiringData()
    {
        $svc = new \App\Services\DashboardService();
        $data = $svc->expiringChartData(30);
        return $this->response->setJSON($data);
    }
}
