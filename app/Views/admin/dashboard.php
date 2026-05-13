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
            --border-radius: 5px;
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

        .top-navbar { position: sticky; top: 0; z-index: 1000;
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

            50% { background-position: 100% 50%; box-shadow: 0 0 10px rgba(78,115,223,0.4); }
            100% { background-position: 0% 50%; box-shadow: 0 0 0 rgba(78,115,223,0); }
        }

            50% { background-position: 100% 50%; box-shadow: 0 0 10px rgba(78,115,223,0.4); }
            100% { background-position: 0% 50%; box-shadow: 0 0 0 rgba(78,115,223,0); }
        }

            50% { background-position: 100% 50%; box-shadow: 0 0 12px rgba(78,115,223,0.6); filter: brightness(1.1); }
            100% { background-position: 0% 50%; box-shadow: 0 0 0 rgba(78,115,223,0); filter: brightness(1); }
        }

            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
            70% { box-shadow: 0 0 0 10px rgba(78, 115, 223, 0); }
            100% { box-shadow: 0 0 0 0 rgba(78, 115, 223, 0); }
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
        .top-navbar { position: sticky; top: 0; z-index: 1000;
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
        body > #mobileMenuToggle { display: none !important; }

        /* CONTAINER */
        .container {
            padding: 0 20px 20px;
        }

        /* SUMMARY CARD */
        .summary-card {
            background: white;
            border-radius: 5px;
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
            border-radius: 5px;
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
            border-radius: 5px;
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
            border-radius: 5px;
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
            border-radius: 5px;
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
            border-radius: 5px;
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
            border-radius: 5px;
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
            border-radius: 5px;
            font-weight: 500;
        }

        /* HIDE GLOBAL MENU TOGGLE */
        body > #mobileMenuToggle { display: none !important; }

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
            .profile-name { display: block; font-size: 0.85rem; max-width: 70px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .profile-role { display: block; font-size: 0.75rem; }
            .top-navbar h5 { font-size: 1rem; }
            .table { min-width: 600px; }
        }
        
        
            /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-1, .btn.rounded-1, .btn-add-to-cart, .btn, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
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

        /* --- COLORED SHADOWS FOR SUMMARY CARDS --- */
        .summary-card.border-primary {
            box-shadow: 0 8px 20px rgba(78, 115, 223, 0.15) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .summary-card.border-success {
            box-shadow: 0 8px 20px rgba(28, 200, 138, 0.15) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .summary-card.border-danger {
            box-shadow: 0 8px 20px rgba(231, 74, 59, 0.15) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .summary-card.border-warning {
            box-shadow: 0 8px 20px rgba(246, 194, 62, 0.15) !important;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .summary-card:hover {
            transform: translateY(-3px);
        }
        .summary-card.border-primary:hover {
            box-shadow: 0 12px 25px rgba(78, 115, 223, 0.25) !important;
        }
        .summary-card.border-success:hover {
            box-shadow: 0 12px 25px rgba(28, 200, 138, 0.25) !important;
        }
        .summary-card.border-danger:hover {
            box-shadow: 0 12px 25px rgba(231, 74, 59, 0.25) !important;
        }
        .summary-card.border-warning:hover {
            box-shadow: 0 12px 25px rgba(246, 194, 62, 0.25) !important;
        }

        /* --- UNIFIED TABLE SCROLLING & SIZING FIX --- */
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
    <?= view('partials/admin_topbar', [
        'title' => 'Admin Dashboard',
        'icon' => 'bi bi-speedometer2',
        'show_profile' => true
    ]) ?>

    <div class="container">
        <?php
        $totalVariationItems = 0;
        $expandedLowStock = 0;
        $expandedExpiringSoon = 0;

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
                if ($daysLeft !== 999 && $daysLeft <= 10 && $daysLeft >= 0) {
                    $expandedExpiringSoon++;
                }
            }
        }
        ?>
        <!-- 📊 DATA ANALYTICS SECTION -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-primary border-4">
                    <h6 class="text-muted">Total Stock Value</h6>
                    <h3 class="fw-bold text-primary" id="totalStockValue">₱<?= number_format($totalValue, 2) ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-success border-4">
                    <h6 class="text-muted">Total Items</h6>
                    <h3 class="fw-bold text-success" id="totalItems"><?= $totalVariationItems ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-warning border-4">
                    <h6 class="text-muted">Low Stock</h6>
                    <h3 class="fw-bold text-warning" id="lowStockCount"><?= $expandedLowStock ?></h3>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-card text-center border-start border-danger border-4">
                    <h6 class="text-muted">Expiring Soon</h6>
                    <h3 class="fw-bold text-danger" id="expiringCount"><?= $expandedExpiringSoon ?></h3>
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
    let itemsData = <?= json_encode($items) ?>;
    
    // 1. Stock by Category
    const categories = {};
    itemsData.forEach(item => {
        const cat = item.category || 'Uncategorized';
        categories[cat] = (categories[cat] || 0) + 1;
    });

    let categoryChart = new Chart(document.getElementById('categoryChart'), {
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

    // 2. Top 5 Items by Sales (Integrated)
    let topSalesData = <?= $topSalesData ?? '[]' ?>;

    let topItemsChart = new Chart(document.getElementById('topItemsChart'), {
        type: 'bar',
        data: {
            labels: topSalesData.map(i => i.name),
            datasets: [
                {
                    label: 'Total Sales (₱)',
                    data: topSalesData.map(i => i.total_value),
                    backgroundColor: 'rgba(78, 115, 223, 0.8)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 1,
                    yAxisID: 'y'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { 
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true, 
                    ticks: { callback: value => '₱' + value.toLocaleString() } 
                },
                x: { grid: { display: false } }
            },
            plugins: { 
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) { 
                            return 'Sales: ₱ ' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2}); 
                        }
                    }
                }
            }
        }
    });

    // Handle Live Chart Updates
    setInterval(async () => {
        try {
            const res = await fetch("<?= site_url('items/getDashboardData') ?>");
            if (!res.ok) return;
            const data = await res.json();
            itemsData = data.items;

            // Update Total Value
            document.getElementById('totalStockValue').innerText = '₱' + parseFloat(data.totalValue).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('totalItems').innerText = itemsData.length;

            let lowStockCount = 0;
            let expiringCount = 0;

            const categoryCounts = {};

            itemsData.forEach(item => {
                // Category counts
                const cat = item.category || 'Uncategorized';
                categoryCounts[cat] = (categoryCounts[cat] || 0) + 1;

                // Low Stock
                const isSiomai = item.name.toLowerCase().includes('siomai');
                if (isSiomai) {
                    if ((parseInt(item.pack_small_qty) || 0) <= 10 && (parseInt(item.pack_medium_qty) || 0) <= 10 && (parseInt(item.pack_biggest_qty) || 0) <= 10) {
                        lowStockCount++;
                    }
                } else if (parseInt(item.quantity) <= 10) {
                    lowStockCount++;
                }

                // Expiring
                if (item.status === 'expiring soon' || item.status === 'expired') {
                    expiringCount++;
                }

                // Note: updating table rows automatically would disrupt ongoing user interactions, so we only update metrics + charts
            });

            document.getElementById('lowStockCount').innerText = lowStockCount;
            document.getElementById('expiringCount').innerText = expiringCount;

            // Update Categories Chart
            categoryChart.data.labels = Object.keys(categoryCounts);
            categoryChart.data.datasets[0].data = Object.values(categoryCounts);
            categoryChart.update();

            // Update Top 5 Items Chart
            if (data.topSalesData) {
                topSalesData = data.topSalesData;
                topItemsChart.data.labels = topSalesData.map(i => i.name);
                topItemsChart.data.datasets[0].data = topSalesData.map(i => i.total_value);
                topItemsChart.update();
            }

        } catch (err) {
            console.error('Failed to fetch live updates:', err);
        }
    }, 5000);

});
</script>
</body>
</html>
