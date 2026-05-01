<?php
$mysqli = new mysqli("localhost", "root", "", "halimawsiomai");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql1 = "CREATE TABLE IF NOT EXISTS `online_orders` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` VARCHAR(20) NOT NULL UNIQUE,
    `customer_name` VARCHAR(100) NOT NULL,
    `customer_email` VARCHAR(100) NOT NULL,
    `customer_phone` VARCHAR(20) NOT NULL,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `status` ENUM('Pending', 'Completed', 'Cancelled') DEFAULT 'Pending',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
)";

$sql2 = "CREATE TABLE IF NOT EXISTS `online_order_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` VARCHAR(20) NOT NULL,
    `product_id` INT NOT NULL,
    `product_name` VARCHAR(100) NOT NULL,
    `variation` VARCHAR(50) DEFAULT NULL,
    `quantity` INT NOT NULL,
    `price` DECIMAL(10,2) NOT NULL,
    `subtotal` DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (`order_id`) REFERENCES `online_orders`(`order_id`) ON DELETE CASCADE
)";

if ($mysqli->query($sql1) === TRUE) {
    echo "Table online_orders created successfully\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}

if ($mysqli->query($sql2) === TRUE) {
    echo "Table online_order_items created successfully\n";
} else {
    echo "Error creating table: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
