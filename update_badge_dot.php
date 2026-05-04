<?php
$files = [
    'app/Views/templates/header.php',
    'app/Views/pos/staff.php',
    'app/Views/pos/index.php',
    'app/Views/partials/admin_sidebar.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $c = file_get_contents($file);
        // We will insert `flex-shrink: 0 !important;` right after `display: inline-flex !important;`
        $c2 = str_replace(
            "display: inline-flex !important;",
            "display: inline-flex !important;\n    flex-shrink: 0 !important;",
            $c
        );
        if ($c !== $c2) {
            file_put_contents($file, $c2);
            echo "Updated " . $file . "\n";
        }
    }
}
