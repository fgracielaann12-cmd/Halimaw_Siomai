<?php include APPPATH.'Views/partials/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Edit Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* === Navbar Styling === */
        .navbar-nav .nav-link {
            transition: color 0.3s ease, border-bottom 0.3s ease;
            padding-bottom: 0.25rem;
        }
        .navbar-nav .nav-link:hover {
            color: #ffc107;
            border-bottom: 2px solid #ffc107;
        }
        .navbar-nav .nav-link.active {
            color: #ffc107;
            border-bottom: 2px solid #ffc107;
            font-weight: 600;
        }

        /* === Enhanced Table Styling === */
        .table-wrapper {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            padding: 20px;
        }

        table.table {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0;
            min-width: 1000px;
        }

        table.table thead th {
            background-color: #212529;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-size: 0.85rem;
            padding: 12px;
            vertical-align: middle;
        }

        table.table tbody td {
            vertical-align: middle;
            text-align: center;
            font-size: 0.9rem;
            padding: 10px 8px;
            border-color: #dee2e6;
        }

        table.table tbody tr {
            transition: background-color 0.25s ease, transform 0.15s ease;
        }

        table.table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.002);
        }

        table.table tbody tr:nth-child(even) {
            background-color: #fcfcfc;
        }

        /* === Preformatted Data Cells === */
        pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            text-align: left;
        }

        /* === Buttons === */
        a.btn:hover {
            background-color: #212529;
            color: #fff !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        /* === Top Scrollbar === */
        .table-scroll-wrapper { position: relative; margin-top: 8px; }
        .table-scroll-top { overflow-x: auto; overflow-y: hidden; height: 16px; margin-bottom: 6px; }
        .table-scroll-bottom { overflow-x: auto; }
    </style>
</head>
<body>

<!-- ✅ NAVBAR START -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-3" href="<?= site_url('items') ?>">
            <img src="http://192.168.0.55/inventa/public/Images/Inventa.png" alt="Inventa Logo" style="width: 50px; height: 50px;">
            <span class="brand-text">Inventa</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-3">
                <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items') ?>">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/expiringSoon') ?>">Expiring Soon</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/deleted') ?>">Expired</a></li>
                <li class="nav-item"><a class="nav-link text-white active" href="<?= site_url('items/logs') ?>">Logs</a></li> 
                <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('/logout') ?>">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- ✅ NAVBAR END -->

<div class="container mt-5">

    <a href="<?= base_url('/items') ?>" 
       class="btn btn-outline-dark fw-semibold rounded-pill shadow-sm px-3 py-1 mb-3 d-inline-flex align-items-center"
       style="transition: all 0.3s ease;">
       <i></i> Back to Dashboard
    </a>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success text-center shadow-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="table-wrapper">
        <div class="table-scroll-wrapper">
            <div class="table-scroll-top"><div id="scroll-sync" style="height:1px;"></div></div>
            <div class="table-responsive table-scroll-bottom">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Item ID</th>
                            <th>Updated By</th>
                            <th>Old Data</th>
                            <th>New Data</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?= esc($log['id']) ?></td>
                                    <td><?= esc($log['item_id']) ?></td>
                                    <td><?= esc($log['updated_by']) ?></td>
                                    <td><pre><?= print_r(json_decode($log['old_data'], true), true) ?></pre></td>
                                    <td><pre><?= print_r(json_decode($log['new_data'], true), true) ?></pre></td>
                                    <td><?= esc($log['updated_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center">No logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const top = document.querySelector('.table-scroll-top');
    const bottom = document.querySelector('.table-scroll-bottom');
    if (top && bottom) {
        const inner = document.getElementById('scroll-sync');
        const table = bottom.querySelector('table');
        if (inner && table) inner.style.width = table.offsetWidth + 'px';
        top.addEventListener('scroll', () => { bottom.scrollLeft = top.scrollLeft; });
        bottom.addEventListener('scroll', () => { top.scrollLeft = bottom.scrollLeft; });
    }
});
</script>
</body>
</html>

<?php include APPPATH.'Views/partials/footer.php'; ?>
