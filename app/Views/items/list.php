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
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 50%;
            width: 42px;
            height: 42px;
            font-size: 1.3rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .mobile-menu-toggle:hover {
            background: var(--sidebar-hover);
        }

        /* HIDE GLOBAL MENU TOGGLE */
        body > #mobileMenuToggle { display: none !important; }

        /* CONTAINER */
        .container {
            max-width: 98%;
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
            overflow-x: hidden;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: white;
        }

        @media (max-width: 991px) {
            .table-responsive-custom {
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .hide-mobile { display: none; }
            .summary-card h3 { font-size: 1.5rem; }
            .summary-card h6 { font-size: 0.8rem; }
        }

        /* 🔽 Dropdown Slide Animation */
        @keyframes slideDownFade {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .dropdown-menu.show {
            animation: slideDownFade 0.2s ease-out forwards;
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
            top: 80px;
            background: #fff3cd;
            color: #856404;
        }
        .expiry-alert {
            top: 160px;
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
        #itemsTable th, #itemsTable td {
            text-align: center !important;
            white-space: nowrap;
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
            width: 60px;
            text-align: center !important;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 0 !important;
            -moz-appearance: textfield;
        }
        .quantity-control input::-webkit-outer-spin-button,
        .quantity-control input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
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
            .mobile-menu-toggle { display: flex; }
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
            .top-navbar { padding: 10px 15px; }
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

function getSku($name, $variation = '') {
    $nameLower = strtolower($name);
    
    $siomaiBases = [
        'pork siomai' => 'PRK-SMAI',
        'chicken siomai' => 'CHCK-SMAI',
        'beef siomai' => 'BEEF-SMAI',
        'sharkfin siomai' => 'SHRKSF-SMAI',
        'sharksfin siomai' => 'SHRKSF-SMAI',
        'japanese siomai' => 'JPNS-SMAI',
        'shrimp siomai' => 'SHRP-SMAI'
    ];
    
    $otherSkus = [
        'burger patty' => 'BRGR-PATTY-151G',
        'chicken pastil' => 'CHCK-PASTIL-200G',
        'chili garlic oil' => 'CHIL-GRLC-OIL-120G',
        'toyomansi sauce' => 'TYMNS-SAUCE-150ML',
        'toyo mansi sauce' => 'TYMNS-SAUCE-150ML'
    ];

    foreach ($siomaiBases as $k => $v) {
        if (strpos($nameLower, $k) !== false) {
            return $v . '-' . $variation;
        }
    }
    
    foreach ($otherSkus as $k => $v) {
        if (strpos($nameLower, $k) !== false) {
            return $v;
        }
    }

    return 'UNK-SKU';
}
?>  

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP NAVBAR -->
    <div class="top-navbar" style="padding-left: 20px;">
        <div class="d-flex align-items-center gap-3">
            <button class="mobile-menu-toggle" id="mobileMenuToggleInline">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0"><i class="bi bi-box-seam me-2" style="font-size: 1.25rem;"></i>Admin Inventory</h5>
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
        <div class="controls-section d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border p-3 rounded bg-white shadow-sm">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1.8;">
                <div class="position-relative w-100">
                    <input type="text" id="searchQuery" class="form-control" style="padding-right: 2.2rem;" placeholder="Search by item name" oninput="filterStatus()">
                    <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3" style="color: #6c757d; opacity: 0.6; pointer-events: none;"></i>
                </div>
                <style>
                    .search-btn-responsive { width: 100%; }
                    @media (min-width: 768px) { .search-btn-responsive { width: 120px !important; flex-shrink: 0; } }
                </style>
                <button onclick="filterStatus()" class="btn btn-primary search-btn-responsive">Search</button>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                <label class="form-label mb-0 fw-bold">Category:</label>
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center bg-white text-dark" type="button" id="statusFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                        <span id="statusFilterText">All</span>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="statusFilterBtn">
                        <li><a class="dropdown-item active" href="#" onclick="selectCategory('all', 'All', event)">All</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectCategory('Food', 'Food', event)">Food</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectCategory('Non-Food', 'Non-Food', event)">Non-Food</a></li>
                    </ul>
                    <input type="hidden" id="statusFilter" value="all">
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                <label class="form-label mb-0 fw-bold text-nowrap">Sort by:</label>
                <div class="dropdown w-100">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center bg-white text-dark" type="button" id="sortFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                        <span id="sortFilterText">Default</span>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="sortFilterBtn">
                        <li><a class="dropdown-item active" href="#" onclick="selectSort('default', 'Default', event)">Default</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('name_asc', 'Name (A–Z)', event)">Name (A–Z)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('name_desc', 'Name (Z–A)', event)">Name (Z–A)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('quantity_asc', 'Quantity (Low → High)', event)">Quantity (Low → High)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('quantity_desc', 'Quantity (High → Low)', event)">Quantity (High → Low)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('date_asc', 'Date (Oldest → Newest)', event)">Date (Oldest → Newest)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('date_desc', 'Date (Newest → Oldest)', event)">Date (Newest → Oldest)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('expiring_soon', 'Expiring Soon', event)">Expiring Soon</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('expired', 'Expired', event)">Expired</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('active', 'Active', event)">Active</a></li>
                        <li><a class="dropdown-item text-danger fw-semibold" href="#" onclick="selectSort('low_stock', 'Low Stock', event)">Low Stock</a></li>
                    </ul>
                    <input type="hidden" id="sortFilter" value="default">
                </div>
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
                        <th class="text-center align-middle">Product ID</th>
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle">SKU</th>
                        <th class="text-center align-middle">Price</th>
                        <th class="text-center align-middle">Quantity</th>
                        <th class="text-center align-middle hide-mobile">Value %</th>
                        <th class="text-center align-middle hide-mobile">Category</th>
                        <th class="text-center align-middle hide-mobile">Expiration Date</th>
                        <th class="text-center align-middle">Status</th>
                        <th class="text-center align-middle hide-mobile">Date Entry</th>
                        <th class="text-center align-middle">Actions</th>
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
                    <?php if (stripos($item['name'], 'siomai') !== false): ?>
                        <?php 
                        $sizes = [
                            ['s' => '-S', 's_sku' => 'S12', 'l' => 'Small', 'q' => $item['pack_small_qty'] ?? 0, 'p' => (!empty($item['price_12']) && $item['price_12'] > 0) ? $item['price_12'] : 115, 'ql' => '(12)'],
                            ['s' => '-M', 's_sku' => 'M20', 'l' => 'Medium', 'q' => $item['pack_medium_qty'] ?? 0, 'p' => (!empty($item['price_20']) && $item['price_20'] > 0) ? $item['price_20'] : 185, 'ql' => '(20)'],
                            ['s' => '-L', 's_sku' => 'L40', 'l' => 'Large', 'q' => $item['pack_biggest_qty'] ?? 0, 'p' => (!empty($item['price_40']) && $item['price_40'] > 0) ? $item['price_40'] : 335, 'ql' => '(40)']
                        ];
                        foreach ($sizes as $idx => $sz): 
                        ?>
                        <tr data-id="<?= $item['id'] ?>" class="<?= ($sz['q'] <= 10) ? 'table-warning' : '' ?>" <?= $idx > 0 ? 'style="border-top:1px dashed #dee2e6;"' : '' ?>>
                            <td class="text-center align-middle"><?= esc($item['product_id']) ?><?= $sz['s'] ?></td>
                            <td class="text-center align-middle"><?= esc($item['name']) ?> <small class="text-muted">(<?= $sz['l'] ?>)</small></td>
                            <td class="text-center align-middle"><?= esc(getSku($item['name'], $sz['s_sku'])) ?></td>
                            <td class="text-center align-middle">₱<?= number_format($sz['p'], 2) ?></td>
                            <td class="text-center align-middle"><span><?= esc($sz['q']) ?></span> <small class="text-muted"><?= $sz['ql'] ?></small></td>
                            <td class="text-center align-middle hide-mobile">
                                <?php 
                                $val = $sz['p'] * $sz['q'];
                                echo $totalValue > 0 ? number_format(($val / $totalValue) * 100, 1) : '0';
                                ?>%
                            </td>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['category'] ?? '—') ?></td>
                            <td class="text-center align-middle hide-mobile"><?= empty($item['expiration_date']) ? '—' : esc($item['expiration_date']) ?></td>
                            <td class="text-center align-middle">
                                <span class="badge 
                                    <?= $status == 'expired' ? 'bg-danger' :
                                    ($status == 'expiring soon' ? 'bg-warning text-dark' :
                                    ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['created_at']) ?></td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <div class="d-flex align-items-center quantity-control" data-id="<?= $item['id'] ?>" data-size="<?= $sz['s'] ?>">
                                        <button type="button" class="btn btn-sm btn-outline-danger open-qty-modal" data-action="decrease">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control form-control-sm qty-amount mx-1" readonly value="<?= esc($sz['q']) ?>">
                                        <button type="button" class="btn btn-sm btn-outline-success open-qty-modal" data-action="increase">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr data-id="<?= $item['id'] ?>" class="<?= $isLowStock ? 'table-warning' : '' ?>">
                            <td class="text-center align-middle"><?= esc($item['product_id']) ?></td>
                            <td class="text-center align-middle"><?= esc($item['name']) ?></td>
                            <td class="text-center align-middle"><?= esc(getSku($item['name'])) ?></td>
                            <td class="text-center align-middle">₱<?= number_format($item['price'], 2) ?></td>
                            <td class="text-center align-middle"><span><?= esc($item['quantity']) ?></span></td>
                            <td class="text-center align-middle hide-mobile">
                                <?php 
                                $val = $item['price'] * $item['quantity'];
                                echo $totalValue > 0 ? number_format(($val / $totalValue) * 100, 1) : '0';
                                ?>%
                            </td>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['category'] ?? '—') ?></td>
                            <td class="text-center align-middle hide-mobile"><?= empty($item['expiration_date']) ? '—' : esc($item['expiration_date']) ?></td>
                            <td class="text-center align-middle">
                                <span class="badge 
                                    <?= $status == 'expired' ? 'bg-danger' :
                                    ($status == 'expiring soon' ? 'bg-warning text-dark' :
                                    ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['created_at']) ?></td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-1 justify-content-center">
                                    <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <div class="d-flex align-items-center quantity-control" data-id="<?= $item['id'] ?>">
                                        <button type="button" class="btn btn-sm btn-outline-danger open-qty-modal" data-action="decrease">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control form-control-sm qty-amount mx-1" readonly value="<?= esc($item['quantity']) ?>">
                                        <button type="button" class="btn btn-sm btn-outline-success open-qty-modal" data-action="increase">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
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
                    <input type="hidden" id="variationSize">
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
    const mobileMenuToggles = [document.getElementById('mobileMenuToggle'), document.getElementById('mobileMenuToggleInline')];
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    mobileMenuToggles.forEach(toggle => {
        if (toggle) {
            toggle.addEventListener('click', () => {
                const isActive = sidebar.classList.contains('active');
                if (!isActive) {
                    sidebar.classList.add('active');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                } else {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }
    });

    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
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
        const qtyContainer = this.closest(".quantity-control");
        const sizeBadge = qtyContainer ? (qtyContainer.dataset.size || "") : "";

        document.getElementById("itemId").value = id;
        document.getElementById("productId").value = document.querySelector(`tr[data-id="${id}"] td:nth-child(2)`).textContent.trim().replace(/\-S|\-M|\-L|\-B/, '') + sizeBadge;
        document.getElementById("actionType").value = this.dataset.action;
        document.getElementById("variationSize").value = sizeBadge;
        document.getElementById("quantityInput").value = 0;
        new bootstrap.Modal(document.getElementById("qtyModal")).show();
    }
    document.querySelectorAll(".open-qty-modal").forEach(btn => btn.addEventListener("click", openQtyModal));

    document.getElementById("qtyForm").addEventListener("submit", async e => {
        e.preventDefault();
        const id = document.getElementById("itemId").value;
        const action = document.getElementById("actionType").value;
        const sizeBadge = document.getElementById("variationSize").value;
        const quantity = parseInt(document.getElementById("quantityInput").value);
        if (!["increase", "decrease"].includes(action)) return alert("Invalid action.");
        const endpoint = `<?= site_url('items/') ?>${action}Quantity/${id}`;
        try {
            const res = await fetch(endpoint, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ amount: quantity, size: sizeBadge })
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

    // Custom Dropdown Logic
    window.selectCategory = (value, text, event) => {
        if (event) event.preventDefault();
        document.getElementById('statusFilterText').innerText = text;
        document.getElementById('statusFilter').value = value;
        const items = event.target.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
        items.forEach(i => i.classList.remove('active'));
        event.target.classList.add('active');
        if (typeof filterStatus === 'function') filterStatus();
    };

    window.selectSort = (value, text, event) => {
        if (event) event.preventDefault();
        document.getElementById('sortFilterText').innerText = text;
        document.getElementById('sortFilter').value = value;
        const items = event.target.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
        items.forEach(i => i.classList.remove('active'));
        event.target.classList.add('active');
        if (typeof sortItems === 'function') sortItems();
    };

    // Search & Filter
    window.filterTable = () => {
        const query = (document.getElementById("searchQuery")?.value || "").toLowerCase().trim();
        const category = (document.getElementById("statusFilter")?.value || "all").toLowerCase();
        const sortValue = document.getElementById("sortFilter")?.value || "default";
        
        document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
            const pid = row.children[0]?.textContent.toLowerCase() || "";
            const name = row.children[1]?.textContent.toLowerCase() || "";
            const rowCategory = row.children[6]?.textContent.toLowerCase().trim() || "";
            const statusBadge = row.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
            
            let matchesSortFilter = true;
            if (sortValue === "expiring_soon") matchesSortFilter = (statusBadge === "expiring soon" || statusBadge === "expiring today");
            else if (sortValue === "expired") matchesSortFilter = (statusBadge === "expired");
            else if (sortValue === "active") matchesSortFilter = (statusBadge === "active");
            else if (sortValue === "low_stock") matchesSortFilter = row.classList.contains("table-warning");
            
            const matchesSearch = name.includes(query) || pid.includes(query);
            const matchesCategory = category === "all" || rowCategory === category;
            
            row.style.display = (matchesSearch && matchesCategory && matchesSortFilter) ? "" : "none";
        });
    };
    window.searchItems = window.filterTable;
    window.filterStatus = window.filterTable;

    // Sort
    window.sortItems = () => {
        const sortValue = document.getElementById("sortFilter")?.value || "default";
        const tbody = document.querySelector("#itemsTable tbody");
        if (!tbody) return;
        const rows = Array.from(tbody.querySelectorAll("tr"));
        rows.sort((a, b) => {
            const nameA = a.children[1]?.textContent.trim().toLowerCase() || "";
            const nameB = b.children[1]?.textContent.trim().toLowerCase() || "";
            const qtyA = parseFloat(a.children[4]?.textContent) || 0;
            const qtyB = parseFloat(b.children[4]?.textContent) || 0;
            const dateA = new Date(a.children[9]?.textContent.trim() || 0);
            const dateB = new Date(b.children[9]?.textContent.trim() || 0);
            const statusA = a.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
            const statusB = b.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
            const statusOrder = { 'expired': 0, 'expiring today': 1, 'expiring soon': 1, 'active': 2, 'n/a': 3 };
            switch (sortValue) {
                case "name_asc": return nameA.localeCompare(nameB);
                case "name_desc": return nameB.localeCompare(nameA);
                case "quantity_asc": return qtyA - qtyB;
                case "quantity_desc": return qtyB - qtyA;
                case "date_asc": return dateA - dateB;
                case "date_desc": return dateB - dateA;
                case "expiring_soon": return (statusOrder[statusA] || 99) - (statusOrder[statusB] || 99);
                case "expired": return (statusA === "expired" ? -1 : 1) - (statusB === "expired" ? -1 : 1);
                case "active": return (statusOrder[statusB] || 99) - (statusOrder[statusA] || 99);
                case "low_stock": return qtyA - qtyB;
                default: return parseInt(a.dataset.originalIndex || 0) - parseInt(b.dataset.originalIndex || 0);
            }
        });
        rows.forEach(r => tbody.appendChild(r));
        if (typeof window.filterTable === "function") window.filterTable();
    };

    // Capture original index on load
    document.querySelectorAll("#itemsTable tbody tr").forEach((row, i) => {
        row.dataset.originalIndex = i;
    });

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



});
</script>
</body>
</html>