<?php
$db = new mysqli('localhost', 'root', '', 'halimawsiomai');
$res = $db->query('SELECT id, user_id, item_id, quantity, action, status FROM stock_requests ORDER BY id DESC LIMIT 5');
while($row = $res->fetch_assoc()) { print_r($row); }
