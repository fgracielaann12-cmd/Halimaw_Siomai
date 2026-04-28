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
        .container {
            padding: 0 20px 20px;
            max-width: 100%;
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

        /* HIDE GLOBAL MENU TOGGLE */
        body > #mobileMenuToggle { display: none !important; }

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
            .mobile-menu-toggle { display: flex; }
            #sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; }
            #sidebar.active { transform: translateX(0); }
            
            .controls-section {
                flex-direction: column;
                align-items: stretch;
            }
            .controls-section > div {
                width: 100%;
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
            .sidebar-overlay.active { display: block; }
        }

        @media (max-width: 480px) {
            #salesTable th, #salesTable td { padding: 8px 10px; }
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }
            .chart-filters {
                width: 100%;
                display: flex;
            }
            .chart-filter-btn {
                flex: 1;
                min-width: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 6px 0;
                font-size: 0.7rem;
                white-space: nowrap;
            }
        }
        /* PERFORMANCE METRICS CSS */
        .img-metric-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 12px;
            display: flex;
            align-items: center;
            height: 100%;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s, border 0.2s;
            border: 2px solid transparent;
        }
        .img-metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        }
        .img-metric-card.active {
            border: 2px solid var(--primary);
        }
        .metric-icon-circle {
            width: 40px;
            height: 40px;
            background-color: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }
        .metric-content {
            flex: 1;
            margin: 0 15px;
        }
        .metric-title {
            font-size: 0.70rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .metric-value {
            font-size: 1.35rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0px;
            line-height: 1.2;
        }
        .metric-growth {
            font-size: 0.75rem;
            font-weight: 600;
        }
        .metric-growth.positive { color: #10b981; }
        .metric-growth.negative { color: #ef4444; }
        .metric-vs {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .metric-sparkline {
            width: 70px;
            height: 40px;
            flex-shrink: 0;
        }
        .chart-card-premium {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 20px;
        }
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .chart-title-group .title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0px;
        }
        .chart-filters {
            display: inline-flex;
            background: #f0f4f8;
            padding: 4px;
            border-radius: 8px;
        }
        .chart-filter-btn {
            background: transparent;
            border: none;
            padding: 6px 16px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #4b5563;
            border-radius: 6px;
            transition: all 0.2s;
            cursor: pointer;
        }
        .chart-filter-btn.active {
            background: #3b82f6;
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
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
                <h5 class="mb-0"><i class="bi bi-graph-up me-2" style="font-size: 1.25rem;"></i>Sales Records</h5>
            </div>
        </div>

        <div class="container">
            <!-- 📊 PERFORMANCE ANALYTICS SECTION -->
            <div class="row g-3 mb-4" id="metricCards">
                <!-- Daily Sales -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="img-metric-card active" data-filter="daily">
                        <div class="metric-icon-circle" style="background-color: #3b82f6;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Daily Sales</div>
                            <div class="metric-value">₱<?= number_format($metricsData['daily']['value'] ?? 0, 2) ?></div>
                            <div>
                                <?php $gDaily = $metricsData['daily']['growth'] ?? 0; ?>
                                <span class="metric-growth <?= $gDaily >= 0 ? 'positive' : 'negative' ?>">
                                    <?= $gDaily >= 0 ? '↑' : '↓' ?> <?= number_format(abs($gDaily), 1) ?>% 
                                </span>
                                <span class="metric-vs">vs yesterday</span>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="sparklineDaily"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Weekly Sales -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="img-metric-card" data-filter="weekly">
                        <div class="metric-icon-circle" style="background-color: #10b981;">
                            <i class="bi bi-calendar-week"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Weekly Sales</div>
                            <div class="metric-value">₱<?= number_format($metricsData['weekly']['value'] ?? 0, 2) ?></div>
                            <div>
                                <?php $gWeekly = $metricsData['weekly']['growth'] ?? 0; ?>
                                <span class="metric-growth <?= $gWeekly >= 0 ? 'positive' : 'negative' ?>">
                                    <?= $gWeekly >= 0 ? '↑' : '↓' ?> <?= number_format(abs($gWeekly), 1) ?>% 
                                </span>
                                <span class="metric-vs">vs last week</span>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="sparklineWeekly"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Monthly Sales -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="img-metric-card" data-filter="monthly">
                        <div class="metric-icon-circle" style="background-color: #8b5cf6;">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Monthly Sales</div>
                            <div class="metric-value">₱<?= number_format($metricsData['monthly']['value'] ?? 0, 2) ?></div>
                            <div>
                                <?php $gMonthly = $metricsData['monthly']['growth'] ?? 0; ?>
                                <span class="metric-growth <?= $gMonthly >= 0 ? 'positive' : 'negative' ?>">
                                    <?= $gMonthly >= 0 ? '↑' : '↓' ?> <?= number_format(abs($gMonthly), 1) ?>% 
                                </span>
                                <span class="metric-vs">vs last month</span>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="sparklineMonthly"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Total Orders (Month) -->
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="img-metric-card" data-filter="orders">
                        <div class="metric-icon-circle" style="background-color: #f59e0b;">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="metric-content">
                            <div class="metric-title">Total Orders (Month)</div>
                            <div class="metric-value"><?= number_format($metricsData['orders']['value'] ?? 0) ?></div>
                            <div>
                                <?php $gOrders = $metricsData['orders']['growth'] ?? 0; ?>
                                <span class="metric-growth <?= $gOrders >= 0 ? 'positive' : 'negative' ?>">
                                    <?= $gOrders >= 0 ? '↑' : '↓' ?> <?= number_format(abs($gOrders), 1) ?>% 
                                </span>
                                <span class="metric-vs">vs last month</span>
                            </div>
                        </div>
                        <div class="metric-sparkline">
                            <canvas id="sparklineOrders"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-lg-8">
                    <!-- PREMIUM CHART CARD -->
                    <div class="chart-card-premium h-100 mb-0">
                        <div class="chart-header">
                            <div class="chart-title-group">
                                <h4 class="title">Sales Overview</h4>
                            </div>
                            <div class="chart-filters" id="overviewFilters">
                                <button class="chart-filter-btn active" data-filter="daily">Daily</button>
                                <button class="chart-filter-btn" data-filter="weekly">Weekly</button>
                                <button class="chart-filter-btn" data-filter="monthly">Monthly</button>
                                <button class="chart-filter-btn" data-filter="orders">Orders</button>
                            </div>
                        </div>
                        <!-- Height set to 320px for an elegant display -->
                        <div style="height: 320px; width: 100%; position: relative;">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <!-- TOP 5 ITEMS BY SALES VALUE -->
                    <div class="chart-card-premium h-100 mb-0">
                        <div class="chart-header">
                            <div class="chart-title-group">
                                <h4 class="title">Top 5 Items</h4>
                            </div>
                        </div>
                        <div style="height: 320px; width: 100%; position: relative;">
                            <canvas id="topItemsSalesChart"></canvas>
                        </div>
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

            <!-- Controls -->
            <div class="controls-section d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border p-3 rounded bg-white shadow-sm">
                <!-- Search -->
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1.8;">
                    <label class="form-label mb-0 fw-bold text-nowrap">Search Item:</label>
                    <div class="position-relative w-100">
                        <input type="text" id="searchQuery" class="form-control" style="padding-right: 2.2rem;" placeholder="Search by product or user" oninput="searchSales()">
                        <i class="bi bi-search position-absolute top-50 end-0 translate-middle-y me-3" style="color: #6c757d; opacity: 0.6; pointer-events: none;"></i>
                    </div>
                </div>
                
                <!-- Date Range -->
                <div class="d-flex flex-column flex-md-row align-items-md-center gap-2" style="flex:1;">
                    <label for="dateFilter" class="form-label mb-0 fw-bold text-nowrap">Date:</label>
                    <input type="date" id="dateFilter" class="form-control" onchange="filterDate()">
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
                <a href="<?= site_url('items/export-sales-csv') ?>" class="btn-export w-100 w-md-auto mb-0">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export to CSV
                </a>
                <a href="<?= site_url('admin/sales/transactions') ?>" class="btn btn-primary w-100 w-md-auto" style="border-radius: 50px; padding: 8px 20px; font-weight: 600;">
                    <i class="bi bi-clock-history me-1"></i> Transaction History
                </a>
            </div>

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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
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
        window.searchSales = () => {
            const query = (document.getElementById("searchQuery")?.value || "").toLowerCase();
            document.querySelectorAll("#salesTable tbody tr").forEach(row => {
                const product = row.children[1]?.textContent.toLowerCase() || "";
                const user = row.children[2]?.textContent.toLowerCase() || "";
                row.style.display = (product.includes(query) || user.includes(query)) ? "" : "none";
            });
        };

        // Date Filter
        window.filterDate = () => {
            const filterDate = document.getElementById("dateFilter")?.value || "";
            document.querySelectorAll("#salesTable tbody tr").forEach(row => {
                const saleDate = row.children[7]?.textContent.split(' ')[0] || ""; // YYYY-MM-DD
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

        // 📈 INITIALIZE MAIN SALES TREND CHART
        const salesTrendDataRaw = <?= $salesTrendData ?? '[]' ?>;
        const trendDates = salesTrendDataRaw.map(s => s.date);
        const trendValues = salesTrendDataRaw.map(s => parseFloat(s.daily_total));

        const weeklyTrendData = <?= $metricsData['weekly']['trend'] ?? '[]' ?>;
        const weeklyLabels = <?= $metricsData['weekly']['labels'] ?? '[]' ?>;
        
        const monthlyTrendData = <?= $metricsData['monthly']['trend'] ?? '[]' ?>;
        const monthlyLabels = <?= $metricsData['monthly']['labels'] ?? '[]' ?>;

        const ordersTrendData = <?= $metricsData['orders']['trend'] ?? '[]' ?>;

        let mainChart;
        const canvasElement = document.getElementById('salesTrendChart');
        if(canvasElement) {
            const ctx = canvasElement.getContext('2d');
            let gradient = ctx.createLinearGradient(0, 0, 0, 320); // matching canvas height
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.02)');

            mainChart = new Chart(canvasElement, {
                type: 'line',
                data: {
                    labels: trendDates.length ? trendDates : ['No Data'],
                    datasets: [{
                        label: 'Total Sales (₱)',
                        data: trendValues.length ? trendValues : [0],
                        backgroundColor: gradient,
                        borderColor: '#3b82f6',
                        borderWidth: 3, pointBackgroundColor: '#ffffff', pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2, pointHoverRadius: 6, pointHoverBackgroundColor: '#3b82f6',
                        pointHoverBorderColor: '#ffffff', pointHoverBorderWidth: 3, pointRadius: 0,
                        pointHitRadius: 10, fill: true, tension: 0.4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    scales: {
                        y: { beginAtZero: true, border: { display: false }, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, ticks: { callback: value => '₱' + value.toLocaleString(), color: '#6b7280', font: { size: 11, family: "'Poppins', sans-serif" } } },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: '#6b7280', font: { size: 11, family: "'Poppins', sans-serif" } } }
                    },
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1f2937', titleFont: { size: 13, family: "'Poppins', sans-serif" }, bodyFont: { size: 13, family: "'Poppins', sans-serif", weight: 'bold' }, padding: 12, cornerRadius: 8, displayColors: false, callbacks: { label: function(context) { return '₱ ' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2}); } } } }
                }
            });
        }

        // 📈 INITIALIZE TOP 5 ITEMS SALES CHART
        const topItemsData = <?= $topItemsData ?? '[]' ?>;
        const topItemsCanvas = document.getElementById('topItemsSalesChart');
        if (topItemsCanvas && topItemsData.length > 0) {
            new Chart(topItemsCanvas, {
                type: 'bar',
                data: {
                    labels: topItemsData.map(i => i.name),
                    datasets: [
                        {
                            label: 'Total Sales (₱)',
                            data: topItemsData.map(i => i.total_value),
                            backgroundColor: 'rgba(78, 115, 223, 0.8)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            yAxisID: 'y'
                        },
                        {
                            label: 'Quantity Sold',
                            data: topItemsData.map(i => i.total_quantity || 0),
                            backgroundColor: 'rgba(28, 200, 138, 0.8)',
                            borderColor: 'rgba(28, 200, 138, 1)',
                            borderWidth: 1,
                            borderRadius: 4,
                            yAxisID: 'y1'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            type: 'linear',
                            display: true,
                            position: 'left',
                            beginAtZero: true, 
                            border: { display: false }, 
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { callback: value => '₱' + value.toLocaleString() } 
                        },
                        y1: {
                            type: 'linear',
                            display: true,
                            position: 'right',
                            beginAtZero: true,
                            grid: { drawOnChartArea: false }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: { 
                        legend: { display: true, position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) { 
                                    if(context.datasetIndex === 0) {
                                    return 'Total Sales: ₱ ' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2}); 
                                    } else {
                                        return 'Quantity: ' + context.parsed.y;
                                    }
                                }
                            }
                        }
                    }
                }
            });
        }

        const updateChartFilter = (filter) => {
            if(!mainChart) return;
            // Handle Data
            if (filter === 'daily') { mainChart.data.labels = trendDates; mainChart.data.datasets[0].data = trendValues; }
            else if (filter === 'weekly') { mainChart.data.labels = weeklyLabels.length ? weeklyLabels : ['No Data']; mainChart.data.datasets[0].data = weeklyTrendData.length ? weeklyTrendData : [0]; }
            else if (filter === 'monthly') { mainChart.data.labels = monthlyLabels.length ? monthlyLabels : ['No Data']; mainChart.data.datasets[0].data = monthlyTrendData.length ? monthlyTrendData : [0]; }
            else if (filter === 'orders') { mainChart.data.labels = monthlyLabels.length ? monthlyLabels : ['No Data']; mainChart.data.datasets[0].data = ordersTrendData.length ? ordersTrendData : [0]; }
            
            // Format Y-axis and Tooltips based on what data we show
            if (filter === 'orders') {
                mainChart.data.datasets[0].label = 'Total Orders';
                mainChart.options.scales.y.ticks.callback = value => value.toLocaleString();
                mainChart.options.plugins.tooltip.callbacks.label = function(context) { return context.parsed.y.toLocaleString(); };
            } else {
                mainChart.data.datasets[0].label = 'Total Sales (₱)';
                mainChart.options.scales.y.ticks.callback = value => '₱' + value.toLocaleString();
                mainChart.options.plugins.tooltip.callbacks.label = function(context) { return '₱ ' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2}); };
            }
            mainChart.update();
        };

        const syncFilterUI = (filter) => {
            // Sync small buttons
            const overviewFilters = document.getElementById('overviewFilters');
            if (overviewFilters) {
                overviewFilters.querySelectorAll('.chart-filter-btn').forEach(b => {
                    b.classList.toggle('active', b.getAttribute('data-filter') === filter);
                });
            }
            // Sync metric cards
            const metricCards = document.getElementById('metricCards');
            if (metricCards) {
                metricCards.querySelectorAll('.img-metric-card').forEach(c => {
                    c.classList.toggle('active', c.getAttribute('data-filter') === filter);
                });
            }
        };

        // Attach click listeners to small pill buttons
        const overviewFilters = document.getElementById('overviewFilters');
        if (overviewFilters) {
            overviewFilters.querySelectorAll('.chart-filter-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const filter = e.target.getAttribute('data-filter');
                    syncFilterUI(filter);
                    updateChartFilter(filter);
                });
            });
        }
        
        // Attach click listeners to large metric cards
        const metricCards = document.getElementById('metricCards');
        if (metricCards) {
            metricCards.querySelectorAll('.img-metric-card').forEach(card => {
                card.addEventListener('click', (e) => {
                    const target = e.target.closest('.img-metric-card');
                    if(!target) return;
                    const filter = target.getAttribute('data-filter');
                    syncFilterUI(filter);
                    updateChartFilter(filter);
                });
            });
        }

        // 📈 INITIALIZE SPARKLINE CHARTS
        const sparklineDefaults = {
            type: 'line',
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: { x: { display: false }, y: { display: false, beginAtZero: true} },
                elements: { point: { radius: 1 } }, layout: { padding: 0 }
            }
        };

        const createSparkline = (id, data, colorStr, bgStr) => {
            const ctx = document.getElementById(id);
            if (!ctx) return;
            return new Chart(ctx, {
                ...sparklineDefaults,
                data: {
                    labels: data.map((_, i) => i),
                    datasets: [{
                        data: data.length ? data : [0,0],
                        borderColor: colorStr, backgroundColor: bgStr,
                        borderWidth: 2, fill: true, tension: 0.4
                    }]
                }
            });
        };

        const metricsDaily = <?= $metricsData['daily']['trend'] ?? '[]' ?>;
        const metricsWeekly = <?= $metricsData['weekly']['trend'] ?? '[]' ?>;
        const metricsMonthly = <?= $metricsData['monthly']['trend'] ?? '[]' ?>;
        const metricsOrders = <?= $metricsData['orders']['trend'] ?? '[]' ?>;

        createSparkline('sparklineDaily', metricsDaily, '#3b82f6', 'rgba(59, 130, 246, 0.2)');
        createSparkline('sparklineWeekly', metricsWeekly, '#10b981', 'rgba(16, 185, 129, 0.2)');
        createSparkline('sparklineMonthly', metricsMonthly, '#8b5cf6', 'rgba(139, 92, 246, 0.2)');
        createSparkline('sparklineOrders', metricsOrders, '#f59e0b', 'rgba(245, 158, 11, 0.2)');
    });
    </script>
</body>
</html>