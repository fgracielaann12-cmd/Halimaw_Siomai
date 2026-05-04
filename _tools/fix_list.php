<?php
$files = [
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\app\\Views\\items\\list.php",
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\Halimaw_Siomai\\app\\Views\\items\\list.php"
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Fix 1a: table-responsive-custom
    $content = str_replace(
        ".table-responsive-custom {\r\n            overflow-x: hidden;\r\n            -webkit-overflow-scrolling: touch;\r\n            border-radius: var(--border-radius);\r\n            box-shadow: var(--card-shadow);\r\n            background: white;\r\n        }",
        ".table-responsive-custom {\r\n            overflow-x: auto;\r\n            overflow-y: auto;\r\n            max-height: 65vh;\r\n            -webkit-overflow-scrolling: touch;\r\n            border-radius: var(--border-radius);\r\n            box-shadow: var(--card-shadow);\r\n            background: white;\r\n            margin-bottom: 30px;\r\n        }",
        $content
    );
    $content = str_replace(
        ".table-responsive-custom {\n            overflow-x: hidden;\n            -webkit-overflow-scrolling: touch;\n            border-radius: var(--border-radius);\n            box-shadow: var(--card-shadow);\n            background: white;\n        }",
        ".table-responsive-custom {\n            overflow-x: auto;\n            overflow-y: auto;\n            max-height: 65vh;\n            -webkit-overflow-scrolling: touch;\n            border-radius: var(--border-radius);\n            box-shadow: var(--card-shadow);\n            background: white;\n            margin-bottom: 30px;\n        }",
        $content
    );

    // Fix 1b: sidebar
    $content = str_replace(
        "box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);\r\n        }",
        "box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);\r\n            overflow-y: auto;\r\n        }",
        $content
    );
    $content = str_replace(
        "box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);\n        }",
        "box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);\n            overflow-y: auto;\n        }",
        $content
    );

    // Fix 2: sticky header gap
    $content = str_replace("position: sticky;\r\n            top: 0;\r\n            z-index: 2;", "position: sticky;\r\n            top: -1px;\r\n            z-index: 10;\r\n            box-shadow: 0 1px 0 var(--primary), 0 -1px 0 var(--primary);", $content);
    $content = str_replace("position: sticky;\n            top: 0;\n            z-index: 2;", "position: sticky;\n            top: -1px;\n            z-index: 10;\n            box-shadow: 0 1px 0 var(--primary), 0 -1px 0 var(--primary);", $content);

    // Fix 3: table-warning replacements
    $content = str_replace("class=\"<?= (\$sz['q'] <= 10) ? 'table-warning' : '' ?>\"", "data-low-stock=\"<?= (\$sz['q'] <= 10) ? 'true' : 'false' ?>\"", $content);
    $content = str_replace("class=\"<?= \$isLowStock ? 'table-warning' : '' ?>\"", "data-low-stock=\"<?= \$isLowStock ? 'true' : 'false' ?>\"", $content);
    $content = str_replace("row.classList.contains(\"table-warning\")", "(row.getAttribute(\"data-low-stock\") === \"true\")", $content);
    $content = str_replace("row.classList.contains('table-warning')", "(row.getAttribute(\"data-low-stock\") === \"true\")", $content);

    file_put_contents($file, $content);
}
echo "Done";
