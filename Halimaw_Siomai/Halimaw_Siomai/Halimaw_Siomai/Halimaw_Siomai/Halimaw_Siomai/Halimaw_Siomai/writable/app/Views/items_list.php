<!DOCTYPE html>
<html>
<head>
    <title>Items List</title>
    <style>
        body { font-family: Arial, sans-serif; margin:40px; }
        table { border-collapse: collapse; width:100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    
    

<?php if (session()->getFlashdata('expiring_warning')): ?>
    <script>
        // ✅ Safely encode message for JS
        alert(<?= json_encode(session()->getFlashdata('expiring_warning')) ?>);
    </script>
<?php endif; ?>
<h2>Welcome, <?= esc($username) ?>!</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Expiration Date</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= esc($item['id']) ?></td>
                <td><?= esc($item['item_name']) ?></td>
                <td><?= esc($item['quantity']) ?></td>
                <td><?= esc($item['expiration_date']) ?></td>
                <td><?= esc($item['status']) ?></td>
            </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr><td colspan="5">No items found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
