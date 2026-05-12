<?php
$mysqli = new mysqli('localhost', 'root', '', 'halimawsiomai');
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

$checks = [
    'sales' => ['is_seen'],
    'items' => ['is_expiring_seen', 'is_expired_seen', 'expiration_date', 'created_at'],
    'stock_requests' => ['status'],
    'returns' => ['evidence_path']
];

foreach ($checks as $table => $cols) {
    echo "Table $table: ";
    $res = $mysqli->query("SHOW COLUMNS FROM $table");
    if (!$res) {
        echo "MISSING TABLE!\n";
        continue;
    }
    $existing = [];
    while($row = $res->fetch_assoc()) $existing[] = $row['Field'];
    
    $missing = array_diff($cols, $existing);
    if (empty($missing)) {
        echo "OK\n";
    } else {
        echo "MISSING COLS: " . implode(', ', $missing) . "\n";
    }
}

$tables = ['items', 'users', 'sales', 'stock_requests', 'item_logs', 'stock_request_logs', 'returns', 'pull_outs'];
foreach ($tables as $table) {
    $res = $mysqli->query("DESCRIBE $table");
    if ($res) {
        while($row = $res->fetch_assoc()) {
            if ($row['Field'] == 'id') {
                echo "Table $table ID: " . $row['Extra'] . "\n";
            }
        }
    }
}
?>
