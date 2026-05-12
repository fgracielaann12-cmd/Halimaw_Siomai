<?php
require 'index.php'; // Boots CI4

$session = session();
$session->set([
    'isLoggedIn' => true,
    'user_id' => 5,
    'username' => 'Derrick',
    'role' => 'staff'
]);

$model = new \App\Models\StockRequestModel();
$data = [
    'user_id'    => 5,
    'item_id'    => 5615,
    'quantity'   => 45,
    'reason'     => 'wowowowo from test_endpoint',
    'action'     => 'add',
    'status'     => 'pending',
    'created_at' => date('Y-m-d H:i:s'),
];
$inserted = $model->insert($data);

if (!$inserted) {
    echo "Failed: ";
    print_r($model->errors());
} else {
    echo "Success! ID: $inserted\n";
}
