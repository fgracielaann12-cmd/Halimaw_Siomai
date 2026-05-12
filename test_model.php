<?php
require 'index.php'; // Boots CI4

$model = new \App\Models\StockRequestModel();
$id = $model->insert([
    'user_id' => 5,
    'item_id' => 5615,
    'quantity' => 123,
    'action' => 'add',
    'reason' => 'test model insert',
    'status' => 'pending',
    'created_at' => date('Y-m-d H:i:s')
]);

echo "Inserted ID: $id\n";
