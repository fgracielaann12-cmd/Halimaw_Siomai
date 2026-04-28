<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Expired Items | Halimaw Siomai</title>
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
            --border-radius: 0.65rem;
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
            width: 44px;
            height: 44px;
            border-radius: 6px;
            background-color: #f0f2f5;
            padding: 2px;
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

        /* TOP NAVBAR */
        .top-navbar {
            background: white;
            padding: 12px 20px;
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

        /* CONTAINER */
        .container {
            padding: 30px 20px;
        }


        /* FILTER CARD */
        .filter-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 25px;
        }

        #searchQuery {
            flex: 1;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            font-size: 0.95rem;
        }
        #searchQuery:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            outline: none;
        }

        /* TABLE CARD */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            padding: 0;
            margin-bottom: 24px;
        }

        /* 🔽 Dropdown Slide Animation */
        @keyframes slideDownFade {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .dropdown-menu.show {
            animation: slideDownFade 0.2s ease-out forwards;
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: var(--primary);
            color: white;
        }

        /* TABLE */
        .table {
            width: 100%;
            font-size: 0.8rem;
            margin: 0;
        }
        .table th, .table td {
            white-space: nowrap;
            padding: 0.5rem 0.5rem;
        }

        /* Custom Table Scrollbar */
        .table-responsive::-webkit-scrollbar {
            height: 8px;
        }
        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
            margin: 0 10px;
        }
        .table-responsive::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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

        /* MEDIA QUERIES */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            .mobile-menu-toggle-inline { display: flex; }
            body > #mobileMenuToggle { display: none !important; }
            .top-navbar {
                border-radius: 0 !important;
                margin: 0 0 15px 0 !important;
            }
            #sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; padding-top: 0 !important; }
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
            #searchQuery { width: 100%; }
            .table { 
                min-width: 800px; 
                font-size: 0.9rem !important; 
            }
            .table th, .table td {
                padding: 0.75rem 0.5rem !important;
            }
        }
    </style>
</head>
<body>

