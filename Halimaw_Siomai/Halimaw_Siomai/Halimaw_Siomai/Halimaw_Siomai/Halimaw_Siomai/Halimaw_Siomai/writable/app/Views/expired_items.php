<!DOCTYPE html>
<html>
<head>
    <title>Expired Items</title>
</head>
<body>
    <h1>Expired Items</h1>

    <?php if (empty($items)): ?>
        <p>No expired items found.</p>
    <?php else: ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Expiration Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= esc($item['name']) ?></td>
                        <td><?= esc($item['quantity']) ?></td>
                        <td><?= esc($item['price']) ?></td>
                        <td><?= esc($item['expiration_date']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
