<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ItemLogModel;
use CodeIgniter\Controller;

class Cron extends Controller
{
    /**
     * Inventory expiry sweep
     * Refactored for batch processing, transactions, and chunking.
     */
    public function checkExpiry()
    {
        $db = \Config\Database::connect();
        $itemModel = new ItemModel();
        $logModel = new ItemLogModel();
        
        $today = date('Y-m-d');
        $tenDaysLater = date('Y-m-d', strtotime('+10 days'));
        $chunkSize = 200;
        
        echo "Starting inventory expiry sweep at " . date('Y-m-d H:i:s') . "\n";

        try {
            $db->transStart();

            // --- 1. MARK EXPIRING SOON ---
            // Process items currently 'active' that will expire within 10 days
            $processedExpiring = 0;
            while (true) {
                $expiringItems = $itemModel->where('status', 'active')
                    ->where('expiration_date >=', $today)
                    ->where('expiration_date <=', $tenDaysLater)
                    ->limit($chunkSize)
                    ->findAll();

                if (empty($expiringItems)) break;

                $updateData = [];
                $logData = [];
                foreach ($expiringItems as $item) {
                    $updateData[] = [
                        'id'     => $item['id'],
                        'status' => 'expiring_soon'
                    ];
                    
                    $logData[] = [
                        'item_id'    => $item['id'],
                        'old_data'   => 'status: active',
                        'new_data'   => 'status: expiring_soon',
                        'updated_by' => 0, // System/Cron
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }

                $itemModel->updateBatch($updateData, 'id');
                $logModel->insertBatch($logData);
                
                $processedExpiring += count($expiringItems);
                echo "Processed chunk of $processedExpiring expiring soon items...\n";
            }

            // --- 2. HANDLE EXPIRED ITEMS ---
            // Process items where expiration date has passed
            $processedExpired = 0;
            while (true) {
                // Pick items that are not already 'deleted' or 'expired'
                $expiredItems = $itemModel->whereIn('status', ['active', 'expiring_soon'])
                    ->where('expiration_date <', $today)
                    ->limit($chunkSize)
                    ->findAll();

                if (empty($expiredItems)) break;

                $updateData = [];
                $logData = [];
                foreach ($expiredItems as $item) {
                    $newStatus = ($item['auto_delete'] == 1) ? 'deleted' : 'expired';
                    
                    $updateData[] = [
                        'id'     => $item['id'],
                        'status' => $newStatus
                    ];
                    
                    $logData[] = [
                        'item_id'    => $item['id'],
                        'old_data'   => 'status: ' . $item['status'],
                        'new_data'   => 'status: ' . $newStatus,
                        'updated_by' => 0,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }

                $itemModel->updateBatch($updateData, 'id');
                $logModel->insertBatch($logData);
                
                $processedExpired += count($expiredItems);
                echo "Processed chunk of $processedExpired expired items...\n";
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                $db->transRollback();
                throw new \RuntimeException("Transaction failed to complete.");
            }

            echo "Sweep complete. Expiring: $processedExpiring, Expired: $processedExpired.\n";

        } catch (\Exception $e) {
            if ($db->transEnabled()) {
                $db->transRollback();
            }
            log_message('error', 'Cron Expiry Sweep Error: ' . $e->getMessage());
            echo "ERROR: " . $e->getMessage() . "\n";
        }
    }
}
