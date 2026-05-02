<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records - Halimaw Siomai</title>
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
            box-shadow: var(--card-shadow);
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
        .container {
            padding: 20px;
            max-width: 100%;
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

        /* CARDS */
        .summary-card, .table-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 20px;
        }
        .summary-card h6 {
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

        /* BUTTONS */
        .btn-export {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            background: var(--success);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.2s;
            margin-bottom: 20px;
        }
        .btn-export:hover {
            background: #16a369;
            transform: translateY(-2px);
        }

        /* FORM CONTROLS */
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
            max-width: 500px;
            text-align: center;
            border-radius: var(--border-radius);
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
        .success-alert { background: linear-gradient(135deg, #1cc88a, #17a673); }
        .error-alert { background: linear-gradient(135deg, #e74a3b, #d93a2a); }
        .close-alert {
            background: transparent;
            border: none;
            color: white;
            font-size: 1.3rem;
            cursor: pointer;
            margin-left: 12px;
        }

        /* TABLE */
        .table-card {
            padding: 0;
        }
        #salesTable {
            min-width: 900px;
            font-size: 0.9rem;
            margin: 0;
        }
        #salesTable thead th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        #salesTable tbody tr {
            transition: background 0.2s;
        }
        #salesTable tbody tr:hover {
            background-color: #f8f9ff;
        }
        #salesTable .btn {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
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
        }

        @media (max-width: 480px) {
            #salesTable th:nth-child(6), #salesTable td:nth-child(6),
            #salesTable th:nth-child(7), #salesTable td:nth-child(7) {
                display: none;
            }
            #salesTable { min-width: 600px; font-size: 0.75rem; }
            #salesTable th, #salesTable td { padding: 4px 6px; }
            #salesTable .btn { font-size: 0.7rem; padding: 2px 6px; }
        }
    </style>
</head>
<body>
    <?= view('partials/admin_sidebar') ?>

    <div class="main-content">
        <div class="container">
            <!-- Summary Card -->
            <div class="summary-card">
                <?php
                $totalSales = count($sales);
                $totalRevenue = array_sum(array_column($sales, 'total'));
                ?>
                <h6><i class="bi bi-cash-coin me-2"></i>Sales Summary</h6>
                <div class="summary-item">
                    <span>Total Sales</span>
                    <span><?= $totalSales ?></span>
                </div>
                <div class="summary-item">
                    <span>Total Revenue</span>
                    <span>₱<?= number_format($totalRevenue, 2) ?></span>
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

            <!-- Controls -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2 w-100">
                    <!-- Search -->
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                        <input type="text" id="searchQuery" class="form-control" placeholder="Search by product or user" oninput="searchSales()">
                        <button onclick="searchSales()" class="btn btn-primary w-100 w-md-auto">Search</button>
                    </div>
                    
                    <!-- Payment Method Filter -->
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                        <label for="paymentFilter" class="form-label mb-0 fw-bold">Payment:</label>
                        <select id="paymentFilter" class="form-select" onchange="filterPayment()">
                            <option value="all">All Methods</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                        <label for="dateFilter" class="form-label mb-0 fw-bold">Date:</label>
                        <input type="date" id="dateFilter" class="form-control" onchange="filterDate()">
                    </div>
                </div>
            </div>

            <!-- Export Button -->
            <a href="<?= site_url('items/export-sales-csv') ?>" class="btn-export mb-3">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export to CSV
            </a>

            <!-- Table -->
            <?php if (!empty($sales) && is_array($sales)): ?>
            <div class="table-card">
                <div class="table-responsive">
                    <table id="salesTable" class="table table-bordered table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>User</th>
                                <th>Pack</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $now = new DateTime();
                            foreach ($sales as $index => $sale): 
                                $saleTime = new DateTime($sale->created_at ?? date('Y-m-d H:i:s'));
                                $minutesDiff = abs($now->getTimestamp() - $saleTime->getTimestamp()) / 60;
                                $isRecent = $minutesDiff <= 3;
                            ?>
                            <tr class="sale-row <?= $isRecent ? 'table-success' : '' ?>" style="<?= $isRecent ? 'transition: background 1s ease;' : '' ?>" data-saletime="<?= $saleTime->getTimestamp() * 1000 ?>">
                                <td>
                                    <?= $index + 1 ?>
                                    <?php if ($isRecent): ?>
                                        <br><span class="badge bg-success new-badge" style="font-size: 0.65rem;">New</span>
                                    <?php endif; ?>
                                </td>
                                <td class="product-name <?= $isRecent ? 'fw-bold' : '' ?>"><?= esc($sale->product_name) ?></td>
                                <td><?= esc($sale->user_name) ?></td>
                                <td><?= esc($sale->pack ?? '-') ?></td>
                                <td><?= esc($sale->quantity) ?></td>
                                <td>₱<?= number_format($sale->price, 2) ?></td>
                                <td class="total-price <?= $isRecent ? 'fw-bold text-success' : '' ?>">₱<?= number_format($sale->total, 2) ?></td>
                                <td><?= esc($sale->payment_method ?? 'N/A') ?></td>
                                <td><?= esc($sale->created_at) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">No sales records found.</div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
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

        // Search
        window.searchSales = () => {
            const query = (document.getElementById("searchQuery")?.value || "").toLowerCase();
            document.querySelectorAll("#salesTable tbody tr").forEach(row => {
                const product = row.children[1]?.textContent.toLowerCase() || "";
                const user = row.children[2]?.textContent.toLowerCase() || "";
                row.style.display = (product.includes(query) || user.includes(query)) ? "" : "none";
            });
        };

        // Payment Filter
        window.filterPayment = () => {
            const filter = (document.getElementById("paymentFilter")?.value || "all").toLowerCase();
            document.querySelectorAll("#salesTable tbody tr").forEach(row => {
                const payment = row.children[7]?.textContent.toLowerCase() || "";
                row.style.display = (filter === "all" || payment.includes(filter)) ? "" : "none";
            });
        };

        // Date Filter
        window.filterDate = () => {
            const filterDate = document.getElementById("dateFilter")?.value || "";
            document.querySelectorAll("#salesTable tbody tr").forEach(row => {
                const saleDate = row.children[8]?.textContent.split(' ')[0] || ""; // YYYY-MM-DD
                row.style.display = (!filterDate || saleDate === filterDate) ? "" : "none";
            });
        };

        // Alerts
        document.querySelectorAll('.notification-alert').forEach((alert, i) => {
            setTimeout(() => alert.classList.add('show'), 100 + i * 150);
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 400);
            }, 5000);
            alert.querySelector('.close-alert')?.addEventListener('click', () => alert.remove());
        });
        // Auto-fade recent highlights after 3 minutes in real-time
        setInterval(() => {
            const nowTime = Date.now();
            document.querySelectorAll('.sale-row.table-success').forEach(row => {
                const saleTime = parseInt(row.getAttribute('data-saletime'));
                if (!isNaN(saleTime)) {
                    // 3 minutes = 180000 ms
                    if (nowTime - saleTime >= 180000) {
                        row.classList.remove('table-success');
                        const newBadge = row.querySelector('.new-badge');
                        if (newBadge) newBadge.remove();
                        const productName = row.querySelector('.product-name');
                        if(productName) productName.classList.remove('fw-bold');
                        const total = row.querySelector('.total-price');
                        if(total) {
                            total.classList.remove('fw-bold');
                            total.classList.remove('text-success');
                        }
                    }
                }
            });
        }, 5000); // Check every 5 seconds
    });
    </script>
</body>
</html>