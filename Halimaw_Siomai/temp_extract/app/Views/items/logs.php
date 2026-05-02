<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Item Edit Logs | Halimaw Siomai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #858796;
            --success: #1cc88a;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --light: #f8f9fc;
            --dark: #5a5c69;
            --sidebar-bg: #2c3e50;
            --sidebar-text: #d1d5db;
            --sidebar-hover: #34495e;
            --sidebar-active: #4e73df;
            --card-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --border-radius: 0.35rem;
        }

        * {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f8f9fc;
            color: #3a3b45;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        #sidebar .navbar-brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        #sidebar .navbar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
        }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }
        #sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }
        #sidebar .nav-link.active {
            background: linear-gradient(90deg, var(--sidebar-hover), var(--sidebar-active));
            color: white;
            border-left: 3px solid var(--sidebar-active);
        }
        .nav-link.text-danger {
            color: #ff6b6b !important;
        }
        .nav-link.text-danger:hover {
            background: rgba(231, 74, 59, 0.15);
            color: var(--danger) !important;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* MOBILE MENU */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 998;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
        }

        /* CONTAINER */
        .container {
            padding: 30px 20px;
        }

        /* BUTTONS */
        .btn-back, .btn-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.2s;
        }
        .btn-back {
            background: var(--primary);
            color: white;
        }
        .btn-back:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .btn-export {
            background: var(--success);
            color: white;
        }
        .btn-export:hover {
            background: #17a673;
            transform: translateY(-2px);
        }

        /* CARD */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 25px;
        }

        /* TABLE */
        .table {
            min-width: 700px;
            font-size: 0.9rem;
            margin: 0;
        }
        .table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
        }
        .table .text-start {
            text-align: left !important;
        }
        .table ul {
            margin: 0;
            padding-left: 16px;
        }
        .table li {
            margin-bottom: 4px;
        }

        /* DIFF HIGHLIGHT */
        .text-danger {
            font-weight: 600;
            color: var(--danger);
        }
        .text-success {
            font-weight: 600;
            color: var(--success);
        }

        /* ALERTS */
        .alert {
            border-radius: var(--border-radius);
            font-weight: 500;
            text-align: center;
        }

        /* PAGINATION */
        .pagination {
            justify-content: center;
            margin-top: 20px;
        }
        .pagination .page-link {
            color: var(--primary);
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            #sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; }
            #sidebar.active { transform: translateX(0); }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .sidebar-overlay.active { display: block; }

            .container { padding: 20px 15px; }
            .d-md-flex { flex-direction: column; gap: 10px; }
            .btn-back, .btn-export { width: 100%; }
            .table { min-width: 600px; font-size: 0.85rem; }
        }
    </style>
</head>
<body>

<?php $currentPath = uri_string(); ?>

<?= view('partials/admin_sidebar') ?>
<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="container">
        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="d-md-flex justify-content-between align-items-center mb-4">
            <a href="<?= base_url('/items') ?>" class="btn-back mb-2 mb-md-0">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
            <a href="<?= base_url('items/export-logs-csv') ?>" class="btn-export mb-2 mb-md-0">
                <i class="bi bi-file-earmark-arrow-down me-1"></i> Export Logs (CSV)
            </a>
        </div>

        <!-- Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Product ID</th>
                            <th>Updated By</th>
                            <th>Changes</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($logs)): ?>
                            <?php foreach ($logs as $log): ?>
                                <?php
                                $old = json_decode($log['old_data'], true) ?? [];
                                $new = json_decode($log['new_data'], true) ?? [];
                                $name = $log['item_name'] ?? 'N/A';
                                $productId = $log['product_id'] ?? 'N/A';
                                ?>
                                <tr>
                                    <td><?= esc($name) ?></td>
                                    <td><?= esc($productId) ?></td>
                                    <td><?= esc($log['updated_by']) ?></td>
                                    <td class="text-start">
                                        <ul class="list-unstyled mb-0">
                                            <?php foreach ($new as $key => $value): ?>
                                                <?php $oldValue = $old[$key] ?? '';
                                                $changed = (string) $oldValue !== (string) $value; ?>
                                                <li>
                                                    <strong><?= esc($key) ?>:</strong>
                                                    <?php if ($changed): ?>
                                                        <span class="text-danger"><?= esc($oldValue === '' ? '(empty)' : $oldValue) ?></span>
                                                        →
                                                        <span class="text-success"><?= esc($value) ?></span>
                                                    <?php else: ?>
                                                        <?= esc($value) ?>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </td>
                                    <td><?= esc($log['updated_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No logs found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="mt-3 d-flex justify-content-center">
                    <?= $pager->links('logs', 'bootstrap_full') ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', () => {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    const navLinks = document.querySelectorAll('#sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
});
</script>
</body>
</html>