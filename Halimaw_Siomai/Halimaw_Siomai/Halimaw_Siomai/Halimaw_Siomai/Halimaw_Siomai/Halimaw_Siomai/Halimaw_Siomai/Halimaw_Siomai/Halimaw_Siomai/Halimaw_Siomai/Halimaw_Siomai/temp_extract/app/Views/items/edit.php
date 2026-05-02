<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Edit Item | Halimaw Siomai</title>
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
            max-width: 780px;
            padding: 30px 20px;
        }

        /* CARD */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 30px;
        }

        .form-label {
            font-weight: 500;
            margin-bottom: 6px;
            color: var(--dark);
        }

        .form-control, .form-select {
            font-size: 0.95rem;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            padding: 8px 12px;
            margin-bottom: 16px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            outline: none;
        }

        .form-check {
            margin-bottom: 20px;
        }

        /* BUTTONS */
        .btn-pill {
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
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
        .btn-update {
            background: var(--success);
            color: white;
        }
        .btn-update:hover {
            background: #17a673;
            transform: translateY(-2px);
        }

        /* ALERTS */
        .alert {
            border-radius: var(--border-radius);
            font-weight: 500;
            padding: 12px 16px;
            margin-bottom: 20px;
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
            .card { padding: 20px; }
            .btn-pill { width: 100%; }
            .d-flex.justify-content-between { flex-direction: column; gap: 10px; }
        }
    </style>
</head>
<body>

<?php $currentPath = uri_string(); ?>

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
            <a class="nav-link <?= ($currentPath === 'items' || $currentPath === '') ? 'active' : '' ?>" href="<?= site_url('items') ?>">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === 'admin/pos') ? 'active' : '' ?>" href="<?= site_url('admin/pos') ?>">
                <i class="bi bi-calculator"></i> POS
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === 'admin/stock-requests') ? 'active' : '' ?>" href="<?= site_url('admin/stock-requests') ?>">
                <i class="bi bi-cart-check"></i> Stock Requests
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === 'items/expiring-soon') ? 'active' : '' ?>" href="<?= site_url('items/expiring-soon') ?>">
                <i class="bi bi-clock-history"></i> Expiring Soon
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === 'items/deleted') ? 'active' : '' ?>" href="<?= site_url('items/deleted') ?>">
                <i class="bi bi-x-circle"></i> Expired
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === 'admin/users') ? 'active' : '' ?>" href="<?= site_url('admin/users') ?>">
                <i class="bi bi-people"></i> Staff Management
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= ($currentPath === 'items/logs') ? 'active' : '' ?>" href="<?= site_url('items/logs') ?>">
                <i class="bi bi-journal-text"></i> Audit Logs
            </a>
        </li>
        <li class="nav-item mt-4">
            <a class="nav-link text-danger" href="<?= site_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</nav>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="container">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Edit Form -->
        <form action="<?= base_url('items/update/' . $item['id']) ?>" method="post" class="card">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="product_id" class="form-label">Product ID</label>
                <input type="text" class="form-control" id="product_id" name="product_id"
                    value="<?= esc($item['product_id']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= esc($item['name']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                    value="<?= esc($item['quantity']) ?>" required>
            </div>

            <!-- SIOMAI PACK INVENTORY AND PRICING -->
            <div class="card bg-light mb-3 p-3 border-0">
                <h6 class="text-primary fw-bold mb-3">Siomai Packs (Optional Override)</h6>
                <div class="row">
                    <div class="col-6 mb-2">
                        <label class="form-label" style="font-size: 0.85rem;">Small Pack Qty</label>
                        <input type="number" class="form-control" name="pack_small_qty" value="<?= esc($item['pack_small_qty'] ?? 0) ?>">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label" style="font-size: 0.85rem;">Small Pack Price</label>
                        <input type="number" step="0.01" class="form-control" name="pack_small_price" value="<?= esc($item['pack_small_price'] ?? 115) ?>">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label" style="font-size: 0.85rem;">Medium Pack Qty</label>
                        <input type="number" class="form-control" name="pack_medium_qty" value="<?= esc($item['pack_medium_qty'] ?? 0) ?>">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label" style="font-size: 0.85rem;">Medium Pack Price</label>
                        <input type="number" step="0.01" class="form-control" name="pack_medium_price" value="<?= esc($item['pack_medium_price'] ?? 185) ?>">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label" style="font-size: 0.85rem;">Biggest Pack Qty</label>
                        <input type="number" class="form-control" name="pack_biggest_qty" value="<?= esc($item['pack_biggest_qty'] ?? 0) ?>">
                    </div>
                    <div class="col-6 mb-2">
                        <label class="form-label" style="font-size: 0.85rem;">Biggest Pack Price</label>
                        <input type="number" step="0.01" class="form-control" name="pack_biggest_price" value="<?= esc($item['pack_biggest_price'] ?? 335) ?>">
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">General Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price"
                    value="<?= esc($item['price']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date"
                    value="<?= esc($item['expiration_date']) ?>">
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Food" <?= ($item['category'] === 'Food') ? 'selected' : '' ?>>Food</option>
                    <option value="Non-Food" <?= ($item['category'] === 'Non-Food') ? 'selected' : '' ?>>Non-Food</option>
                </select>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="auto_delete" name="auto_delete" value="1"
                    <?= $item['auto_delete'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="auto_delete">Auto Delete When Expired</label>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <div>
                    <a href="<?= base_url('items') ?>" class="btn btn-secondary btn-pill me-2"><i class="bi bi-arrow-left me-1"></i> Back</a>
                    <a href="<?= base_url('items/delete/' . $item['id']) ?>" class="btn btn-danger btn-pill" onclick="return confirm('Are you sure you want to completely delete this item? This action will move it to the Trash.');">
                        <i class="bi bi-trash me-1"></i> Delete
                    </a>
                </div>
                <button type="submit" class="btn btn-update btn-pill"><i class="bi bi-save me-1"></i> Update Item</button>
            </div>
        </form>
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

    // Auto-hide alerts
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 3000);
    });
});
</script>
</body>
</html>