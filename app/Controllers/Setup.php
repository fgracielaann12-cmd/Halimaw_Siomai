<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Setup extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        $forge = \Config\Database::forge();
        
        // 1. Create online_orders tables if not exists
        $sql1 = "CREATE TABLE IF NOT EXISTS `online_orders` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` varchar(50) NOT NULL,
            `customer_name` varchar(150) NOT NULL,
            `customer_email` varchar(150) NOT NULL,
            `customer_phone` varchar(50) NOT NULL,
            `total_amount` decimal(10,2) NOT NULL,
            `status` varchar(50) DEFAULT 'Pending',
            `created_at` datetime NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
        
        $sql2 = "CREATE TABLE IF NOT EXISTS `online_order_items` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_id` varchar(50) NOT NULL,
            `product_id` int(11) NOT NULL,
            `product_name` varchar(150) NOT NULL,
            `variation` varchar(50) DEFAULT NULL,
            `quantity` int(11) NOT NULL,
            `price` decimal(10,2) NOT NULL,
            `subtotal` decimal(10,2) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";

        // 2. Add missing columns to items table
        $itemsColumns = $db->getFieldNames('items');
        $missingItems = [];
        
        if (!in_array('image_path', $itemsColumns)) {
            $missingItems['image_path'] = ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'sku'];
        }
        if (!in_array('is_variation_child', $itemsColumns)) {
            $missingItems['is_variation_child'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'status'];
        }
        if (!in_array('variation_group_id', $itemsColumns)) {
            $missingItems['variation_group_id'] = ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'is_variation_child'];
        }
        if (!in_array('variation_label', $itemsColumns)) {
            $missingItems['variation_label'] = ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true, 'after' => 'variation_group_id'];
        }
        if (!in_array('is_expiring_seen', $itemsColumns)) {
            $missingItems['is_expiring_seen'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'variation_label'];
        }
        if (!in_array('is_expired_seen', $itemsColumns)) {
            $missingItems['is_expired_seen'] = ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0, 'after' => 'is_expiring_seen'];
        }

        // 🔸 Packaging Columns
        if (!in_array('pack_small_qty', $itemsColumns)) {
            $missingItems['pack_small_qty'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
        }
        if (!in_array('pack_medium_qty', $itemsColumns)) {
            $missingItems['pack_medium_qty'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
        }
        if (!in_array('pack_biggest_qty', $itemsColumns)) {
            $missingItems['pack_biggest_qty'] = ['type' => 'INT', 'constraint' => 11, 'default' => 0];
        }
        if (!in_array('pack_small_price', $itemsColumns)) {
            $missingItems['pack_small_price'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 115.00];
        }
        if (!in_array('pack_medium_price', $itemsColumns)) {
            $missingItems['pack_medium_price'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 185.00];
        }
        if (!in_array('pack_biggest_price', $itemsColumns)) {
            $missingItems['pack_biggest_price'] = ['type' => 'DECIMAL', 'constraint' => '10,2', 'default' => 335.00];
        }

        try {
            $db->query($sql1);
            $db->query($sql2);
            
            if (!empty($missingItems)) {
                $forge->addColumn('items', $missingItems);
            }

            return "<div style='font-family:sans-serif; text-align:center; padding: 50px;'>
                        <h2 style='color:green;'>✅ Database Successfully Updated!</h2>
                        <p>The missing tables and columns (including <b>image_path</b>, notifications, and variations) have been checked and updated.</p>
                        <p>You can now continue using the system without Database Errors.</p>
                        <a href='" . site_url('items') . "' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#28a745; color:white; text-decoration:none; border-radius:5px;'>Go to Items</a>
                    </div>";
        } catch (\Exception $e) {
            return "<div style='font-family:sans-serif; text-align:center; padding: 50px;'>
                        <h2 style='color:red;'>❌ Error Updating Database</h2>
                        <p>" . $e->getMessage() . "</p>
                        <p>Please check your database permissions.</p>
                    </div>";
        }
    }
}
