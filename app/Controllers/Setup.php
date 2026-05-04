<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Setup extends Controller
{
    public function index()
    {
        $db = \Config\Database::connect();
        
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

        try {
            $db->query($sql1);
            $db->query($sql2);
            return "<div style='font-family:sans-serif; text-align:center; padding: 50px;'>
                        <h2 style='color:green;'>✅ Database Successfully Updated!</h2>
                        <p>The missing tables (online_orders & online_order_items) have been created.</p>
                        <p>Your collaborator can now test the online ordering system without errors.</p>
                    </div>";
        } catch (\Exception $e) {
            return "<div style='font-family:sans-serif; text-align:center; padding: 50px;'>
                        <h2 style='color:red;'>❌ Error Updating Database</h2>
                        <p>" . $e->getMessage() . "</p>
                    </div>";
        }
    }
}
