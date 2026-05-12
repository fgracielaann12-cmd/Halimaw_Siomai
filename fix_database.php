<?php
/**
 * HALIMAW SIOMAI - DATABASE FIX & SYNC TOOL
 * 
 * This script resolves common database issues like:
 * 1. Missing columns (notifications, variations, evidence)
 * 2. Missing AUTO_INCREMENT on primary keys
 * 3. Duplicate entry '0' errors (caused by non-AI primary keys)
 * 4. Missing logging tables
 */

// Connection settings from app/Config/Database.php usually, but here we assume defaults
$database = 'halimawsiomai';
$mysqli = new mysqli('localhost', 'root', '', $database);

if ($mysqli->connect_error) {
    die("<div style='color:red; font-family:sans-serif;'><h2>Connection failed</h2>" . $mysqli->connect_error . "<br>Please check if your database name is '$database' and user is 'root'.</div>");
}

echo "<div style='font-family:sans-serif; padding: 20px; max-width: 800px; margin: auto; background: #f8f9fc; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);'>";
echo "<h1 style='color: #4e73df;'>Halimaw Siomai Database Sync</h1>";
echo "<p>Running diagnostics and applying fixes to match the latest project version...</p>";
echo "<hr style='border: 0; border-top: 1px solid #e3e6f0;'>";
echo "<pre style='background: #f1f3f9; padding: 15px; border-radius: 8px; font-size: 13px; line-height: 1.6;'>";

// --- 1. ENSURE TABLES EXIST ---
$createTables = [
    "CREATE TABLE IF NOT EXISTS item_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        item_id INT NOT NULL,
        old_data TEXT,
        new_data TEXT,
        updated_by VARCHAR(100),
        updated_at DATETIME
    )",
    "CREATE TABLE IF NOT EXISTS stock_request_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        request_id INT NOT NULL,
        action VARCHAR(50),
        message TEXT,
        performed_by VARCHAR(100),
        created_at DATETIME
    )"
];

foreach ($createTables as $sql) {
    $mysqli->query($sql);
}

// --- 2. CORE SCHEMA FIXES (AUTO_INCREMENT & ID 0) ---
$tablesToFix = [
    'users', 'items', 'sales', 'stock_requests', 
    'item_logs', 'stock_request_logs', 'returns', 
    'pull_outs', 'deleted_items'
];

foreach ($tablesToFix as $table) {
    echo "Checking table <b>$table</b>... ";
    
    $res = $mysqli->query("SHOW TABLES LIKE '$table'");
    if ($res->num_rows == 0) {
        echo "<span style='color:orange;'>Not found (Skipped)</span>\n";
        continue;
    }

    // Fix ID 0 if it exists (causes Duplicate Entry '0' errors)
    $res0 = $mysqli->query("SELECT id FROM $table WHERE id = 0");
    if ($res0 && $res0->num_rows > 0) {
        $mysqli->query("UPDATE $table SET id = (SELECT (MAX(id)+1) FROM (SELECT id FROM $table) as t) WHERE id = 0");
        echo "<span style='color:blue;'>Moved ID 0.</span> ";
    }

    // Ensure AUTO_INCREMENT
    $describe = $mysqli->query("DESCRIBE $table");
    $idCol = null;
    while($col = $describe->fetch_assoc()) {
        if ($col['Field'] == 'id') {
            $idCol = $col;
            break;
        }
    }

    if ($idCol) {
        if (strpos($idCol['Extra'], 'auto_increment') === false) {
            $type = $idCol['Type'];
            if ($mysqli->query("ALTER TABLE $table MODIFY id $type AUTO_INCREMENT")) {
                echo "<span style='color:green;'>Fixed AUTO_INCREMENT.</span> ";
            } else {
                echo "<span style='color:red;'>Failed AI: " . $mysqli->error . "</span> ";
            }
        } else {
            echo "<span style='color:#858796;'>AI OK.</span> ";
        }
    } else {
        echo "<span style='color:orange;'>No ID col.</span> ";
    }
    echo "\n";
}

// --- 3. MISSING COLUMNS SYNC ---
$columnUpdates = [
    'sales' => [
        'is_seen' => "TINYINT(1) DEFAULT 0 AFTER created_at"
    ],
    'items' => [
        'is_expiring_seen' => "TINYINT(1) DEFAULT 0",
        'is_expired_seen' => "TINYINT(1) DEFAULT 0",
        'variation_group_id' => "VARCHAR(255) NULL",
        'is_variation_child' => "TINYINT(1) DEFAULT 0",
        'variation_label' => "VARCHAR(255) NULL",
        'pack_small_qty' => "INT(11) DEFAULT 0",
        'pack_medium_qty' => "INT(11) DEFAULT 0",
        'pack_biggest_qty' => "INT(11) DEFAULT 0",
        'pack_small_price' => "DECIMAL(10,2) DEFAULT 115.00",
        'pack_medium_price' => "DECIMAL(10,2) DEFAULT 185.00",
        'pack_biggest_price' => "DECIMAL(10,2) DEFAULT 335.00"
    ],
    'returns' => [
        'evidence_path' => "VARCHAR(255) NULL AFTER reason"
    ],
    'stock_requests' => [
        'status' => "VARCHAR(50) DEFAULT 'pending'"
    ]
];

echo "\n--- Synchronizing Columns ---\n";
foreach ($columnUpdates as $table => $cols) {
    foreach ($cols as $col => $def) {
        $check = $mysqli->query("SHOW COLUMNS FROM $table LIKE '$col'");
        if ($check->num_rows == 0) {
            echo "Adding column <b>$col</b> to <b>$table</b>... ";
            if ($mysqli->query("ALTER TABLE $table ADD COLUMN $col $def")) {
                echo "<span style='color:green;'>Success.</span>\n";
            } else {
                echo "<span style='color:red;'>Failed: " . $mysqli->error . "</span>\n";
            }
        }
    }
}

echo "\n<b>Finalizing...</b> ";
echo "<span style='color:green;'>Database is now synced with the latest version.</span>";
echo "</pre>";
echo "<div style='text-align: center; margin-top: 20px;'>
        <a href='index.php' style='background: #4e73df; color: white; padding: 10px 25px; border-radius: 5px; text-decoration: none; font-weight: bold;'>Return to Dashboard</a>
      </div>";
echo "</div>";
?>
