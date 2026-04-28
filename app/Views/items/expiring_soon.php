<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Expiring Soon | Halimaw Siomai</title>
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
            transform: translateX(5px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
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

        /* TOP NAVBAR */
        .top-navbar {
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

        /* SEARCH */
        .search-container {
            max-width: 500px;
            margin: 0 auto 25px;
        }
        .search-bar {
            display: flex;
            gap: 10px;
        }
        #searchQuery {
            flex: 1;
            padding: 8px 16px;
            border-radius: 30px;
            border: 1px solid #ddd;
            font-size: 0.95rem;
        }
        #searchQuery:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            outline: none;
        }

        .search-button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .search-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* CARD */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
        }

        /* TABLE */
        .table {
            min-width: 700px;
            font-size: 0.9rem;
            margin: 0;
        }
        .table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
        }
        .table .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 30px;
            font-weight: 500;
        }

        /* ALERTS */
        .alert {
            border-radius: var(--border-radius);
            font-weight: 500;
            text-align: center;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: flex; }
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
            .search-container { padding: 0 10px; }
            .search-bar { flex-direction: column; }
            .search-button, #searchQuery { width: 100%; }
        }
    </style>
</head>
<body>

<?php $currentPath = uri_string(); ?>

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP NAVBAR -->
    <div class="top-navbar" style="padding-left: 20px;">
        <div class="d-flex align-items-center gap-3">
            <button class="mobile-menu-toggle" id="mobileMenuToggleInline">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0"><i class="bi bi-clock-history me-2" style="font-size: 1.25rem;"></i>Expiring soon</h5>
        </div>
    </div>

    <div class="container">
        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Controls -->
        <div class="controls-section d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border p-3 rounded bg-white shadow-sm">
            <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 w-100">
                <label class="form-label mb-0 fw-bold text-nowrap">Search Item:</label>
                <div class="position-relative w-100">
                    <input type="text" id="searchQuery" class="form-control" style="padding-right: 2.2rem;" placeholder="Search by item name" oninput="searchItems()">
                    <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3" style="color: #6c757d; opacity: 0.6; pointer-events: none;"></i>
                </div>
            </div>
        </div>

        <!-- Table -->
        <?php if (!empty($items) && is_array($items)): ?>
            <div class="table-card">
                <div class="table-responsive">
                    <table id="itemsTable" class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Quantity</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Expiration Date</th>
                                <th>Days Left</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item):
                                $today = new DateTime();
                                $expiration = !empty($item['expiration_date']) && $item['expiration_date'] !== '0000-00-00' 
                                    ? new DateTime($item['expiration_date']) 
                                    : null;
                                $daysLeft = $expiration ? (int) $today->diff($expiration)->format('%r%a') : null;

                                if ($daysLeft === null) {
                                    $badgeClass = "bg-secondary";
                                    $statusLabel = "—";
                                } elseif ($daysLeft < 0) {
                                    $badgeClass = "bg-danger";
                                    $statusLabel = "Expired (" . abs($daysLeft) . " days ago)";
                                } elseif ($daysLeft <= 10) {
                                    $badgeClass = "bg-warning text-dark";
                                    $statusLabel = "$daysLeft days left";
                                } else {
                                    $badgeClass = "bg-success";
                                    $statusLabel = "$daysLeft days left";
                                }
                                ?>
                                <tr>
                                    <td><?= esc($item['name']) ?></td>
                                    <td><?= esc($item['quantity']) ?></td>
                                    <td><?= esc($item['category'] ?? '—') ?></td>
                                    <td>₱<?= esc(number_format($item['price'], 2)) ?></td>
                                    <td><?= $expiration ? esc($item['expiration_date']) : '—' ?></td>
                                    <td><?= $daysLeft !== null ? $daysLeft : '—' ?></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= esc($statusLabel) ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center py-4"> No items are expiring soon!</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
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

    // Search
    window.searchItems = function() {
        const query = document.getElementById('searchQuery').value.toLowerCase().trim();
        document.querySelectorAll('#itemsTable tbody tr').forEach(row => {
            const name = row.children[0].textContent.toLowerCase();
            row.style.display = name.includes(query) ? '' : 'none';
        });
    };
});
</script>
</body>
</html>