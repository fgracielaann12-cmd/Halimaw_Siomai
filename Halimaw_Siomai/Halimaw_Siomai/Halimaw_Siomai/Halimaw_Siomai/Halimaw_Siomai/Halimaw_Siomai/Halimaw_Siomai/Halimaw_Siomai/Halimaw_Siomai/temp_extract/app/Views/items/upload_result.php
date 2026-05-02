<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-5">

    <div class="container">
        <h2>✅ Upload Successful</h2>
        <p><?= count($imported) ?> items imported successfully.</p>

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Expiration Date</th>
                    <th>Barcode</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Auto Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($imported as $item): ?>
                    <tr>
                        <td><?= esc($item['product_id']) ?></td>
                        <td><?= esc($item['name']) ?></td>
                        <td><?= esc($item['quantity']) ?></td>
                        <td><?= esc($item['price']) ?></td>
                        <td><?= esc($item['expiry_date']) ?></td>
                        <td><?= esc($item['barcode']) ?></td>
                        <td><?= esc($item['category']) ?></td>
                        <td><?= esc($item['subcategory']) ?></td>
                        <td><?= $item['auto_delete'] ? '✅ Yes' : '❌ No' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="<?= base_url('items/upload') ?>" class="btn btn-secondary mt-3">Back to Upload</a>
    </div>

</body>

</html>