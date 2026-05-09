<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title><?= $title ?? 'Halimaw Siomai Admin' ?></title>

    <!-- Bootstrap 5.3.3 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #2c3e50;        /* Dark blue */
            --sidebar-text: #d1d5db;
            --sidebar-hover: #34495e;
            --sidebar-active: #4e73df;     /* Blue active */
            --content-bg: #f8f9fc;
            --card-shadow: 0 0.15rem 1.75rem rgba(58, 59, 69, 0.15);
            --border-radius: 5px;
            --primary: #4e73df;            /* Blue header */
            --warning-bg: #fff3cd;         /* Yellow alert */
            --danger-bg: #f8d7da;          /* Red alert */
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
        .container > .row, .controls-section, .summary-card, .animated-actions {
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
            background-color: var(--content-bg);
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
            box-shadow: var(--card-shadow);
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
            width: 44px;
            height: 44px;
            border-radius: 6px;
            background-color: #f0f2f5;
            padding: 2px;
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
        #sidebar .nav-link.text-danger {
            color: #ff6b6b !important;
        }
        #sidebar .nav-link.text-danger:hover {
            background: rgba(231, 74, 59, 0.15);
            color: var(--danger) !important;
        }

        /* MAIN CONTENT */
        #content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }

        /* TOP NAVBAR */
        .top-navbar { position: sticky; top: 0; z-index: 1000;
            background: white;
            height: 60px;
            padding: 0 20px;
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            box-shadow: var(--card-shadow);
            margin: -20px -20px 24px -20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-navbar .navbar-title-container {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .top-navbar h5 {
            margin: 0;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
            display: flex;
            align-items: center;
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
        .mobile-menu-toggle-inline {
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
        .mobile-menu-toggle-inline:hover {
            background: var(--sidebar-hover);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        .sidebar-overlay.active {
            display: block;
        }

        /* ✨ ENHANCED TABLE (MATCHES SCREENSHOT) */
        .table-card {
            background: white;
            border-radius: 5px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            padding: 0;
            margin-bottom: 24px;
        }
        .table {
            margin: 0;
            min-width: 900px;
        }
        .table thead th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            padding: 14px 12px;
            text-align: center;
            vertical-align: middle;
        }
        .table tbody td {
            padding: 12px 10px;
            text-align: center;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
        }
        .table tbody tr.low-stock {
            background-color: var(--warning-bg);
        }
        .table tbody tr.expiring-soon {
            background-color: var(--danger-bg);
        }

        /* ✨ BADGES (MATCHES SCREENSHOT) */
        .badge {
            font-size: 0.8rem;
            padding: 0.35em 0.6em;
            border-radius: 8px;
            font-weight: 600;
        }
        .badge.bg-success { background-color: #28a745; color: white; }
        .badge.bg-warning { background-color: #ffc107; color: #212529; }
        .badge.bg-danger { background-color: #dc3545; color: white; }
        .badge.bg-secondary { background-color: #6c757d; color: white; }

        /* PERFECT NOTIFICATION CIRCLE */
        .badge-dot {
            width: 20px !important;
            height: 20px !important;
    flex-shrink: 0 !important;
            padding: 0 !important;
            border-radius: 50% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            text-align: center !important;
            font-size: 0.75rem !important;
            font-weight: 700 !important;
        }

        /* ✨ BUTTONS (MATCHES SCREENSHOT) */
        .btn {
            border-radius: 5px;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        .btn-secondary {
            background-color: #e9ecef;
            color: #495057;
            border-color: #dee2e6;
        }
        .btn-secondary:hover {
            background-color: #dfe2e5;
            border-color: #ced4da;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }

        /* ✨ FORM CONTROLS */
        .form-control,
        .form-select {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 0.45rem 0.75rem;
            font-family: 'Poppins', sans-serif;
        }
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }

        /* ✨ ALERTS (MATCHES SCREENSHOT) */
        .alert {
            border-radius: 5px;
            border: none;
            padding: 14px 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }
        .alert-success {
            background: #d1e7dd;
            color: #146c43;
        }
        .alert-danger {
            background: #f8d7da;
            color: #842024;
        }
        .alert-info {
            background: #cff4fc;
            color: #055160;
        }
        .alert-warning {
            background: var(--warning-bg);
            color: #664d03;
        }

        /* ✨ INVENTORY SUMMARY CARD (MATCHES SCREENSHOT) */
        .inventory-summary {
            background: white;
            border-radius: 5px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 24px;
            border-left: 4px solid var(--primary);
        }
        .inventory-summary h6 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid var(--primary);
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

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            .mobile-menu-toggle-inline { display: flex; }
            body > #mobileMenuToggle { display: none !important; }
            #sidebar { transform: translateX(-100%); width: 280px; }

        #sidebar .nav {
            width: 100%;
            min-width: 0;
        }
        #sidebar .nav-item {
            width: 100%;
            min-width: 0;
        }
            #content { margin-left: 0; width: 100%; }
            .top-navbar { position: sticky; top: 0; z-index: 1000; border-radius: 0; margin-bottom: 15px; } 
            #sidebar.active { transform: translateX(0); }
            .table { min-width: 600px; font-size: 0.85rem; }
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
</head>

<body>
    <?php 
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

    <?= view('partials/admin_sidebar') ?>

    <!-- CONTENT WRAPPER -->
    <div id="content">
        <div class="top-navbar">
            <div class="navbar-title-container">
                <button class="mobile-menu-toggle-inline d-lg-none" id="mobileMenuToggleInline">
                    <i class="bi bi-list"></i>
                </button>
                <h5><i class="bi bi-people me-2"></i><?= $title ?? 'Staff Management' ?></h5>
            </div>
            <?= $this->renderSection('header_actions') ?>
        </div>
        <?= $this->renderSection('content') ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
        });
    </script>
</body>
</html>
