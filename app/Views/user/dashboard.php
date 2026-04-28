<?php
if (!function_exists('getProductSKU')) {
    function getProductSKU($name, $variation) {
        $n = strtolower($name);
        $base = null;
        $sku = 'N/A';
        
        if (strpos($n, 'pork siomai') !== false) $base = 'PRK-SMAI';
        elseif (strpos($n, 'chicken siomai') !== false) $base = 'CHCKN-SMAI';
        elseif (strpos($n, 'beef siomai') !== false) $base = 'BEEF-SMAI';
        elseif (strpos($n, 'sharksfin siomai') !== false) $base = 'SHKSFIN-SMAI';
        elseif (strpos($n, 'japanese siomai') !== false) $base = 'JAP-SMAI';
        elseif (strpos($n, 'shrimp siomai') !== false) $base = 'SRIMP-SMAI';
        elseif (strpos($n, 'burger') !== false) $sku = 'BRGR-PTTY-151G';
        elseif (strpos($n, 'pastil') !== false || strpos($n, 'pastel') !== false) $sku = 'CHCKN-PSTL-200G';
        elseif (strpos($n, 'chili') !== false) $sku = 'CHIL-GRLC-OIL-120G';
        elseif (strpos(str_replace(' ', '', $n), 'toyomansi') !== false) $sku = 'TYMNS-SCE-150ML';
        
        if ($base) {
            if ($variation === 'S') return $base . '-S12';
            if ($variation === 'M') return $base . '-M20';
            if ($variation === 'L') return $base . '-L40';
            return $base;
        }
        
        return $sku;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
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
            --border-radius: 0.65rem;
            --orange: #ff9800;
        }

        .border-orange { border-color: var(--orange) !important; }
        .text-orange { color: var(--orange) !important; }
        .bg-orange { background-color: var(--orange) !important; color: white !important; }

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
            width: 52px;
            height: 52px;
            border-radius: 8px;
            background-color: #f0f2f5;
            padding: 2px;
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
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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
            height: 60px;
            padding: 0 20px;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--card-shadow);
            margin: 0 0 10px 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-navbar h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }

        /* USER PROFILE */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-initial {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 13px;
        }
        .user-profile .profile-text {
            line-height: 1.1;
        }
        .user-profile .profile-text div {
            font-size: 0.85rem;
            font-weight: 600;
        }
        .user-profile .profile-text small {
            font-size: 0.7rem;
        }

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

        @media (max-width: 991px) {
            .mobile-menu-toggle {
                display: flex;
            }
        }

        .container {
            max-width: 96%;
            padding: 5px 20px 120px 20px; /* Extra bottom padding to help dropdowns fit vertically in viewport */
            margin-top: 0;
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
        .summary-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 15px;
            margin-bottom: 20px;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        @media (min-width: 768px) {
            .summary-card { padding: 20px; }
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

        /* 🔽 Dropdown Slide Animation */
        @keyframes slideDownFade {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .dropdown-menu.show {
            animation: slideDownFade 0.2s ease-out forwards;
        }

        @media (max-width: 768px) {
            .hide-mobile { display: none; }
            .container { padding: 10px; }
            .summary-card h3 { font-size: 1.5rem; }
            .summary-card h6 { font-size: 0.8rem; }
        }

        /* TABLE */
        #itemsTable {
            min-width: 900px;
            font-size: 0.9rem;
            margin: 0;
            background: white;
        }
        #itemsTable thead th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
            white-space: nowrap;
        }
        #itemsTable tbody td {
            white-space: nowrap;
        }
        #itemsTable tbody tr {
            transition: background 0.2s;
        }
        #itemsTable tbody tr:hover {
            background-color: #f8f9ff;
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
    $totalVariationItems = 0;
    $expandedLowStock = 0;
    $expandedExpiringSoon = 0;
    $expandedExpired = 0;

    foreach ($items as $i) {
        $isSiomai = stripos($i['name'], 'siomai') !== false;
        $variations = [];
        if ($isSiomai) {
            $variations[] = ['qty' => $i['pack_small_qty'] ?? 0];
            $variations[] = ['qty' => $i['pack_medium_qty'] ?? 0];
            $variations[] = ['qty' => $i['pack_biggest_qty'] ?? 0];
        } else {
            $variations[] = ['qty' => $i['quantity'] ?? 0];
        }

        $daysLeft = 999;
        if (!empty($i['expiration_date']) && $i['expiration_date'] !== '0000-00-00') {
            $today = new DateTime();
            $expiration = new DateTime($i['expiration_date']);
            $daysLeft = (int) $today->diff($expiration)->format('%r%a');
        }

        foreach ($variations as $v) {
            $totalVariationItems++;
            if ($v['qty'] <= 10) {
                $expandedLowStock++;
            }
            if ($daysLeft !== 999) {
                if ($daysLeft < 0) {
                    $expandedExpired++;
                } elseif ($daysLeft <= 10) {
                    $expandedExpiringSoon++;
                }
            }
        }
    }
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

    if (!function_exists('getProductSKU')) {
        function getProductSKU($name, $variation) {
            $n = strtolower($name);
            $base = null;
            $sku = 'N/A';
            
            if (strpos($n, 'pork siomai') !== false) $base = 'PRK-SMAI';
            elseif (strpos($n, 'chicken siomai') !== false) $base = 'CHCKN-SMAI';
            elseif (strpos($n, 'beef siomai') !== false) $base = 'BEEF-SMAI';
            elseif (strpos($n, 'sharksfin siomai') !== false) $base = 'SHKSFIN-SMAI';
            elseif (strpos($n, 'japanese siomai') !== false) $base = 'JAP-SMAI';
            elseif (strpos($n, 'shrimp siomai') !== false) $base = 'SRIMP-SMAI';
            elseif (strpos($n, 'burger') !== false) $sku = 'BRGR-PTTY-151G';
            elseif (strpos($n, 'pastil') !== false || strpos($n, 'pastel') !== false) $sku = 'CHCKN-PSTL-200G';
            elseif (strpos($n, 'chili') !== false) $sku = 'CHIL-GRLC-OIL-120G';
            elseif (strpos(str_replace(' ', '', $n), 'toyomansi') !== false) $sku = 'TYMNS-SCE-150ML';
            
            if ($base) {
                if ($variation === 'S') return $base . '-S12';
                if ($variation === 'M') return $base . '-M20';
                if ($variation === 'L') return $base . '-L40';
            }
            return $sku;
        }
    }
    ?>

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
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-arrow-repeat"></i> Request Stock Adjustment
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#pullOutModal">
                    <i class="bi bi-trash3"></i> Pull-Outs
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
        <!-- TOP NAVBAR WITH USER PROFILE -->
        <div class="top-navbar" style="padding-left: 20px;">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0"><i class="bi bi-person-badge me-2" style="font-size: 1.25rem;"></i>Staff Dashboard</h5>
            </div>
            <div class="user-profile">
                <div class="profile-initial" id="profileInitial">
                    <?php 
                    $username = session()->get('username') ?? 'User';
                    $initials = substr($username, 0, 1);
                    echo strtoupper($initials);
                    ?>
                </div>
                <div class="profile-text">
                    <div><?= esc(ucfirst(session()->get('username') ?? 'User')) ?></div>
                    <?php 
                    $roleLabel = session()->get('role') ?? 'Staff';
                    if (strtolower($roleLabel) === 'user') $roleLabel = 'Staff';
                    ?>
                    <small class="text-muted"><?= esc(ucfirst($roleLabel)) ?></small>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- 📊 STOCKS ANALYTICS -->
            <h5 class="mb-3 fw-bold text-muted"><i class="bi bi-graph-up-arrow me-2"></i>Stocks Analytics</h5>
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="summary-card text-center border-start border-success border-4">
                        <h5 class="text-muted fw-semibold">Total Items</h5>
                        <h2 class="fw-bold mb-0 text-success text-truncate" title="<?= $totalVariationItems ?>" style="font-size: clamp(1.2rem, 2.5vw, 1.8rem);"><?= $totalVariationItems ?></h2>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="summary-card text-center border-start border-warning border-4">
                        <h5 class="text-muted fw-semibold">Low Stock</h5>
                        <h2 class="fw-bold mb-0 text-warning text-truncate" title="<?= $expandedLowStock ?>" style="font-size: clamp(1.2rem, 2.5vw, 1.8rem);"><?= $expandedLowStock ?></h2>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="summary-card text-center border-start border-orange border-4">
                        <h5 class="text-muted fw-semibold">Expiring Soon</h5>
                        <h2 class="fw-bold mb-0 text-orange text-truncate" title="<?= $expandedExpiringSoon ?>" style="font-size: clamp(1.2rem, 2.5vw, 1.8rem);"><?= $expandedExpiringSoon ?></h2>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="summary-card text-center border-start border-danger border-4">
                        <h5 class="text-muted fw-semibold">Expired</h5>
                        <h2 class="fw-bold mb-0 text-danger text-truncate" title="<?= $expandedExpired ?>" style="font-size: clamp(1.2rem, 2.5vw, 1.8rem);"><?= $expandedExpired ?></h2>
                    </div>
                </div>
            </div>

            <!-- 🔔 ALERTS -->
            <div class="alert-section">
            </div>

            <!-- 🔍 CONTROLS -->
            <div class="controls-section d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border p-3 rounded bg-white shadow-sm">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1.8;">
                    <label for="searchQuery" class="form-label mb-0 fw-bold text-nowrap">Search Item:</label>
                    <div class="position-relative w-100">
                        <input type="text" id="searchQuery" class="form-control" style="padding-right: 2.2rem;" placeholder="Search by item name" oninput="filterTable()">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3" style="color: #6c757d; opacity: 0.6; pointer-events: none;"></i>
                    </div>
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

            <!-- 📊 INVENTORY TITLE -->
            <div class="text-center mb-3 mt-4">
                <h2 style="font-weight: 700; color: var(--dark); margin: 0;">
                    Staff Inventory
                </h2>
            </div>

            <!-- 📊 TABLE -->
            <?php if (!empty($items) && is_array($items)): ?>
            <div class="table-responsive-custom">
                <table id="itemsTable" class="table table-bordered table-hover align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Expiration Date</th>
                            <th>Days Left</th>
                            <th>Status</th>
                            <th class="hide-mobile">Date Entry</th>
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
                                    $status = 'expiring today';
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
                            
                            $displayRows = [];
                            if ($isSiomai) {
                                $displayRows[] = [
                                    'variation' => 'S',
                                    'pack_name' => 'Small',
                                    'qty' => $item['pack_small_qty'] ?? 0,
                                    'id_suffix' => '-S',
                                    'price' => (!empty($item['pack_small_price']) && $item['pack_small_price'] > 0) ? $item['pack_small_price'] : 115
                                ];
                                $displayRows[] = [
                                    'variation' => 'M',
                                    'pack_name' => 'Medium',
                                    'qty' => $item['pack_medium_qty'] ?? 0,
                                    'id_suffix' => '-M',
                                    'price' => (!empty($item['pack_medium_price']) && $item['pack_medium_price'] > 0) ? $item['pack_medium_price'] : 185
                                ];
                                $displayRows[] = [
                                    'variation' => 'L',
                                    'pack_name' => 'Large',
                                    'qty' => $item['pack_biggest_qty'] ?? 0,
                                    'id_suffix' => '-L',
                                    'price' => (!empty($item['pack_biggest_price']) && $item['pack_biggest_price'] > 0) ? $item['pack_biggest_price'] : 335
                                ];
                            } else {
                                $displayRows[] = [
                                    'variation' => null,
                                    'pack_name' => '',
                                    'qty' => $item['quantity'],
                                    'id_suffix' => '',
                                    'price' => $item['price']
                                ];
                            }
                        ?>
                        <?php foreach ($displayRows as $vItem): ?>
                        <?php
                            $isLowStock = $vItem['qty'] <= 10;
                            $priceDisplay = !empty($vItem['price']) ? '₱' . number_format((float)$vItem['price'], 2) : '<span class="text-muted">—</span>';
                        ?>
                        <tr class="text-center <?= $isLowStock ? 'table-warning' : '' ?>" data-id="<?= $item['id'] ?>">
                            <td><?= esc($item['product_id']) ?><?= $vItem['id_suffix'] ?></td>
                            <td><?= esc(getProductSKU($item['name'], $vItem['variation'] ?? null)) ?></td>
                            <td>
                                <?= esc($item['name']) ?>
                                <?php if ($vItem['pack_name']): ?>
                                    <small class="text-muted d-block"><?= esc($vItem['pack_name']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $priceDisplay ?></td>
                            <td>
                                <?php if ($isLowStock): ?>
                                <strong><?= esc($vItem['qty']) ?></strong> <span class="badge bg-warning text-dark ms-1">Low</span>
                                <?php else: ?>
                                <?= esc($vItem['qty']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($item['category'] ?? '—') ?></td>
                            <td><?= empty($item['expiration_date']) ? '—' : esc($item['expiration_date']) ?></td>
                            <td data-days-left="<?= $daysLeft ?? 0 ?>"><?= $daysLeftText ?></td>
                            <td>
                                <span class="badge 
                                    <?= $status == 'expired' ? 'bg-danger' :
                                    ($status == 'expiring today' || $status == 'expiring soon' ? 'bg-orange' :
                                    ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="hide-mobile"><?= esc($item['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-center">No items found in the database.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ✅ Enhanced Stock Request Modal -->
    <div class="modal fade" id="helpModal" tabindex="-1" aria-labelledby="helpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--sidebar-bg); color: white; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="modal-title w-100 text-center" id="helpModalLabel">
                        <i class="bi bi-arrow-repeat me-2"></i>Stock Adjustment Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="stockAlertContainer"></div>
                    <form id="stockRequestFormModal">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="requestItemModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-box me-1"></i> Select Item
                            </label>
                            <select id="requestItemModal" class="form-select shadow-sm" required style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                                <option value="">— Choose an item —</option>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                        $isSiomai = stripos($item['name'], 'siomai') !== false;
                                        $displayRows = [];
                                        if ($isSiomai) {
                                            $displayRows[] = ['variation' => 'S', 'pack_name' => 'Small', 'id_suffix' => '-S'];
                                            $displayRows[] = ['variation' => 'M', 'pack_name' => 'Medium', 'id_suffix' => '-M'];
                                            $displayRows[] = ['variation' => 'L', 'pack_name' => 'Large', 'id_suffix' => '-L'];
                                        } else {
                                            $displayRows[] = ['variation' => null, 'pack_name' => '', 'id_suffix' => ''];
                                        }
                                    ?>
                                    <?php foreach ($displayRows as $vItem): ?>
                                        <option value="<?= esc($item['id']) ?>" data-variation="<?= esc($vItem['pack_name']) ?>">
                                            <?= esc($item['product_id']) ?><?= esc($vItem['id_suffix']) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="requestActionModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-arrow-left-right me-1"></i> Adjustment Type
                            </label>
                            <select id="requestActionModal" class="form-select shadow-sm" required style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                                <option value="">— Select action —</option>
                                <option value="add">Add Stock</option>
                                <option value="subtract">Reduce Stock</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="requestQtyModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-hash me-1"></i> Quantity
                            </label>
                            <input type="number" id="requestQtyModal" class="form-control shadow-sm" min="1" placeholder="Enter adjustment amount" required
                                   style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                        </div>
                        <div class="mb-4">
                            <label for="requestReasonModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-journal-text me-1"></i> Reason / Notes
                            </label>
                            <textarea id="requestReasonModal" class="form-control shadow-sm" rows="3" placeholder="e.g., spillage, delivery, inventory correction..." required
                                      style="border-radius: var(--border-radius); padding: 0.6rem 1rem;"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn fw-bold" style="
                                background: var(--primary);
                                color: white;
                                border: none;
                                padding: 0.75rem;
                                border-radius: var(--border-radius);
                                font-size: 1rem;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                                transition: all 0.25s ease;
                            ">
                                <i class="bi bi-send me-2"></i>Submit Stock Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Pull-Out Modal -->
    <div class="modal fade" id="pullOutModal" tabindex="-1" aria-labelledby="pullOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: var(--sidebar-bg); color: white; border-bottom: 1px solid rgba(255,255,255,0.1);">
                    <h5 class="modal-title w-100 text-center" id="pullOutModalLabel">
                        <i class="bi bi-trash3 me-2"></i>Record Food Waste / Pull-Out
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="pullOutFormModal">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label for="pullOutItemModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-box me-1"></i> Select Item
                            </label>
                            <select id="pullOutItemModal" class="form-select shadow-sm" required style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                                <option value="">— Choose an item —</option>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                        $isSiomai = stripos($item['name'], 'siomai') !== false;
                                        $displayRows = [];
                                        if ($isSiomai) {
                                            $displayRows[] = ['variation' => 'S', 'pack_name' => 'Small', 'id_suffix' => '-S'];
                                            $displayRows[] = ['variation' => 'M', 'pack_name' => 'Medium', 'id_suffix' => '-M'];
                                            $displayRows[] = ['variation' => 'L', 'pack_name' => 'Large', 'id_suffix' => '-L'];
                                        } else {
                                            $displayRows[] = ['variation' => null, 'pack_name' => '', 'id_suffix' => ''];
                                        }
                                    ?>
                                    <?php foreach ($displayRows as $vItem): ?>
                                        <option value="<?= esc($item['id']) ?>" data-variation="<?= esc($vItem['pack_name']) ?>">
                                            <?= esc($item['product_id']) ?><?= esc($vItem['id_suffix']) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="pullOutReasonModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-exclamation-triangle me-1"></i> Reason
                            </label>
                            <select id="pullOutReasonModal" class="form-select shadow-sm" required style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                                <option value="">— Select Reason —</option>
                                <option value="SPOILED">Spoiled / Expired</option>
                                <option value="CONTAMINATED">Contaminated</option>
                                <option value="DAMAGED_PACKAGING">Damaged Packaging</option>
                                <option value="CUSTOMER_RETURN">Customer Return</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="pullOutQtyModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-hash me-1"></i> Quantity to Pull-Out
                            </label>
                            <input type="number" id="pullOutQtyModal" class="form-control shadow-sm" min="1" placeholder="Enter amount" required
                                   style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                        </div>
                        <div class="mb-4">
                            <label for="pullOutNoteModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-journal-text me-1"></i> Additional Notes (Optional)
                            </label>
                            <textarea id="pullOutNoteModal" class="form-control shadow-sm" rows="3" placeholder="Provide extra details..."
                                      style="border-radius: var(--border-radius); padding: 0.6rem 1rem;"></textarea>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light border shadow-sm px-4 fw-semibold" data-bs-dismiss="modal" style="border-radius: var(--border-radius);">Cancel</button>
                            <button type="submit" class="btn btn-danger shadow-sm px-4 fw-semibold" style="border-radius: var(--border-radius);">
                                <i class="bi bi-send me-2"></i>Submit Pull-Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        if ($('#requestItemModal').length) {
            $('#requestItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#helpModal'),
                width: '100%'
            });
        }
        
        if ($('#pullOutItemModal').length) {
            $('#pullOutItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#pullOutModal'),
                width: '100%'
            });
        }
        
        if ($('#requestActionModal').length) {
            $('#requestActionModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#helpModal'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }

        if ($('#pullOutReasonModal').length) {
            $('#pullOutReasonModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#pullOutModal'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }
        
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
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991) {
                    closeSidebar();
                }
            });
        });

        // 🔄 CUSTOM DROPDOWN LOGIC
        window.selectCategory = (value, text, event) => {
            if (event) event.preventDefault();
            document.getElementById('statusFilterText').innerText = text;
            document.getElementById('statusFilter').value = value;
            const items = event.target.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
            items.forEach(i => i.classList.remove('active'));
            event.target.classList.add('active');
            if (typeof filterTable === 'function') filterTable();
        };

        window.selectSort = (value, text, event) => {
            if (event) event.preventDefault();
            document.getElementById('sortFilterText').innerText = text;
            document.getElementById('sortFilter').value = value;
            const items = event.target.closest('.dropdown-menu').querySelectorAll('.dropdown-item');
            items.forEach(i => i.classList.remove('active'));
            event.target.classList.add('active');
            if (typeof sortItems === 'function') sortItems();
            if (typeof filterTable === 'function') filterTable(); // Also trigger filter incase
        };

        // 🔎 FILTERS & SEARCH
        window.filterTable = () => {
            const query = (document.getElementById("searchQuery")?.value || "").toLowerCase().trim();
            const category = (document.getElementById("statusFilter")?.value || "all").toLowerCase();
            const sortValue = document.getElementById("sortFilter")?.value || "default";
            
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const name = row.children[2]?.textContent.toLowerCase() || "";
                const pid = row.children[0]?.textContent.toLowerCase() || "";
                const rowCategory = row.children[5]?.textContent.toLowerCase().trim() || "";
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
        window.searchItems = window.filterTable; // Fallback helper

        // 🔄 SORT
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

        // 📉 FILTERS
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
        window.showExpiringSoon = () => {
            let found = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const days = parseInt(row.children[6]?.dataset.daysLeft) || 9999;
                if (days >= 0 && days <= 10) {
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

        // ✅ STOCK REQUEST SUBMISSION
        const form = document.getElementById("stockRequestFormModal");
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = form.querySelector("button[type='submit']");
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submitting...';

                const selectElement = document.getElementById("requestItemModal");
                const itemId = selectElement.value;
                const variation = selectElement.options[selectElement.selectedIndex].getAttribute("data-variation");
                const action = document.getElementById("requestActionModal").value;
                const quantity = parseInt(document.getElementById("requestQtyModal").value) || 0;
                let reason = document.getElementById("requestReasonModal").value.trim();

                if (variation) {
                    reason = `[Variation: ${variation}] ` + reason;
                }

                if (!itemId || !action || !quantity || !reason) {
                    alert("Please fill all fields.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Stock Request';
                    return;
                }

                try {
                    const response = await fetch("<?= site_url('user/submit-stock-request') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new URLSearchParams({
                            item_id: itemId,
                            action: action,
                            quantity: quantity,
                            reason: reason
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message || "Request submitted successfully!");
                        form.reset();
                        bootstrap.Modal.getInstance(document.getElementById("helpModal")).hide();
                    } else {
                        alert(result.message || "Failed to submit request.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("An error occurred while submitting.");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Stock Request';
                }
            });
        }
        // ✅ PULL-OUT SUBMISSION
        const pullOutForm = document.getElementById("pullOutFormModal");
        if (pullOutForm) {
            pullOutForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = pullOutForm.querySelector("button[type='submit']");
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submitting...';

                const pullOutItemSelect = document.getElementById("pullOutItemModal");
                const itemId = pullOutItemSelect.value;
                const variation = pullOutItemSelect.options[pullOutItemSelect.selectedIndex].getAttribute("data-variation");
                const reason = document.getElementById("pullOutReasonModal").value;
                const quantity = parseInt(document.getElementById("pullOutQtyModal").value) || 0;
                let note = document.getElementById("pullOutNoteModal").value.trim();

                if (variation) {
                    note = `[Variation: ${variation}] ` + note;
                }

                if (!itemId || !reason || !quantity) {
                    alert("Please fill all required fields.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Pull-Out';
                    return;
                }

                try {
                    const response = await fetch("<?= site_url('user/submit-pull-out') ?>", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: new URLSearchParams({
                            product_id: itemId,
                            pull_out_reason: reason,
                            quantity: quantity,
                            reason_note: note
                        })
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message || "Pull-out submitted successfully!");
                        pullOutForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById("pullOutModal")).hide();
                    } else {
                        alert(result.message || "Failed to submit pull-out.");
                    }
                } catch (err) {
                    console.error(err);
                    alert("An error occurred while submitting.");
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Pull-Out';
                }
            });
        }

    });
    </script>
</body>
</html>
