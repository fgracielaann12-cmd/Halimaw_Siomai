<?php
$files = [
    "c:\\xampp\\htdocs\\Halimaw_Siomai\\Halimaw_Siomai\\app\\Views\\sales\\list.php"
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    // Fix 1: btn-export CSS
    $oldCss = ".btn-export {\r\n            display: inline-flex;\r\n            align-items: center;\r\n            justify-content: center;\r\n            padding: 8px 20px;\r\n            font-size: 0.95rem;\r\n            font-weight: 600;\r\n            background: var(--success);\r\n            color: white;\r\n            border: none;\r\n            border-radius: 5px;\r\n            transition: all 0.2s;\r\n            margin-bottom: 20px;\r\n        }";
    $newCss = ".btn-export {\r\n            display: inline-flex;\r\n            align-items: center;\r\n            justify-content: center;\r\n            padding: 8px 20px;\r\n            font-size: 0.95rem;\r\n            font-weight: 600;\r\n            background: var(--success);\r\n            color: white;\r\n            border: none;\r\n            transition: all 0.2s;\r\n        }";
    
    $oldCssLF = str_replace("\r\n", "\n", $oldCss);
    $newCssLF = str_replace("\r\n", "\n", $newCss);
    
    $content = str_replace($oldCss, $newCss, $content);
    $content = str_replace($oldCssLF, $newCssLF, $content);

    // Fix 2: the buttons HTML
    $oldHtml = "<a href=\"<?= site_url('items/export-sales-csv') ?>\" class=\"btn-export w-100 w-md-auto mb-0\">\r\n                    <i class=\"bi bi-file-earmark-spreadsheet me-1\"></i> Export to CSV\r\n                </a>\r\n                <a href=\"<?= site_url('admin/sales/transactions') ?>\" class=\"btn btn-primary w-100 w-md-auto\" style=\"border-radius: 5px; padding: 8px 20px; font-weight: 600;\">\r\n                    <i class=\"bi bi-clock-history me-1\"></i> Transaction History\r\n                </a>";
    $newHtml = "<a href=\"<?= site_url('items/export-sales-csv') ?>\" class=\"btn btn-export w-100 w-md-auto mb-0\">\r\n                    <i class=\"bi bi-file-earmark-spreadsheet me-1\"></i> Export to CSV\r\n                </a>\r\n                <a href=\"<?= site_url('admin/sales/transactions') ?>\" class=\"btn btn-primary w-100 w-md-auto mb-0\" style=\"display: inline-flex; align-items: center; justify-content: center; padding: 8px 20px; font-weight: 600; font-size: 0.95rem; border: none; transition: all 0.2s;\">\r\n                    <i class=\"bi bi-clock-history me-1\"></i> Transaction History\r\n                </a>";
    
    $oldHtmlLF = str_replace("\r\n", "\n", $oldHtml);
    $newHtmlLF = str_replace("\r\n", "\n", $newHtml);

    $content = str_replace($oldHtml, $newHtml, $content);
    $content = str_replace($oldHtmlLF, $newHtmlLF, $content);

    file_put_contents($file, $content);
}
echo "Done replacing content.\n";
