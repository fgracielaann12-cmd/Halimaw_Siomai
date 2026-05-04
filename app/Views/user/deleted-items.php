<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Expired Items | Halimaw_Siomai</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background-color: #e9ecef;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2 {
      font-weight: 600;
      color: #343a40;
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

    #itemsTable {
      border-radius: 5px;
      overflow: hidden;
      background: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      min-width: 1100px;
    }

    #itemsTable th {
      background-color: #212529;
      color: #ffffff;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      font-size: 0.85rem;
      padding: 12px;
      vertical-align: middle;
    }

    #itemsTable td {
      vertical-align: middle;
      text-align: center;
      font-size: 0.9rem;
      padding: 10px 8px;
      border-color: #dee2e6;
    }

    #itemsTable tbody tr {
      transition: background-color 0.25s ease, transform 0.15s ease;
    }

    #itemsTable tbody tr:hover {
      background-color: #f8f9fa;
      transform: scale(1.002);
    }

    #itemsTable tbody tr:nth-child(even) {
      background-color: #fcfcfc;
    }

    .badge {
      font-size: 0.8rem;
      font-weight: 500;
      border-radius: 0.4rem;
      padding: 0.4em 0.6em;
    }

    .bg-warning.text-dark {
      background-color: #c82333 !important;
      color: #fff !important;
      border: 1px solid #991b1b;
    }

    .bg-warning.text-light {
      background-color: #e4ce08 !important;
      color: #000 !important;
      border: 1px solid #b9aa00;
    }

    .bg-secondary {
      background-color: #27557a !important;
      color: #fff !important;
      border: 1px solid #1d2c38;
    }

    .btn-sm {
      border-radius: 6px;
      padding: 5px 8px;
    }

    .search-sort-bar {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 12px;
    }

    .search-sort-bar input {
      width: 250px;
    }

    .form-select-sm {
      width: 160px;
    }

    .table-scroll-wrapper {
      position: relative;
      margin-top: 8px;
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

    a.btn:hover,
    button.btn-outline-dark:hover {
      background-color: #212529 !important;
      color: #fff !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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
            z-index: 1050 !important;
        }
    </style>
</head>

<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-2" href="<?= site_url('items') ?>">
        <img src="<?= base_url('Images/Inventa.png') ?>" alt="Inventa Logo" style="width:45px;height:45px;">
        <span class="brand-text">Inventa</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto gap-3">
          <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items') ?>">Dashboard</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/expiringSoon') ?>">Expiring
              Soon</a></li>
          <li class="nav-item"><a class="nav-link active text-white" href="<?= site_url('items/deleted') ?>">Expired</a>
          </li>
          <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/logs') ?>">Audit Logs</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('/logout') ?>">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Content -->
  <div class="container mt-5">

    <a href="<?= base_url('/items') ?>" class="btn btn-outline-dark fw-semibold rounded-1 shadow-sm px-3 py-1 mb-3">
      Back to Dashboard
    </a>

    <!-- Flash messages -->
    <?php if (session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show shadow-sm">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php elseif (session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <?php if (empty($items) && empty($deletedItems)): ?>
      <div class="alert alert-warning text-center shadow-sm">No expired items found.</div>
    <?php else: ?>

      <!-- Search + Sort -->
      <div class="search-sort-bar">
        <div class="position-relative">
          <input type="text" id="searchQuery" class="form-control ps-4 rounded-1 shadow-sm"
            placeholder="Search by Product ID or Name" oninput="searchItems()">
        </div>

        <label for="categoryFilter" class="fw-bold mb-0 ms-2">Category:</label>
        <select id="categoryFilter" class="form-select form-select-sm" onchange="filterAndSort()">
          <option value="all">All</option>
          <option value="food">Food</option>
          <option value="non-food">Non-Food</option>
        </select>

        <label for="sortFilter" class="fw-bold mb-0 ms-2">Sort:</label>
        <select id="sortFilter" class="form-select form-select-sm" onchange="filterAndSort()">
          <option value="deleted_desc">Latest Deleted</option>
          <option value="deleted_asc">Oldest Deleted</option>
          <option value="name_asc">Name (A–Z)</option>
          <option value="name_desc">Name (Z–A)</option>
          <option value="quantity_asc">Quantity (Low → High)</option>
          <option value="quantity_desc">Quantity (High → Low)</option>
          <option value="date_asc">Date (Oldest → Newest)</option>
          <option value="date_desc">Date (Newest → Oldest)</option>
        </select>

        <label for="deleteTypeFilter" class="fw-bold mb-0 ms-2">Delete Type:</label>
        <select id="deleteTypeFilter" class="form-select form-select-sm" onchange="filterAndSort()">
          <option value="all">All</option>
          <option value="auto">Auto Deleted</option>
          <option value="manual">Manually Deleted</option>
        </select>
      </div>

      <!-- Table -->
      <div class="p-4 bg-white rounded shadow-sm">
        <div class="table-responsive">
          <table id="itemsTable" class="table table-bordered table-hover align-middle">
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
              <?php if (!empty($items)): ?>
                <?php foreach ($items as $item): ?>
                  <?php
                  $today = new DateTime();
                  $expiration = new DateTime($item['expiration_date']);
                  $interval = $today->diff($expiration);
                  $daysLeft = (int) $interval->format('%r%a');
                  if ($daysLeft >= 0)
                    continue;
                  ?>
                  <tr>
                    <td><?= esc($item['product_id']) ?></td>
                    <td class="text-start"><?= esc($item['name']) ?></td>
                    <td><?= esc($item['category'] ?? 'N/A') ?></td>
                    <td><?= esc($item['quantity']) ?></td>
                    <td>₱<?= esc(number_format($item['price'], 2)) ?></td>
                    <td><?= esc($item['expiration_date']) ?></td>
                    <td><span class="text-danger fw-semibold">Expired <?= abs($daysLeft) ?> days ago</span></td>
                    <td><?= esc($item['barcode']) ?></td>
                    <td><span class="badge <?= $item['auto_delete'] ? 'bg-warning text-dark' : 'bg-secondary' ?>">
                        <?= $item['auto_delete'] ? 'Auto Deleted' : 'Manually Deleted' ?></span></td>
                    <td><span class="badge bg-danger">Expired</span></td>
                    <td><?= esc($item['created_at']) ?></td>
                    <td>-</td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>

              <?php if (!empty($deletedItems)): ?>
                <?php foreach ($deletedItems as $item): ?>
                  <?php
                  // calculate how many days between expiration and deletion
                  $daysExpired = null;

                  if (!empty($item['expiration_date']) && !empty($item['deleted_at'])) {
                    $expDate = strtotime($item['expiration_date']);
                    $delDate = strtotime($item['deleted_at']);

                    $daysExpired = floor(($delDate - $expDate) / (60 * 60 * 24));
                    $daysExpired = max(0, $daysExpired); // ✅ prevent negative days
                  }

                  // build the label shown in the "Days Expired" column
                  if ($daysExpired !== null) {
                    $daysLabel = "Deleted {$daysExpired} day" . ($daysExpired == 1 ? '' : 's') . " ago";
                  } else {
                    $daysLabel = "No expiration date";
                  }

                  // determine delete type text
// determine delete type text correctly
                  if (strpos($item['status'], 'manual') !== false) {
                    $deleteType = 'Manually Deleted';
                  } elseif (strpos($item['status'], 'auto') !== false) {
                    $deleteType = 'Auto Deleted';
                  } else {
                    $deleteType = ucfirst($item['status']);
                  }

                  ?>
                  <tr>
                    <td><?= esc($item['product_id']) ?></td>
                    <td><?= esc($item['name']) ?></td>
                    <td><?= esc($item['category']) ?></td>
                    <td><?= esc($item['quantity']) ?></td>
                    <td>₱<?= number_format($item['price'], 2) ?></td>
                    <td><?= esc($item['expiration_date'] ?? 'N/A') ?></td>
                    <td><?= $daysLabel ?></td>
                    <td><?= esc($item['barcode'] ?? 'N/A') ?></td>
                    <td>
                      <span
                        class="badge <?= (strpos($deleteType, 'Auto') !== false) ? 'bg-warning text-dark' : 'bg-secondary' ?>">
                        <?= $deleteType ?>
                      </span>
                    </td>

                    <td><?= esc($item['status']) ?></td>
                    <td><?= esc($item['created_at']) ?></td>
                    <td><?= esc($item['deleted_at']) ?></td>
                  </tr>
                <?php endforeach; ?>

              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function searchItems() { filterAndSort(); }

    function filterAndSort() {
      const query = (document.getElementById("searchQuery")?.value || '').toLowerCase();
      const category = document.getElementById("categoryFilter").value;
      const sort = document.getElementById("sortFilter").value;
      const deleteType = document.getElementById("deleteTypeFilter").value;
      const table = document.getElementById("itemsTable");
      const rows = Array.from(table.querySelectorAll("tbody tr"));

      rows.forEach(row => {
        const product = (row.children[0]?.textContent || '').toLowerCase();
        const name = (row.children[1]?.textContent || '').toLowerCase();
        const cat = (row.children[2]?.textContent || '').toLowerCase();
        const deleteTypeText = (row.children[8]?.textContent || '').toLowerCase();

        const matchSearch = product.includes(query) || name.includes(query);
        const matchCategory = category === "all" || cat === category;
        const matchDeleteType =
          deleteType === "all" ||
          (deleteType === "auto" && deleteTypeText.includes("auto")) ||
          (deleteType === "manual" && deleteTypeText.includes("manual"));

        row.style.display = (matchSearch && matchCategory && matchDeleteType) ? "" : "none";
      });

      const visibleRows = rows.filter(r => r.style.display !== "none");
      const getVal = (r, i) => (r.children[i]?.textContent || '').trim().toLowerCase();

      visibleRows.sort((a, b) => {
        switch (sort) {
          case "name_asc":
            return getVal(a, 1).localeCompare(getVal(b, 1));
          case "name_desc":
            return getVal(b, 1).localeCompare(getVal(a, 1));
          case "quantity_asc":
            return (parseInt(getVal(a, 3)) || 0) - (parseInt(getVal(b, 3)) || 0);
          case "quantity_desc":
            return (parseInt(getVal(b, 3)) || 0) - (parseInt(getVal(a, 3)) || 0);
          case "date_asc":
            return new Date(getVal(a, 5)) - new Date(getVal(b, 5));
          case "date_desc":
            return new Date(getVal(b, 5)) - new Date(getVal(a, 5));
          case "deleted_asc": {
            const dateA = getVal(a, 11) ? new Date(getVal(a, 11)) : new Date(0);
            const dateB = getVal(b, 11) ? new Date(getVal(b, 11)) : new Date(0);
            return dateA - dateB;
          }
          // ✅ Sort by latest deleted (manual or auto)
          case "deleted_desc":
          default: {
            const typeA = getVal(a, 8); // column for Delete Type
            const typeB = getVal(b, 8);
            const dateA = getVal(a, 11) ? new Date(getVal(a, 11)) : new Date(0);
            const dateB = getVal(b, 11) ? new Date(getVal(b, 11)) : new Date(0);

            // ✅ Manual deleted first, then sort by latest Deleted At
            if (typeA.includes("manual") && !typeB.includes("manual")) return -1;
            if (!typeA.includes("manual") && typeB.includes("manual")) return 1;
            return dateB - dateA;
          }

        }
      });

      const tbody = table.querySelector("tbody");
      visibleRows.forEach(r => tbody.appendChild(r));
    }

    // ✅ Default sort when page loads
    document.addEventListener('DOMContentLoaded', () => {
      const sortSelect = document.getElementById('sortFilter');
      sortSelect.value = 'deleted_desc'; // latest deleted first
      filterAndSort();
    });
  </script>


</body>

</html>