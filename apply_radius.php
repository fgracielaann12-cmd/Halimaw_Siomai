<?php

$dir = new RecursiveDirectoryIterator('c:\xampp\htdocs\Halimaw_Siomai\Halimaw_Siomai\app\Views');
$iterator = new RecursiveIteratorIterator($dir);

$cssRule = <<<EOD
        /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-pill, .btn.rounded-circle, .btn-add-to-cart, .btn-pill, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
        }
EOD;

$count = 0;
foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php' && strpos($file->getPathname(), DIRECTORY_SEPARATOR . 'auth' . DIRECTORY_SEPARATOR) === false) {
        $content = file_get_contents($file->getPathname());
        
        // Remove my previous 12px or 5px injection if it exists
        $content = preg_replace('/\/\* Unified .*? Border Radius for All Buttons System-Wide \*\/\s*[\s\S]*?border-radius: (12px|5px) !important;\s*\}/', '', $content);
        
        // Find </style> and append the new rule before it
        if (stripos($content, '</style>') !== false) {
            $content = preg_replace('/(<\/style>)/i', $cssRule . "\n    $1", $content);
            file_put_contents($file->getPathname(), $content);
            $count++;
        }
    }
}

echo "Updated $count files with 5px radius rule.\n";

// Additionally, replace inline style="border-radius: ...;" with 5px if found on buttons, but the !important rule should cover it.
