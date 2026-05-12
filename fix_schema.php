<?php
$mysqli = new mysqli('localhost', 'root', '', 'halimawsiomai');

// Add evidence_path to returns
if ($mysqli->query("ALTER TABLE returns ADD COLUMN evidence_path VARCHAR(255) NULL AFTER reason")) {
    echo "Added evidence_path to returns\n";
} else {
    echo "Error adding evidence_path to returns: " . $mysqli->error . "\n";
}
