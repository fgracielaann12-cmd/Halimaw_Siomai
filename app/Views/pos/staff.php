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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halimaw Siomai Staff POS</title>
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Poppins Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
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

        .top-navbar {
            animation: fadeSlideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .pos-items {
            opacity: 0;
            animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.1s forwards;
        }
        .pos-sidebar {
            opacity: 0;
            animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards;
        }

        body {
            background-color: #f8f9fc;
            color: #3a3b45;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-x: clip;
        }

        /* SIDEBAR — MATCHES DASHBOARD EXACTLY */
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
            overflow-y: auto;
        }
        #sidebar::-webkit-scrollbar { width: 6px; }
        #sidebar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 5px; }

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
            width: 45px;
            height: 45px;
            border-radius: 10px !important;
            background-color: #f8f9fa;
            padding: 3px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        /* BADGES */
        .badge {
            font-size: 0.8rem;
            padding: 0.35em 0.6em;
            border-radius: 8px;
            font-weight: 600;
        }
        .badge.bg-danger { background-color: #dc3545; color: white; }

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

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            margin-top: 0 !important;
            padding-top: 0 !important;
            transition: margin-left 0.3s ease;
        }

        /* TOP NAVBAR */
        .top-navbar {
            background: white;
            height: 60px;
            padding: 0 20px;
            border-radius: 0 0 var(--border-radius) var(--border-radius); /* Ensure flat top */
            box-shadow: var(--card-shadow);
            margin: 0 0 10px 0 !important; /* Forcibly remove any top margin */
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
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

        /* MOBILE MENU — MATCHES DASHBOARD STYLE */
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
            position: fixed;
            top: 9px;
            left: 15px;
            z-index: 99999;
        }

        .sidebar-overlay.active ~ .mobile-menu-toggle {
            display: none !important;
        }
        .mobile-menu-toggle:hover {
            background: var(--sidebar-hover);
        }
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: flex; }
            .hide-mobile { display: none !important; }
            .top-navbar {
                border-radius: 0 !important;
                margin: 0 0 15px 0 !important;
                padding-left: 70px !important;
            }
        }

        /* POS LAYOUT */
        .pos-layout {
            display: flex;
            gap: 24px;
            padding: 0 24px 24px;
            flex-wrap: wrap;
        }

        .pos-items {
            flex: 1;
            min-width: 300px;
        }

        .pos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(216px, 1fr));
            justify-content: center;
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .pos-item-card {
            background: white;
            border-radius: 5px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            padding: 15px;
            text-align: center;
            transition: all 0.25s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .pos-item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            border-color: var(--primary);
        }

        .pos-item-card.selected {
            border: 2px solid var(--primary);
            background-color: #f0f8ff;
        }

        .pos-item-card.out-of-stock {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .pos-item-card img {
            width: 100%;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 8px !important;
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .pos-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            .pos-item-card {
                padding: 10px;
            }
        }

        .pos-item-card h6 {
            font-size: 0.95rem;
            font-weight: 700;
            margin: 0 0 6px 0;
            color: #4a4a4a;
            line-height: 1.2;
        }

        .pos-item-card .stock-info {
            font-size: 0.75rem;
            color: var(--secondary);
            margin: 2px 0;
        }

        .pos-item-card .stock-low {
            color: var(--danger);
            font-weight: 600;
        }

        .pos-item-card p.price {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--primary);
            margin: 4px 0;
        }

        .pack-buttons {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-top: auto;
        }

        .pack-buttons button {
            padding: 3px 6px;
            font-size: 0.7rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f8f9fa;
            color: var(--dark);
            cursor: pointer;
            transition: all 0.2s;
        }

        .pack-buttons button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .btn-add-to-cart {
            background-color: var(--success) !important;
            color: white !important;
            border: none !important;
            padding: 10px !important;
            font-weight: 600 !important;
            border-radius: 5px; !important;
            width: 100% !important;
            transition: all 0.2s ease !important;
            margin-top: 8px !important;
            display: block !important;
        }

        .btn-add-to-cart:hover {
            background-color: #17a673 !important;
            transform: scale(1.02);
        }

        /* SIDEBAR CART */
        .pos-sidebar {
            width: 340px;
        }

        .cart-summary, .checkout-summary {
            background: white;
            border-radius: 5px;
            box-shadow: var(--card-shadow);
            padding: 20px;
        }

        .cart-summary {
            margin-bottom: 20px;
        }

        .cart-summary h4, .checkout-summary h4 {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .cart-items {
            max-height: 200px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .cart-items::-webkit-scrollbar {
            width: 6px;
        }
        .cart-items::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .cart-items::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }
        .cart-items::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }

        .cart-items p.text-muted {
            text-align: center;
            color: var(--secondary);
            margin: 0;
            padding: 20px 0;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 10px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            border-radius: 4px;
        }

        @keyframes highlightFlash {
            0% { background-color: rgba(28, 200, 138, 0.4); }
            100% { background-color: transparent; }
        }
        .cart-item.highlight-flash {
            animation: highlightFlash 1.5s ease-out;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .quantity-control {
            display: flex;
            gap: 4px;
        }

        .quantity-control button {
            width: 28px;
            height: 28px;
            padding: 0;
            margin: 0;
            border-radius: 6px;
            background: #f1f2f6;
            border: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
        }

        .quantity-control input {
            width: 36px;
            height: 28px;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            background: white;
            line-height: 26px;
        }

        .item-price {
            font-weight: 600;
            color: var(--primary);
        }

        /* CHECKOUT */
        .checkout-summary .total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.1rem;
            font-weight: 700;
            margin: 16px 0;
            padding: 12px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        #checkout-btn {
            background: var(--success);
            color: white;
            border: none;
            padding: 10px;
            font-weight: 600;
            border-radius: 5px;
            width: 100%;
            margin: 12px 0;
            transition: background 0.2s;
        }

        #checkout-btn:hover {
            background: #17a673;
        }

        #clear-cart {
            background: #f8f9fa;
            color: var(--dark);
            border: 1px solid #ddd;
            padding: 10px;
            font-weight: 500;
            border-radius: 5px;
            width: 100%;
            transition: all 0.2s;
        }

        #clear-cart:hover {
            background: #e9ecef;
        }

        /* PAYMENT METHODS */
        .payment-methods {
            margin-top: 16px;
        }

        .payment-methods h6 {
            font-size: 0.9rem;
            color: var(--secondary);
            margin-bottom: 8px;
        }

        .payment-options {
            display: flex;
            gap: 8px;
            flex-wrap: nowrap;
        }

        .payment-option {
            flex: 1;
            justify-content: center;
            padding: 8px 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
            text-align: center;
        }

        .payment-option:hover {
            background: #f8f9fa;
        }

        .payment-option.active {
            border-color: var(--primary);
            background: #eef5ff;
            color: var(--primary);
            font-weight: 600;
        }

        /* ALERTS */
        .notification-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-50px);
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            text-align: center;
            border-radius: 5px;
            padding: 14px 24px;
            font-size: 0.95rem;
            font-weight: 500;
            color: white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
            pointer-events: auto;
            opacity: 0;
            transition: all 0.4s ease;
        }

        .notification-alert.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        .close-alert {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            margin-left: 12px;
        }

        .success-alert {
            background: linear-gradient(135deg, #1cc88a, #17a673);
        }

        .error-alert {
            background: linear-gradient(135deg, #e74a3b, #d93a2a);
        }

        /* === NEW: POS TUTORIAL STYLES === */
        .pos-tutorial {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 9998;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .pos-tutorial.show {
            opacity: 1;
            pointer-events: all;
        }

        .tutorial-content {
            background: white;
            border-radius: 5px;
            max-width: 600px;
            width: 90%;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        .tutorial-content h3 {
            color: var(--primary);
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
        }

        .tutorial-steps {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .tutorial-step {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .step-number {
            background: var(--primary);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-shrink: 0;
            font-weight: bold;
        }

        .step-content h4 {
            margin: 0 0 8px;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .step-content p {
            margin: 0;
            color: var(--secondary);
            font-size: 0.95rem;
        }

        .close-tutorial {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 20px;
            background: #666;
            border: none;
            color: #ffffff;
            cursor: pointer;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
            z-index: 10;
        }
        .close-tutorial:hover { background: #555; }
        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: flex !important; }
            #sidebar { transform: translateX(-100%); width: 280px; position: fixed; top: 0; left: 0; height: 100vh; z-index: 1050; }

        #sidebar .nav {
            width: 100%;
            min-width: 0;
        }
        #sidebar .nav-item {
            width: 100%;
            min-width: 0;
        }
            
            
            .main-content { margin-left: 0; width: 100%; padding-top: 25px; }
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

            .pos-layout {
                flex-direction: column;
                padding: 0 12px 12px;
            }
            .pos-sidebar { width: 100%; }
        }

        @media (max-width: 480px) {
            .pos-item-card { min-height: auto; }
            .tutorial-content {
                padding: 20px;
            }
            .tutorial-step {
                gap: 10px;
            }
            .step-number {
                width: 24px;
                height: 24px;
                font-size: 0.9rem;
            }
        }
        
        /* TOAST NOTIFICATION */
        .pos-toast {
            visibility: hidden;
            min-width: 300px;
            background-color: var(--success);
            color: #fff;
            text-align: center;
            border-radius: 5px;
            padding: 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            transform: translateX(-50%);
            bottom: 20px;
            font-size: 1rem;
            font-weight: 500;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            opacity: 0;
            transition: opacity 0.3s, bottom 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .pos-toast.show {
            visibility: visible;
            opacity: 1;
            bottom: 40px;
        }
        /* SHOPEE MODAL STYLES */
        .shopee-modal-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .shopee-modal-overlay.show {
            display: flex;
            opacity: 1;
        }
        .shopee-modal-content {
            background: white;
            border-radius: 8px;
            width: 850px;
            max-width: 95vw;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            position: relative;
            transform: scale(0.9);
            transition: transform 0.3s;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        .shopee-modal-overlay.show .shopee-modal-content {
            transform: scale(1);
        }
        .shopee-close-btn {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            background: #666; /* Grey circle background */
            border: none;
            color: #ffffff; /* White X button */
            cursor: pointer;
            z-index: 50;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }
        .shopee-close-btn:hover { background: #555; }
        
        .shopee-modal-body {
            display: flex;
            padding: 30px;
            gap: 30px;
            overflow-y: auto;
            flex: 1;
            min-height: 0;
        }
        @media (max-width: 850px) {
            .shopee-modal-body { flex-direction: column; padding: 20px; gap: 20px; }
            .shopee-modal-left { width: 100% !important; max-width: 100% !important; }
            .shopee-modal-content { width: 100%; height: auto; margin: 10px; }
            .sm-actions { padding-left: 0 !important; }
        }
        .shopee-modal-left {
            width: 380px;
            max-width: 45%;
            flex-shrink: 0;
        }
        .shopee-modal-left img {
            width: 100%;
            height: auto;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #f0f0f0;
        }
        .shopee-modal-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0; /* Prevent flex overflow */
        }
        .shopee-modal-right h2 {
            font-size: 1.25rem;
            font-weight: 500;
            color: rgba(0,0,0,.8);
            margin-bottom: 20px;
            line-height: 1.4;
        }
        .shopee-price-box {
            background: #fafafa;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        .shopee-price-box .sm-currency {
            font-size: 1.2rem;
            color: var(--primary);
            margin-right: 5px;
        }
        .shopee-price-box #smPrice {
            font-size: 2.2rem;
            font-weight: 500;
            color: var(--primary);
        }
        .sm-info-row {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
        }
        .sm-label {
            color: #757575;
            width: 110px;
            font-size: 0.95rem;
        }
        .sm-variations {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            flex: 1;
        }
        .sm-variations button {
            padding: 8px 16px;
            background: white;
            border: 1px solid rgba(0,0,0,.09);
            border-radius: 5px;
            cursor: pointer;
            outline: none;
            position: relative;
            color: rgba(0,0,0,.8);
        }
        .sm-variations button:hover {
            color: var(--primary);
            border-color: var(--primary);
        }
        .sm-variations button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .sm-quantity-control {
            display: flex;
            align-items: center;
        }
        .sm-quantity-control button {
            width: 32px;
            height: 32px;
            background: transparent;
            border: 1px solid rgba(0,0,0,.09);
            cursor: pointer;
            font-size: 1rem;
            color: rgba(0,0,0,.8);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .sm-quantity-control input {
            width: 50px;
            height: 32px;
            border: 1px solid rgba(0,0,0,.09);
            border-left: none;
            border-right: none;
            text-align: center;
            font-size: 1rem;
            outline: none;
        }
        .sm-actions {
            margin-top: 15px;
            display: flex;
            gap: 15px;
            padding-left: 110px;
        }
        @media(max-width: 768px) {
            .sm-actions { padding-left: 0; margin-top: 0; }
        }
        .sm-btn-add {
            background: var(--success);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            white-space: nowrap;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .sm-btn-add:hover { 
            background: #218838; 
            transform: translateY(-1px);
        }
        .sm-btn-back {
            background: #fff;
            color: #555;
            border: 1px solid rgba(0,0,0,.09);
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 0.95rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            white-space: nowrap;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .sm-btn-back:hover {
            background: #f8f8f8;
            border-color: rgba(0,0,0,.2);
        }
        
        
            /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-1, .btn.rounded-1, .btn-add-to-cart, .btn, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
        }
    
        /* Hide spin buttons for number inputs */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
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
        .card img {
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
    
        /* Hide spin buttons for number inputs */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
          -webkit-appearance: none; 
          margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield;
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
    <!-- Mobile Menu Toggle was moved to header -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Standalone hamburger button - outside navbar to avoid stacking context issues -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- SIDEBAR -->
    <nav id="sidebar">
        <a class="navbar-brand" href="#">
            <img src="<?= base_url('public/Images/Inventa.png') ?>" alt="Inventa Logo">
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
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#returnModal">
                    <i class="bi bi-arrow-return-left"></i> Customer Returns
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

    <!-- === NEW: POS TUTORIAL OVERLAY === -->
    <div class="pos-tutorial" id="posTutorial">
        <div class="tutorial-content">
            <button class="close-tutorial" id="closeTutorial">&times;</button>
            <h3>Welcome to Halimaw Siomai POS!</h3>
            <div class="tutorial-steps">
                <div class="tutorial-step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h4>Add Items to Cart</h4>
                        <p>Click a product to select size/quantity, then add to cart.</p>
                    </div>
                </div>
                <div class="tutorial-step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h4>Select Packaging</h4>
                        <p>For siomai products, choose your preferred packaging (12pcs, 20pcs, or 40pcs) before adding to cart.</p>
                    </div>
                </div>
                <div class="tutorial-step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h4>Review Cart</h4>
                        <p>Check your items and quantities in the cart summary on the right side of the screen.</p>
                    </div>
                </div>
                <div class="tutorial-step">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h4>Complete Sale</h4>
                        <p>Choose a payment method (Cash, Card, or GCash) and click <strong>Complete Sale</strong>.</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 text-center">
                <div class="form-check d-inline-block text-start">
                    <input class="form-check-input" type="checkbox" id="dontShowTutorialAgain">
                    <label class="form-check-label text-secondary" for="dontShowTutorialAgain" style="font-size: 0.95rem;">
                        Don't show this again
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <!-- TOP NAVBAR WITH USER PROFILE -->
        <div class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="bi bi-calculator me-2" style="font-size: 1.25rem;"></i>Staff POS
                </h5>
            </div>
            <div class="user-profile hide-mobile d-flex align-items-center gap-3">
                <button class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" id="showTutorialBtn" title="Help" style="width: 32px; height: 32px; padding: 0;">
                    <i class="bi bi-question-lg text-secondary" style="font-size: 1.2rem;"></i>
                </button>
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

        <div class="pos-layout">
            <!-- POS ITEMS -->
            <div class="pos-items">
                <div class="pos-grid">
                    <?php
                    // 🔥 SPLIT PRODUCTS INTO SIOMAI AND OTHERS
                    $siomaiItems = [];
                    $otherItems = [];

                    foreach ($products as $product) {
                        if (($product['quantity'] ?? 0) <= 0) continue;

                        $nameLower = strtolower($product['name']);
                        $isCustomVar = !empty($product['is_custom_variation']);
                        $hasPackPrices = (!empty($product['pack_small_price']) && $product['pack_small_price'] > 0);
                        
                        // Fix Bug 1: Only show siomai pack options if it actually has pack data (Legacy system)
                        // Fix Bug 2: Move grouped variations to 'other' to use the custom_variation modal
                        $isSiomai = (strpos($nameLower, 'siomai') !== false && $hasPackPrices && !$isCustomVar);

                        if ($isSiomai) {
                            $siomaiItems[] = $product;
                        } else {
                            $otherItems[] = $product;
                        }
                    }

                    // Display siomai items first
                    foreach ($siomaiItems as $product):
                        $nameLower = strtolower($product['name']);
                        $imgSrc = !empty($product['image_path']) ? base_url($product['image_path']) : base_url('public/Images/' . ($product['image'] ?? 'default.jpg'));
                        $stock = (int) ($product['quantity'] ?? 0);
                        $isLowStock = $stock <= 10;
                        ?>
                        <div class="pos-item-card <?= $stock <= 0 ? 'out-of-stock' : '' ?>"
                             onclick="openShopeeModal(this)"
                             data-name="<?= esc($product['name']) ?>"
                             data-type="siomai"
                             data-product-id="<?= $product['id'] ?>"
                             data-stock="<?= $stock ?>"
                             data-image="<?= $imgSrc ?>"
                             data-expr="<?= esc($product['expiration_date'] ?? '') ?>"
                             data-prices='{"Small Pack":<?=($product['pack_small_price'] ?? 115)?>,"Medium Pack":<?=($product['pack_medium_price'] ?? 185)?>,"Large Pack":<?=($product['pack_biggest_price'] ?? 335)?>}'
                             data-packstocks='{"Small Pack":<?=($product['pack_small_qty'] ?? 0)?>,"Medium Pack":<?=($product['pack_medium_qty'] ?? 0)?>,"Large Pack":<?=($product['pack_biggest_qty'] ?? 0)?>}'>
                            
                            <img src="<?= $imgSrc ?>" alt="<?= esc($product['name']) ?>">
                            <h6><?= esc($product['name']) ?></h6>
                        </div>
                    <?php endforeach; ?>

                    <!-- Then display other items (no dropdowns) -->
                    <?php foreach ($otherItems as $product):
                        $imgSrc = !empty($product['image_path']) ? base_url($product['image_path']) : base_url('public/Images/' . ($product['image'] ?? 'default.jpg'));
                        $stock = (int) ($product['quantity'] ?? 0);
                        $isLowStock = $stock <= 10;
                        $nameLower = strtolower($product['name']);
                        $price = $product['price'] ?? 0;
                        
                        $isCustomVar = !empty($product['is_custom_variation']);

                        // 🔥 SET CORRECT PRICE FOR KEY ITEMS
                        if (strpos($nameLower, 'burger patty') !== false) {
                            $price = 190.00;
                        } elseif (strpos($nameLower, 'pastil') !== false) {
                            $price = 180.00;
                        } elseif (strpos($nameLower, 'chili garlic') !== false) {
                            $price = 120.00;
                        } elseif (strpos($nameLower, 'toyomansi') !== false) {
                            $price = 65.00;
                        }
                        
                        $itemType = 'other';
                        if (strpos($nameLower, 'patty') !== false) {
                            $itemType = 'patty';
                        }
                        if ($isCustomVar) {
                            $itemType = 'custom_variation';
                        }
                    ?>
                        <div class="pos-item-card <?= $stock <= 0 ? 'out-of-stock' : '' ?>"
                             onclick="openShopeeModal(this)"
                             data-name="<?= esc($product['name']) ?>"
                             data-type="<?= $itemType ?>"
                             data-product-id="<?= $product['id'] ?>"
                             data-stock="<?= $stock ?>"
                             data-image="<?= $imgSrc ?>"
                             data-expr="<?= esc($product['expiration_date'] ?? '') ?>"
                             data-price="<?= $price ?>"
                             <?= $isCustomVar ? "data-variations='" . esc($product['custom_variations']) . "'" : "" ?>>
                            
                            <img src="<?= $imgSrc ?>" alt="<?= esc($product['name']) ?>">
                            <h6><?= esc($product['name']) ?></h6>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($siomaiItems) && empty($otherItems)): ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: var(--secondary);">
                            <i class="bi bi-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>No items available in stock.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- CART + CHECKOUT -->
            <div class="pos-sidebar">
                <div class="cart-summary mb-3">
                    <h4><i class="bi bi-globe me-2"></i>Order Online</h4>
                    <div class="cart-items" id="online-orders-list"><p class="text-muted">No online orders yet.</p></div>
                </div>
                <div class="cart-summary">
                    <h4><i class="bi bi-cart me-2"></i>Cart Summary</h4>
                    <div class="cart-items" id="main-cart-items"><p class="text-muted">No items added.</p></div>
                </div>
                <div class="checkout-summary">
                    <h4><i class="bi bi-credit-card me-2"></i>Checkout</h4>
                    <div class="total-row">
                        <span>Total:</span>
                        <strong id="cart-total">₱0.00</strong>
                    </div>
                    

                    <button id="checkout-btn">Complete Sale</button>
                    <button id="clear-cart">Clear Cart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SHOPEE STYLED PRODUCT MODAL -->
    <div id="shopeeModalOverlay" class="shopee-modal-overlay">
        <div class="shopee-modal-content">
            <button class="shopee-close-btn" onclick="closeShopeeModal()">&times;</button>
            
            <div class="shopee-modal-body">
                <div class="shopee-modal-left">
                    <img id="smImage" src="" alt="Product">
                </div>
                
                <div class="shopee-modal-right">
                    <h2 id="smTitle">Product Title</h2>
                    
                    <div class="shopee-price-box">
                        <span class="sm-currency">₱</span><span id="smPrice">0.00</span>
                    </div>

                    <div class="sm-info-row">
                        <span class="sm-label" id="smStockLabel">Stock</span>
                        <span class="sm-value" id="smStock">0 in stock</span>
                    </div>

                    <div class="sm-info-row" id="smPiecesRow" style="display:none;">
                        <span class="sm-label">Pieces</span>
                        <span class="sm-value fw-normal text-dark" id="smPieces" style="font-size: 0.95rem;"></span>
                    </div>

                    <div class="sm-info-row" id="smExprRow" style="display:none;">
                        <span class="sm-label">Expires</span>
                        <span class="sm-value text-muted" id="smExpr"></span>
                    </div>

                    <div class="sm-info-row sm-variation-row" id="smVariationRow">
                        <span class="sm-label">Pack</span>
                        <div class="sm-variations" id="smVariations">
                            <!-- Populated dynamically -->
                        </div>
                    </div>

                    <div class="sm-info-row sm-quantity-row">
                        <span class="sm-label">Quantity</span>
                        <div class="sm-quantity-control">
                            <button onclick="changeSmQty(-1)">-</button>
                            <input type="number" id="smQtyInput" value="1" min="1" onchange="handleSmQtyChange()" onkeyup="if(event.key==='Enter')handleSmQtyChange()" onblur="handleSmQtyChange()">
                            <button onclick="changeSmQty(1)">+</button>
                        </div>
                    </div>

                    <div class="sm-actions">
                        <button class="sm-btn-add" onclick="smAddToCart()" style="width: 100%;">
                            <i class="bi bi-cart-plus"></i> Add To Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Online Order Details Modal -->
    <div class="modal fade" id="onlineOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header" style="background: var(--primary); color: white;">
                    <h5 class="modal-title w-100 text-center fw-bold">
                        <i class="bi bi-globe me-2"></i>Online Order <span id="onlineOrderIdTitle"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Customer Details</h6>
                    <div class="mb-2"><strong>Name:</strong> <span id="ooCustomerName"></span></div>
                    <div class="mb-2"><strong>Phone:</strong> <span id="ooCustomerPhone"></span></div>
                    <div class="mb-4"><strong>Email:</strong> <span id="ooCustomerEmail"></span></div>

                    <h6 class="text-secondary fw-semibold border-bottom pb-2 mb-3">Order Items</h6>
                    <div class="table-responsive mb-4" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-hover table-sm align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="ooItemsList"></tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center p-3 rounded" style="background: #f8f9fa; border-left: 4px solid var(--primary);">
                        <span class="fs-5 fw-semibold text-dark">Grand Total</span>
                        <span class="fs-4 fw-bold text-primary" id="ooGrandTotal"></span>
                    </div>
                </div>
                <div class="modal-footer border-0 d-flex gap-2 w-100">
                    <button type="button" class="btn btn-light flex-fill m-0" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary flex-fill m-0" id="btnConfirmOnlineOrderStaff" onclick="confirmOnlineOrder()">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Checkout Review Modal -->
    <div class="modal fade" id="checkoutReviewModal" tabindex="-1" aria-labelledby="checkoutReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header" style="background: var(--primary); color: white;">
                    <h5 class="modal-title w-100 text-center fw-bold" id="checkoutReviewModalLabel">
                        <i class="bi bi-cart-check me-2"></i>Review Sale
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="table-responsive mb-4" style="max-height: 250px; overflow-y: auto; border-radius: 8px; border: 1px solid #dee2e6;">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="checkout-summary-body">
                                <!-- Populated by JS -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center p-3 mb-3 rounded" style="background: #f8f9fa; border-left: 4px solid var(--primary);">
                        <span class="fs-5 fw-semibold text-dark">Grand Total</span>
                        <span class="fs-4 fw-bold text-primary" id="checkout-grand-total"></span>
                    </div>

                    <!-- VAT TAXATION CHECKBOX -->
                    <div class="form-check mb-3 ms-2">
                        <input class="form-check-input" type="checkbox" id="applyVatTax" style="transform: scale(1.2); cursor: pointer;">
                        <label class="form-check-label ms-1 fw-medium text-dark" for="applyVatTax" style="cursor: pointer; user-select: none;">
                            Apply 12% VAT Taxation
                        </label>
                    </div>

                    <!-- VAT TYPE SELECTION -->
                    <div id="vatTypeBlock" class="mb-3 ms-4" style="display: none;">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vatType" id="vatIncluded" value="included" checked>
                            <label class="form-check-label text-dark" for="vatIncluded">Included</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="vatType" id="vatExcluded" value="excluded">
                            <label class="form-check-label text-dark" for="vatExcluded">Excluded</label>
                        </div>
                    </div>

                    <!-- VAT COMPUTATION AREA -->
                    <div id="vatComputationBlock" class="p-3 mb-4 rounded border" style="display: none; background: #fffcf5; border-color: #ffeeba!important;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary fw-medium">Vatable Sales:</span>
                            <span class="fw-bold text-muted" id="vatableSalesAmount">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-secondary fw-medium" id="vatTaxLabel">12% VAT (Included):</span>
                            <span class="fw-bold text-warning" id="vatTaxAmount">₱0.00</span>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-2 mt-2">
                            <span class="text-dark fw-bold fs-5">Grand Total Due:</span>
                            <span class="fw-bold text-success fs-4" id="finalAmountWithVat">₱0.00</span>
                        </div>
                    </div>

                    <h6 class="mb-3 text-secondary fw-semibold border-bottom pb-2">Customer Details <small>(Optional)</small></h6>
                    <div class="mb-3">
                        <label for="checkoutCustomerName" class="form-label text-dark fw-medium">Customer Name</label>
                        <input type="text" id="checkoutCustomerName" class="form-control" placeholder="Enter full name">
                    </div>
                    <div class="mb-4">
                        <label for="checkoutCustomerEmail" class="form-label text-dark fw-medium">Customer Email <small class="text-muted fw-normal">(For order verification)</small></label>
                        <input type="email" id="checkoutCustomerEmail" class="form-control" placeholder="example@email.com">
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4 d-flex gap-2">
                    <button type="button" class="btn btn-light flex-grow-1" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirm-sale-btn" class="btn btn-success flex-grow-1 fw-bold shadow-sm">
                        <i class="bi bi-check-circle me-2"></i>Confirm & Pay
                    </button>
                </div>
            </div>
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
                            <select id="requestItemModal" class="form-select shadow-sm" required style="border-radius: 5px; padding: 0.6rem 1rem;">
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
                                            <?php if (function_exists('getProductSKU')): ?>
                                                <?= esc(getProductSKU($item['name'], $vItem['variation'])) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                            <?php else: ?>
                                                <?= esc($item['product_id']) ?><?= esc($vItem['id_suffix']) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="requestActionModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-arrow-left-right me-1"></i> Adjustment Type
                            </label>
                            <select id="requestActionModal" class="form-select shadow-sm" required style="border-radius: 5px; padding: 0.6rem 1rem;">
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
                                   style="border-radius: 5px; padding: 0.6rem 1rem;">
                        </div>
                        <div class="mb-4">
                            <label for="requestReasonModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-journal-text me-1"></i> Reason / Notes
                            </label>
                            <textarea id="requestReasonModal" class="form-control shadow-sm" rows="3" placeholder="e.g., spillage, delivery, inventory correction..." required
                                      style="border-radius: 5px; padding: 0.6rem 1rem;"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn fw-bold" style="
                                background: var(--primary);
                                color: white;
                                border: none;
                                padding: 0.75rem;
                                border-radius: 8px;
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
                            <select id="pullOutItemModal" class="form-select shadow-sm" required style="border-radius: 5px; padding: 0.6rem 1rem;">
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
                                            <?php if (function_exists('getProductSKU')): ?>
                                                <?= esc(getProductSKU($item['name'], $vItem['variation'])) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                            <?php else: ?>
                                                <?= esc($item['product_id']) ?><?= esc($vItem['id_suffix']) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="pullOutReasonModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-exclamation-triangle me-1"></i> Reason
                            </label>
                            <input type="text" id="pullOutReasonModal" class="form-control shadow-sm" required placeholder="e.g. Expired, Spoiled, Damaged, Customer Return" style="border-radius: 5px; padding: 0.6rem 1rem;">
                        </div>
                        <div class="mb-4">
                            <label for="pullOutCategoryModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-tags me-1"></i> Category
                            </label>
                            <select id="pullOutCategoryModal" class="form-select shadow-sm" required style="border-radius: 5px; padding: 0.6rem 1rem;">
                                <option value="">— Select Category —</option>
                                <option value="Expiry">Expiry</option>
                                <option value="Handling Error">Handling Error</option>
                                <option value="Storage Issue">Storage Issue</option>
                                <option value="Quality Issue">Quality Issue</option>
                                <option value="Customer Return">Customer Return</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="pullOutQtyModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-hash me-1"></i> Quantity to Pull-Out
                            </label>
                            <input type="number" id="pullOutQtyModal" class="form-control shadow-sm" min="1" placeholder="Enter amount" required
                                   style="border-radius: 5px; padding: 0.6rem 1rem;">
                        </div>
                        <div class="mb-4">
                            <label for="pullOutNoteModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-journal-text me-1"></i> Additional Notes (Optional)
                            </label>
                            <textarea id="pullOutNoteModal" class="form-control shadow-sm" rows="3" placeholder="Provide extra details..."
                                      style="border-radius: 5px; padding: 0.6rem 1rem;"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="pullOutImageModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-image me-1"></i> Upload Proof (Optional)
                            </label>
                            <input type="file" id="pullOutImageModal" class="form-control shadow-sm" accept="image/*" style="border-radius: 5px; padding: 0.6rem 1rem;">
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-light border shadow-sm px-4 fw-semibold" data-bs-dismiss="modal" style="border-radius: 5px;">Cancel</button>
                            <button type="submit" class="btn btn-danger shadow-sm px-4 fw-semibold" style="border-radius: 5px;">
                                <i class="bi bi-send me-2"></i>Submit Pull-Out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Customer Return Modal -->
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header p-3 border-0 position-relative" style="background-color: #2c3e50; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title fw-semibold text-white w-100 text-center" id="returnModalLabel" style="font-size: 1.15rem;">
                        <i class="bi bi-arrow-return-left me-2"></i>Process Customer Return
                    </h5>
                    <button type="button" class="btn-close btn-close-white position-absolute top-50 end-0 translate-middle-y me-3" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <form id="returnFormModal">
                        <?= csrf_field() ?>
                        
                        <div class="mb-4">
                            <label for="returnTransactionId" class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="bi bi-receipt me-1"></i> Transaction ID
                            </label>
                            <input type="text" id="returnTransactionId" class="form-control form-control-lg" required placeholder="Enter Transaction ID (e.g. TXN-12345)" style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                        </div>

                        <div class="mb-4">
                            <label for="returnItemModal" class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="bi bi-box me-1"></i> Select Item
                            </label>
                            <select id="returnItemModal" class="form-select form-select-lg" required style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                                <option value="" disabled selected>— Choose an item —</option>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                        $isSiomai = stripos($item['name'], 'siomai') !== false;
                                        $displayRows = [];
                                        if ($isSiomai) {
                                            $displayRows[] = ['variation' => 'S', 'pack_name' => 'Small Pack', 'id_suffix' => '-S'];
                                            $displayRows[] = ['variation' => 'M', 'pack_name' => 'Medium Pack', 'id_suffix' => '-M'];
                                            $displayRows[] = ['variation' => 'L', 'pack_name' => 'Large Pack', 'id_suffix' => '-L'];
                                        } else {
                                            $displayRows[] = ['variation' => null, 'pack_name' => '', 'id_suffix' => ''];
                                        }
                                    ?>
                                    <?php foreach ($displayRows as $vItem): ?>
                                        <option value="<?= esc($item['id']) ?>" data-variation="<?= esc($vItem['pack_name']) ?>">
                                            <?php if (function_exists('getProductSKU')): ?>
                                                <?= esc(getProductSKU($item['name'], $vItem['variation'])) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                            <?php else: ?>
                                                <?= esc($item['product_id']) ?><?= esc($vItem['id_suffix']) ?> - <?= esc($item['name']) ?><?= $vItem['pack_name'] ? ' (' . esc($vItem['pack_name']) . ')' : '' ?>
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="returnQtyModal" class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="bi bi-hash me-1"></i> Quantity
                                </label>
                                <input type="number" id="returnQtyModal" class="form-control form-control-lg" min="1" placeholder="Enter amount" required
                                       style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                            </div>
                            <div class="col-md-6">
                                <label for="returnReasonModal" class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Reason for Return
                                </label>
                                <select id="returnReasonModal" class="form-select form-select-lg" required style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                                    <option value="" disabled selected>— Select Reason —</option>
                                    <option value="Wrong Order">Wrong Order</option>
                                    <option value="Change of Mind">Change of Mind</option>
                                    <option value="Quality Issue">Quality Issue</option>
                                    <option value="Damaged Item">Damaged Item</option>
                                </select>
                            </div>
                        </div>

                        <!-- PROOF OF EVIDENCE -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-2" style="font-size: 0.95rem;">
                                <i class="bi bi-camera me-1 text-primary"></i> Proof of Evidence (Optional)
                            </label>
                            <input type="file" class="form-control form-control-lg" id="returnEvidenceModal" accept="image/*,video/*" style="border-radius: 8px; border: 1px solid #ced4da; font-size: 1rem; box-shadow: none;">
                            <small class="text-muted mt-1 d-block" style="font-size: 0.85rem;"><i class="bi bi-info-circle me-1"></i>Attach photo or video showing the item's condition.</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark mb-3 d-block" style="font-size: 0.95rem;">
                                <i class="bi bi-check-circle me-1"></i> Item Condition Evaluation
                            </label>
                            
                            <div class="d-flex flex-column gap-3">
                                <!-- Restockable Option -->
                                <div class="form-check p-3 rounded" style="background-color: #f8fff9; border: 1px solid #28a745;">
                                    <input class="form-check-input ms-2 mt-2" type="radio" name="returnCondition" id="condRestockable" value="RESTOCKABLE" required style="transform: scale(1.3);">
                                    <label class="form-check-label ms-3 w-100" for="condRestockable" style="cursor:pointer;">
                                        <div class="fw-bold text-success" style="font-size: 1.05rem;">RESTOCKABLE</div>
                                        <small class="text-muted">Item is in perfect condition, safe for consumption, and can be resold immediately.</small>
                                    </label>
                                </div>
                                
                                <!-- Non-Restockable Option -->
                                <div class="form-check p-3 rounded" style="background-color: #fff8f8; border: 1px solid #dc3545;">
                                    <input class="form-check-input ms-2 mt-2" type="radio" name="returnCondition" id="condNonRestockable" value="NON-RESTOCKABLE" required style="transform: scale(1.3);">
                                    <label class="form-check-label ms-3 w-100" for="condNonRestockable" style="cursor:pointer;">
                                        <div class="fw-bold text-danger" style="font-size: 1.05rem;">NON-RESTOCKABLE (Waste)</div>
                                        <small class="text-muted">Item is compromised, spoiled, damaged, or unsafe. This will automatically generate a <strong class="text-danger">Pull-Out Request</strong>.</small>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <style>
                                .btn-return-cancel { transition: all 0.2s ease-in-out; }
                                .btn-return-cancel:hover { background-color: #d1d5db !important; border-color: #d1d5db !important; }
                                .btn-return-process { transition: all 0.2s ease-in-out; }
                                .btn-return-process:hover { background-color: #1e40af !important; transform: translateY(-1px); box-shadow: 0 4px 6px rgba(0,0,0,0.1) !important; }
                            </style>
                            <button type="button" class="btn btn-light px-4 py-2 fw-bold btn-return-cancel" data-bs-dismiss="modal" style="border-radius: 10px !important; border: 1px solid #e2e8f0; background-color: #f8fafc; color: #0f172a; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4 py-2 fw-bold btn-return-process" style="border-radius: 10px !important; background-color: #1d4ed8; border: none; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                <i class="bi bi-send me-1"></i> Process Return
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // --- MOBILE MENU TOGGLE ---
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        
        function toggleSidebar() {
            if (!sidebar || !sidebarOverlay) return;
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.remove('active');
            if (sidebarOverlay) sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991) closeSidebar();
            });
        });

        if ($('#requestItemModal').length) {
            $('#requestItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#helpModal'),
                width: '100%'
            });
        }

        if ($('#requestActionModal').length) {
            $('#requestActionModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#helpModal'),
                width: '100%',
                minimumResultsForSearch: Infinity // hide search for small lists
            });
        }

        if ($('#pullOutItemModal').length) {
            $('#pullOutItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#pullOutModal'),
                width: '100%'
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

        if ($('#returnItemModal').length) {
            $('#returnItemModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#returnModal'),
                width: '100%'
            });
        }

        if ($('#returnReasonModal').length) {
            $('#returnReasonModal').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#returnModal'),
                width: '100%',
                minimumResultsForSearch: Infinity
            });
        }


        // ✅ STOCK REQUEST SUBMISSION
        const stockRequestForm = document.getElementById("stockRequestFormModal");
        if (stockRequestForm) {
            stockRequestForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = stockRequestForm.querySelector("button[type='submit']");
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
                        stockRequestForm.reset();
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
                const category = document.getElementById("pullOutCategoryModal").value;
                const quantity = parseInt(document.getElementById("pullOutQtyModal").value) || 0;
                let note = document.getElementById("pullOutNoteModal").value.trim();
                const imageFile = document.getElementById("pullOutImageModal").files[0];

                if (!itemId || !reason || !category || !quantity) {
                    alert("Please fill all required fields.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submit Pull-Out';
                    return;
                }

                const formData = new FormData();
                formData.append('item_id', itemId);
                if (variation) formData.append('variation', variation.toLowerCase());
                formData.append('quantity', quantity);
                formData.append('reason', reason);
                formData.append('category', category);
                formData.append('note', note);
                if (imageFile) formData.append('image', imageFile);

                try {
                    const response = await fetch("<?= site_url('user/submit-pull-out') ?>", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });
                    const result = await response.json();
                    if (result.success) {
                        alert(result.message || "Pull-out recorded successfully!");
                        pullOutForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById("pullOutModal")).hide();
                    } else {
                        alert(result.message || "Failed to record pull-out.");
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
        // ✅ RETURNS SUBMISSION
        const returnForm = document.getElementById("returnFormModal");
        if (returnForm) {
            returnForm.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = returnForm.querySelector("button[type='submit']");
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-hourglass me-2"></i>Processing...';

                const selectElement = document.getElementById("returnItemModal");
                const itemId = selectElement.value;
                const variation = selectElement.options[selectElement.selectedIndex].getAttribute("data-variation");
                
                const transactionId = document.getElementById("returnTransactionId").value.trim();
                const quantity = parseInt(document.getElementById("returnQtyModal").value) || 0;
                const reason = document.getElementById("returnReasonModal").value;
                const evidenceFile = document.getElementById("returnEvidenceModal").files[0];
                
                let condition = "";
                const condRadios = document.getElementsByName("returnCondition");
                for (let i=0; i<condRadios.length; i++) {
                    if (condRadios[i].checked) {
                        condition = condRadios[i].value;
                        break;
                    }
                }

                if (!transactionId || !itemId || !quantity || !reason || !condition) {
                    alert("Please fill all required fields correctly.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Process Return';
                    return;
                }

                const formData = new FormData();
                formData.append("transaction_id", transactionId);
                formData.append("item_id", itemId);
                if(variation) formData.append("variation", variation);
                formData.append("quantity", quantity);
                formData.append("reason", reason);
                formData.append("return_condition", condition);
                if(evidenceFile) formData.append("evidence_file", evidenceFile);

                try {
                    const response = await fetch("<?= site_url('user/submit-return') ?>", {
                        method: "POST",
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "X-CSRF-TOKEN": document.querySelector('input[name="<?= csrf_token() ?>"]').value
                        },
                        body: formData
                    });

                    const data = await response.json();
                    if (data.status === 'success' || data.success) {
                        alert("Return processed successfully: " + (data.message || ''));
                        returnForm.reset();
                        bootstrap.Modal.getInstance(document.getElementById("returnModal")).hide();
                    } else {
                        alert("Error: " + (data.message || 'Failed'));
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Process Return';
                    }
                } catch (err) {
                    console.error(err);
                    alert("A network error occurred. Please check console.");
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Process Return';
                }
            });
        }

        // POS TUTORIAL FUNCTIONALITY
        const posTutorial = document.getElementById('posTutorial');
        const closeTutorial = document.getElementById('closeTutorial');
        const showTutorialBtn = document.getElementById('showTutorialBtn');
        const dontShowCheckbox = document.getElementById('dontShowTutorialAgain');
        
        function showTutorial() {
            posTutorial.classList.add('show');
            if (dontShowCheckbox) {
                dontShowCheckbox.checked = false; // Reset checkbox state
            }
        }
        
        closeTutorial.addEventListener('click', () => {
            if (dontShowCheckbox && dontShowCheckbox.checked) {
                localStorage.setItem('hidePosTutorial', 'true');
            }
            posTutorial.classList.remove('show');
        });

        if (showTutorialBtn) {
            showTutorialBtn.addEventListener('click', () => {
                showTutorial();
            });
        }
        
        if (localStorage.getItem('hidePosTutorial') !== 'true') {
            showTutorial();
        }

        // POS Functionality
        const cartItems = [];
        const cartContainer = document.getElementById('main-cart-items');
        const cartTotal = document.getElementById('cart-total');
        let selectedPaymentMethod = 'cash';

        // --- SHOPEE MODAL CONTROLLER ---
        let currentModalItem = null;
        
        window.openShopeeModal = function(card) {
            if (card.classList.contains('out-of-stock')) return;

            const name = card.dataset.name;
            const type = card.dataset.type;
            const productId = parseInt(card.dataset.productId);
            const stock = parseInt(card.dataset.stock || "0");
            const image = card.dataset.image;
            const expr = card.dataset.expr;
            
            // Populate basic DOM
            document.getElementById('smImage').src = image;
            document.getElementById('smTitle').textContent = name;
            document.getElementById('smStock').textContent = stock + " left";
            
            if (expr && expr.trim() !== "") {
                document.getElementById('smExprRow').style.display = 'flex';
                document.getElementById('smExpr').textContent = expr;
            } else {
                document.getElementById('smExprRow').style.display = 'none';
            }

            document.getElementById('smQtyInput').value = 1;

            currentModalItem = {
                name,
                type,
                productId,
                stock,
                pack: null,
                price: 0,
                packSize: 1
            };

            const varRow = document.getElementById('smVariationRow');
            const varContainer = document.getElementById('smVariations');
            varContainer.innerHTML = '';

            if (type === 'siomai') {
                document.getElementById('smStockLabel').textContent = "Left";
                varRow.style.display = 'flex';
                const prices = JSON.parse(card.dataset.prices);
                const packStocks = JSON.parse(card.dataset.packstocks);
                let first = true;
                
                for (const [pack, price] of Object.entries(prices)) {
                    const btn = document.createElement('button');
                    btn.textContent = pack;
                    
                    const localStock = packStocks[pack] || 0;
                    if(localStock <= 0) {
                        btn.style.opacity = '0.5';
                        btn.style.cursor = 'not-allowed';
                        btn.disabled = true;
                    }

                    function updatePiecesDisplay(p) {
                        document.getElementById('smPiecesRow').style.display = 'flex';
                        if (p === 'Small Pack') document.getElementById('smPieces').textContent = '12 pcs';
                        else if (p === 'Medium Pack') document.getElementById('smPieces').textContent = '20 pcs';
                        else if (p === 'Large Pack') document.getElementById('smPieces').textContent = '40 pcs';
                        else document.getElementById('smPieces').textContent = '';
                    }

                    if (first && localStock > 0) {
                        btn.classList.add('active');
                        currentModalItem.pack = pack;
                        currentModalItem.price = parseFloat(price);
                        currentModalItem.stock = parseInt(localStock);
                        currentModalItem.packSize = 1;
                        document.getElementById('smStock').textContent = localStock + " packs";
                        updatePiecesDisplay(pack);
                        first = false;
                    }
                    
                    btn.onclick = () => {
                        varContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        currentModalItem.pack = pack;
                        currentModalItem.price = parseFloat(price);
                        currentModalItem.stock = parseInt(localStock);
                        currentModalItem.packSize = 1;
                        document.getElementById('smStock').textContent = localStock + " packs";
                        document.getElementById('smQtyInput').value = 1; // reset validation
                        updatePiecesDisplay(pack);
                        updateSmPriceDisplay();
                    };
                    varContainer.appendChild(btn);
                }
                
                if (first) {
                    document.getElementById('smStock').textContent = "0 packs";
                    currentModalItem.stock = 0;
                    document.getElementById('smPiecesRow').style.display = 'none';
                }
            } else if (type === 'custom_variation') {
                document.getElementById('smStockLabel').textContent = "Left";
                varRow.style.display = 'flex';
                
                const customVars = JSON.parse(card.dataset.variations || '[]');
                let first = true;
                
                for (const v of customVars) {
                    const btn = document.createElement('button');
                    btn.textContent = v.label;
                    
                    if(v.stock <= 0) {
                        btn.style.opacity = '0.5';
                        btn.style.cursor = 'not-allowed';
                        btn.disabled = true;
                    }
                    
                    if (first && v.stock > 0) {
                        btn.classList.add('active');
                        currentModalItem.pack = v.label;
                        currentModalItem.price = parseFloat(v.price);
                        currentModalItem.stock = parseInt(v.stock);
                        currentModalItem.packSize = 1;
                        currentModalItem.productId = parseInt(v.id);
                        document.getElementById('smStock').textContent = v.stock + " left";
                        first = false;
                    }
                    
                    btn.onclick = () => {
                        varContainer.querySelectorAll('button').forEach(b => b.classList.remove('active'));
                        btn.classList.add('active');
                        currentModalItem.pack = v.label;
                        currentModalItem.price = parseFloat(v.price);
                        currentModalItem.stock = parseInt(v.stock);
                        currentModalItem.packSize = 1;
                        currentModalItem.productId = parseInt(v.id);
                        document.getElementById('smStock').textContent = v.stock + " left";
                        document.getElementById('smQtyInput').value = 1;
                        updateSmPriceDisplay();
                    };
                    varContainer.appendChild(btn);
                }
                
                if (first) {
                    document.getElementById('smStock').textContent = "0 left";
                    currentModalItem.stock = 0;
                }
                document.getElementById('smPiecesRow').style.display = 'none';
            } else {
                document.getElementById('smStockLabel').textContent = "Left";
                varRow.style.display = 'none';
                
                currentModalItem.price = parseFloat(card.dataset.price);
                if (type === 'patty') {
                    currentModalItem.packSize = 1;
                    document.getElementById('smPiecesRow').style.display = 'flex';
                    document.getElementById('smPieces').textContent = '6 pcs';
                } else {
                    document.getElementById('smPiecesRow').style.display = 'none';
                }
            }

            updateSmPriceDisplay();
            
            // Show modal smoothly
            const overlay = document.getElementById('shopeeModalOverlay');
            overlay.style.display = 'flex';
            // Force reflow
            void overlay.offsetWidth;
            overlay.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        window.closeShopeeModal = function() {
            const overlay = document.getElementById('shopeeModalOverlay');
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }, 300);
        }

        window.updateSmPriceDisplay = function() {
            if (currentModalItem) {
                document.getElementById('smPrice').textContent = currentModalItem.price.toFixed(2);
            }
        }

        window.changeSmQty = function(delta) {
            const input = document.getElementById('smQtyInput');
            let val = parseInt(input.value) + delta;
            if (val < 1) val = 1;
            
            // Limit to stock (using logic based on pack sizes)
            // If they order 2 qty of 20pcs, it takes 40 underlying stock. 
            // So we check if (val * packSize) > stock
            const requestedPcs = val * currentModalItem.packSize;
            if (requestedPcs > currentModalItem.stock) {
                // Flash stock limit
                val = val - 1; 
            }
            input.value = val;
        }

        window.handleSmQtyChange = function() {
            const input = document.getElementById('smQtyInput');
            let val = parseInt(input.value);
            if (isNaN(val) || val < 1) {
                val = 1;
            }
            
            const requestedPcs = val * currentModalItem.packSize;
            if (requestedPcs > currentModalItem.stock) {
                val = Math.floor(currentModalItem.stock / currentModalItem.packSize);
                if (val < 1) val = 1;
                showPosToast('Maximum stock reached');
            }
            input.value = val;
        }

        window.handleSmQtyChange = function() {
            const input = document.getElementById('smQtyInput');
            let val = parseInt(input.value);
            if (isNaN(val) || val < 1) {
                val = 1;
            }
            
            const requestedPcs = val * currentModalItem.packSize;
            if (requestedPcs > currentModalItem.stock) {
                val = Math.floor(currentModalItem.stock / currentModalItem.packSize);
                if (val < 1) val = 1;
                showPosToast('Maximum stock reached');
            }
            input.value = val;
        }

        window.smAddToCart = function() {
            if (!currentModalItem) return;
            const qty = parseInt(document.getElementById('smQtyInput').value);
            
            // Validate stock
            const totalRequestedPcs = qty * currentModalItem.packSize;
            
            // Count existing cart pieces for this product
            let currentCartPcs = 0;
            cartItems.forEach(item => {
                if (item.product_id === currentModalItem.productId) {
                    currentCartPcs += (item.qty * item.packSize);
                }
            });

            if (currentCartPcs + totalRequestedPcs > currentModalItem.stock) {
                alert("Not enough stock available for this selection.");
                return;
            }

            const existingIndex = cartItems.findIndex(i => i.name === currentModalItem.name && i.pack === currentModalItem.pack);
            let highlightIdx = 0;
            if (existingIndex >= 0) {
                const existingItem = cartItems.splice(existingIndex, 1)[0];
                existingItem.qty += qty;
                cartItems.unshift(existingItem);
            } else {
                cartItems.unshift({
                    name: currentModalItem.name,
                    pack: currentModalItem.pack,
                    price: currentModalItem.price,
                    qty: qty,
                    product_id: currentModalItem.productId,
                    type: currentModalItem.type,
                    packSize: currentModalItem.packSize
                });
            }

            updateCart(highlightIdx);
            closeShopeeModal();
            showPosToast(`Added ${qty} × ${currentModalItem.name} to cart!`);
        }

        window.showPosToast = function(message) {
            let toast = document.getElementById("posToast");
            if (!toast) {
                toast = document.createElement("div");
                toast.id = "posToast";
                toast.className = "pos-toast";
                document.body.appendChild(toast);
            }
            toast.innerHTML = `<i class="bi bi-check-circle-fill"></i> ${message}`;
            toast.classList.add("show");
            setTimeout(function(){ toast.classList.remove("show"); }, 2500);
        }

        function updateCart(highlightIndex = -1) {
            cartContainer.innerHTML = '';
            if (cartItems.length === 0) {
                cartContainer.innerHTML = '<p class="text-muted">No items added.</p>';
                cartTotal.textContent = "₱0.00";
                return;
            }
            
            cartItems.forEach((item, index) => {
                const div = document.createElement('div');
                div.classList.add('cart-item');
                if (index === highlightIndex) {
                    div.classList.add('highlight-flash');
                }
                let displayName = item.name;
                if (item.pack) {
                    displayName += ` (${item.pack})`;
                }
                div.innerHTML = `
                    <span class="item-name">${displayName} × ${item.qty}</span>
                    <div class="quantity-control">
                        <button class="qty-decrease" data-index="${index}">-</button>
                        <input type="number" class="cart-qty-input" data-index="${index}" value="${item.qty}" min="1" style="width: 40px; text-align: center; border: 1px solid #ddd; border-radius: 4px; border-left: none; border-right: none; height: 28px; outline: none;">
                        <button class="qty-increase" data-index="${index}">+</button>
                    </div>
                    <span class="item-price">₱${(item.qty * item.price).toFixed(2)}</span>
                `;
                cartContainer.appendChild(div);
            });

            document.querySelectorAll('.cart-qty-input').forEach(input => {
                input.addEventListener('change', (e) => {
                    const idx = parseInt(e.target.dataset.index);
                    let val = parseInt(e.target.value);
                    if (isNaN(val) || val < 1) val = 1;
                    
                    const item = cartItems[idx];
                    // Verify stock
                    let currentOtherCartPcs = 0;
                    cartItems.forEach((i, currentIdx) => {
                        if (i.product_id === item.product_id && currentIdx !== idx) {
                            currentOtherCartPcs += (i.qty * i.packSize);
                        }
                    });
                    
                    // We need the total stock from the UI or dataset, but since we don't have it directly in the cart item,
                    // we'll just let them set it. Ideally we should validate, but for now we update.
                    item.qty = val;
                    updateCart(idx);
                });
            });
            document.querySelectorAll('.qty-increase').forEach(btn => {
                btn.addEventListener('click', () => {
                    cartItems[btn.dataset.index].qty++;
                    updateCart(parseInt(btn.dataset.index));
                });
            });
            document.querySelectorAll('.qty-decrease').forEach(btn => {
                const i = parseInt(btn.dataset.index);
                btn.addEventListener('click', () => {
                    cartItems[i].qty--;
                    if (cartItems[i].qty <= 0) cartItems.splice(i, 1);
                    updateCart();
                });
            });

            const total = cartItems.reduce((sum, i) => sum + i.price * i.qty, 0);
            cartTotal.textContent = `₱${total.toFixed(2)}`;
        }

        // Payment method selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', () => {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
                selectedPaymentMethod = option.dataset.method;
            });
        });

        // Clear cart
        document.getElementById('clear-cart').addEventListener('click', () => {
            if (cartItems.length > 0 && confirm("Clear all items?")) {
                cartItems.length = 0;
                updateCart();
            }
        });

        document.getElementById('checkout-btn').addEventListener('click', async () => {
            if (selectedPaymentMethod === 'card' || selectedPaymentMethod === 'gcash') {
                alert("This Payment Method is Currently Unsupported at this Moment");
                return;
            }

            if (cartItems.length === 0) {
                alert("Cart is empty!");
                return;
            }

            // Populate checkout modal
            const tbody = document.getElementById('checkout-summary-body');
            tbody.innerHTML = '';
            cartItems.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="text-dark fw-medium">${item.name} ${item.pack ? `<br><small class="text-muted fw-normal">${item.pack}</small>` : ''}</td>
                    <td class="text-center">${item.qty}</td>
                    <td class="text-end">₱${item.price.toFixed(2)}</td>
                    <td class="text-end fw-bold text-dark">₱${(item.price * item.qty).toFixed(2)}</td>
                `;
                tbody.appendChild(tr);
            });
            document.getElementById('checkout-grand-total').textContent = cartTotal.textContent;
            
            // VAT Logic Reset
            const applyVatTax = document.getElementById('applyVatTax');
            const vatBlock = document.getElementById('vatComputationBlock');
            const vatTypeBlock = document.getElementById('vatTypeBlock');
            const vatIncludedRadio = document.getElementById('vatIncluded');
            const vatExcludedRadio = document.getElementById('vatExcluded');

            applyVatTax.checked = false;
            vatIncludedRadio.checked = true;
            vatBlock.style.display = 'none';
            vatTypeBlock.style.display = 'none';

            function updateVatCalculation() {
                if (applyVatTax.checked) {
                    const rawTotal = cartItems.reduce((sum, item) => sum + (item.price * item.qty), 0);
                    let vatableSales, vat, grandTotal;

                    if (vatIncludedRadio.checked) {
                        vatableSales = rawTotal / 1.12;
                        vat = rawTotal - vatableSales;
                        grandTotal = rawTotal;
                        document.getElementById('vatTaxLabel').textContent = "12% VAT (Included):";
                    } else {
                        vatableSales = rawTotal;
                        vat = rawTotal * 0.12;
                        grandTotal = rawTotal + vat;
                        document.getElementById('vatTaxLabel').textContent = "12% VAT (Added):";
                    }
                    
                    document.getElementById('vatableSalesAmount').textContent = `₱${vatableSales.toFixed(2)}`;
                    document.getElementById('vatTaxAmount').textContent = `₱${vat.toFixed(2)}`;
                    document.getElementById('finalAmountWithVat').textContent = `₱${grandTotal.toFixed(2)}`;
                    
                    vatBlock.style.display = 'block';
                    vatTypeBlock.style.display = 'block';
                } else {
                    vatBlock.style.display = 'none';
                    vatTypeBlock.style.display = 'none';
                }
            }

            applyVatTax.onchange = updateVatCalculation;
            vatIncludedRadio.onchange = updateVatCalculation;
            vatExcludedRadio.onchange = updateVatCalculation;
            
            // Clear previous inputs
            document.getElementById('checkoutCustomerName').value = pendingOnlineCustomerName || '';
            document.getElementById('checkoutCustomerEmail').value = pendingOnlineCustomerEmail || '';

            // Show modal
            new bootstrap.Modal(document.getElementById('checkoutReviewModal')).show();
        });

        // ✅ Handle confirm from inside the modal
        document.getElementById('confirm-sale-btn').addEventListener('click', async () => {
            const customerName = document.getElementById('checkoutCustomerName').value.trim();
            const customerEmail = document.getElementById('checkoutCustomerEmail').value.trim();
            const applyVatTax = document.getElementById('applyVatTax');
            const vatIncludedRadio = document.getElementById('vatIncluded');

            const submitBtn = document.getElementById('confirm-sale-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch('<?= site_url("staff/pos/sell") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ 
                        cart: cartItems, 
                        payment_method: selectedPaymentMethod,
                        customer_name: customerName,
                        customer_email: customerEmail,
                        apply_vat: applyVatTax.checked,
                        vat_type: vatIncludedRadio.checked ? 'included' : 'excluded'
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showNotification('success', `Sale completed! Total: ₱${data.total.toFixed(2)} (${selectedPaymentMethod.toUpperCase()})`);
                    cartItems.length = 0;
                    updateCart();
                    
                    // Hide Modal
                    const modalEl = document.getElementById('checkoutReviewModal');
                    const modalInst = bootstrap.Modal.getInstance(modalEl);
                    if (modalInst) modalInst.hide();
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || "Failed to complete sale");
                }
            } catch (err) {
                console.error(err);
                showNotification('error', err.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Confirm & Pay';
            }
        });

        // Notifications
        function showNotification(type, message) {
            const alert = document.createElement('div');
            alert.className = `notification-alert ${type}-alert show`;
            const icon = type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
            alert.innerHTML = `
                <i class="bi ${icon}"></i>
                <span>${message}</span>
                <button class="close-alert">&times;</button>
            `;
            document.body.appendChild(alert);
            alert.querySelector('.close-alert').addEventListener('click', () => alert.remove());
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 400);
            }, 5000);
        }

        // Auto-show existing alerts
        document.querySelectorAll('.notification-alert').forEach(alert => {
            setTimeout(() => alert.classList.add('show'), 100);
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 400);
            }, 5000);
            alert.querySelector('.close-alert')?.addEventListener('click', () => alert.remove());
        });

        // Online Orders Logic
        let pendingOnlineOrders = [];
        let pendingOnlineCustomerName = '';
        let pendingOnlineCustomerEmail = '';

        function fetchOnlineOrders() {
            fetch('<?= site_url("api/pending-orders") ?>')
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        pendingOnlineOrders = data.data;
                        renderOnlineOrders();
                    }
                })
                .catch(err => console.error('Error fetching online orders:', err));
        }

        function renderOnlineOrders() {
            const list = document.getElementById('online-orders-list');
            if(pendingOnlineOrders.length === 0) {
                list.innerHTML = '<p class="text-muted">No online orders yet.</p>';
                return;
            }

            let html = '';
            pendingOnlineOrders.forEach((order, index) => {
                html += `
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <div>
                            <a href="#" class="fw-bold text-primary text-decoration-none" onclick="viewOnlineOrder(${index}); return false;">${order.order_id}</a>
                            <div class="text-muted" style="font-size: 0.85rem;">${order.customer_name}</div>
                        </div>
                        <span class="badge bg-warning text-dark">Pending</span>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        window.viewOnlineOrder = function(index) {
            const order = pendingOnlineOrders[index];
            document.getElementById('onlineOrderIdTitle').textContent = order.order_id;
            document.getElementById('ooCustomerName').textContent = order.customer_name;
            document.getElementById('ooCustomerPhone').textContent = order.customer_phone;
            document.getElementById('ooCustomerEmail').textContent = order.customer_email;
            document.getElementById('ooGrandTotal').textContent = '₱' + parseFloat(order.total_amount).toFixed(2);

            const itemsList = document.getElementById('ooItemsList');
            let itemsHtml = '';
            order.items.forEach(item => {
                let name = item.product_name;
                if (item.variation) name += ` <small class="text-muted">(${item.variation})</small>`;
                itemsHtml += `
                    <tr>
                        <td>${name}</td>
                        <td>x${item.quantity}</td>
                        <td class="text-end">₱${parseFloat(item.subtotal).toFixed(2)}</td>
                    </tr>
                `;
            });
            itemsList.innerHTML = itemsHtml;

            const modal = new bootstrap.Modal(document.getElementById('onlineOrderModal'));
            modal.show();
        };

        window.confirmOnlineOrder = function() {
            const orderId = document.getElementById('onlineOrderIdTitle').textContent;
            if (!orderId) return;
            
            if (!confirm(`Are you sure you want to confirm Order ${orderId}?`)) {
                return;
            }

            const btn = document.getElementById('btnConfirmOnlineOrderStaff');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Confirming...';
            btn.disabled = true;

            fetch('<?= site_url("api/confirm-order") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ order_id: orderId })
            })
            .then(res => res.json())
            .then(data => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                
                if (data.status === 'success') {
                    // Populate cart with online order items
                    const order = pendingOnlineOrders.find(o => o.order_id === orderId);
                    if (order) {
                        cartItems.length = 0; // Clear existing cart
                        order.items.forEach(item => {
                            cartItems.push({
                                name: item.product_name,
                                pack: item.variation,
                                price: parseFloat(item.price),
                                qty: parseInt(item.quantity),
                                product_id: parseInt(item.product_id),
                                type: item.variation ? 'siomai' : 'other',
                                packSize: 1
                            });
                        });
                        updateCart();
                        
                        // Save customer details for when staff manually clicks Checkout
                        pendingOnlineCustomerName = order.customer_name;
                        pendingOnlineCustomerEmail = order.customer_email;
                    }

                    const modal = bootstrap.Modal.getInstance(document.getElementById('onlineOrderModal'));
                    modal.hide();
                    fetchOnlineOrders(); // Refresh the list
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(err => {
                console.error('Error confirming order:', err);
                alert('Failed to confirm order. Please try again.');
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        };

        // Fetch every 10 seconds
        setInterval(fetchOnlineOrders, 10000);
        fetchOnlineOrders(); // Initial fetch
    });
    </script>
<script src="<?= base_url('js/table-pagination.js') ?>"></script>
</body>
</html>