<?php $currentPath = uri_string(); ?>

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <!-- TOP NAVBAR MATCHING STAFF POS/DASHBOARD -->
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="mobile-menu-toggle-inline d-lg-none" id="mobileMenuToggleInline">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0"><i class="bi bi-trash-fill me-2" style="font-size: 1.25rem;"></i>Expired</h5>
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


        <?php if (!empty($items) || !empty($deletedItems)): ?>
            <!-- Filter Card -->
            <div class="filter-card">
                <div class="filter-row d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-3 w-100">
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:2.7;">
                        <label class="form-label mb-0 fw-bold text-nowrap">Search Item:</label>
                        <div class="position-relative w-100">
                            <input type="text" id="searchQuery" class="form-control" style="padding-right: 2.2rem;" placeholder="Search by Product ID or Name" oninput="filterAndSort()">
                            <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3" style="color: #6c757d; opacity: 0.6; pointer-events: none;"></i>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                        <label class="form-label mb-0 fw-bold text-nowrap">Category:</label>
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center bg-white text-dark" type="button" id="categoryFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                                <span id="categoryFilterText">All Categories</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="categoryFilterBtn">
                                <li><a class="dropdown-item active" href="#" onclick="selectDropdown('categoryFilter', 'all', 'All Categories', event)">All Categories</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('categoryFilter', 'food', 'Food', event)">Food</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('categoryFilter', 'non-food', 'Non-Food', event)">Non-Food</a></li>
                            </ul>
                            <input type="hidden" id="categoryFilter" value="all">
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                        <label class="form-label mb-0 fw-bold text-nowrap">Sort By:</label>
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center bg-white text-dark" type="button" id="sortFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                                <span id="sortFilterText">Latest Deleted</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="sortFilterBtn">
                                <li><a class="dropdown-item active" href="#" onclick="selectDropdown('sortFilter', 'deleted_desc', 'Latest Deleted', event)">Latest Deleted</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'deleted_asc', 'Oldest Deleted', event)">Oldest Deleted</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'name_asc', 'Name (A–Z)', event)">Name (A–Z)</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'name_desc', 'Name (Z–A)', event)">Name (Z–A)</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'quantity_asc', 'Quantity (Low → High)', event)">Quantity (Low → High)</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'quantity_desc', 'Quantity (High → Low)', event)">Quantity (High → Low)</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'date_asc', 'Date (Oldest → Newest)', event)">Date (Oldest → Newest)</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('sortFilter', 'date_desc', 'Date (Newest → Oldest)', event)">Date (Newest → Oldest)</a></li>
                            </ul>
                            <input type="hidden" id="sortFilter" value="deleted_desc">
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                        <label class="form-label mb-0 fw-bold text-nowrap">Delete Type:</label>
                        <div class="dropdown w-100">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center bg-white text-dark" type="button" id="deleteTypeFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" style="border: 1px solid #dee2e6; border-radius: 0.375rem; padding: 0.375rem 0.75rem;">
                                <span id="deleteTypeFilterText">All Delete Types</span>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="deleteTypeFilterBtn">
                                <li><a class="dropdown-item active" href="#" onclick="selectDropdown('deleteTypeFilter', 'all', 'All Delete Types', event)">All Delete Types</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('deleteTypeFilter', 'auto', 'Auto Deleted', event)">Auto Deleted</a></li>
                                <li><a class="dropdown-item" href="#" onclick="selectDropdown('deleteTypeFilter', 'manual', 'Manually Deleted', event)">Manually Deleted</a></li>
                            </ul>
                            <input type="hidden" id="deleteTypeFilter" value="all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Expiration Date</th>
                                <th>Days Expired</th>
                                <th>Delete Type</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Deleted At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_merge($items ?? [], $deletedItems ?? []) as $item): ?>
                                <?php
                                $today = new DateTime();
                                $expiration = !empty($item['expiration_date']) ? new DateTime($item['expiration_date']) : null;
                                $deletedAt = !empty($item['deleted_at']) ? new DateTime($item['deleted_at']) : null;
                                $daysExpired = 0;
                                if ($expiration && $today > $expiration)
                                    $daysExpired = $today->diff($expiration)->days;
                                if ($deletedAt && $expiration)
                                    $daysExpired = max(0, floor(($deletedAt->getTimestamp() - $expiration->getTimestamp()) / 86400));
                                $deleteType = strpos(strtolower($item['status'] ?? ''), 'manual') !== false 
                                    ? 'Manually Deleted' 
                                    : (strpos(strtolower($item['status'] ?? ''), 'auto') !== false 
                                        ? 'Auto Deleted' 
                                        : ucfirst($item['status'] ?? 'Unknown'));
                                $deleteBadge = strpos($deleteType, 'Auto') !== false ? 'bg-warning text-dark' : 'bg-secondary';
                                ?>
                                <tr>
                                    <td><?= esc($item['Product_id'] ?? '-') ?></td>
                                    <td class="text-start"><?= esc($item['name'] ?? '-') ?></td>
                                    <td><?= esc($item['category'] ?? '-') ?></td>
                                    <td><?= esc($item['quantity'] ?? '-') ?></td>
                                    <td>₱<?= esc(number_format($item['price'] ?? 0, 2)) ?></td>
                                    <td><?= esc($item['expiration_date'] ?? '-') ?></td>
                                    <td><?= $daysExpired > 0 ? "Expired $daysExpired day" . ($daysExpired == 1 ? '' : 's') . " ago" : '-' ?></td>
                                    <td><span class="badge <?= $deleteBadge ?>"><?= $deleteType ?></span></td>
                                    <td><span class="badge bg-danger">Expired</span></td>
                                    <td><?= esc($item['created_at'] ?? '-') ?></td>
                                    <td><?= esc($item['deleted_at'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center py-4">No expired items found.</div>
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
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
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

    // Helper for Custom Dropdowns
    window.selectDropdown = function(inputId, value, text, event) {
        event.preventDefault();
        document.getElementById(inputId).value = value;
        document.getElementById(inputId + 'Text').textContent = text;
        const menu = event.target.closest('.dropdown-menu');
        menu.querySelectorAll('.dropdown-item').forEach(item => item.classList.remove('active'));
        event.target.classList.add('active');
        filterAndSort();
    };

    // Filter & Sort
    window.filterAndSort = function() {
        const query = (document.getElementById('searchQuery').value || '').toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const sort = document.getElementById('sortFilter').value;
        const deleteType = document.getElementById('deleteTypeFilter').value;
        const rows = Array.from(document.querySelectorAll('.table tbody tr'));

        rows.forEach(row => {
            const product = row.children[0].textContent.toLowerCase();
            const name = row.children[1].textContent.toLowerCase();
            const cat = row.children[2].textContent.toLowerCase();
            const type = row.children[7].textContent.toLowerCase();
            row.style.display = ((product.includes(query) || name.includes(query)) && 
                (category === 'all' || cat === category) && 
                (deleteType === 'all' || 
                 (deleteType === 'auto' && type.includes('auto')) || 
                 (deleteType === 'manual' && type.includes('manual')))) ? '' : 'none';
        });

        const visible = rows.filter(r => r.style.display !== 'none');
        visible.sort((a, b) => {
            const getVal = (r, i) => r.children[i].textContent.trim().toLowerCase();
            switch (sort) {
                case 'name_asc': return getVal(a, 1).localeCompare(getVal(b, 1));
                case 'name_desc': return getVal(b, 1).localeCompare(getVal(a, 1));
                case 'quantity_asc': return parseInt(getVal(a, 3) || 0) - parseInt(getVal(b, 3) || 0);
                case 'quantity_desc': return parseInt(getVal(b, 3) || 0) - parseInt(getVal(a, 3) || 0);
                case 'date_asc': return new Date(getVal(a, 5)) - new Date(getVal(b, 5));
                case 'date_desc': return new Date(getVal(b, 5)) - new Date(getVal(a, 5));
                case 'deleted_asc': return new Date(getVal(a, 10) || 0) - new Date(getVal(b, 10) || 0);
                case 'deleted_desc':
                default:
                    const typeA = getVal(a, 7), typeB = getVal(b, 7);
                    const dateA = new Date(getVal(a, 10) || 0), dateB = new Date(getVal(b, 10) || 0);
                    if (typeA.includes('manual') && !typeB.includes('manual')) return -1;
                    if (!typeA.includes('manual') && typeB.includes('manual')) return 1;
                    return dateB - dateA;
            }
        });
        const tbody = document.querySelector('.table tbody');
        visible.forEach(r => tbody.appendChild(r));
    };

    // Initialize
    document.getElementById('sortFilter').value = 'deleted_desc';
    filterAndSort();
});
</script>
</body>
</html>