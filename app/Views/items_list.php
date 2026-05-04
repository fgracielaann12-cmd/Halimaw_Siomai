<!DOCTYPE html>
<html>
<head>
    <title>Items List</title>
    <style>
        body { font-family: Arial, sans-serif; margin:40px; }
        table { border-collapse: collapse; width:100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
            /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-1, .btn.rounded-1, .btn-add-to-cart, .btn, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
        }
    </style>
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
