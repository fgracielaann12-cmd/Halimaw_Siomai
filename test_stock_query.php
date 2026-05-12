<?php
$db = new mysqli('localhost', 'root', '', 'halimawsiomai');
$query = "SELECT stock_requests.*, items.name AS item_name, items.created_at AS item_date, items.expiration_date AS item_exp, users.username AS user_name
          FROM stock_requests
          LEFT JOIN items ON items.id = stock_requests.item_id
          LEFT JOIN users ON users.id = stock_requests.user_id
          GROUP BY stock_requests.id
          ORDER BY stock_requests.created_at DESC";
$res = $db->query($query);
if ($res) {
    echo "Query OK. Rows: " . $res->num_rows . "\n";
} else {
    echo "Query FAILED: " . $db->error . "\n";
}
