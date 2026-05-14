<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Add Item | Halimaw POS Inventory System</title>
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
        * { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        @keyframes fadeSlideDown { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeScaleUp { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
        .top-navbar { position: sticky; top: 0; z-index: 1000; animation: fadeSlideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .container > .row, .controls-section, .summary-card { opacity: 0; animation: fadeScaleUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s forwards; }
        body { background-color: #f8f9fc; color: #3a3b45; margin: 0; padding: 0; display: flex; overflow-x: clip; }
        #sidebar { width: var(--sidebar-width); background: var(--sidebar-bg); color: var(--sidebar-text); height: 100vh; position: fixed; top: 0; left: 0; z-index: 1050; transition: transform 0.3s ease; display: flex; flex-direction: column; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); }
        #sidebar .nav { width: 100%; min-width: 0; }
        #sidebar .nav-item { width: 100%; min-width: 0; }
        #sidebar .navbar-brand { padding: 1.25rem 1.5rem; font-size: 1.15rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        #sidebar .navbar-brand img { width: 44px; height: 44px; border-radius: 6px; background-color: #f0f2f5; padding: 2px; }
        #sidebar .nav-link { color: var(--sidebar-text); padding: 0.75rem 1.25rem; margin: 0.25rem 1rem; border-radius: 0.4rem; font-size: 0.95rem; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.75rem; font-weight: 500; text-decoration: none; white-space: normal; line-height: 1.2; overflow: hidden; width: calc(100% - 2rem); }
        #sidebar .nav-link:hover { transform: translateX(5px); box-shadow: 0 4px 10px rgba(0,0,0,0.1); background-color: var(--sidebar-hover); color: white; }
        @keyframes navGlow { 0% { box-shadow: 0 0 5px rgba(78,115,223,0.3); filter: brightness(1); } 50% { box-shadow: 0 0 15px rgba(78,115,223,0.9); filter: brightness(1.2); } 100% { box-shadow: 0 0 5px rgba(78,115,223,0.3); filter: brightness(1); } }
        #sidebar .nav-link.active { background: linear-gradient(90deg, var(--sidebar-hover), var(--sidebar-active)); color: white; font-weight: 500; border-radius: 0.4rem; animation: navGlow 2s infinite ease-in-out; text-decoration: none; white-space: normal; line-height: 1.2; overflow: hidden; width: calc(100% - 2rem); }
        .nav-link.text-danger { color: #ff6b6b !important; }
        .nav-link.text-danger:hover { background: rgba(231, 74, 59, 0.15); color: var(--danger) !important; }
        .main-content { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); min-height: 100vh; transition: margin-left 0.3s ease; }
        .mobile-menu-toggle { display: none; position: fixed; top: 15px; left: 15px; z-index: 998; background: var(--sidebar-bg); color: white; border: none; border-radius: 50%; width: 40px; height: 40px; font-size: 1.2rem; cursor: pointer; box-shadow: var(--card-shadow); }
        .page-content-container { display: flex; justify-content: center; padding: 30px 20px; min-height: calc(100vh - 66px); }
        .form-card { width: 100%; max-width: 500px; padding: 30px; background: white; border-radius: 5px; box-shadow: var(--card-shadow); }
        h2 { font-weight: 700; text-align: center; margin-bottom: 30px; color: var(--dark); font-size: 1.5rem; }
        .form-control, .form-select { font-size: 0.95rem; border-radius: 5px; border: 1px solid #ddd; padding: 10px 12px; margin: 12px 0; transition: all 0.2s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25); outline: none; }
        .form-check { margin: 12px 0; }
        .form-check-input { margin-right: 8px; }
        .form-check-label { font-weight: 500; }
        .file-upload-container { margin-top: 20px; padding: 25px; border: 2px dashed #ddd; border-radius: 5px; background-color: #fafafa; text-align: center; transition: all 0.3s; cursor: pointer; }
        .file-upload-container.dragover, .file-upload-container:hover { background-color: #f0f8ff; border-color: var(--primary); }
        .file-upload-label { display: block; color: var(--primary); font-weight: 600; }
        .file-name-display { margin-top: 12px; background: #eef5ff; padding: 8px 12px; border-radius: 6px; display: none; align-items: center; gap: 10px; justify-content: center; color: var(--dark); }
        .remove-file-btn { color: var(--danger); cursor: pointer; font-size: 1.3rem; }
        .submit-button { width: 100%; padding: 12px; background: var(--primary); color: white; border: none; border-radius: 5px; cursor: pointer; margin-top: 25px; font-size: 1.05rem; font-weight: 600; transition: all 0.2s; }
        .submit-button:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .fade-message { padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; text-align: center; }
        .alert-success { background-color: #e6f4ea; color: #1e5631; }
        .alert-danger { background-color: #fde8e8; color: #721c24; }


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
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
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
            width: 44px;
            height: 44px;
            border-radius: 6px;
            background-color: #f0f2f5;
            padding: 2px;
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

        /* PAGE LAYOUT */
        .page-content-container {
            display: flex;
            justify-content: center;
            padding: 5px 20px 30px 20px;
            min-height: calc(100vh - 66px);
        }

        .form-card {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background: white;
            border-radius: 5px;
            box-shadow: var(--card-shadow);
        }

        h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
            color: var(--dark);
            font-size: 1.5rem;
        }

        /* FORM CONTROLS */
        .form-control, .form-select {
            font-size: 0.95rem;
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px 12px;
            margin: 12px 0;
            transition: all 0.2s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
            outline: none;
        }

        .form-check {
            margin: 12px 0;
        }

        .form-check-input {
            margin-right: 8px;
        }

        .form-check-label {
            font-weight: 500;
        }

        /* FILE UPLOAD */
        .file-upload-container {
            margin-top: 20px;
            padding: 25px;
            border: 2px dashed #ddd;
            border-radius: 5px;
            background-color: #fafafa;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .file-upload-container.dragover,
        .file-upload-container:hover {
            background-color: #f0f8ff;
            border-color: var(--primary);
        }

        .file-upload-label {
            display: block;
            color: var(--primary);
=======
        /* Pack Sizes Section */
        .pack-sizes-header {
            font-size: 0.85rem;
>>>>>>> 9540bbc6c32afc140d67be9ea08283a106b5b29b
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 10px;
        }
        .pack-row .form-control {
            margin: 4px 0;
            padding: 8px 10px;
            font-size: 0.88rem;
        }
        .total-qty-display {
            background: #f0f4ff;
            border: 1px solid #d0dbff;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.9rem;
            color: #4e73df;
            font-weight: 500;
            margin: 8px 0 16px 0;
        }

        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            #sidebar { transform: translateX(-100%); }
            #sidebar .nav { width: 100%; min-width: 0; }
            #sidebar .nav-item { width: 100%; min-width: 0; }
            .main-content { margin-left: 0; width: 100%; }
            .top-navbar { position: sticky; top: 0; z-index: 1000; border-radius: 0; margin-bottom: 15px; }
            #sidebar.active { transform: translateX(0); }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1040; }
            .sidebar-overlay.active { display: block; }
            .form-card { padding: 20px; }
        }

        button, .btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .submit-button, a.btn {
            border-radius: 12px !important;
        }
    </style>

    <style>
        :root { --border-radius: 12px !important; }
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn,
        input, select, textarea, .form-control, .form-select,
        .table, .table-card, .table-responsive, table,
        .card, .pos-item-card, .summary-card, .alert, .badge, .modal-content, .modal-header, .nav-link, .login-card,
        .rounded, .rounded-1, .rounded-2, .rounded-3 {
            border-radius: 12px !important;
        }
        .table thead th, table thead th, .table th {
            position: sticky !important; top: -1px !important; z-index: 10 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
            background-color: var(--primary, #4e73df) !important; color: white !important;
        }
        .controls-section { position: relative; z-index: 10 !important; }
    </style>

    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function () { history.go(1); };
        function enforceClientAuth() {
            if (localStorage.getItem('auth_status') === 'logged_out') {
                document.documentElement.style.display = 'none';
                if(document.body) document.body.style.display = 'none';
                window.location.replace('/Halimaw_Siomai/index.php/login?blocked=1&cb=' + new Date().getTime());
            }
        }
        enforceClientAuth();
        window.addEventListener('pageshow', enforceClientAuth);
    </script>
</head>
<body>

<?= view('partials/admin_sidebar') ?>

<div class="main-content">
    <div class="p-3 pb-0">
        <a href="<?= site_url('items') ?>" class="btn btn-light shadow-sm border d-inline-flex align-items-center gap-2" style="border-radius: 8px; padding: 8px 16px; font-weight: 500; color: #4e73df;">
            <i class="bi bi-arrow-left" style="font-size: 1.1rem;"></i>
            <span>Back to Inventory</span>
        </a>
    </div>
    <div class="page-content-container">
        <div class="form-card">
            <h2>Add New Item</h2>

            <?php
                $errors = session()->getFlashdata('errors');
                $error  = session()->getFlashdata('error');
            ?>
            <?php if ($error): ?>
                <div class="alert alert-danger rounded" style="font-size:0.9rem;">
                    <?= esc($error) ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger rounded" style="font-size:0.9rem;">
                    <ul class="mb-0">
                        <?php foreach ($errors as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="itemForm" method="post"
                  action="<?= base_url('/index.php/items/store') ?>"
                  enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>

                <!-- Product ID -->
                <div class="mb-3">
                    <input type="text" name="product_id" id="productIdInput"
                           class="form-control <?= isset($errors['product_id']) ? 'is-invalid' : '' ?>"
                           placeholder="e.g. P001"
                           value="<?= esc($nextProductId ?? old('product_id')) ?>"
                           maxlength="20" required>
                    <?php if (isset($errors['product_id'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['product_id']) ?></div>
                    <?php endif; ?>
                    <small class="text-muted">Auto-generated suggestion. You may change this.</small>
                </div>

                <!-- Product Name -->
                <div class="mb-3">
                    <input type="text" name="name"
                           class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                           placeholder="Product Name"
                           value="<?= old('name') ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['name']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- SKU -->
                <div class="mb-3">
                    <input type="text" name="sku"
                           class="form-control <?= isset($errors['sku']) ? 'is-invalid' : '' ?>"
                           placeholder="SKU Code (e.g. PRK-SMAI-S12)"
                           value="<?= old('sku') ?>" required>
                    <?php if (isset($errors['sku'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['sku']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- =============================================
                     PACK SIZES — Always visible, no toggle
                     ============================================= -->
                <div class="card mb-3 border-0 shadow-sm p-3">
                    <div class="pack-sizes-header">
                        <i class="bi bi-tags me-1"></i>
                        <small class="text-muted fw-normal ms-1"></small>
                    </div>

                    <!-- Column headers -->
                    <div class="row mb-1 fw-bold text-secondary" style="font-size:0.8rem;">
                        <div class="col-4">Label</div>
                        <div class="col-3">Qty</div>
                        <div class="col-4">Price (₱)</div>
                        <div class="col-1"></div>
                    </div>

                    <!-- Dynamic rows injected here -->
                    <div id="packRowsWrapper"></div>

                    <!-- Add row button -->
                    <button type="button" id="addPackRowBtn"
                            class="btn btn-sm btn-outline-primary mt-2">
                        <i class="bi bi-plus-lg me-1"></i> Add Size
                    </button>

                    <!-- Auto-calculated total -->
                    <div class="total-qty-display mt-3">
                        Total Qty: <strong id="totalQtyValue">0</strong>
                        <small class="text-muted">(auto-calculated from pack sizes)</small>
                    </div>
                    <!-- Hidden input carries total to controller -->
                    <input type="hidden" name="total_quantity" id="totalQtyHidden" value="0">
                </div>
                <!-- END PACK SIZES -->

                <!-- Expiration Date -->
                <div class="mb-3">
                    <input type="date" name="expiration_date" id="expiration_date"
                           class="form-control <?= isset($errors['expiration_date']) ? 'is-invalid' : '' ?>"
                           value="<?= old('expiration_date') ?>">
                    <?php if (isset($errors['expiration_date'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['expiration_date']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Category -->
                <div class="mb-3">
                    <select name="category" id="category"
                            class="form-select <?= isset($errors['category']) ? 'is-invalid' : '' ?>"
                            required>
                        <option value="">Select Category</option>
                        <option value="Food"      <?= old('category') === 'Food'      ? 'selected' : '' ?>>Food</option>
                        <option value="Non-Food"  <?= old('category') === 'Non-Food'  ? 'selected' : '' ?>>Non-Food</option>
                        <option value="Condiments"<?= old('category') === 'Condiments'? 'selected' : '' ?>>Condiments</option>
                    </select>
                    <?php if (isset($errors['category'])): ?>
                        <div class="invalid-feedback"><?= esc($errors['category']) ?></div>
                    <?php endif; ?>
                </div>

                <!-- Subcategory (Non-Food only) -->
                <div id="subcategoryContainer" style="display:none;" class="mb-3">
                    <select name="subcategory" id="subcategory" class="form-select">
                        <option value="">Select Subcategory</option>
                        <option value="Expirable"     <?= old('subcategory') === 'Expirable'     ? 'selected' : '' ?>>Expirable</option>
                        <option value="Non-Expirable" <?= old('subcategory') === 'Non-Expirable' ? 'selected' : '' ?>>Non-Expirable</option>
                    </select>
                </div>

                <!-- Auto Delete -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="auto_delete"
                           id="auto_delete" <?= old('auto_delete') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="auto_delete">Auto Delete on Expiry</label>
                </div>

                <!-- Product Image -->
                <div class="file-upload-container mt-3" id="imageDropArea"
                     style="cursor:pointer; position:relative; padding:0;">
                    <div class="file-upload-label" style="cursor:pointer; display:block; padding:30px;">
                        Drag &amp; Drop Product Image here or<br>
                        <span style="text-decoration:underline;">Click to Browse</span><br>
                        <small class="text-muted">(JPG, PNG, WEBP)</small>
                    </div>
                    <input type="file" name="product_image" id="product_image"
                           accept=".jpg,.jpeg,.png,.webp" style="display:none;">
                    <div id="imagePreviewContainer" style="display:none; margin-bottom:15px; position:relative;">
                        <img id="imagePreview" src=""
                             style="max-width:100%; max-height:150px; object-fit:contain; border-radius:8px; border:1px solid #ddd; padding:3px; background:white;">
                    </div>
                    <div id="imageNameDisplay" class="file-name-display mb-3"
                         style="display:none; margin-left:15px; margin-right:15px;">
                        <span id="imageName"></span>
                        <i class="bi bi-x-circle remove-file-btn" id="removeImageBtn"></i>
                    </div>
                </div>

                <button class="submit-button" type="submit">Add New Item</button>
            </form>

            <hr class="my-4">

            <!-- BULK UPLOAD -->
            <h2>Bulk Upload</h2>
            <form id="uploadForm" method="post"
                  action="<?= base_url('/items/bulk-upload') ?>"
                  enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="file-upload-container" id="dropArea">
                    <div class="file-upload-label">
                        Drag &amp; Drop CSV here or<br>
                        <span style="text-decoration:underline;">Click to Browse</span>
                    </div>
                    <input type="file" name="bulk_file" id="bulk_file"
                           accept=".csv" style="display:none;">
                    <div id="fileNameDisplay" class="file-name-display">
                        <span id="fileName"></span>
                        <i class="bi bi-x-circle remove-file-btn" id="removeFileBtn"></i>
                    </div>
                    <div class="form-text mt-2 text-muted">
                        Format: <b>product_id, name, sku, price, quantity, category, expiration_date, auto_delete, image_path</b><br>
                        <small>Download
                            <a href="<?= base_url('items/download-sample-template') ?>"
                               class="text-primary"
                               onclick="event.stopPropagation()"
                               download>sample template</a>
                        </small>
                    </div>
                </div>
                <button class="submit-button mt-3" type="submit">Upload CSV File</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    /* =============================================
       MOBILE SIDEBAR
       ============================================= */
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar           = document.getElementById('sidebar');
    const sidebarOverlay    = document.getElementById('sidebarOverlay');
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
    document.querySelectorAll('#sidebar .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    /* =============================================
       PACK SIZE ROWS — Always visible, no toggle
       ============================================= */
    const packRowsWrapper = document.getElementById('packRowsWrapper');
    const addPackRowBtn   = document.getElementById('addPackRowBtn');
    const totalQtyValue   = document.getElementById('totalQtyValue');
    const totalQtyHidden  = document.getElementById('totalQtyHidden');

    function generateSuffix(label) {
        const words = label.trim().split(/\s+/);
        if (words.length === 1) {
            return label.substring(0, 3).toUpperCase();
        }
        return words.map(w => w[0].toUpperCase()).join('');
    }

    function reindexRows() {
        packRowsWrapper.querySelectorAll('.pack-row').forEach((row, i) => {
            row.querySelector('.pack-label-input').name = `variations[${i}][label]`;
            row.querySelector('.pack-qty-input').name   = `variations[${i}][qty]`;
            row.querySelector('.pack-price-input').name = `variations[${i}][price]`;
        });
    }

    function updateRemoveBtns() {
        const rows = packRowsWrapper.querySelectorAll('.pack-row');
        rows.forEach(r => {
            r.querySelector('.remove-pack-row').disabled = (rows.length === 1);
        });
    }

    function recalcTotal() {
        let total = 0;
        packRowsWrapper.querySelectorAll('.pack-qty-input').forEach(inp => {
            total += parseInt(inp.value) || 0;
        });
        totalQtyValue.textContent = total;
        totalQtyHidden.value      = total;
    }

    function createPackRow(defaultLabel = '') {
        const idx = packRowsWrapper.children.length;
        const row = document.createElement('div');
        row.className = 'pack-row row mb-2 align-items-center';
        row.innerHTML = `
            <div class="col-4">
                <input type="text"
                       name="variations[${idx}][label]"
                       class="form-control pack-label-input"
                       placeholder="e.g. Small"
                       maxlength="30"
                       value="${defaultLabel}"
                       required
                       pattern="[A-Za-z0-9 \\-]+"
                       title="Letters, numbers, spaces and hyphens only">
            </div>
            <div class="col-3">
                <input type="number"
                       name="variations[${idx}][qty]"
                       class="form-control pack-qty-input"
                       placeholder="Qty" min="0" required>
            </div>
            <div class="col-4">
                <input type="number"
                       name="variations[${idx}][price]"
                       class="form-control pack-price-input"
                       placeholder="Price" step="0.01" min="0.01" required>
            </div>
            <div class="col-1">
                <button type="button"
                        class="btn btn-sm btn-outline-danger remove-pack-row"
                        title="Remove row">
                    <i class="bi bi-dash"></i>
                </button>
            </div>`;
        packRowsWrapper.appendChild(row);
        row.querySelector('.pack-qty-input').addEventListener('input', recalcTotal);
        updateRemoveBtns();
    }

    // Default 3 rows on load
    ['Small', 'Medium', 'Large'].forEach(label => createPackRow(label));

    // Add row
    addPackRowBtn.addEventListener('click', () => {
        createPackRow('');
        reindexRows();
        updateRemoveBtns();
    });

    // Remove row
    packRowsWrapper.addEventListener('click', e => {
        const btn = e.target.closest('.remove-pack-row');
        if (!btn) return;
        const rows = packRowsWrapper.querySelectorAll('.pack-row');
        if (rows.length > 1) {
            btn.closest('.pack-row').remove();
            reindexRows();
            updateRemoveBtns();
            recalcTotal();
        }
    });

    /* =============================================
       CATEGORY / EXPIRATION LOGIC
       ============================================= */
    const categorySelect      = document.getElementById('category');
    const subcategoryContainer = document.getElementById('subcategoryContainer');
    const subcategorySelect   = document.getElementById('subcategory');
    const autoDelete          = document.getElementById('auto_delete');
    const expirationInput     = document.getElementById('expiration_date');
    const itemForm            = document.getElementById('itemForm');

    function handleCategory() {
        if (categorySelect.value === 'Non-Food') {
            subcategoryContainer.style.display = 'block';
        } else {
            subcategoryContainer.style.display = 'none';
            subcategorySelect.value = '';
        }
        const isExpirable =
            categorySelect.value === 'Food' ||
            categorySelect.value === 'Condiments' ||
            (categorySelect.value === 'Non-Food' && subcategorySelect.value === 'Expirable');
        const isNonExpirable =
            categorySelect.value === 'Non-Food' && subcategorySelect.value === 'Non-Expirable';

        if (isExpirable) {
            expirationInput.disabled = false;
            expirationInput.required = true;
            expirationInput.classList.remove('disabled');
            if (autoDelete) { autoDelete.disabled = false; }
        } else if (isNonExpirable) {
            expirationInput.value    = '';
            expirationInput.disabled = true;
            expirationInput.required = false;
            expirationInput.classList.add('disabled');
            if (autoDelete) { autoDelete.disabled = true; autoDelete.checked = false; }
        } else {
            expirationInput.disabled = false;
            expirationInput.required = false;
            if (autoDelete) { autoDelete.disabled = false; }
        }
    }

    categorySelect.addEventListener('change', handleCategory);
    subcategorySelect.addEventListener('change', handleCategory);
    handleCategory();

    /* =============================================
       FORM SUBMIT VALIDATION
       ============================================= */
    itemForm.addEventListener('submit', function (e) {
        // Clear disabled expiration so it's not sent
        if (expirationInput.disabled) expirationInput.value = '';

        // Food must have expiration date
        if ((categorySelect.value === 'Food' || categorySelect.value === 'Condiments')
            && !expirationInput.value) {
            e.preventDefault();
            alert('Please select an expiration date for ' + categorySelect.value + ' items.');
            expirationInput.focus();
            return;
        }

        // Validate all pack rows
        const rows = packRowsWrapper.querySelectorAll('.pack-row');
        let rowsValid = true;
        rows.forEach((row, i) => {
            const label = row.querySelector('.pack-label-input').value.trim();
            const qty   = row.querySelector('.pack-qty-input').value;
            const price = row.querySelector('.pack-price-input').value;
            if (!label || qty === '' || !price || parseFloat(price) <= 0) {
                rowsValid = false;
            }
        });
        if (!rowsValid) {
            e.preventDefault();
            alert('Please fill in Label, Qty, and Price for every pack size row.');
            return;
        }

        if (!itemForm.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            itemForm.classList.add('was-validated');
            alert('Please fill out all required fields.');
        }
    });

    /* =============================================
       BULK CSV UPLOAD
       ============================================= */
    const dropArea       = document.getElementById('dropArea');
    const fileInput      = document.getElementById('bulk_file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameText   = document.getElementById('fileName');
    const removeFileBtn  = document.getElementById('removeFileBtn');

    dropArea.addEventListener('click', e => {
        if (e.target.tagName === 'A') return;
        fileInput.click();
    });
    dropArea.addEventListener('dragover', e => { e.preventDefault(); dropArea.classList.add('dragover'); });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.name.endsWith('.csv')) {
            fileInput.files = e.dataTransfer.files;
            showFileName(file.name);
        } else { alert('Please upload a valid CSV file'); }
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            if (fileInput.files[0].name.endsWith('.csv')) {
                showFileName(fileInput.files[0].name);
            } else { alert('Please upload a valid CSV file'); fileInput.value = ''; }
        }
    });
    removeFileBtn.addEventListener('click', () => { fileInput.value = ''; fileNameDisplay.style.display = 'none'; });
    function showFileName(name) { fileNameText.textContent = name; fileNameDisplay.style.display = 'inline-flex'; }

    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        if (fileInput.files.length === 0) { e.preventDefault(); alert('Please select a CSV file.'); }
        else if (!fileInput.files[0].name.endsWith('.csv')) { e.preventDefault(); alert('Only CSV files are allowed.'); }
    });

    /* =============================================
       PRODUCT IMAGE UPLOAD
       ============================================= */
    const imgDropArea   = document.getElementById('imageDropArea');
    const imgInput      = document.getElementById('product_image');
    const imgNameDisplay = document.getElementById('imageNameDisplay');
    const imgNameText   = document.getElementById('imageName');
    const imgRemoveBtn  = document.getElementById('removeImageBtn');

    imgDropArea.addEventListener('click', e => {
        if (e.target.closest('#removeImageBtn') ||
            e.target.closest('#imageNameDisplay') ||
            e.target.closest('#imagePreviewContainer')) {
            e.preventDefault(); e.stopPropagation(); return;
        }
        imgInput.click();
    });
    imgDropArea.addEventListener('dragover', e => { e.preventDefault(); imgDropArea.classList.add('dragover'); });
    imgDropArea.addEventListener('dragleave', () => imgDropArea.classList.remove('dragover'));
    imgDropArea.addEventListener('drop', e => {
        e.preventDefault(); imgDropArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (file.type.startsWith('image/') || ['jpg','jpeg','png','webp'].includes(ext)) {
                imgInput.files = e.dataTransfer.files; showImgName(file);
            } else { alert('Please upload a valid JPG, PNG, or WEBP image'); }
        }
    });
    imgInput.addEventListener('change', () => {
        if (imgInput.files.length > 0) {
            const file = imgInput.files[0];
            const ext  = file.name.split('.').pop().toLowerCase();
            if (file.type.startsWith('image/') || ['jpg','jpeg','png','webp'].includes(ext)) {
                showImgName(file);
            } else { alert('Please upload a valid image file'); imgInput.value = ''; }
        }
    });
    imgRemoveBtn.addEventListener('click', e => {
        e.stopPropagation();
        imgInput.value = '';
        imgNameDisplay.style.display = 'none';
        const pc = document.getElementById('imagePreviewContainer');
        const pi = document.getElementById('imagePreview');
        if (pc && pi) { pc.style.display = 'none'; pi.src = ''; }
    });
    function showImgName(file) {
        imgNameText.textContent = file.name;
        imgNameDisplay.style.display = 'inline-flex';
        const reader = new FileReader();
        reader.onload = e => {
            const pc = document.getElementById('imagePreviewContainer');
            const pi = document.getElementById('imagePreview');
            if (pc && pi) { pi.src = e.target.result; pc.style.display = 'block'; }
        };
        reader.readAsDataURL(file);
    }
});
</script>
</body>
</html>