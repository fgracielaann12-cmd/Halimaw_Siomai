<?php include APPPATH . 'Views/partials/header.php'; ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background-color: white;
            font-family: "Segoe UI", Arial, sans-serif;
            font-size: 0.95rem;
            color: #333;
        }

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

        .container {
            max-width: 96%;
            overflow-x: auto;
        }

        h2 {
            font-weight: 700;
            letter-spacing: 1px;
        }

        .btn-add-new-item {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 18px;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(135deg, #607d8b, #2e7d32);
            color: white;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-add-new-item:hover {
            background: linear-gradient(135deg, #388e3c, #4caf50);
            transform: scale(1.05);
        }

        #searchQuery {
            width: 230px;
        }

        #statusFilter {
            width: 170px;
        }

        .form-select,
        .form-control {
            font-size: 0.9rem;
            border-radius: 6px;
        }

        .fade-message {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            transition: opacity 0.8s ease;
        }

        .inventory-alert {
            text-align: center;
            font-weight: 500;
            font-size: 0.95rem;
            width: 100%;
        }

        /* ✅ Table Styling */
        #itemsTable {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
            background-color: #fff;
            min-width: 1200px;
        }

        #itemsTable th,
        #itemsTable td {
            vertical-align: middle;
            text-align: center;
            padding: 6px 4px;
        }

        #itemsTable th {
            background-color: #343a40;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        #itemsTable td {
            font-size: 0.85rem;
        }

        #itemsTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #itemsTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.4em 0.6em;
            border-radius: 8px;
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
            border-radius: 5px;
            padding: 2px 0;
            font-size: 0.9rem;
        }

        .btn-sm {
            font-size: 0.85rem;
            padding: 3px 8px;
        }

        /* ✅ Scrollbars */
        .table-scroll-wrapper {
            position: relative;
            margin-top: 15px;
        }

        .table-scroll-top {
            overflow-x: auto;
            overflow-y: hidden;
            height: 16px;
            margin-bottom: 6px;
        }

        .table-scroll-bottom {
            overflow-x: auto;
        }

        .table-scroll-top::-webkit-scrollbar,
        .table-scroll-bottom::-webkit-scrollbar {
            height: 8px;
            background-color: #f1f1f1;
        }

        .table-scroll-top::-webkit-scrollbar-thumb,
        .table-scroll-bottom::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .table-scroll-top::-webkit-scrollbar-thumb:hover,
        .table-scroll-bottom::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        @media (max-width: 992px) {
            .btn-add-new-item {
                font-size: 0.9rem;
                padding: 7px 14px;
            }

            #searchQuery,
            #statusFilter {
                width: 150px;
            }

            #itemsTable th,
            #itemsTable td {
                font-size: 0.8rem;
                padding: 6px;
            }

            h2 {
                font-size: 1.4rem;
            }
        }

        /* ✅ Smooth pulse animation (gentle breathing effect) */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            }

            50% {
                opacity: 0.8;
                box-shadow: 0 0 25px rgba(0, 0, 0, 0.25);
            }
        }

        /* 🔴 Expiry Alert - Red with glow */
        .expiry-alert {
            background-color: #dc3545;
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            margin-bottom: 18px;
            border: 2px solid #b71c1c;
            box-shadow: 0 0 12px rgba(220, 53, 69, 0.5);
            animation: pulse 3s infinite ease-in-out;
        }

        /* 🟡 Low Stock Alert - Yellow with glow */
        .low-stock-alert {
            background-color: #ffc107;
        }

        .expiry-alert {
            cursor: pointer;
            /* 👈 makes it feel clickable */
            transition: transform 0.2s ease;
        }

        .expiry-alert:hover {
            transform: scale(1.02);
            box-shadow: 0 0 20px rgba(220, 53, 69, 0.7);
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-3" href="<?= site_url('items') ?>">
                <img src="/inventa/public/Images/Inventa.png" alt="Inventa Logo" style="width: 50px; height: 50px;">
                <span class="brand-text">Inventa</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto gap-3">
                    <li class="nav-item"><a class="nav-link text-white active"
                            href="<?= site_url('user/dashboard') ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="<?= site_url('items/expiringSoon') ?>">Expiring Soon</a></li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="<?= site_url('items/deleted') ?>">Expired</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('/logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-5">
        <h2 class="mb-4 text-center">INVENTORY MANAGEMENT</h2>
        <div class="card shadow-sm p-3 rounded-3 mb-2" style="max-width: 280px;">
            <?php
            $totalItems = count($items);
            $totalQuantity = array_sum(array_column($items, 'quantity'));
            $totalValue = array_sum(array_map(fn($i) => $i['quantity'] * $i['price'], $items));
            ?>

            <p class="mb-1"><strong>Total Items:</strong> <?= $totalItems ?></p>
            <p class="mb-1"><strong>Total Quantity:</strong> <?= $totalQuantity ?></p>
            <p class="mb-0"><strong>Total Value:</strong> ₱<?= number_format($totalValue, 2) ?></p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success fade-message text-center"><?= session()->getFlashdata('success') ?></div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger fade-message text-center"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?= site_url('items/add') ?>" class="btn-add-new-item">
                <i class="bi bi-plus-lg me-2"></i> Add New Item
            </a>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="searchQuery" class="form-control" placeholder="Search by item name"
                    oninput="searchItems()">
                <button class="btn btn-secondary" onclick="searchItems()">Search</button>
                <label for="statusFilter" class="form-label mb-0 fw-bold">Category:</label>
                <select id="statusFilter" class="form-select" onchange="filterStatus()">
                    <option value="all">All</option>
                    <option value="Food">Food</option>
                    <option value="Non-Food">Non-Food</option>
                </select>

                <label for="sortFilter" class="form-label mb-0 fw-bold ms-2">Sort:</label>
                <select id="sortFilter" class="form-select form-select-sm" style="width: 160px;" onchange="sortItems()">
                    <option value="default">Default</option>
                    <option value="name_asc">Name (A–Z)</option>
                    <option value="name_desc">Name (Z–A)</option>
                    <option value="quantity_asc">Quantity (Low → High)</option>
                    <option value="quantity_desc">Quantity (High → Low)</option>
                    <option value="date_asc">Date (Oldest → Newest)</option>
                    <option value="date_desc">Date (Newest → Oldest)</option>
                    <option value="expiring_soon">Expiring Soon</option>
                    <option value="active">Active</option>
                </select>


            </div>
        </div>

        <?php $lowStockItems = array_filter($items, fn($i) => $i['quantity'] <= 10); ?>
        <?php if (!empty($lowStockItems)): ?>
            <div class="alert alert-warning fade-message text-center inventory-alert" id="lowStockAlert"
                style="cursor: pointer;" onclick="showLowStockItems()">
                ⚠️ You have <?= count($lowStockItems) ?> item(s) running low on stock.
                <span style="text-decoration: underline; font-size: 0.9rem; margin-left:6px;">Click to view</span>
            </div>

            <!-- Show All button (hidden by default) -->
            <div class="text-center mb-3">
                <button id="showAllBtn" class="btn btn-outline-secondary btn-sm" style="display:none;"
                    onclick="showAllItems()">
                    Show All Items
                </button>
            </div>

        <?php endif; ?>
        <?php
        // 🔴 Expired or Expiring Soon items
        $expiringItems = array_filter($items, function ($i) {
            if (empty($i['expiration_date']) || $i['expiration_date'] === '0000-00-00')
                return false;
            $today = new DateTime();
            $expiration = new DateTime($i['expiration_date']);
            $daysLeft = (int) $today->diff($expiration)->format('%r%a');
            return $daysLeft <= 10; // includes expired (negative) and expiring soon
        });
        ?>

        <?php if (!empty($expiringItems)): ?>
            <a href="<?= site_url('items/expiringSoon') ?>" class="text-decoration-none">
                <div class="alert expiry-alert">
                    ⚠️ You have <?= count($expiringItems) ?> item(s) expiring soon!
                    <span style="font-size: 0.9rem; text-decoration: underline;">View details →</span>

                </div>
            </a>
        <?php endif; ?>



        <?php if (!empty($items) && is_array($items)): ?>
            <div class="p-4 bg-whitesmoke rounded shadow-sm">

                <!-- ✅ Top & Bottom Scrollbars -->
                <div class="table-scroll-wrapper">
                    <div class="table-scroll-top">
                        <div id="scroll-sync" style="height:1px;"></div>
                    </div>

                    <div class="table-responsive table-scroll-bottom">
                        <!-- ✅ Upload Excel Header Section -->

                        <!-- ✅ Item List Section with Summary -->
                        <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
                            <!-- Left side summary -->

                            </form>
                        </div>

                        <!-- ✅ Table -->
                        <table id="itemsTable" class="table table-bordered table-striped table-hover align-middle">
                            <thead class="table-dark text-center">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>Product ID</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Category</th>
                                    <th>Expiration Date</th>
                                    <th>Days Left</th>
                                    <th>Barcode</th>
                                    <th>Auto Delete</th>
                                    <th>Status</th>
                                    <th>Date Entry</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                    $today = new DateTime();

                                    // ✅ Handle expiration logic safely
                                    if (empty($item['expiration_date']) || $item['expiration_date'] === '0000-00-00') {
                                        $status = 'na';
                                        $statusLabel = "➖ N/A (No expiration)";
                                        $daysLeftText = "—";
                                    } else {
                                        $expiration = new DateTime($item['expiration_date']);
                                        $interval = $today->diff($expiration);
                                        $daysLeft = (int) $interval->format('%r%a');

                                        if ($daysLeft < 0) {
                                            $status = 'expired';
                                            $statusLabel = "🔴 Expired (" . abs($daysLeft) . " days ago)";
                                            $daysLeftText = abs($daysLeft) . " days ago";
                                        } elseif ($daysLeft <= 10) {
                                            $status = 'expiring soon';
                                            $statusLabel = "⚠️ Expiring Soon <br> ($daysLeft days left)";
                                            $daysLeftText = "$daysLeft days left";
                                        } else {
                                            $status = 'active';
                                            $statusLabel = "🟢 Active ($daysLeft days left)";
                                            $daysLeftText = "$daysLeft days left";
                                        }
                                    }

                                    // ✅ Low stock highlight
                                    $isLowStock = $item['quantity'] <= 10;
                                    ?>
                                    <tr class="text-center" data-id="<?= $item['id'] ?>">

                                        <td><input type="checkbox" class="item-checkbox" value="<?= $item['id'] ?>"></td>
                                        <td><?= esc($item['parcel_id']) ?></td>
                                        <td><?= esc($item['name']) ?></td>
                                        <td class="quantity-cell text-center <?= $isLowStock ? 'table-danger fw-bold' : '' ?>">
                                            <?php if ($isLowStock): ?>
                                                <div
                                                    style="display: flex; flex-direction: column; align-items: center; line-height: 1.3;">
                                                    <span style="font-weight: bold;"><?= esc($item['quantity']) ?></span>
                                                    <span class="badge bg-danger mt-1">Low Stock</span>
                                                </div>
                                            <?php else: ?>
                                                <?= esc($item['quantity']) ?>
                                            <?php endif; ?>
                                        </td>

                                        </td>
                                        <td><?= esc(number_format($item['price'], 2)) ?></td>
                                        <td><?= esc($item['category'] ?? '—') ?></td>
                                        <td><?= empty($item['expiration_date']) ? '—' : esc($item['expiration_date']) ?></td>
                                        <td><?= $daysLeftText ?></td>
                                        <td><?= esc($item['barcode']) ?></td>
                                        <td><?= $item['auto_delete'] ? 'Yes' : 'No' ?></td>
                                        <td class="status-badge" data-status="<?= $status ?>">
                                            <span class="badge 
                <?= $status == 'expired' ? 'bg-danger' :
                    ($status == 'expiring soon' ? 'bg-warning text-dark' :
                        ($status == 'na' ? 'bg-secondary' : 'bg-success')) ?>">
                                                <?= $statusLabel ?>
                                            </span>
                                        </td>
                                        <td><?= esc($item['created_at']) ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <!-- Edit -->
                                                <a href="<?= site_url('items/edit/' . $item['id']) ?>"
                                                    class="btn btn-sm btn-primary">
                                                    Edit
                                                </a>

                                                <!-- Quantity Control -->
                                                <div class="d-flex align-items-center quantity-control"
                                                    data-id="<?= $item['id'] ?>">
                                                    <button type="button" class="btn btn-sm btn-outline-danger open-qty-modal"
                                                        data-action="decrease">
                                                        <i class="bi bi-dash"></i>
                                                    </button>

                                                    <input type="number" class="form-control form-control-sm qty-amount mx-1"
                                                        readonly value="<?= esc($item['quantity']) ?>">

                                                    <button type="button" class="btn btn-sm btn-outline-success open-qty-modal"
                                                        data-action="increase">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>

                                                <!-- Delete (Separate Form, Now Properly Closed) -->
                                                <form action="<?= site_url('items/delete/' . $item['id']) ?>" method="post"
                                                    onsubmit="return confirm('Are you sure you want to delete this item?');"
                                                    class="m-0">
                                                    <?= csrf_field() ?>
                                                    <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                        data-id="<?= $item['id'] ?>">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>

                            </tbody>


                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center">No items found in the database.</div>
            <?php endif; ?>
        </div>

        <!-- Quantity Modal -->
        <div class="modal fade" id="qtyModal" tabindex="-1" aria-labelledby="qtyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="qtyModalLabel">Update Quantity</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="qtyForm">
                            <input type="hidden" id="itemId">
                            <input type="hidden" id="actionType">
                            <div class="mb-3">
                                <label for="productId" class="form-label">Product ID</label>
                                <input type="text" id="productId" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="quantityInput" class="form-label">Quantity</label>
                                <input type="number" id="quantityInput" class="form-control" min="1" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- ✅ Optional if you use CodeIgniter CSRF -->
        <!-- <meta name="csrf-token" content="<?= csrf_hash() ?>"> -->

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || null;

                /* ============================================================
                   1️⃣ QUANTITY MODAL (+ / −)
                ============================================================ */
                function openQtyModalHandler() {
                    const row = this.closest("tr");
                    if (!row) return;

                    const id = row.dataset.id;
                    const qtyCell = row.querySelector(".quantity-cell");
                    const currentQty = parseInt((qtyCell?.textContent || "").replace(/[^0-9]/g, "")) || 0;

                    document.getElementById("itemId").value = id;
                    document.getElementById("productId").value = id;
                    document.getElementById("actionType").value = this.dataset.action;
                    document.getElementById("quantityInput").value = 0;


                    const modal = new bootstrap.Modal(document.getElementById("qtyModal"));
                    modal.show();
                }

                function bindQtyModalEvents() {
                    document.querySelectorAll(".open-qty-modal").forEach(btn => {
                        btn.removeEventListener("click", openQtyModalHandler);
                        btn.addEventListener("click", openQtyModalHandler);
                    });
                }

                bindQtyModalEvents();

                // ✅ Handle quantity form submit
                document.getElementById("qtyForm").addEventListener("submit", async function (e) {
                    e.preventDefault(); // ✅ Prevent any normal form submission

                    const id = document.getElementById("itemId").value;
                    const action = document.getElementById("actionType").value;
                    const quantity = parseInt(document.getElementById("quantityInput").value);

                    let endpoint = "";
                    if (action === "increase") {
                        endpoint = "<?= site_url('items/increaseQuantity/') ?>" + id;
                    } else if (action === "decrease") {
                        endpoint = "<?= site_url('items/decreaseQuantity/') ?>" + id;
                    } else {
                        alert("Invalid action.");
                        return;
                    }

                    try {
                        const response = await fetch(endpoint, {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ amount: quantity })

                        });

                        const result = await response.json();

                        if (result.success) {
                            // Hide modal properly before reloading
                            const modal = bootstrap.Modal.getInstance(document.getElementById("qtyModal"));
                            modal.hide();

                            alert(result.message || "Quantity updated successfully!");
                            location.reload();
                        } else {
                            alert(result.message || "Error updating quantity!");
                        }
                    } catch (error) {
                        console.error(error);
                        alert("Something went wrong while updating quantity.");
                    }
                });


                /* ============================================================
                   2️⃣ SEARCH + CATEGORY FILTER
                ============================================================ */
                window.searchItems = function () {
                    const query = (document.getElementById("searchQuery")?.value || "").toLowerCase();
                    const filter = (document.getElementById("statusFilter")?.value || "all").toLowerCase();
                    const rows = document.querySelectorAll("#itemsTable tbody tr");

                    rows.forEach(row => {
                        const name = (row.children[2]?.textContent || "").toLowerCase();
                        const category = (row.children[5]?.textContent || "").toLowerCase();
                        const matchesQuery = name.includes(query);
                        const matchesFilter = (filter === "all" || category === filter);
                        row.style.display = (matchesQuery && matchesFilter) ? "" : "none";
                    });
                };
                window.filterStatus = window.searchItems;

                /* ============================================================
                   3️⃣ SORT ITEMS
                ============================================================ */
                window.sortItems = function () {
                    const sortValue = document.getElementById("sortFilter")?.value || "default";
                    const tbody = document.querySelector("#itemsTable tbody");
                    if (!tbody) return;

                    const rows = Array.from(tbody.querySelectorAll("tr"));

                    rows.sort((a, b) => {
                        const nameA = (a.children[2]?.textContent || "").trim().toLowerCase();
                        const nameB = (b.children[2]?.textContent || "").trim().toLowerCase();
                        const qtyA = parseFloat((a.children[3]?.textContent || "").replace(/[^0-9.-]/g, "")) || 0;
                        const qtyB = parseFloat((b.children[3]?.textContent || "").replace(/[^0-9.-]/g, "")) || 0;
                        const dateA = new Date(a.children[11]?.textContent.trim() || 0);
                        const dateB = new Date(b.children[11]?.textContent.trim() || 0);
                        const statusA = a.querySelector(".status-badge")?.dataset.status || "";
                        const statusB = b.querySelector(".status-badge")?.dataset.status || "";

                        switch (sortValue) {
                            case "name_asc": return nameA.localeCompare(nameB);
                            case "name_desc": return nameB.localeCompare(nameA);
                            case "quantity_asc": return qtyA - qtyB;
                            case "quantity_desc": return qtyB - qtyA;
                            case "date_asc": return dateA - dateB;
                            case "date_desc": return dateB - dateA;
                            case "expiring_soon":
                                if (statusA === "expiring soon" && statusB !== "expiring soon") return -1;
                                if (statusA !== "expiring soon" && statusB === "expiring soon") return 1;
                                return 0;
                            case "active":
                                if (statusA === "active" && statusB !== "active") return -1;
                                if (statusA !== "active" && statusB === "active") return 1;
                                return 0;
                            default: return 0;
                        }
                    });

                    tbody.innerHTML = "";
                    rows.forEach(r => tbody.appendChild(r));
                };

                /* ============================================================
                   4️⃣ LOW STOCK FILTER
                ============================================================ */
                window.showLowStockItems = function () {
                    const rows = document.querySelectorAll("#itemsTable tbody tr");
                    let found = 0;

                    rows.forEach(row => {
                        const qtyText = (row.children[3]?.textContent || "").trim();
                        const qty = parseInt(qtyText.replace(/[^0-9-]/g, "")) || 0;
                        if (qty <= 10) {
                            row.style.display = "";
                            found++;
                        } else {
                            row.style.display = "none";
                        }
                    });

                    const btn = document.getElementById("showAllBtn");
                    if (btn) btn.style.display = found ? "inline-block" : "none";
                    if (!found) alert("No low-stock items found.");
                };

                window.showAllItems = function () {
                    document.querySelectorAll("#itemsTable tbody tr").forEach(r => r.style.display = "");
                    const btn = document.getElementById("showAllBtn");
                    if (btn) btn.style.display = "none";
                };

                /* ============================================================
                   5️⃣ SELECT ALL CHECKBOXES
                ============================================================ */
                const selectAll = document.getElementById("selectAll");
                const checkboxes = document.querySelectorAll(".item-checkbox");

                if (selectAll) {
                    selectAll.addEventListener("change", function () {
                        checkboxes.forEach(c => c.checked = this.checked);
                    });
                }

                checkboxes.forEach(cb => {
                    cb.addEventListener("change", function () {
                        if (!this.checked) selectAll.checked = false;
                        else if (Array.from(checkboxes).every(c => c.checked)) selectAll.checked = true;
                    });
                });

                /* ============================================================
                   6️⃣ DELETE MULTIPLE
                ============================================================ */
                document.querySelectorAll(".delete-btn").forEach(btn => {
                    btn.addEventListener("click", async function (e) {
                        e.preventDefault();

                        const selectedIds = Array.from(document.querySelectorAll(".item-checkbox:checked")).map(x => x.value);
                        const tr = this.closest("tr");
                        const singleId = tr?.querySelector(".item-checkbox")?.value;
                        const ids = selectedIds.length ? selectedIds : [singleId];

                        if (!ids.length) return alert("Please select at least one item.");
                        if (!confirm(`Delete ${ids.length} item(s)?`)) return;

                        const headers = {
                            "Content-Type": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        };
                        if (csrfToken) headers["X-CSRF-TOKEN"] = csrfToken;

                        try {
                            const res = await fetch("<?= site_url('items/deleteMultiple') ?>", {
                                method: "POST",
                                headers,
                                body: JSON.stringify({ ids })
                            });

                            const json = await res.json();
                            alert(json.message || "Done");
                            if (json.success) location.reload();
                        } catch (err) {
                            console.error("Delete error", err);
                            alert("Delete failed. See console/network.");
                        }
                    });
                });

                /* ============================================================
                   7️⃣ AUTO REBIND
                ============================================================ */
                const tableBody = document.querySelector("#itemsTable tbody");
                if (tableBody) {
                    const mo = new MutationObserver(() => bindQtyModalEvents());
                    mo.observe(tableBody, { childList: true, subtree: true });
                }

            });
        </script>



</body>

</html>


<div class="row my-4">
    <div class="col-md-6">
        <div class="card p-3 mb-3">
            <h5>Expiring items (30 days)</h5>
            <canvas id="expiringChart" style="height:220px;"></canvas>
        </div>
    </div>
</div>
<script>
    fetch('<?= site_url("/api/expiring-data") ?>').then(r => r.json()).then(json => {
        const ctx = document.getElementById('expiringChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: { labels: json.labels, datasets: [{ label: 'Expiring items', data: json.data }] },
            options: { responsive: true, maintainAspectRatio: false }
        });
    });
</script>
<?php include APPPATH . 'Views/partials/footer.php'; ?>