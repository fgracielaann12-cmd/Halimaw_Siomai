<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=halimawsiomai', 'root', '');
    $db->exec('DELETE FROM stock_requests WHERE id = 29');
    echo "Deleted ID 29 successfully.";
} catch (Exception $e) {
    echo $e->getMessage();
}
