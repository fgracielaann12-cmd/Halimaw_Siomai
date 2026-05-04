<?php
$files = [
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\app\\Views\\items\\deleted_items.php",
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\Halimaw_Siomai\\app\\Views\\items\\deleted_items.php"
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // 1. Remove filter card block completely
    $content = preg_replace('/<!-- Filter Card -->[\s\S]*?<\/div>\s*<\/div>\s*<\/div>\s*<!-- Table Card -->/i', '<!-- Table Card -->', $content);

    // 2. Disable JS filterAndSort
    $jsSearch = "/\/\/ Filter & Sort[\s\S]*?filterAndSort\(\);/i";
    $jsReplace = "// Filter & Sort disabled\n    window.filterAndSort = function() { return; };";
    $content = preg_replace($jsSearch, $jsReplace, $content);

    // 3. Update table scrolling and sticky header
    $cssSearch = "        /* Custom Table Scrollbar */";
    $cssReplace = "        .table-responsive {\n            max-height: 65vh;\n            overflow-y: auto;\n        }\n        /* Custom Table Scrollbar */";
    $content = str_replace($cssSearch, $cssReplace, $content);
    
    $thSearch = "        .table th {\r\n            background: var(--primary);\r\n            color: white;\r\n            font-weight: 600;\r\n            position: sticky;\r\n            top: 0;\r\n            z-index: 2;\r\n        }";
    $thReplace = "        .table th {\n            background: var(--primary);\n            color: white;\n            font-weight: 600;\n            position: sticky;\n            top: -1px;\n            z-index: 10;\n            box-shadow: 0 2px 4px rgba(0,0,0,0.1);\n        }";
    $content = str_replace($thSearch, $thReplace, $content);
    $content = str_replace(str_replace("\r\n", "\n", $thSearch), $thReplace, $content);

    file_put_contents($file, $content);
}
echo "Done modifying deleted_items.php\n";
