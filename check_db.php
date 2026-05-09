<?php
require 'vendor/autoload.php';

// Initialize CodeIgniter
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
$paths = new \Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootWeb($paths);

$db = \Config\Database::connect();
$query = $db->query("DESCRIBE items");
$results = $query->getResult();

echo "Columns in 'items' table:\n";
foreach ($results as $row) {
    echo "- " . $row->Field . " (" . $row->Type . ")\n";
}
