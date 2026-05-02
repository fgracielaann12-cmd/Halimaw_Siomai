<?php
$db = new mysqli('localhost', 'root', '', 'halimawsiomai');
if ($db->connect_error) die("Connection failed");

echo "=== ITEMS ===\n";
$res = $db->query("SHOW COLUMNS FROM items");
while($row=$res->fetch_assoc()){ echo $row['Field'] . ' - ' . $row['Type'] . "\n"; }

echo "\n=== SALES ===\n";
$res = $db->query("SHOW COLUMNS FROM sales");
while($row=$res->fetch_assoc()){ echo $row['Field'] . ' - ' . $row['Type'] . "\n"; }
