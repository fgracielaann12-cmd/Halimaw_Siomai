<?php
$mysqli = new mysqli('localhost', 'root', '', 'halimawsiomai');
$result = $mysqli->query("DESCRIBE stock_requests");
if ($result) {
    while($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . "\n";
    }
} else {
    echo "Error: " . $mysqli->error;
}
