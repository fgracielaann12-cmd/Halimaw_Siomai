<?php
$files = [
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\app\\Views\\user\\dashboard.php",
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\Halimaw_Siomai\\app\\Views\\user\\dashboard.php"
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Fix z-index for controls-section
    $content = preg_replace(
        '/(\.controls-section\s*\{[^}]*?z-index:\s*)10(\s*;[^}]*\})/ism',
        '${1}1050${2}',
        $content
    );

    file_put_contents($file, $content);
}
echo "Done";
