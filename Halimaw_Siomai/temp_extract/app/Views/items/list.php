<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Inventory Dashboard | Halimaw Siomai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            text-decoration: none;
        }
        #sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
            text-decoration: none;
        }
        #sidebar .nav-link.active {
            background: linear-gradient(90deg, var(--sidebar-hover), var(--sidebar-active));
            color: white;
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

        /* TOP NAVBAR */
        .top-navbar {
            background: white;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-navbar h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }

        /* USER PROFILE */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .profile-initial {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
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
            padding: 0 20px 20px;
        }

        /* SUMMARY CARD */
        .summary-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 15px;
            margin-bottom: 20px;
            height: 100%;
        }
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        @media (min-width: 768px) {
            .chart-container { height: 300px; }
            .summary-card { padding: 20px; }
        }

        /* Responsive Table */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: white;
        }

        @media (max-width: 768px) {
            .hide-mobile { display: none; }
            .summary-card h3 { font-size: 1.5rem; }
            .summary-card h6 { font-size: 0.8rem; }
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        .summary-item span:last-child {
            font-weight: 600;
            color: var(--primary);
        }

        /* BUTTONS */
        .btn-add-new-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.2s;
            margin-bottom: 20px;
            text-decoration: none;
        }
        .btn-add-new-item:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .form-select, .form-control {
            font-size: 0.9rem;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            padding: 6px 10px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            outline: none;
        }

        /* ALERTS */
        .notification-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-50px);
            z-index: 9999;
            min-width: 280px;
            max-width: 480px;
            text-align: center;
            border-radius: var(--border-radius);
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 500;
            color: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transition: all 0.5s ease;
        }
        .notification-alert.show { opacity: 1; transform: translateX(-50%) translateY(0); }
        .success-alert { background: linear-gradient(135deg, var(--success), #17a673); }
        .error-alert { background: linear-gradient(135deg, var(--danger), #d93a2a); }
        .close-alert {
            background: transparent;
            border: none;
            color: inherit;
            font-size: 1.3rem;
            cursor: pointer;
            margin-left: 12px;
        }

        .inventory-alert, .expiry-alert {
            position: fixed;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9998;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.5s;
            max-width: 480px;
            text-align: center;
        }
        .inventory-alert {
            top: 70px;
            background: #fff3cd;
            color: #856404;
        }
        .expiry-alert {
            top: 110px;
            background: #f8d7da;
            color: #721c24;
        }
        .inventory-alert.show, .expiry-alert.show { opacity: 1; }

        /* TABLE */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 0;
            margin-bottom: 20px;
        }
        #itemsTable {
            min-width: 900px;
            font-size: 0.9rem;
            margin: 0;
        }
        #itemsTable thead th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        #itemsTable tbody tr {
            transition: background 0.2s;
        }
        #itemsTable tbody tr:hover {
            background-color: #f8f9ff;
        }
        #itemsTable .btn {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
        }
        #itemsTable .btn-edit {
            background: var(--primary);
            color: white;
        }
        #itemsTable .btn-delete {
            background: var(--danger);
            color: white;
        }
        .quantity-control {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .quantity-control input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .quantity-control button {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 4px;
            background: #f1f2f6;
            border: 1px solid #ddd;
            color: var(--dark);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 30px;
            font-weight: 500;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            #sidebar { transform: translateX(-100%); width: 280px; }
            
            
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

            .container { padding: 0 15px 15px; }
            .top-navbar { padding: 10px 15px; padding-left: 65px; }
            .table { min-width: 600px; }
        }
    </style>
</head>
<body>

<?php 
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

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP NAVBAR WITH USER PROFILE -->
    <div class="top-navbar">
        <h5>Inventory Dashboard</h5>
        <div class="user-profile">
            <div class="profile-initial" id="profileInitial">
                <?php 
                $username = session()->get('username') ?? 'User';
                $initials = substr($username, 0, 1);
                echo strtoupper($initials);
                ?>
            </div>
            <div>
                <div><?= esc(session()->get('username') ?? 'User') ?></div>
                <small class="text-muted"><?= esc(session()->get('role') ?? 'Staff') ?></small>
            </div>
        </div>
    </div>

    <div class="container">
        <?php
        $totalValue = array_reduce($items, fn($sum, $item) => $sum + (($item['price'] ?? 0) * ($item['quantity'] ?? 0)), 0);
        $lowStockItems = array_filter($items, function($i) {
            if (stripos($i['name'], 'siomai') !== false) {
                return ($i['pack_small_qty'] ?? 0) <= 10 && ($i['pack_medium_qty'] ?? 0) <= 10 && ($i['pack_biggest_qty'] ?? 0) <= 10;
            }
            return $i['quantity'] <= 10;
        });
        $expiringItems = array_filter($items, function ($i) {
            if (empty($i['expiration_date']) || $i['expiration_date'] === '0000-00-00') return false;
            $today = new DateTime();
            $expiration = new DateTime($i['expiration_date']);
            $daysLeft = (int) $today->diff($expiration)->format('%r%a');
            return $daysLeft <= 10;
        });
        ?>
        <!-- 📊 DATA ANALYTICS SECTION -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-primary border-4">
                    <h6 class="text-muted">Total Stock Value</h6>
                    <h3 class="fw-bold text-primary">₱<?= number_format($totalValue, 2) ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-success border-4">
                    <h6 class="text-muted">Total Items</h6>
                    <h3 class="fw-bold text-success"><?= count($items) ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-warning border-4">
                    <h6 class="text-muted">Low Stock</h6>
                    <h3 class="fw-bold text-warning"><?= count($lowStockItems) ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-danger border-4">
                    <h6 class="text-muted">Expiring Soon</h6>
                    <h3 class="fw-bold text-danger"><?= count($expiringItems) ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-lg-6">
                <div class="summary-card">
                    <h6><i class="bi bi-pie-chart-fill me-2"></i>Stock by Category</h6>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="summary-card">
                    <h6><i class="bi bi-bar-chart-line-fill me-2"></i>Top 5 Items by Value</h6>
                    <div class="chart-container">
                        <canvas id="topItemsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="notification-alert success-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span><?= session()->getFlashdata('success') ?></span>
                <button class="close-alert">&times;</button>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="notification-alert error-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span><?= session()->getFlashdata('error') ?></span>
                <button class="close-alert">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Controls -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                <input type="text" id="searchQuery" class="form-control" placeholder="Search by item name" oninput="searchItems()">
                <button onclick="searchItems()" class="btn btn-primary w-100 w-md-auto">Search</button>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                <label for="statusFilter" class="form-label mb-0 fw-bold">Category:</label>
                <select id="statusFilter" class="form-select" onchange="filterStatus()">
                    <option value="all">All</option>
                    <option value="Food">Food</option>
                    <option value="Non-Food">Non-Food</option>
                </select>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                <label for="sortFilter" class="form-label mb-0 fw-bold">Sort:</label>
                <select id="sortFilter" class="form-select" onchange="sortItems()">
                    <option value="default">Default</option>
                    <option value="name_asc">Name (A–Z)</option>
                    <option value="name_desc">Name (Z–A)</option>
                    <option value="quantity_asc">Quantity (Low → High)</option>
                    <option value="quantity_desc">Quantity (High → Low)</option>
                    <option value="date_asc">Date (Oldest → Newest)</option>
                    <option value="date_desc">Date (Newest → Oldest)</option>
                    <option value="expiring_soon">Expiring Soon</option>
                    <option value="active">Active</option>
                </select>
            </div>
        </div>

        <!-- Alerts -->
        <?php if (!empty($lowStockItems)): ?>
            <div class="inventory-alert" id="lowStockAlert" onclick="showLowStockItems()" style="cursor:pointer;">
                You have <?= count($lowStockItems) ?> item(s) running low on stock.
                <span style="text-decoration: underline; font-size: 0.9rem; margin-left:6px;">Click to view</span>
            </div>
            <div class="text-center mb-3">
                <button id="showAllBtn" class="btn btn-outline-secondary btn-sm" style="display:none;" onclick="showAllItems()">Show All Items</button>
            </div>
        <?php endif; ?>

        <?php if (!empty($expiringItems)): ?>
            <a href="<?= site_url('items/expiring-soon') ?>" class="text-decoration-none">
                <div class="expiry-alert">
                    You have <?= count($expiringItems) ?> item(s) expiring soon!
                    <span style="font-size: 0.9rem; text-decoration: underline;">View details →</span>
                </div>
            </a>
        <?php endif; ?>

        <!-- Add New Item & Refresh Group -->
        <div class="d-flex mb-3 align-items-center gap-2">
            <a href="<?= site_url('items/add') ?>" class="btn-add-new-item mb-0">Add New Item</a>
            <button onclick="location.reload()" class="btn btn-light shadow-sm border" style="height: 40px; width: 45px; display: flex; align-items: center; justify-content: center; border-radius: var(--border-radius);" title="Refresh Table">
                <i class="bi bi-arrow-clockwise" style="font-size: 1.2rem;"></i>
            </button>
        </div>

        <!-- Table -->
        <?php if (!empty($items) && is_array($items)): ?>
        <div class="table-responsive-custom">
            <table id="itemsTable" class="table table-bordered table-hover align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th class="hide-mobile">Value %</th>
                        <th class="hide-mobile">Category</th>
                        <th class="hide-mobile">Expiration Date</th>
                        <th>Status</th>
                        <th class="hide-mobile">Date Entry</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                    <?php
                        $today = new DateTime();
                        if (empty($item['expiration_date']) || $item['expiration_date'] === '0000-00-00') {
                            $status = 'na';
                            $statusLabel = "N/A";
                            $daysLeftText = "—";
                        } else {
                            $expiration = new DateTime($item['expiration_date']);
                            $interval = $today->diff($expiration);
                            $daysLeft = (int) $interval->format('%r%a');
                            if ($daysLeft < 0) {
                                $status = 'expired';
                                $statusLabel = "Expired";
                                $daysLeftText = abs($daysLeft) . " days ago";
                            } elseif ($daysLeft == 0) {
                                $status = 'expiring soon';
                                $statusLabel = "Expiring Today";
                                $daysLeftText = "Today";
                            } elseif ($daysLeft <= 10) {
                                $status = 'expiring soon';
                                $statusLabel = "Expiring Soon";
                                $daysLeftText = "$daysLeft days left";
                            } else {
                                $status = 'active';
                                $statusLabel = "Active";
                                $daysLeftText = "$daysLeft days left";
                            }
                        }
                        $isSiomai = stripos($item['name'], 'siomai') !== false;
                        if ($isSiomai) {
                            $isLowStock = ($item['pack_small_qty'] ?? 0) <= 10 && ($item['pack_medium_qty'] ?? 0) <= 10 && ($item['pack_biggest_qty'] ?? 0) <= 10;
                        } else {
                            $isLowStock = $item['quantity'] <= 10;
                        }
                    ?>
                    <tr data-id="<?= $item['id'] ?>" class="<?= $isLowStock ? 'table-warning' : '' ?>">
                        <td><input type="checkbox" class="item-checkbox" value="<?= $item['id'] ?>"></td>
                        <td>
                            <?php if (stripos($item['name'], 'siomai') !== false): ?>
                                <div style="font-size: 0.75rem; line-height: 1.5;">
                                    <div><span class="text-muted">Small:</span> <br><strong><?= esc($item['product_id']) ?>-S</strong></div>
                                    <div class="mt-1"><span class="text-muted">Medium:</span> <br><strong><?= esc($item['product_id']) ?>-M</strong></div>
                                    <div class="mt-1"><span class="text-muted">Biggest:</span> <br><strong><?= esc($item['product_id']) ?>-B</strong></div>
                                </div>
                            <?php else: ?>
                                <?= esc($item['product_id']) ?>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($item['name']) ?></td>
                        <td>₱<?= number_format($item['price'], 2) ?></td>
                        <td>
                            <?php if (stripos($item['name'], 'siomai') !== false): ?>
                                <div style="font-size: 0.8rem; line-height: 1.4;">
                                    <div>S <small class="text-muted">(12)</small>: <strong><?= esc($item['pack_small_qty'] ?? 0) ?></strong></div>
                                    <div>M <small class="text-muted">(20)</small>: <strong><?= esc($item['pack_medium_qty'] ?? 0) ?></strong></div>
                                    <div>B <small class="text-muted">(40)</small>: <strong><?= esc($item['pack_biggest_qty'] ?? 0) ?></strong></div>
                                </div>
                            <?php else: ?>
                                <?= esc($item['quantity']) ?>
                            <?php endif; ?>
                        </td>
                        <td class="hide-mobile">
                            <?php 
                            $val = $item['price'] * $item['quantity'];
                            echo $totalValue > 0 ? number_format(($val / $totalValue) * 100, 1) : '0';
                            ?>%
                        </td>
                        <td class="hide-mobile"><?= esc($item['category'] ?? '—') ?></td>
                        <td class="hide-mobile"><?= empty($item['expiration_date']) ? '—' : esc($item['expiration_date']) ?></td>
                        <td>
                            <span class="badge 
                                <?= $status == 'expired' ? 'bg-danger' :
                                ($status == 'expiring soon' ? 'bg-warning text-dark' :
                                ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                <?= $statusLabel ?>
                            </span>
                        </td>
                        <td class="hide-mobile"><?= esc($item['created_at']) ?></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-sm btn-edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <?php if (stripos($item['name'], 'siomai') === false): ?>
                                <div class="d-flex align-items-center quantity-control" data-id="<?= $item['id'] ?>">
                                    <button type="button" class="btn btn-sm btn-outline-danger open-qty-modal" data-action="decrease">
                                        <i class="bi bi-dash"></i>
                                    </button>
                                    <input type="number" class="form-control form-control-sm qty-amount mx-1" readonly value="<?= esc($item['quantity']) ?>">
                                    <button type="button" class="btn btn-sm btn-outline-success open-qty-modal" data-action="increase">
                                        <i class="bi bi-plus"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">No items found in the database.</div>
        <?php endif; ?>
    </div>
</div>

<!-- Quantity Modal -->
<div class="modal fade" id="qtyModal" tabindex="-1" aria-labelledby="qtyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--primary); color: white;">
                <h5 class="modal-title" id="qtyModalLabel">Update Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: invert(1);"></button>
            </div>
            <div class="modal-body">
                <form id="qtyForm">
                    <input type="hidden" id="itemId">
                    <input type="hidden" id="actionType">
                    <div class="mb-3">
                        <label for="productId" class="form-label">Product ID</label>
                        <input type="text" id="productId" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="quantityInput" class="form-label">Quantity</label>
                        <input type="number" id="quantityInput" class="form-control" min="1" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // Mobile Menu
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', () => {
            const isActive = sidebar.classList.contains('active');
            if (!isActive) {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                // removed arrow
            } else {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
                // removed arrow
            }
        });
    }

    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
        if (mobileMenuToggle) {
            // removed arrow
        }
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }

    const navLinks = document.querySelectorAll('#sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                closeSidebar();
            }
        });
    });

    // Quantity Modal
    function openQtyModal() {
        const row = this.closest("tr");
        if (!row) return;
        const id = row.dataset.id;
        document.getElementById("itemId").value = id;
        document.getElementById("productId").value = id;
        document.getElementById("actionType").value = this.dataset.action;
        document.getElementById("quantityInput").value = 0;
        new bootstrap.Modal(document.getElementById("qtyModal")).show();
    }
    document.querySelectorAll(".open-qty-modal").forEach(btn => btn.addEventListener("click", openQtyModal));

    document.getElementById("qtyForm").addEventListener("submit", async e => {
        e.preventDefault();
        const id = document.getElementById("itemId").value;
        const action = document.getElementById("actionType").value;
        const quantity = parseInt(document.getElementById("quantityInput").value);
        if (!["increase", "decrease"].includes(action)) return alert("Invalid action.");
        const endpoint = `<?= site_url('items/') ?>${action}Quantity/${id}`;
        try {
            const res = await fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ amount: quantity })
            });
            const result = await res.json();
            if (result.success) {
                bootstrap.Modal.getInstance(document.getElementById("qtyModal")).hide();
                alert(result.message || "Quantity updated successfully!");
                location.reload();
            } else alert(result.message || "Error updating quantity!");
        } catch (err) {
            console.error(err);
            alert("Something went wrong.");
        }
    });

    // Search & Filter
    window.searchItems = window.filterStatus = () => {
        const query = (document.getElementById("searchQuery")?.value || "").toLowerCase();
        const filter = (document.getElementById("statusFilter")?.value || "all").toLowerCase();
        document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
            const name = row.children[2]?.textContent.toLowerCase() || "";
            const category = row.children[6]?.textContent.toLowerCase() || "";
            row.style.display = (name.includes(query) && (filter === "all" || category === filter)) ? "" : "none";
        });
    };

    // Sort
    window.sortItems = () => {
        const sortValue = document.getElementById("sortFilter")?.value || "default";
        const tbody = document.querySelector("#itemsTable tbody");
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll("tr"));
        rows.sort((a, b) => {
            const nameA = a.children[2]?.textContent.trim().toLowerCase() || "";
            const nameB = b.children[2]?.textContent.trim().toLowerCase() || "";
            const qtyA = parseFloat(a.children[4]?.textContent) || 0;
            const qtyB = parseFloat(b.children[4]?.textContent) || 0;
            const dateA = new Date(a.children[9]?.textContent.trim() || 0);
            const dateB = new Date(b.children[9]?.textContent.trim() || 0);
            const statusA = a.querySelector(".badge")?.textContent.toLowerCase() || "";
            const statusB = b.querySelector(".badge")?.textContent.toLowerCase() || "";
            const statusOrder = { 'expired': 0, 'expiring soon': 1, 'active': 2, 'n/a': 3 };
            switch (sortValue) {
                case "name_asc": return nameA.localeCompare(nameB);
                case "name_desc": return nameB.localeCompare(nameA);
                case "quantity_asc": return qtyA - qtyB;
                case "quantity_desc": return qtyB - qtyA;
                case "date_asc": return dateA - dateB;
                case "date_desc": return dateB - dateA;
                case "expiring_soon": return (statusOrder[statusA] || 99) - (statusOrder[statusB] || 99);
                case "active": return (statusOrder[statusB] || 99) - (statusOrder[statusA] || 99);
                default: return 0;
            }
        });
        rows.forEach(r => tbody.appendChild(r));
    };

    // Low Stock
    window.showLowStockItems = () => {
        let found = 0;
        document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
            if (row.classList.contains('table-warning')) {
                row.style.display = "";
                found++;
            } else row.style.display = "none";
        });
        document.getElementById("showAllBtn").style.display = found ? "inline-block" : "none";
    };
    window.showAllItems = () => {
        document.querySelectorAll("#itemsTable tbody tr").forEach(r => r.style.display = "");
        document.getElementById("showAllBtn").style.display = "none";
    };

    // Select All
    const selectAll = document.getElementById("selectAll");
    const checkboxes = document.querySelectorAll(".item-checkbox");
    if (selectAll) {
        selectAll.addEventListener("change", () => checkboxes.forEach(c => c.checked = selectAll.checked));
    }
    checkboxes.forEach(cb => cb.addEventListener("change", () => {
        selectAll.checked = Array.from(checkboxes).every(c => c.checked);
    }));

    // Delete
    document.querySelectorAll(".btn-delete").forEach(btn => {
        btn.addEventListener("click", async e => {
            e.preventDefault();
            const selectedIds = Array.from(document.querySelectorAll(".item-checkbox:checked")).map(cb => cb.value);
            let ids = selectedIds.length ? selectedIds : [btn.closest('tr').querySelector('.item-checkbox').value];
            if (!ids.length) return alert("No item to delete.");
            if (!confirm(`Are you sure you want to delete ${ids.length} item(s)?`)) return;
            try {
                const res = await fetch("<?= site_url('items/deleteMultiple') ?>", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ ids })
                });
                const result = await res.json();
                alert(result.message || "Items deleted successfully!");
                if (result.success) location.reload();
            } catch (err) {
                console.error(err);
                alert("Failed to delete item(s).");
            }
        });
    });

    // Alerts
    document.querySelectorAll('.notification-alert, .inventory-alert, .expiry-alert').forEach((alert, i) => {
        setTimeout(() => alert.classList.add('show'), 100 + i * 150);
        if (alert.classList.contains('notification-alert')) {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 400);
            }, 5000);
            alert.querySelector('.close-alert')?.addEventListener('click', () => alert.remove());
        } else {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 400);
            }, 4000);
        }
    });

    // 📈 INITIALIZE CHARTS
    const itemsData = <?= json_encode($items) ?>;
    
    // 1. Stock by Category
    const categories = {};
    itemsData.forEach(item => {
        const cat = item.category || 'Uncategorized';
        categories[cat] = (categories[cat] || 0) + 1;
    });

    new Chart(document.getElementById('categoryChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(categories),
            datasets: [{
                data: Object.values(categories),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 2. Top 5 Items by Value
    const itemValues = itemsData.map(item => ({
        name: item.name,
        value: (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 0)
    })).sort((a, b) => b.value - a.value).slice(0, 5);

    new Chart(document.getElementById('topItemsChart'), {
        type: 'bar',
        data: {
            labels: itemValues.map(i => i.name),
            datasets: [{
                label: 'Stock Value (₱)',
                data: itemValues.map(i => i.value),
                backgroundColor: 'rgba(78, 115, 223, 0.8)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { callback: value => '₱' + value.toLocaleString() } }
            },
            plugins: { legend: { display: false } }
        }
    });
});
</script>
</body>
</html>