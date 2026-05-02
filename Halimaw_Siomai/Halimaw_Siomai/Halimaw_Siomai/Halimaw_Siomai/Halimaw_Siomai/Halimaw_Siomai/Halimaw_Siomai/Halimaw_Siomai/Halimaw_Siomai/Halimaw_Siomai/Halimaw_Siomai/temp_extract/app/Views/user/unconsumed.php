<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            box-shadow: var(--card-shadow);
        }
        #sidebar .navbar-brand {
            padding: 1.25rem 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        #sidebar .navbar-brand img {
            width: 48px;
            height: 48px;
            border-radius: 8px;
        }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.85rem 1.25rem;
            margin: 0.25rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            text-decoration: none;
        }
        #sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            text-decoration: none;
        }
        #sidebar .nav-link.active {
            background-color: var(--sidebar-hover);
            color: white;
            font-weight: 600;
            border-left: 3px solid var(--sidebar-active);
        }
        #sidebar .nav-link.active:hover {
            filter: brightness(1.15);
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

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 998;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
        }
        .mobile-menu-toggle:hover {
            background: var(--sidebar-hover);
        }

        .container {
            max-width: 96%;
            padding: 20px;
            margin-top: 5px;
        }

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
        .sidebar-overlay.active {
            display: block;
        }

        /* 🎯 ENHANCED STATS SECTION */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        @media (min-width: 992px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 576px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 15px;
            box-shadow: var(--card-shadow);
            border-top: 4px solid var(--primary);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        @media (min-width: 768px) {
            .stat-card { padding: 22px 20px; align-items: flex-start; text-align: left; }
        }

        /* Responsive Table Wrapper */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: white;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .hide-mobile { display: none; }
            .container { padding: 10px; }
            .stat-value { font-size: 1.4rem; }
            .stat-title { font-size: 0.8rem; }
        }

        /* TABLE */
        #itemsTable {
            width: 100%;
            min-width: 800px;
            font-size: 0.9rem;
            margin: 0;
            background: white;
        }
        #itemsTable tbody tr {
            transition: none !important;
            cursor: default !important;
        }
        #itemsTable tbody tr:hover {
            background-color: transparent !important;
        }
        #itemsTable tbody tr:nth-child(even) {
            background-color: #fafbff;
        }

        /* CONTROLS */
        .controls-section {
            background: white;
            padding: 18px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }
        .controls-section .form-control,
        .controls-section .form-select {
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            padding: 8px 12px;
            min-width: 180px;
        }
        .controls-section .btn {
            border-radius: var(--border-radius);
            background: var(--primary);
            color: white;
            font-weight: 500;
            padding: 8px 16px;
        }
        .controls-section .btn:hover {
            background: var(--primary-dark);
        }
        .controls-section label {
            font-weight: 600;
            color: var(--dark);
            margin: 0 8px 0 0;
        }

        /* MODAL ENHANCEMENTS */
        .modal-content {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }
        .modal .btn[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            #sidebar { transform: translateX(-100%); width: 280px; }
            
            
            .main-content { margin-left: 0; width: 100%; }
            .container { padding-top: 15px; } /* Prevent title overlap */
            #sidebar.active { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .stat-card { min-width: 200px; }
        }
        @media (max-width: 767px) {
            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }
            .controls-section > div {
                width: 100%;
            }
            #itemsTable { min-width: 600px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <?php
    // 🔶 Expiring Soon Items
    $expiringSoonItems = array_filter($items, function ($i) {
        if (empty($i['expiration_date']) || $i['expiration_date'] === '0000-00-00')
            return false;
        $today = new DateTime();
        $expiration = new DateTime($i['expiration_date']);
        $daysLeft = (int) $today->diff($expiration)->format('%r%a');
        return $daysLeft <= 10;
    });
    // 🔴 Low Stock Items
    $lowStockItems = array_filter($items, fn($i) => $i['quantity'] <= 10);
    $currentPath = uri_string();

    function isActive($paths) {
        if (!is_array($paths)) $paths = [$paths];
        $currentPath = uri_string();
        foreach ($paths as $path) {
            if ($path === '') {
                if ($currentPath === '') return 'active';
                continue;
            }
            if ($currentPath === $path || strpos($currentPath, $path . '/') === 0 || strpos($currentPath, $path) === 0) {
                return 'active';
            }
        }
        return '';
    }
    ?>

    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="bi bi-list"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <nav id="sidebar">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url('Images/Inventa.png') ?>" alt="Inventa Logo">
            <span>Halimaw Siomai</span>
        </a>
        <ul class="nav flex-column px-2 mt-3">
            <li class="nav-item">
                <a class="nav-link <?= isActive(['user/dashboard', '', 'dashboard']) ?>" href="<?= site_url('user/dashboard') ?>">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive(['admin/staff/pos']) ?>" href="<?= site_url('admin/staff/pos') ?>">
                    <i class="bi bi-calculator"></i> Staff POS
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= isActive(['user/unconsumed']) ?>" href="<?= site_url('user/unconsumed') ?>">
                    <i class="bi bi-cart-x"></i> Unconsumed Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-arrow-repeat"></i> Request Stock Adjustment
                </a>
            </li>
            <li class="nav-item mt-3 mb-2">
                <hr style="border-top: 1px solid rgba(255,255,255,0.3); opacity: 1; margin: 0 1.5rem;">
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="<?= site_url('logout') ?>">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    
    <div class="main-content">
        <div class="container">
            <h2 class="text-center mb-4" style="font-weight: 700; color: var(--dark);"><i class="bi bi-cart-x me-2"></i>Unconsumed Products History</h2>
            
            <div class="table-responsive-custom mt-4">
                <table id="itemsTable" class="table table-hover align-middle mb-0 text-center">
                    <thead style="background: var(--sidebar-bg); color: white;">
                        <tr>
                            <th>Product ID</th>
                            <th>Quantity</th>
                            <th>Name of Product</th>
                            <th>Price</th>
                            <th>Loss Revenue</th>
                            <th>Expiration Date</th>
                            <th>Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= esc($item['product_id']) ?></td>
                                    <td><?= esc($item['quantity']) ?></td>
                                    <td class="text-start fw-bold"><?= esc($item['name']) ?></td>
                                    <td class="text-success fw-semibold">₱<?= number_format($item['price'] ?? 0, 2) ?></td>
                                    <td class="text-danger fw-semibold">₱<?= number_format(($item['price'] ?? 0) * $item['quantity'], 2) ?></td>
                                    <td>
                                        <span class="badge bg-danger">
                                            <i class="bi bi-calendar-x me-1"></i>
                                            <?= esc($item['expiration_date'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td><?= esc($item['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-muted py-4">No unconsumed products history found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
            }
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', () => {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
        });
    </script>
</body>
</html>