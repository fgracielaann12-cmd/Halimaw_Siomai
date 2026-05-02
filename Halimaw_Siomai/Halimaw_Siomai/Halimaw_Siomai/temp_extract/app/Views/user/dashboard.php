<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            box-shadow: var(--card-shadow);
        }
        #sidebar .navbar-brand {
            padding: 1.25rem 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        #sidebar .navbar-brand img {
            width: 48px;
            height: 48px;
            border-radius: 8px;
        }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.85rem 1.25rem;
            margin: 0.25rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.95rem;
            transition: all 0.25s ease;
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
            background-color: var(--sidebar-hover);
            color: white;
            font-weight: 600;
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

        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 998;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
        }
        .mobile-menu-toggle:hover {
            background: var(--sidebar-hover);
        }

        .container {
            max-width: 96%;
            padding: 20px;
            margin-top: 5px;
        }

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
        .sidebar-overlay.active {
            display: block;
        }

        /* 🎯 ENHANCED STATS SECTION */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }
        @media (min-width: 992px) {
            .stats-grid { grid-template-columns: repeat(4, 1fr); }
        }
        @media (max-width: 576px) {
            .stats-grid { grid-template-columns: 1fr; }
        }

        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 15px;
            box-shadow: var(--card-shadow);
            border-top: 4px solid var(--primary);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        @media (min-width: 768px) {
            .stat-card { padding: 22px 20px; align-items: flex-start; text-align: left; }
        }

        /* Responsive Table Wrapper */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            background: white;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .hide-mobile { display: none; }
            .container { padding: 10px; }
            .stat-value { font-size: 1.4rem; }
            .stat-title { font-size: 0.8rem; }
        }

        /* TABLE */
        #itemsTable {
            width: 100%;
            min-width: 800px;
            font-size: 0.9rem;
            margin: 0;
            background: white;
        }
        #itemsTable tbody tr {
            transition: none !important;
            cursor: default !important;
        }
        #itemsTable tbody tr:hover {
            background-color: transparent !important;
        }
        #itemsTable tbody tr:nth-child(even) {
            background-color: #fafbff;
        }

        /* CONTROLS */
        .controls-section {
            background: white;
            padding: 18px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            align-items: center;
        }
        .controls-section .form-control,
        .controls-section .form-select {
            border-radius: var(--border-radius);
            border: 1px solid #ddd;
            padding: 8px 12px;
            min-width: 180px;
        }
        .controls-section .btn {
            border-radius: var(--border-radius);
            background: var(--primary);
            color: white;
            font-weight: 500;
            padding: 8px 16px;
        }
        .controls-section .btn:hover {
            background: var(--primary-dark);
        }
        .controls-section label {
            font-weight: 600;
            color: var(--dark);
            margin: 0 8px 0 0;
        }

        /* MODAL ENHANCEMENTS */
        .modal-content {
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }
        .modal .btn[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            #sidebar { transform: translateX(-100%); width: 280px; }
            
            
            .main-content { margin-left: 0; width: 100%; }
            .container { padding-top: 15px; } /* Prevent title overlap */
            #sidebar.active { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .stat-card { min-width: 200px; }
        }
        @media (max-width: 767px) {
            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }
            .controls-section > div {
                width: 100%;
            }
            #itemsTable { min-width: 600px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>
    <?php
    // 🔶 Expiring Soon Items
    $expiringSoonItems = array_filter($items, function ($i) {
        if (empty($i['expiration_date']) || $i['expiration_date'] === '0000-00-00')
            return false;
        $today = new DateTime();
        $expiration = new DateTime($i['expiration_date']);
        $daysLeft = (int) $today->diff($expiration)->format('%r%a');
        return $daysLeft <= 10;
    });
    // 🔴 Low Stock Items
    $lowStockItems = array_filter($items, fn($i) => $i['quantity'] <= 10);
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
                <a class="nav-link <?= isActive(['user/unconsumed']) ?>" href="<?= site_url('user/unconsumed') ?>">
                    <i class="bi bi-cart-x"></i> Unconsumed Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpModal">
                    <i class="bi bi-arrow-repeat"></i> Request Stock Adjustment
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

    <div class="main-content">
        <div class="container">
            <h2 class="text-center mb-4" style="font-weight: 700; color: var(--dark);"><i class="bi bi-person-badge me-2"></i>Staff Dashboard</h2>

            <!-- 📊 STOCKS ANALYTICS -->
            <h5 class="mb-3 fw-bold text-muted"><i class="bi bi-graph-up-arrow me-2"></i>Stocks Analytics</h5>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div class="stat-title">TOTAL ITEMS</div>
                    <div class="stat-value"><?= count($items) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                    </div>
                    <div class="stat-title">LOW STOCK</div>
                    <div class="stat-value"><?= count($lowStockItems) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="bi bi-clock-history text-danger"></i>
                    </div>
                    <div class="stat-title">EXPIRING SOON</div>
                    <div class="stat-value"><?= count($expiringSoonItems) ?></div>
                </div>
            </div>

            <!-- 🔔 ALERTS -->
            <div class="alert-section">
                <?php if (!empty($lowStockItems)): ?>
                <div class="inventory-alert" onclick="showLowStockItems()" style="cursor:pointer;">
                    ⚠️ You have <?= count($lowStockItems) ?> item(s) running low on stock.
                    <span style="text-decoration: underline; margin-left:6px;">View</span>
                </div>
                <?php endif; ?>


                <div class="text-center mb-2">
                    <button id="showAllBtn" class="btn btn-outline-secondary btn-sm" style="display:none;" onclick="showAllItems()">
                        Show All Items
                    </button>
                </div>
            </div>

            <!-- 🔍 CONTROLS -->
            <div class="controls-section">
                <input type="text" id="searchQuery" class="form-control" placeholder="Search by item name" oninput="searchItems()">
                <button class="btn" onclick="searchItems()">Search</button>

                <label for="sortFilter" class="mb-0">Sort:</label>
                <select id="sortFilter" class="form-select" onchange="sortItems()">
                    <option value="default">Default</option>
                    <option value="name_asc">Name (A–Z)</option>
                    <option value="name_desc">Name (Z–A)</option>
                    <option value="quantity_asc">Quantity (Low → High)</option>
                    <option value="quantity_desc">Quantity (High → Low)</option>
                    <option value="date_asc">Date (Oldest → Newest)</option>
                    <option value="date_desc">Date (Newest → Oldest)</option>
                    <option value="expiring_soon">Expiring Soon</option>
                </select>
            </div>

            <!-- 📊 TABLE -->
            <?php if (!empty($items) && is_array($items)): ?>
            <div class="table-responsive-custom">
                <table id="itemsTable" class="table table-hover align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th class="hide-mobile">Category</th>
                            <th class="hide-mobile">Expiration Date</th>
                            <th>Days Left</th>
                            <th>Status</th>
                            <th class="hide-mobile">Date Entry</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                        <?php
                            $today = new DateTime();
                            if (empty($item['expiration_date']) || $item['expiration_date'] === '0000-00-00') {
                                $status = 'na';
                                $statusLabel = "N/A";
                                $daysLeftText = "—";
                            } else {
                                $expiration = new DateTime($item['expiration_date']);
                                $interval = $today->diff($expiration);
                                $daysLeft = (int) $interval->format('%r%a');
                                if ($daysLeft < 0) {
                                    $status = 'expired';
                                    $statusLabel = "Expired";
                                    $daysLeftText = abs($daysLeft) . " days ago";
                                } elseif ($daysLeft == 0) {
                                    $status = 'expiring today';
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
                            $isLowStock = $item['quantity'] <= 10;

                            // Display name with "(6 pcs)" if it's a patty
                            $displayName = esc($item['name']);
                            if (stripos($item['name'], 'patty') !== false) {
                                $displayName .= ' <small class="text-success hide-mobile">(6 pcs)</small>';
                            }

                            // Safe price display
                            $priceDisplay = !empty($item['price']) 
                                ? '₱' . number_format((float)$item['price'], 2) 
                                : '<span class="text-muted">—</span>';
                        ?>
                        <tr class="text-center <?= $isLowStock ? 'table-warning' : '' ?>" data-id="<?= $item['id'] ?>">
                            <td><?= esc($item['product_id']) ?></td>
                            <td><?= $displayName ?></td>
                            <td>
                                <?php if ($isLowStock): ?>
                                <strong><?= esc($item['quantity']) ?></strong> <span class="badge bg-danger ms-1">Low</span>
                                <?php else: ?>
                                <?= esc($item['quantity']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= $priceDisplay ?></td>
                            <td class="hide-mobile"><?= esc($item['category'] ?? '—') ?></td>
                            <td class="hide-mobile"><?= empty($item['expiration_date']) ? '—' : esc($item['expiration_date']) ?></td>
                            <td data-days-left="<?= $daysLeft ?? 0 ?>"><?= $daysLeftText ?></td>
                            <td>
                                <span class="badge 
                                    <?= $status == 'expired' ? 'bg-danger' :
                                    ($status == 'expiring today' || $status == 'expiring soon' ? 'bg-warning text-dark' :
                                    ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                    <?= $statusLabel ?>
                                </span>
                            </td>
                            <td class="hide-mobile"><?= esc($item['created_at']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="alert alert-warning text-center">No items found in the database.</div>
            <?php endif; ?>
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
                            <select id="requestItemModal" class="form-select shadow-sm" required style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                                <option value="">— Choose an item —</option>
                                <?php foreach ($items as $item): ?>
                                    <option value="<?= esc($item['id']) ?>">
                                        <?= esc($item['name']) ?>
                                        <?php if (stripos($item['name'], 'patty') !== false): ?>
                                            (6 pcs)
                                        <?php endif; ?>
                                        (Qty: <?= esc($item['quantity']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="requestActionModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-arrow-left-right me-1"></i> Adjustment Type
                            </label>
                            <select id="requestActionModal" class="form-select shadow-sm" required style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
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
                                   style="border-radius: var(--border-radius); padding: 0.6rem 1rem;">
                        </div>
                        <div class="mb-4">
                            <label for="requestReasonModal" class="form-label fw-semibold text-dark mb-2">
                                <i class="bi bi-journal-text me-1"></i> Reason / Notes
                            </label>
                            <textarea id="requestReasonModal" class="form-control shadow-sm" rows="3" placeholder="e.g., spillage, delivery, inventory correction..." required
                                      style="border-radius: var(--border-radius); padding: 0.6rem 1rem;"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn fw-bold" style="
                                background: var(--primary);
                                color: white;
                                border: none;
                                padding: 0.75rem;
                                border-radius: var(--border-radius);
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', () => {
                const isActive = sidebar.classList.contains('active');
                if (!isActive) {
                    sidebar.classList.add('active');
                    sidebarOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                    // removed arrow
                } else {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                    // removed arrow
                }
            });
        }
        
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
        document.querySelectorAll('#sidebar .nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 991) {
                    closeSidebar();
                }
            });
        });

        // 🔎 SEARCH
        window.searchItems = () => {
            const query = (document.getElementById("searchQuery")?.value || "").toLowerCase().trim();
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const name = row.children[1]?.textContent.toLowerCase() || "";
                const pid = row.children[0]?.textContent.toLowerCase() || "";
                row.style.display = (name.includes(query) || pid.includes(query)) ? "" : "none";
            });
        };

        // 🔄 SORT
        window.sortItems = () => {
            const sortValue = document.getElementById("sortFilter")?.value || "default";
            const tbody = document.querySelector("#itemsTable tbody");
            if (!tbody) return;
            const rows = Array.from(tbody.querySelectorAll("tr"));
            rows.sort((a, b) => {
                const nameA = a.children[1]?.textContent.trim().toLowerCase() || "";
                const nameB = b.children[1]?.textContent.trim().toLowerCase() || "";
                const qtyA = parseFloat(a.children[2]?.textContent) || 0;
                const qtyB = parseFloat(b.children[2]?.textContent) || 0;
                const dateA = new Date(a.children[9]?.textContent.trim() || 0);
                const dateB = new Date(b.children[9]?.textContent.trim() || 0);
                const statusA = a.querySelector(".badge")?.textContent.toLowerCase() || "";
                const statusB = b.querySelector(".badge")?.textContent.toLowerCase() || "";
                const statusOrder = { 'expired': 0, 'expiring today': 1, 'expiring soon': 1, 'active': 2, 'n/a': 3 };
                switch (sortValue) {
                    case "name_asc": return nameA.localeCompare(nameB);
                    case "name_desc": return nameB.localeCompare(nameA);
                    case "quantity_asc": return qtyA - qtyB;
                    case "quantity_desc": return qtyB - qtyA;
                    case "date_asc": return dateA - dateB;
                    case "date_desc": return dateB - dateA;
                    case "expiring_soon": return (statusOrder[statusA] || 99) - (statusOrder[statusB] || 99);
                    default: return 0;
                }
            });
            rows.forEach(r => tbody.appendChild(r));
        };

        // 📉 FILTERS
        window.showLowStockItems = () => {
            let found = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                if (row.classList.contains('table-warning')) {
                    row.style.display = "";
                    found++;
                } else row.style.display = "none";
            });
            document.getElementById("showAllBtn").style.display = found ? "inline-block" : "none";
        };
        window.showExpiringSoon = () => {
            let found = 0;
            document.querySelectorAll("#itemsTable tbody tr").forEach(row => {
                const days = parseInt(row.children[6]?.dataset.daysLeft) || 9999;
                if (days >= 0 && days <= 10) {
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

        // ✅ STOCK REQUEST SUBMISSION
        const form = document.getElementById("stockRequestFormModal");
        if (form) {
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
                const submitBtn = form.querySelector("button[type='submit']");
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Submitting...';

                const itemId = document.getElementById("requestItemModal").value;
                const action = document.getElementById("requestActionModal").value;
                const quantity = parseInt(document.getElementById("requestQtyModal").value) || 0;
                const reason = document.getElementById("requestReasonModal").value.trim();

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
                        form.reset();
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
    });
    </script>
</body>
</html>