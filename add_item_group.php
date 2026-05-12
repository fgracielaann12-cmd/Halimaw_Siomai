<?php
$mysqli = new mysqli("localhost", "root", "", "halimaw_siomai");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$result = $mysqli->query("SHOW COLUMNS FROM items LIKE 'item_group'");
if ($result->num_rows == 0) {
    if ($mysqli->query("ALTER TABLE items ADD COLUMN item_group VARCHAR(255) NULL AFTER product_id")) {
        echo "Successfully added 'item_group' column to 'items' table.\n";
    } else {
        echo "Error adding column: " . $mysqli->error . "\n";
    }
} else {
    echo "'item_group' column already exists.\n";
}
$mysqli->close();
