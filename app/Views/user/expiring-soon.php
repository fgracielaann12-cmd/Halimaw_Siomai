<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Expiring Items</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .expiry-heading {
            margin-bottom: 1.5rem;
            text-align: center;
            color: #000000ff;
            font-weight: 600;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 1.75rem;
            letter-spacing: 0.05em;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            padding-bottom: 0.5rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
            transition: color 0.3s ease;
        }

        .expiry-heading:hover {
            color: #768d87;
            border-color: rgba(37, 34, 27, 1);
            cursor: default;
        }

        #itemsTable {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            background-color: #fff;
        }

        #itemsTable th,
        #itemsTable td {
            vertical-align: middle;
            padding: 12px 10px;
            font-size: 0.95rem;
        }

        #itemsTable thead th {
            background-color: #343a40;
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.03em;
        }

        #itemsTable tbody tr {
            transition: background-color 0.2s ease;
        }

        #itemsTable tbody tr:hover {
            background-color: #f9f9f9;
        }

        #itemsTable tbody tr:nth-child(even) {
            background-color: #f5f5f5;
        }

        #itemsTable td,
        #itemsTable th {
            text-align: center;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.5em 0.75em;
            border-radius: 8px;
        }

        /* ✅ Right-aligned search bar */
        .search-bar-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
        }

        .search-bar-container .search-box {
            display: flex;
            align-items: center;
            gap: 10px;
            /* <-- This adds the space between input and button */
            width: 100%;
            max-width: 350px;
        }

        @media (max-width: 768px) {

            #itemsTable th,
            #itemsTable td {
                font-size: 0.85rem;
                padding: 8px;
            }

            .search-bar-container {
                justify-content: center;
            }

            .search-bar-container .search-box {
                max-width: 90%;
            }
        }

        /* Navbar enhancements */
        .navbar-nav .nav-link {
            transition: color 0.3s ease, border-bottom 0.3s ease;
            padding-bottom: 0.25rem;
        }

        .navbar-nav .nav-link:hover {
            color: #ffc107;
            border-bottom: 2px solid #ffc107;
        }

        .navbar-nav .nav-link.active {
            color: #ffc107;
            border-bottom: 2px solid #ffc107;
            font-weight: 600;
        }

        #searchQuery:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 6px rgba(13, 110, 253, 0.25);
        }

        button.btn-outline-dark:hover {
            background-color: #212529;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 8px rgba(0, 0, 0, 0.15);
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

<body class="bg-light">

    <!-- ✅ Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3"
                href="<?= site_url('items') ?>">
                <img src="<?= base_url('public/Images/Inventa.png') ?>" alt="Inventa Logo"
                    style="width: 50px; height: 50px;">
                <span class="brand-text">Inventa</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('items') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white active" href="<?= site_url('items/expiringSoon') ?>">Expiring
                            Soon</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('items/deleted') ?>">Expired</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= site_url('items/logs') ?>"> Audit Logs </a>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="<?= base_url('/logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ✅ Content Section -->
    <div class="container mt-5">

        <a href="<?= base_url('/items') ?>"
            class="btn btn-outline-dark fw-semibold rounded-1 shadow-sm px-3 py-1 mb-3 d-inline-flex align-items-center"
            style="transition: all 0.3s ease;">
            <i class="fa-solid fa-arrow-left me-2"></i> Back to Dashboard
        </a>

        <!-- ✅ Right-Aligned Search Bar -->
        <div class="d-flex align-items-center justify-content-end gap-2">
            <div class="position-relative" style="width: 250px;">
                <i class="fa-solid fa-magnifying-glass position-absolute text-secondary"
                    style="top: 50%; left: 10px; transform: translateY(-50%);"></i>
                <input type="text" id="searchQuery" class="form-control ps-4 rounded-1 shadow-sm"
                    placeholder="Search by item name" oninput="searchItems()"
                    style="border: 1px solid #ced4da; transition: all 0.25s ease;">
            </div>
            <button class="btn btn-outline-dark rounded-1 fw-semibold px-3" onclick="searchItems()">
                <i class="fa-solid fa-search me-1"></i> Search
            </button>
        </div>

        <?php if (!empty($items) && is_array($items)): ?>
            <div class="p-4 bg-white rounded shadow-sm">
                <table id="itemsTable" class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Expiration Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr class="text-center">
                                <td><?= esc($item['name']) ?></td>
                                <td><?= esc($item['quantity']) ?></td>
                                <td><?= esc($item['category'] ?? '—') ?></td>
                                <td>₱<?= esc(number_format($item['price'], 2)) ?></td>
                                <td><?= esc($item['expiration_date']) ?></td>
                                <td><span class="badge bg-warning text-dark">⚠️ Expiring Soon</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center">
                🎉 No items are expiring soon!
            </div>
        <?php endif; ?>
    </div>

    <!-- ✅ Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function searchItems() {
            const searchQuery = document.getElementById('searchQuery').value.toLowerCase().trim();
            const rows = document.querySelectorAll('#itemsTable tbody tr');
            let anyVisible = false;

            rows.forEach(row => {
                const nameCell = row.querySelector('td:nth-child(1)');
                if (!nameCell) return;
                const name = nameCell.textContent.toLowerCase();
                if (name.includes(searchQuery)) {
                    row.style.display = '';
                    anyVisible = true;
                } else {
                    row.style.display = 'none';
                }
            });

            const noResults = document.getElementById('noResultsRow');
            if (!anyVisible) {
                if (!noResults) {
                    const tbody = document.querySelector('#itemsTable tbody');
                    const tr = document.createElement('tr');
                    tr.id = 'noResultsRow';
                    tr.innerHTML = `<td colspan="5" class="text-center text-muted">No items match your search.</td>`;
                    tbody.appendChild(tr);
                }
            } else {
                if (noResults) noResults.remove();
            }
        }

        document.getElementById('searchQuery').addEventListener('input', searchItems);
    </script>

</body>

</html>