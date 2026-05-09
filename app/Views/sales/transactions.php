<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Transaction History') ?> - Halimaw Siomai</title>
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
        .container {
            padding: 0 20px 20px;
            max-width: 100%;
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

        .table-card {
            background: white;
            border-radius: 5px;
            box-shadow: var(--card-shadow);
            padding: 0;
            margin-bottom: 20px;
            overflow: hidden;
        }

        /* ENHANCED TABLE */
        .table {
            margin-bottom: 0;
            color: #4a5568;
            font-size: 0.9rem;
            min-width: 900px;
        }
        .table th {
            border-top: none;
            border-bottom: 2px solid var(--primary-dark);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            background: var(--primary);
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #edf2f7;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: flex; }
            #sidebar { transform: translateX(-100%); }

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
        }

        @media (max-width: 480px) {
            .table th, .table td { padding: 0.75rem; }
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
    <?= view('partials/admin_sidebar') ?>

    <div class="main-content">
        <!-- TOP NAVBAR -->
        <div class="top-navbar" style="padding-left: 20px;">
            <div class="d-flex align-items-center gap-3">
                <button class="mobile-menu-toggle" id="mobileMenuToggleInline">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0"><i class="bi bi-clock-history me-2" style="font-size: 1.25rem;"></i>Transaction History</h5>
            </div>
            <div>
                <a href="<?= site_url('admin/sales') ?>" class="btn btn-light rounded-1 shadow-sm" style="width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; background: #f8f9fa; border: 1px solid #dee2e6; color: #495057;">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>

        <div class="container">
            <div class="table-card mt-3">
                <div class="table-responsive">
                    <table class="table align-middle" id="salesTable">
                        <thead>
                            <tr>
                                <th class="ps-4">Transaction ID</th>
                                <th>User</th>
                                <th>Customer</th>
                                <th>VAT</th>
                                <th class="text-end">Total Amount</th>
                                <th class="pe-4 text-end">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($transactions) && is_array($transactions)): ?>
                                <?php foreach ($transactions as $txn): ?>
                                <tr style="cursor: pointer;" onclick="viewTransactionItems('<?= esc($txn->transaction_id) ?>')">
                                    <td class="ps-3 fw-bold text-primary"><?= esc($txn->transaction_id) ?></td>
                                    <td><?= esc($txn->user_name) ?></td>
                                    <td><?= esc($txn->customer_name ?: 'Walk-in') ?><br><small class="text-muted"><?= esc($txn->customer_email) ?></small></td>
                                    <td>
                                        <?php if ($txn->vat_applied): ?>
                                            <span><?= ucfirst(esc($txn->vat_type)) ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">No</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold text-success">₱<?= number_format($txn->total_amount, 2) ?></td>
                                    <td class="pe-3 text-end text-muted"><?= date('M d, Y h:i A', strtotime($txn->created_at)) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center p-5 text-muted">
                                        <i class="bi bi-inbox fs-1"></i>
                                        <p class="mt-2 mb-0">No transaction history found.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Items Modal -->
    <div class="modal fade" id="transactionItemsModal" tabindex="-1" aria-labelledby="transactionItemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold" id="transactionItemsModalLabel"><i class="bi bi-cart3 me-2"></i>Items for <span id="modalTransactionId"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0" id="modalItemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Product Name</th>
                                    <th>Pack</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th class="text-end pe-4">Total</th>
                                </tr>
                            </thead>
                            <tbody id="modalItemsBody">
                                <!-- Items injected via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary rounded-1 px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        // Mobile Menu
        const mobileMenuToggles = [document.getElementById('mobileMenuToggle'), document.getElementById('mobileMenuToggleInline')];
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.createElement('div');
        sidebarOverlay.className = 'sidebar-overlay';
        document.body.appendChild(sidebarOverlay);

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
        
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
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
    });

    function viewTransactionItems(transactionId) {
        document.getElementById('modalTransactionId').textContent = transactionId;
        const tbody = document.getElementById('modalItemsBody');
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading items...</p></td></tr>';
        
        const modal = new bootstrap.Modal(document.getElementById('transactionItemsModal'));
        modal.show();

        fetch(`<?= site_url('admin/sales/transaction-items/') ?>${transactionId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.items.length > 0) {
                    let html = '';
                    data.items.forEach(item => {
                        const pack = item.pack ? item.pack : '-';
                        html += `
                            <tr>
                                <td class="ps-4 fw-bold text-dark">${item.product_name || 'Unknown Product'}</td>
                                <td>${pack}</td>
                                <td>${item.quantity}</td>
                                <td>₱${parseFloat(item.price).toFixed(2)}</td>
                                <td class="text-end pe-4 fw-bold text-success">₱${parseFloat(item.total).toFixed(2)}</td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = html;
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>No items found for this transaction.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error fetching transaction items:', error);
                tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle fs-2 d-block mb-2"></i>Error loading items.</td></tr>';
            });
    }
    </script>
<script src="<?= base_url('js/table-pagination.js') ?>"></script>
</body>
</html>
