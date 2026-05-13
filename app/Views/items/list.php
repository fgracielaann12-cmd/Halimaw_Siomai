<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Inventory Dashboard | Halimaw </title>
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

        /* --- Premium Startup Animations --- */
        @keyframes fadeSlideDown {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeSlideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeScaleUp {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .top-navbar {
            animation: fadeSlideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .container > h5, .container > .row:first-of-type > h2, .container > h2:first-of-type, .page-title, .pos-items {
            opacity: 0;
            animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;
        }
        .container > .row, .controls-section, .summary-card {
            opacity: 0;
            animation: fadeScaleUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
        }
        .controls-section {
            animation-name: fadeSlideUp !important;
        }
        .container > .text-center {
            opacity: 0;
            animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
        }
        .table-responsive-custom, .pos-sidebar, .table-responsive, .table-card {
            opacity: 0;
            animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.4s forwards;
        }

        body {
            background-color: #f8f9fc;
            color: #3a3b45;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-x: clip;
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
            z-index: 1050;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            overflow-y: auto;
        }

        #sidebar .nav {
            width: 100%;
            min-width: 0;
        }
        #sidebar .nav-item {
            width: 100%;
            min-width: 0;
        }
        #sidebar .navbar-brand {
            padding: 1.25rem 0.75rem;
            font-size: 1.15rem;
            font-weight: 800;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-decoration: none;
            letter-spacing: -0.5px;
            white-space: nowrap;
        }
        #sidebar .navbar-brand img {
            width: 50px;
            height: 50px;
            border-radius: 12px !important;
            background-color: white;
            padding: 6px;
            object-fit: contain;
        }

        #sidebar .sidebar-brand-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            line-height: 1.1;
        }

        #sidebar .sidebar-brand-text .main-text {
            font-size: 1.25rem;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        #sidebar .sidebar-brand-text .sub-text {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #ffffff;
            opacity: 0.9;
            margin-top: 2px;
        }



        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.25rem;
            margin: 0.25rem 1rem;
            border-radius: 0.4rem;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            white-space: normal; line-height: 1.2;
            overflow: hidden;
            width: calc(100% - 2rem);
        }
        #sidebar .nav-link:hover {
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            background-color: var(--sidebar-hover);
            color: white;
            text-decoration: none;
        }

        @keyframes navGlow {
            0% { box-shadow: 0 0 5px rgba(78,115,223,0.3); filter: brightness(1); }
            50% { box-shadow: 0 0 15px rgba(78,115,223,0.9); filter: brightness(1.2); }
            100% { box-shadow: 0 0 5px rgba(78,115,223,0.3); filter: brightness(1); }
        }
        #sidebar .nav-link.active {
            background: linear-gradient(90deg, var(--sidebar-hover), var(--sidebar-active));
            color: white;
            font-weight: 500;
            border-radius: 0.4rem;
            animation: navGlow 2s infinite ease-in-out;
            text-decoration: none;
            white-space: normal; line-height: 1.2;
            overflow: hidden;
            width: calc(100% - 2rem);
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
            position: sticky;
            top: 0;
            z-index: 1000;
            background: white;
            height: 60px;
            padding: 0 20px;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--card-shadow);
            margin: 0 0 20px 0 !important;
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

        .controls-section {
            position: relative;
            z-index: 10;
        }

        /* Responsive Table */
        .table-responsive-custom {
            display: block;
            width: 100%;
            overflow-x: auto;
            overflow-y: auto;
            max-height: 65vh;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: white;
            margin-bottom: 30px;
        }

        @media (max-width: 991px) {
            .table-responsive-custom {
                overflow-x: auto;
            }
        }

        @media (max-width: 768px) {
            .hide-mobile { display: none !important; }
            .summary-card h3 { font-size: 1.5rem; }
            .summary-card h6 { font-size: 0.8rem; }
            .main-content { 
                margin-left: 0 !important; 
                width: 100% !important;
                box-sizing: border-box !important;
                padding: 0 !important; 
            }
            .container {
                padding: 15px !important;
            }
            .top-navbar {
                border-radius: 0 !important;
                margin-bottom: 15px !important;
            }
            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }
            .controls-section > div {
                width: 100%;
            }
        }

        /* --- UNIFIED TABLE SIZING --- */
        .table, table {
            font-size: 0.95rem !important;
        }
        .table th, .table td, table th, table td {
            padding: 12px 15px !important;
            vertical-align: middle !important;
        }
        @media (max-width: 991px) {
            .table, table { font-size: 0.9rem !important; }
            .table th, .table td, table th, table td { padding: 0.75rem 0.5rem !important; }
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
            border-radius: 8px;
            transition: all 0.2s;
            margin-bottom: 20px;
            text-decoration: none;
            white-space: nowrap !important;
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
            margin-left: 12px;
        }

        .floating-alert {
            position: fixed;
            left: 50%;
            z-index: 9998;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            cursor: pointer;
            opacity: 0;
            transform: translateX(-50%) translateY(-20px);
            transition: all 0.4s ease-in-out;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            width: max-content;
            max-width: 92%;
        }
        .floating-alert.show { 
            opacity: 1; 
            transform: translateX(-50%) translateY(0); 
        }
        .inventory-alert {
            top: 80px;
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .expiry-alert {
            top: 155px;
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }


        /* TABLE */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 0;
            margin-bottom: 20px;
        }
        #itemsTable {
            width: 100%;
            font-size: 0.9rem;
            margin: 0;
        }
        #itemsTable th, #itemsTable td {
            text-align: center !important;
            vertical-align: middle !important;
            padding: 6px 4px !important;
            font-size: 0.85rem !important;
        }
        #itemsTable thead th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: -1px;
            z-index: 10;
            box-shadow: 0 1px 0 var(--primary), 0 -1px 0 var(--primary);
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
        #itemsTable .btn-view-info {
            background-color: #ffffff !important;
            color: #5a5c69 !important;
            border: none !important;
            box-shadow: none !important;
            transition: all 0.2s ease-in-out;
        }
        #itemsTable .btn-view-info:hover {
            background-color: #e9ecef !important;
            color: #3a3b45 !important;
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
            transition: all 0.2s;
            border-radius: 4px;
            background: #ffffff;
            border: 1px solid #dee2e6 !important;
            color: #6c757d !important;
        }
        .quantity-control button:hover {
            background-color: #e9ecef !important;
            color: #3a3b45 !important;
            border-color: #adb5bd !important;
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

        #sidebar .nav {
            width: 100%;
            min-width: 0;
        }
        #sidebar .nav-item {
            width: 100%;
            min-width: 0;
        }
            
            
            .main-content { margin-left: 0; width: 100%; }
            .top-navbar { position: sticky; top: 0; z-index: 1000; border-radius: 0; margin-bottom: 15px; }
            #sidebar.active { transform: translateX(0); }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
            }
            .sidebar-overlay.active { display: block; }

            .container { padding: 0 15px 15px; }
            .top-navbar { padding: 10px 15px; }
        }
    </style>
    <!-- UNIFIED 12PX SYSTEM-WIDE RADIUS OVERRIDE -->
    <style>
        :root {
            --border-radius: 12px !important;
        }
        
        /* Buttons */
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn, .chart-filter-btn, .btn-export, .btn-add-new-item,
        
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
            border-radius: 12px !important;
        }
        
        /* Images inside cards */
        .pos-item-card img, .card img {
            border-radius: 12px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        /* --- UNIFIED TABLE SCROLLING & SIZING FIX --- */
        .table, table {
            font-size: 0.95rem !important;
        }
        .table th, .table td, table th, table td {
            padding: 6px 8px !important;
            vertical-align: middle !important;
        }
        @media (max-width: 991px) {
            .table, table { font-size: 0.9rem !important; }
            .table th, .table td, table th, table td { padding: 0.75rem 0.5rem !important; }
        }
        .table-responsive, .table-responsive-custom {
            max-height: 65vh !important;
            overflow-y: auto !important;
        }
        .table-responsive::-webkit-scrollbar, .table-responsive-custom::-webkit-scrollbar {
            width: 8px; height: 8px;
        }
        .table-responsive::-webkit-scrollbar-track, .table-responsive-custom::-webkit-scrollbar-track {
            background: #f1f1f1; border-radius: 4px; margin: 0 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb, .table-responsive-custom::-webkit-scrollbar-thumb {
            background: #c1c1c1; border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover, .table-responsive-custom::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        /* Sticky Headers */
        .table thead th, table thead th, .table th {
            position: sticky !important;
            top: -1px !important;
            z-index: 10 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            background-color: var(--primary, #4e73df) !important;
            color: white !important;
        }
        /* Fix dropdown clipping globally */
        .controls-section {
            position: relative;
            z-index: 10 !important;
        }
    </style>

    

    <!-- DISABLE BROWSER BACK/FORWARD BUTTONS COMPLETELY -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Push an empty state immediately
        history.pushState(null, null, location.href);
        // If the user tries to go back, instantly push them forward again
        window.onpopstate = function () {
            history.go(1);
        };
        
        function enforceClientAuth() {
            if (localStorage.getItem('auth_status') === 'logged_out') {
                document.documentElement.style.display = 'none';
                if(document.body) document.body.style.display = 'none';
                window.location.replace('/Halimaw_Siomai/index.php/login?blocked=1&cb=' + new Date().getTime());
            }
        }
        enforceClientAuth();
        window.addEventListener('pageshow', enforceClientAuth);
    </script>
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
    <?php
    $extra_buttons = '
        <a href="' . site_url('items/add') . '" class="btn-add-new-item mb-0 shadow-sm" style="height: 40px; margin: 0; padding: 0 16px; display: flex; align-items: center; white-space: nowrap !important;">
            <i class="bi bi-plus-lg me-1 me-md-2"></i>
            <span class="d-none d-md-inline">Add New Item</span>
            <span class="d-md-none">Add</span>
        </a>
        <button onclick="location.reload()" class="btn btn-light shadow-sm border" style="height: 40px; width: 45px; display: flex; align-items: center; justify-content: center; border-radius: 8px;" title="Refresh Table">
            <i class="bi bi-arrow-clockwise" style="font-size: 1.2rem; color: #3a3b45;"></i>
        </button>
    ';
    echo view('partials/admin_topbar', [
        'title' => '<span class="d-none d-sm-inline">Admin Inventory</span><span class="d-sm-none">Inventory</span>',
        'icon' => 'bi bi-box-seam',
        'extra_buttons' => $extra_buttons
    ]);
    ?>

    <div class="container">
        <?php
        $totalValue = array_reduce($items, fn($sum, $item) => $sum + (($item['price'] ?? 0) * ($item['quantity'] ?? 0)), 0);
        $lowStockItems = array_filter($items, function($i) {
            // New sibling model: variation children use their own quantity column
            if (!empty($i['is_variation_child'])) {
                return ($i['quantity'] ?? 0) <= 10;
            }
            // Legacy fallback: items using pack_*_qty columns
            $hasPackQty = ($i['pack_small_qty'] ?? 0) > 0 || ($i['pack_medium_qty'] ?? 0) > 0 || ($i['pack_biggest_qty'] ?? 0) > 0;
            if ($hasPackQty) {
                return ($i['pack_small_qty'] ?? 0) <= 10 || ($i['pack_medium_qty'] ?? 0) <= 10 || ($i['pack_biggest_qty'] ?? 0) <= 10;
            }
            return $i['quantity'] <= 10;
        });
        $expiringItems = array_filter($items, function ($i) {
            if (empty($i['expiration_date']) || $i['expiration_date'] === '0000-00-00') return false;
            $today = new DateTime();
            $today->setTime(0, 0, 0);
            $expiration = new DateTime($i['expiration_date']);
            $expiration->setTime(0, 0, 0);
            $daysLeft = (int) $today->diff($expiration)->format('%r%a');
            return $daysLeft >= 0 && $daysLeft <= 10;
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

        <!-- Add New Item (Moved to Header) -->

        <!-- Controls -->
        <div class="controls-section d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border p-3 rounded bg-white shadow-sm">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1.8;">
                <label class="form-label mb-0 fw-bold text-nowrap">Search Item:</label>
                <div class="position-relative w-100">
                    <input type="text" id="searchQuery" class="form-control" style="padding-right: 2.2rem;" placeholder="Search by item name" oninput="filterStatus()">
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
                        <li><a class="dropdown-item" href="#" onclick="selectSort('name_asc', 'Name (A-Z)', event)">Name (A-Z)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('name_desc', 'Name (Z-A)', event)">Name (Z-A)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('quantity_asc', 'Quantity (Low - High)', event)">Quantity (Low - High)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('quantity_desc', 'Quantity (High - Low)', event)">Quantity (High - Low)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('date_asc', 'Date (Oldest - Newest)', event)">Date (Oldest - Newest)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('date_desc', 'Date (Newest - Oldest)', event)">Date (Newest - Oldest)</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('expiring_soon', 'Expiring Soon', event)">Expiring Soon</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('expired', 'Expired', event)">Expired</a></li>
                        <li><a class="dropdown-item" href="#" onclick="selectSort('active', 'Active', event)">Active</a></li>
                        <li><a class="dropdown-item text-danger fw-semibold" href="#" onclick="selectSort('low_stock', 'Low Stock', event)">Low Stock</a></li>
                    </ul>
                    <input type="hidden" id="sortFilter" value="default">
                </div>
            </div>
        </div>

        <!-- Alerts Container spacing handled above -->

        <!-- Alerts Container spacing handled above -->

        <!-- Table -->
        <?php if (!empty($items) && is_array($items)): ?>
        <div class="table-responsive-custom">
            <table id="itemsTable" class="table table-bordered table-hover align-middle mb-0 text-center">
                <thead>
                    <tr>
                        <th class="text-center align-middle hide-mobile">Prod ID</th>
                        <th class="text-center align-middle">Name</th>
                        <th class="text-center align-middle hide-mobile">SKU</th>
                        <th class="text-center align-middle">Price</th>
                        <th class="text-center align-middle">Quantity</th>
                        <th class="text-center align-middle hide-mobile">Category</th>
                        <th class="text-center align-middle hide-mobile">Expiration Date</th>
                        <th class="text-center align-middle hide-mobile">Date Entry</th>
                        <th class="text-center align-middle" style="width: 1%; white-space: nowrap;">Status</th>
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
                            $daysLeftText = "&mdash;";
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
                        // New sibling model: variation children use their own quantity column
                        if (!empty($item['is_variation_child'])) {
                            $hasPackQty  = false;
                            $isLowStock  = ($item['quantity'] ?? 0) <= 10;
                        } else {
                            // Legacy fallback
                            $hasPackQty = ($item['pack_small_qty'] ?? 0) > 0 || ($item['pack_medium_qty'] ?? 0) > 0 || ($item['pack_biggest_qty'] ?? 0) > 0;
                            if ($hasPackQty) {
                                $isLowStock = ($item['pack_small_qty'] ?? 0) <= 10 || ($item['pack_medium_qty'] ?? 0) <= 10 || ($item['pack_biggest_qty'] ?? 0) <= 10;
                            } else {
                                $isLowStock = $item['quantity'] <= 10;
                            }
                        }
                    ?>
                    <?php if ($hasPackQty): ?>
                        <?php 
                        $sizes = [
                            ['s' => '-S', 's_sku' => 'S12', 'l' => 'Small', 'q' => $item['pack_small_qty'] ?? 0, 'p' => (!empty($item['pack_small_price']) && $item['pack_small_price'] > 0) ? $item['pack_small_price'] : 115, 'ql' => '(12)'],
                            ['s' => '-M', 's_sku' => 'M20', 'l' => 'Medium', 'q' => $item['pack_medium_qty'] ?? 0, 'p' => (!empty($item['pack_medium_price']) && $item['pack_medium_price'] > 0) ? $item['pack_medium_price'] : 185, 'ql' => '(20)'],
                            ['s' => '-L', 's_sku' => 'L40', 'l' => 'Large', 'q' => $item['pack_biggest_qty'] ?? 0, 'p' => (!empty($item['pack_biggest_price']) && $item['pack_biggest_price'] > 0) ? $item['pack_biggest_price'] : 335, 'ql' => '(40)']
                        ];
                        foreach ($sizes as $idx => $sz): 
                        ?>
                        <tr data-id="<?= $item['id'] ?>" data-low-stock="<?= ($sz['q'] <= 10) ? 'true' : 'false' ?>" <?= $idx > 0 ? 'style="border-top:1px dashed #dee2e6;"' : '' ?>>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['product_id']) ?><?= $sz['s'] ?></td>
                            <td class="text-center align-middle"><?= esc($item['name']) ?> <small class="text-muted">(<?= $sz['l'] ?>)</small></td>
                            <td class="text-center align-middle hide-mobile"><?= esc(!empty($item['sku']) ? $item['sku'] : getSku($item['name'], $sz['s_sku'])) ?></td>
                            <td class="text-center align-middle text-nowrap">₱<?= number_format($sz['p'], 2) ?></td>
                            <td class="text-center align-middle text-nowrap"><span><?= esc($sz['q']) ?></span> <small class="text-muted"><?= $sz['ql'] ?></small></td>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['category'] ?? '&mdash;') ?></td>
                            <td class="text-center align-middle hide-mobile">
                                <?php if (!empty($item['expiration_date']) && $item['expiration_date'] !== '0000-00-00'): ?>
                                    <?= date('m/d/Y', strtotime($item['expiration_date'])) ?>
                                <?php else: ?>
                                    &mdash;
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle hide-mobile">
                                <?= !empty($item['created_at']) ? date('m/d/Y H:i', strtotime($item['created_at'])) : '&mdash;' ?>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge 
                                    <?= $status == 'expired' ? 'bg-danger' :
                                    ($status == 'expiring soon' ? 'bg-warning text-dark' :
                                    ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-view-info" 
                                            onclick="showItemInfo('<?= esc($item['product_id']) ?><?= $sz['s'] ?>', '<?= esc($item['name']) ?> (<?= $sz['l'] ?>)', '<?= esc(!empty($item['sku']) ? $item['sku'] : getSku($item['name'], $sz['s_sku'])) ?>', '<?= esc($item['category'] ?? '&mdash;') ?>', '<?= esc($sz['q']) ?> <?= $sz['ql'] ?>', '₱<?= number_format($sz['p'], 2) ?>', '<?= !empty($item['created_at']) ? date('m/d/Y H:i', strtotime($item['created_at'])) : '&mdash;' ?>', '<?= empty($item['expiration_date']) || $item['expiration_date'] === '0000-00-00' ? '&mdash;' : date('m/d/Y', strtotime($item['expiration_date'])) ?>', '<span class=\'badge <?= $status == 'expired' ? 'bg-danger' : ($status == 'expiring soon' ? 'bg-warning text-dark' : ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>\' ><?= $statusLabel ?></span>')" title="View Info">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <a href="<?= site_url('items/edit/' . $item['id'] . '?size=' . strtolower($sz['l'])) ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <div class="d-flex align-items-center quantity-control" data-id="<?= $item['id'] ?>" data-size="<?= $sz['s'] ?>">
                                        <button type="button" class="btn btn-sm btn-light border open-qty-modal" data-action="decrease">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control form-control-sm qty-amount mx-1" readonly value="<?= esc($sz['q']) ?>">
                                        <button type="button" class="btn btn-sm btn-light border open-qty-modal" data-action="increase">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr data-id="<?= $item['id'] ?>" data-low-stock="<?= $isLowStock ? 'true' : 'false' ?>">
                            <td class="text-center align-middle hide-mobile"><?= esc($item['product_id']) ?></td>
                            <td class="text-center align-middle">
                                <?= esc($item['name']) ?>
                                <?php if (!empty($item['is_variation_child'])): ?>
                                    <br><span class="badge bg-info text-dark mt-1" style="font-size: 0.65rem;">Variation</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle hide-mobile"><?= esc(!empty($item['sku']) ? $item['sku'] : getSku($item['name'])) ?></td>
                            <td class="text-center align-middle text-nowrap">₱<?= number_format($item['price'], 2) ?></td>
                            <td class="text-center align-middle text-nowrap"><span><?= esc($item['quantity']) ?></span></td>
                            <td class="text-center align-middle hide-mobile"><?= esc($item['category'] ?? '&mdash;') ?></td>
                            <td class="text-center align-middle hide-mobile">
                                <?php if (!empty($item['expiration_date']) && $item['expiration_date'] !== '0000-00-00'): ?>
                                    <?= date('m/d/Y', strtotime($item['expiration_date'])) ?>
                                <?php else: ?>
                                    &mdash;
                                <?php endif; ?>
                            </td>
                            <td class="text-center align-middle hide-mobile">
                                <?= !empty($item['created_at']) ? date('m/d/Y H:i', strtotime($item['created_at'])) : '&mdash;' ?>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge 
                                    <?= $status == 'expired' ? 'bg-danger' :
                                    ($status == 'expiring soon' ? 'bg-warning text-dark' :
                                    ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="d-flex gap-1 justify-content-center">
                                    <button type="button" class="btn btn-sm btn-view-info" 
                                            onclick="showItemInfo('<?= esc($item['product_id']) ?>', '<?= esc($item['name']) ?>', '<?= esc(!empty($item['sku']) ? $item['sku'] : getSku($item['name'])) ?>', '<?= esc($item['category'] ?? '&mdash;') ?>', '<?= esc($item['quantity']) ?>', '₱<?= number_format($item['price'], 2) ?>', '<?= !empty($item['created_at']) ? date('m/d/Y H:i', strtotime($item['created_at'])) : '&mdash;' ?>', '<?= empty($item['expiration_date']) || $item['expiration_date'] === '0000-00-00' ? '&mdash;' : date('m/d/Y', strtotime($item['expiration_date'])) ?>', '<span class=\'badge <?= $status == 'expired' ? 'bg-danger' : ($status == 'expiring soon' ? 'bg-warning text-dark' : ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>\' ><?= $statusLabel ?></span>')" title="View Info">
                                        <i class="bi bi-info-circle"></i>
                                    </button>
                                    <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-sm btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <div class="d-flex align-items-center quantity-control" data-id="<?= $item['id'] ?>">
                                        <button type="button" class="btn btn-sm btn-light border open-qty-modal" data-action="decrease">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" class="form-control form-control-sm qty-amount mx-1" readonly value="<?= esc($item['quantity']) ?>">
                                        <button type="button" class="btn btn-sm btn-light border open-qty-modal" data-action="increase">
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

<!-- Item Info Modal -->
<div class="modal fade" id="itemInfoModal" tabindex="-1" aria-labelledby="itemInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header" style="background: var(--primary); color: white;">
                <h5 class="modal-title w-100 text-center fw-bold" id="itemInfoModalLabel">
                    <i class="bi bi-info-circle me-2"></i>Product Information
                </h5>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h4 id="infoName" class="fw-bold text-dark mb-1"></h4>
                    <span id="infoStatus"></span>
                </div>
                <table class="table table-borderless table-sm mb-0">
                    <tbody>
                        <tr><th class="text-secondary" style="width: 45%;">Product ID</th><td id="infoProductId" class="fw-medium"></td></tr>
                        <tr><th class="text-secondary">SKU</th><td id="infoSku" class="fw-medium"></td></tr>
                        <tr><th class="text-secondary">Category</th><td id="infoCategory" class="fw-medium"></td></tr>
                        <tr><th class="text-secondary">Quantity</th><td id="infoQuantity" class="fw-medium"></td></tr>
                        <tr><th class="text-secondary">Price</th><td id="infoPrice" class="fw-medium"></td></tr>
                        <tr><th class="text-secondary">Batch (Date Entry)</th><td id="infoDateEntry" class="fw-medium"></td></tr>
                        <tr><th class="text-secondary">Expiration Date</th><td id="infoExpiration" class="fw-medium"></td></tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-0 pb-3 justify-content-center">
                <button type="button" class="btn btn-light shadow-sm px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
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

    // Handle URL filters (e.g. ?filter=low_stock)
    const urlParams = new URLSearchParams(window.location.search);
    const filter = urlParams.get('filter');
    if (filter === 'low_stock') {
        setTimeout(() => {
            if (typeof showLowStockItems === 'function') showLowStockItems();
        }, 300);
    }

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
            const rowCategory = row.children[5]?.textContent.toLowerCase().trim() || "";
            const statusBadge = row.querySelector(".badge")?.textContent.trim().toLowerCase() || "";
            
            let matchesSortFilter = true;
            if (sortValue === "expiring_soon") matchesSortFilter = (statusBadge === "expiring soon" || statusBadge === "expiring today");
            else if (sortValue === "expired") matchesSortFilter = (statusBadge === "expired");
            else if (sortValue === "active") matchesSortFilter = (statusBadge === "active");
            else if (sortValue === "low_stock") matchesSortFilter = (row.getAttribute("data-low-stock") === "true");
            
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
            const dateA = new Date(a.children[7]?.textContent.trim() || 0);
            const dateB = new Date(b.children[7]?.textContent.trim() || 0);
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
            if ((row.getAttribute("data-low-stock") === "true")) {
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
    document.querySelectorAll('.notification-alert').forEach((alert, i) => {
        setTimeout(() => alert.classList.add('show'), 100 + i * 150);
        setTimeout(() => {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 400);
        }, 5000);
        alert.querySelector('.close-alert')?.addEventListener('click', () => alert.remove());
    });

    window.showItemInfo = function(product_id, name, sku, category, qty, price, date_entry, exp_date, status_html) {
        document.getElementById('infoProductId').textContent = product_id;
        document.getElementById('infoName').textContent = name;
        document.getElementById('infoSku').textContent = sku;
        document.getElementById('infoCategory').textContent = category;
        document.getElementById('infoQuantity').textContent = qty;
        document.getElementById('infoPrice').textContent = price;
        document.getElementById('infoDateEntry').textContent = date_entry;
        document.getElementById('infoExpiration').textContent = exp_date;
        document.getElementById('infoStatus').innerHTML = status_html;
        new bootstrap.Modal(document.getElementById('itemInfoModal')).show();
    };

});
</script>
<script src="<?= base_url('js/table-pagination.js') ?>"></script>
</body>
</html>
