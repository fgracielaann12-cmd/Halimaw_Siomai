<?php

namespace App\Controllers;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class Cron extends Controller
{
    public function checkExpiry()
    {
        $model = new ItemModel();
        $today = date('Y-m-d');
        $tenDaysLater = date('Y-m-d', strtotime('+10 days'));

        // 1. Items expiring soon
        $expiring = $model->where('status', 'active')
            ->where("expiration_date BETWEEN '$today' AND '$tenDaysLater'")
            ->findAll();

        if ($expiring) {
            // In a real system, send email or notification here
            echo "Items expiring soon:\n";
            foreach ($expiring as $item) {
                echo "- {$item['name']} ({$item['expiration_date']})\n";
            }
        }

        // 2. Expired items
        $expired = $model->where('status', 'active')
            ->where("expiration_date < '$today'")
            ->findAll();

        foreach ($expired as $item) {
            if ($item['auto_delete']) {
                $model->update($item['id'], ['status' => 'deleted']);
            } else {
                $model->update($item['id'], ['status' => 'expired']);
            }
        }

        echo "Expiry check complete.";
    }
}
