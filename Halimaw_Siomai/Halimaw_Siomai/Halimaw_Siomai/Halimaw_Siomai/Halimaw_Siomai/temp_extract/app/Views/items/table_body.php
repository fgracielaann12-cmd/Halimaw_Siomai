<?php foreach ($items as $item): ?>
    <tr>
        <td><?= esc($item['product_id']) ?></td>
        <td><?= esc($item['name']) ?></td>
        <td><?= esc($item['quantity']) ?></td>
        <td><?= esc($item['price']) ?></td>
        <td><?= esc($item['category']) ?></td>
        <td><?= esc($item['expiration_date']) ?></td>
        <td><?= esc($item['barcode']) ?></td>
        <td><?= esc($item['subcategory']) ?></td>
        <td><?= esc($item['auto_delete']) ? 'Yes' : 'No' ?></td>
        <td><?= esc($item['created_at']) ?></td>
    </tr>
<?php endforeach; ?>