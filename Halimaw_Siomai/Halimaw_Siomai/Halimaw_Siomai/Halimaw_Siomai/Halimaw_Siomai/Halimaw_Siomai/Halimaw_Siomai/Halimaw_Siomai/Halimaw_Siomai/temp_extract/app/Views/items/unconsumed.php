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
    <!-- TOP NAVBAR WITH USER PROFILE REMOVED -->
    <div class="top-navbar" style="display: none;">
        <h5></h5>
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
        <h2 class="text-center mb-4" style="font-weight: 700; color: var(--dark);"><i class="bi bi-cart-x me-2"></i>Unconsumed Products History</h2>
        
        <div class="table-responsive-custom mt-4 p-3 bg-white">
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
</div> <!-- End Main Content -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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