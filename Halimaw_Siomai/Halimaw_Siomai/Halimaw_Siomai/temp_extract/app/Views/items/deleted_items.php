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
            padding: 30px 20px;
        }

        /* BUTTONS */
        .btn-back {
            display: inline-flex;
            align-items: center;
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.2s;
            margin-bottom: 20px;
        }
        .btn-back:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* FILTER CARD */
        .filter-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 25px;
        }

        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: center;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
            min-width: 160px;
        }

        .filter-group label {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--dark);
        }

        .search-container {
            display: flex;
            gap: 10px;
            flex: 1;
            min-width: 250px;
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
            background: var(--success);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.2s;
        }
        .search-button:hover {
            background: #17a673;
            transform: translateY(-2px);
        }

        /* TABLE CARD */
        .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
        }

        /* TABLE */
        .table {
            min-width: 900px;
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

        /* MEDIA QUERIES */
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
            .filter-row { flex-direction: column; gap: 15px; }
            .search-container { flex-direction: column; }
            .search-button, #searchQuery { width: 100%; }

            /* Hide less important columns */
            .table th:nth-child(n+9):not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)):not(:nth-child(4)),
            .table td:nth-child(n+9):not(:nth-child(1)):not(:nth-child(2)):not(:nth-child(3)):not(:nth-child(4)) {
                display: none;
            }
            .table { min-width: 600px; }
        }
    </style>
</head>
<body>

<?php $currentPath = uri_string(); ?>

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="container">
        <!-- Flash Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Back Button -->
        <a href="<?= base_url('/items') ?>" class="btn-back">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>

        <?php if (!empty($items) || !empty($deletedItems)): ?>
            <!-- Filter Card -->
            <div class="filter-card">
                <div class="filter-row">
                    <div class="search-container">
                        <input type="text" id="searchQuery" class="form-control" placeholder="Search by Product ID or Name" oninput="filterAndSort()">
                        <button onclick="filterAndSort()" class="search-button">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                    <div class="filter-group">
                        <label for="categoryFilter">Category</label>
                        <select id="categoryFilter" class="form-select" onchange="filterAndSort()">
                            <option value="all">All Categories</option>
                            <option value="food">Food</option>
                            <option value="non-food">Non-Food</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="sortFilter">Sort By</label>
                        <select id="sortFilter" class="form-select" onchange="filterAndSort()">
                            <option value="deleted_desc">Latest Deleted</option>
                            <option value="deleted_asc">Oldest Deleted</option>
                            <option value="name_asc">Name (A–Z)</option>
                            <option value="name_desc">Name (Z–A)</option>
                            <option value="quantity_asc">Quantity (Low → High)</option>
                            <option value="quantity_desc">Quantity (High → Low)</option>
                            <option value="date_asc">Date (Oldest → Newest)</option>
                            <option value="date_desc">Date (Newest → Oldest)</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="deleteTypeFilter">Delete Type</label>
                        <select id="deleteTypeFilter" class="form-select" onchange="filterAndSort()">
                            <option value="all">All Delete Types</option>
                            <option value="auto">Auto Deleted</option>
                            <option value="manual">Manually Deleted</option>
                        </select>
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
                                <th>Barcode</th>
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
                                    <td><?= esc($item['barcode'] ?? '-') ?></td>
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
            const type = row.children[8].textContent.toLowerCase();
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
                case 'deleted_asc': return new Date(getVal(a, 11) || 0) - new Date(getVal(b, 11) || 0);
                case 'deleted_desc':
                default:
                    const typeA = getVal(a, 8), typeB = getVal(b, 8);
                    const dateA = new Date(getVal(a, 11) || 0), dateB = new Date(getVal(b, 11) || 0);
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