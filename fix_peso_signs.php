<?php
$files = [
    'app/Views/items/list.php',
    'app/Views/admin/dashboard.php',
    'app/Views/pos/index.php',
    'app/Views/pos/staff.php',
    'app/Views/user/dashboard.php',
    'app/Views/sales/list.php',
    'app/Views/sales/transactions.php'
];

foreach ($files as $file) {
    $path = '/Users/macbookair/Desktop/Halimaw_Siomai/' . $file;
    if (file_exists($path)) {
        $content = file_get_contents($path);
        
        // Replace broken patterns in HTML/PHP
        $content = str_replace('?<?= number_format', '&#8369;<?= number_format', $content);
        $content = str_replace('?${parseFloat', '&#8369;${parseFloat', $content);
        $content = str_replace('Price (?)', 'Price (&#8369;)', $content);
        
        // Replace literal ₱ with &#8369; in HTML context
        // We use a regex to avoid replacing inside JS strings if possible, 
        // but &#8369; usually works in JS if assigned to innerHTML.
        // For innerText, we might need the literal character.
        
        // Replace literal ₱ with &#8369;
        $content = str_replace('₱', '&#8369;', $content);
        
        // Also fix some specific broken strings in items/list.php seen in read_file
        if ($file === 'app/Views/items/list.php') {
            $content = str_replace('Name (AZ)', 'Name (A-Z)', $content);
            $content = str_replace('Name (ZA)', 'Name (Z-A)', $content);
            $content = str_replace('Quantity (Low ? High)', 'Quantity (Low - High)', $content);
            $content = str_replace('Quantity (High ? Low)', 'Quantity (High - Low)', $content);
            $content = str_replace('Date (Oldest ? Newest)', 'Date (Oldest - Newest)', $content);
            $content = str_replace('Date (Newest ? Oldest)', 'Date (Newest - Oldest)', $content);
            // Replace remaining '?' that look like they should be Peso signs in items/list.php
            // e.g. "?<?= number_format" was handled, but what about other cases?
            // From read_file: <td class="text-center align-middle text-nowrap">?<?= number_format($sz['p'], 2) ?></td>
            // This is handled by the first str_replace.
        }

        file_put_contents($path, $content);
        echo "Fixed $file\n";
    } else {
        echo "File not found: $file\n";
    }
}
