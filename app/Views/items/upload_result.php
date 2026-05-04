<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Upload Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- UNIFIED 5PX SYSTEM-WIDE RADIUS OVERRIDE -->
    <style>
        :root {
            --border-radius: 5px !important;
        }
        
        /* Buttons */
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn, .chart-filter-btn,
        
        /* Textboxes / Inputs */
        input, select, textarea, .form-control, .form-select, .custom-input-group,
        
        /* Tables & Wrappers */
        .table, .table-card, .table-responsive, table, .dataTables_wrapper,
        
        /* Cards & Misc UI */
        .card, .pos-item-card, .summary-card, .img-metric-card, .chart-card-premium, .pos-checkout,
        .alert, .badge, .modal-content, .modal-header, .nav-link, .login-card,
        
        /* Bootstrap Overrides */
        .rounded, .rounded-1, .rounded-2, .rounded-3, .rounded-circle, .rounded-pill,
        .rounded-top, .rounded-bottom, .rounded-start, .rounded-end {
            border-radius: 5px !important;
        }
        
        /* Images inside cards */
        .pos-item-card img, .card img {
            border-radius: 5px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    </style>
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