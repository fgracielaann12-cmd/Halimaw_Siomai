<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Add Item | Halimaw Siomai</title>
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
            padding: 30px 20px;
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
            font-weight: 600;
        }

        .file-name-display {
            margin-top: 12px;
            background: #eef5ff;
            padding: 8px 12px;
            border-radius: 6px;
            display: none;
            align-items: center;
            gap: 10px;
            justify-content: center;
            color: var(--dark);
        }

        .remove-file-btn {
            color: var(--danger);
            cursor: pointer;
            font-size: 1.3rem;
        }

        /* BUTTONS */
        .submit-button {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 25px;
            font-size: 1.05rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .submit-button:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        /* ALERTS */
        .fade-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background-color: #e6f4ea;
            color: #1e5631;
        }

        .alert-danger {
            background-color: #fde8e8;
            color: #721c24;
        }

        /* MOBILE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
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

            .form-card { padding: 20px; }
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

    

    <!-- DISABLE BROWSER BACK/FORWARD BUTTONS COMPLETELY -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Push an empty state immediately
        history.pushState(null, null, location.href);
        // If the user tries to go back, instantly push them forward again
        window.onpopstate = function () {
            history.go(1);
        };
        
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

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="page-content-container">
        <div class="form-card">
            <!-- MANUAL ADD FORM -->
            <h2>Add New Item</h2>
            <form id="itemForm" method="post" action="<?= base_url('/items/store') ?>" enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>
                <input type="text" name="product_id" class="form-control" placeholder="Product ID" value="<?= esc($nextProductId ?? '') ?>" required readonly>
                <input type="text" name="name" class="form-control" placeholder="Product Name" required>
                <input type="text" name="sku" class="form-control" placeholder="SKU Code (e.g. PRK-SMAI-S12)" required>

                <!-- Size Variation Override Checkbox -->
                <div class="form-check form-switch my-3 text-start ps-5">
                    <input class="form-check-input" type="checkbox" id="enable_variations" name="enable_variations" value="1">
                    <label class="form-check-label fw-bold text-secondary" for="enable_variations">Size Variation Override</label>
                </div>

                <!-- Variations Section -->
                <div class="card mb-3 border-0 shadow-sm" id="variations_section" style="display:none; background: #fafbfc; border-radius: 8px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold text-secondary small">Product Size Variations</span>
                            <button type="button" class="btn btn-sm btn-primary" id="add_size_variation_btn">
                                <i class="bi bi-plus-lg me-1"></i> Add Size Variation
                            </button>
                        </div>

                        <div class="table-responsive-custom">
                            <table class="table table-bordered table-sm align-middle text-center mb-0" id="variationsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="font-size: 0.75rem; padding: 4px;">Label (Size)</th>
                                        <th style="font-size: 0.75rem; padding: 4px;">SKU Suffix</th>
                                        <th style="font-size: 0.75rem; padding: 4px;">Price (₱)</th>
                                        <th style="font-size: 0.75rem; padding: 4px;">Qty</th>
                                        <th style="font-size: 0.75rem; padding: 4px; width: 35px;"><i class="bi bi-trash"></i></th>
                                    </tr>
                                </thead>
                                <tbody id="variations_table_body">
                                    <!-- Dynamic size variation rows will appear here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <input type="number" name="quantity" class="form-control" placeholder="Total Quantity" min="0" required>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price (₱)" min="0" required>

                <input type="date" name="expiration_date" id="expiration_date" class="form-control" placeholder="mm/dd/yyyy">

                <select name="category" id="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="Food">Food</option>
                    <option value="Non-Food">Non-Food</option>
                    <option value="Condiments">Condiments</option>
                </select>

                <div id="subcategoryContainer" style="display:none;">
                    <select name="subcategory" id="subcategory" class="form-select">
                        <option value="">Select Subcategory</option>
                        <option value="Expirable">Expirable</option>
                        <option value="Non-Expirable">Non-Expirable</option>
                    </select>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="auto_delete" id="auto_delete">
                    <label class="form-check-label" for="auto_delete">Auto Delete on Expiry</label>
                </div>

                <!-- Product Image Upload -->
                <div class="file-upload-container mt-3" id="imageDropArea" style="cursor: pointer; position: relative; padding: 0;">
                    <div class="file-upload-label" style="cursor: pointer; display: block; padding: 30px;">
                        Drag & Drop Product Image here or <br>
                        <span style="text-decoration: underline;">Click to Browse</span><br>
                        <small class="text-muted">(JPG, PNG, WEBP)</small>
                    </div>
                    <input type="file" name="product_image" id="product_image" accept=".jpg, .jpeg, .png, .webp" style="display:none;">
                    <div id="imagePreviewContainer" style="display:none; margin-bottom: 15px; position: relative;">
                        <img id="imagePreview" src="" style="max-width:100%; max-height:150px; object-fit:contain; border-radius:8px; border: 1px solid #ddd; padding: 3px; background: white;">
                    </div>
                    <div id="imageNameDisplay" class="file-name-display mb-3" style="display:none; margin-left: 15px; margin-right: 15px;">
                        <span id="imageName"></span>
                        <i class="bi bi-x-circle remove-file-btn" id="removeImageBtn"></i>
                    </div>
                </div>



                <button class="submit-button" type="submit">Add New Item</button>
            </form>

            <hr class="my-4">

            <!-- BULK UPLOAD -->
            <h2>Bulk Upload</h2>
            <form id="uploadForm" method="post" action="<?= base_url('/items/bulk-upload') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="file-upload-container" id="dropArea">
                    <div class="file-upload-label">
                        Drag & Drop CSV here or <br>
                        <span style="text-decoration: underline;">Click to Browse</span>
                    </div>
                    <input type="file" name="bulk_file" id="bulk_file" accept=".csv" style="display:none;">
                    <div id="fileNameDisplay" class="file-name-display">
                        <span id="fileName"></span>
                        <i class="bi bi-x-circle remove-file-btn" id="removeFileBtn"></i>
                    </div>
                    <div class="form-text mt-2 text-muted">
                        Format: <b>product_id, name, sku, price, quantity, category, expiration_date, auto_delete, image_path</b><br>
                        <small>Download <a href="<?= base_url('downloads/sample_inventory_template.csv') ?>" class="text-primary" download>sample template</a></small>
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
    // Auto-generation is now handled dynamically by the backend controller.

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

    // Form Logic
    const categorySelect = document.getElementById('category');
    const subcategoryContainer = document.getElementById('subcategoryContainer');
    const subcategorySelect = document.getElementById('subcategory');
    const autoDelete = document.getElementById('auto_delete');
    const expirationInput = document.getElementById('expiration_date');
    const itemForm = document.getElementById('itemForm');

    function handleCategory() {
        if (categorySelect.value === 'Non-Food') {
            subcategoryContainer.style.display = 'block';
        } else {
            subcategoryContainer.style.display = 'none';
            subcategorySelect.value = '';
        }
        if (categorySelect.value === 'Food' || (categorySelect.value === 'Non-Food' && subcategorySelect.value === 'Expirable')) {
            enableExpiration(true);
            enableAutoDelete(true);
        } else if (categorySelect.value === 'Non-Food' && subcategorySelect.value === 'Non-Expirable') {
            disableExpiration();
            enableAutoDelete(false);
        } else {
            enableExpiration(false);
            enableAutoDelete(false);
        }
    }

    function enableExpiration(required = false) {
        expirationInput.disabled = false;
        expirationInput.required = required;
        expirationInput.classList.remove('disabled');
    }
    function disableExpiration() {
        expirationInput.value = '';
        expirationInput.disabled = true;
        expirationInput.required = false;
        expirationInput.classList.add('disabled');
    }
    function enableAutoDelete(enable) {
        if (autoDelete) {
            autoDelete.disabled = !enable;
            autoDelete.checked = enable;
        }
    }

    itemForm.addEventListener('submit', function (e) {
        if (expirationInput.disabled) expirationInput.value = '';
        if (categorySelect.value === 'Food' && !expirationInput.value) {
            e.preventDefault();
            alert('Please select an expiration date for Food items.');
            expirationInput.focus();
            return;
        }
        if (categorySelect.value === 'Non-Food' && subcategorySelect.value === 'Expirable' && !expirationInput.value) {
            e.preventDefault();
            alert('Please select an expiration date for Expirable items.');
            expirationInput.focus();
            return;
        }
        if (!itemForm.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            itemForm.classList.add('was-validated');
            alert('Please fill out all required fields.');
        }
    });

    categorySelect.addEventListener('change', handleCategory);
    subcategorySelect.addEventListener('change', handleCategory);
    handleCategory();

    // File Upload
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('bulk_file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameText = document.getElementById('fileName');
    const removeFileBtn = document.getElementById('removeFileBtn');

    dropArea.addEventListener('click', () => fileInput.click());
    dropArea.addEventListener('dragover', e => { 
        e.preventDefault(); 
        dropArea.classList.add('dragover'); 
    });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file && file.name.endsWith('.csv')) {
            fileInput.files = e.dataTransfer.files;
            showFileName(file.name);
        } else {
            alert('Please upload a valid CSV file');
        }
    });
    fileInput.addEventListener('change', () => { 
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (file.name.endsWith('.csv')) {
                showFileName(file.name);
            } else {
                alert('Please upload a valid CSV file');
                fileInput.value = '';
            }
        }
    });
    removeFileBtn.addEventListener('click', () => { 
        fileInput.value = ''; 
        fileNameDisplay.style.display = 'none'; 
    });
    function showFileName(name) { 
        fileNameText.textContent = name; 
        fileNameDisplay.style.display = 'inline-flex'; 
    }

    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        if (fileInput.files.length === 0) { 
            e.preventDefault(); 
            alert('Please select a CSV file to upload.'); 
        } else if (!fileInput.files[0].name.endsWith('.csv')) {
            e.preventDefault();
            alert('Only CSV files are allowed.');
        }
    });

    // Image Upload
    const imgDropArea = document.getElementById('imageDropArea');
    const imgInput = document.getElementById('product_image');
    const imgNameDisplay = document.getElementById('imageNameDisplay');
    const imgNameText = document.getElementById('imageName');
    const imgRemoveBtn = document.getElementById('removeImageBtn');

    imgDropArea.addEventListener('click', (e) => {
        if (e.target.closest('#removeImageBtn') || e.target.closest('#imageNameDisplay') || e.target.closest('#imagePreviewContainer')) {
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        imgInput.click();
    });
    imgDropArea.addEventListener('dragover', e => { 
        e.preventDefault(); 
        imgDropArea.classList.add('dragover'); 
    });
    imgDropArea.addEventListener('dragleave', () => imgDropArea.classList.remove('dragover'));
    imgDropArea.addEventListener('drop', e => {
        e.preventDefault();
        imgDropArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            const ext = file.name.split('.').pop().toLowerCase();
            if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                imgInput.files = e.dataTransfer.files;
                showImgName(file);
            } else {
                alert('Please upload a valid JPG, PNG, or WEBP image');
            }
        }
    });
    imgInput.addEventListener('change', () => { 
        if (imgInput.files.length > 0) {
            const file = imgInput.files[0];
            const ext = file.name.split('.').pop().toLowerCase();
            if (file.type.startsWith('image/') || ['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                showImgName(file);
            } else {
                alert('Please upload a valid image file');
                imgInput.value = '';
            }
        }
    });
    imgRemoveBtn.addEventListener('click', (e) => { 
        e.stopPropagation();
        imgInput.value = ''; 
        imgNameDisplay.style.display = 'none'; 
        const previewContainer = document.getElementById('imagePreviewContainer');
        const previewImg = document.getElementById('imagePreview');
        if (previewContainer && previewImg) {
            previewContainer.style.display = 'none';
            previewImg.src = '';
        }
    });
    function showImgName(file) { 
        imgNameText.textContent = file.name; 
        imgNameDisplay.style.display = 'inline-flex'; 
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const previewContainer = document.getElementById('imagePreviewContainer');
            const previewImg = document.getElementById('imagePreview');
            if (previewContainer && previewImg) {
                previewImg.src = e.target.result;
                previewContainer.style.display = 'block';
            }
        }
        reader.readAsDataURL(file);
    }

    // Variations Logic
    const enableVariations = document.getElementById('enable_variations');
    const variationsSection = document.getElementById('variations_section');
    const addSizeVariationBtn = document.getElementById('add_size_variation_btn');
    const variationsTableBody = document.getElementById('variations_table_body');
    const mainPrice = document.querySelector('input[name="price"]');
    const mainQuantity = document.querySelector('input[name="quantity"]');

    function createEditableVariationRow(label = '', suffix = '', price = '', quantity = '') {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="p-0 align-middle">
                <input type="text" name="var_label[]" class="form-control m-0 border-0 bg-transparent text-center px-1" placeholder="(Size)" value="${label}" required style="box-shadow: none; font-size: 0.8rem;">
            </td>
            <td class="p-0 align-middle">
                <input type="text" name="var_sku_suffix[]" class="form-control m-0 border-0 bg-transparent text-center px-1" placeholder="-S" value="${suffix}" style="box-shadow: none; font-size: 0.8rem;">
            </td>
            <td class="p-0 align-middle">
                <input type="number" step="0.01" name="var_price[]" class="form-control m-0 border-0 bg-transparent text-center px-1" placeholder="Base" value="${price}" style="box-shadow: none; font-size: 0.8rem;">
            </td>
            <td class="p-0 align-middle">
                <input type="number" name="var_quantity[]" class="form-control m-0 border-0 bg-transparent text-center px-1" placeholder="0" value="${quantity}" required style="box-shadow: none; font-size: 0.8rem;">
            </td>
            <td class="p-0 align-middle text-center">
                <button type="button" class="btn btn-sm text-danger remove-var-btn p-1 m-0 border-0" title="Remove" style="font-size: 1rem;">
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </td>
        `;

        tr.querySelector('.remove-var-btn').addEventListener('click', function() {
            tr.remove();
            toggleMainFormFieldsRequired();
        });

        return tr;
    }

    function addEditableSizeRow(label = '', suffix = '', price = '', quantity = '') {
        const tr = createEditableVariationRow(label, suffix, price, quantity);
        variationsTableBody.appendChild(tr);
        toggleMainFormFieldsRequired();
    }    function toggleMainFormFieldsRequired() {
        const isEnabled = enableVariations.checked;

        if (isEnabled) {
            mainPrice.required = false;
            mainQuantity.required = false;
            mainPrice.disabled = true;
            mainQuantity.disabled = true;
            mainPrice.value = '';
            mainQuantity.value = '';
        } else {
            mainPrice.required = true;
            mainQuantity.required = true;
            mainPrice.disabled = false;
            mainQuantity.disabled = false;
        }
    }

    enableVariations.addEventListener('change', function() {
        const isEnabled = this.checked;
        variationsSection.style.display = isEnabled ? 'block' : 'none';

        if (isEnabled && variationsTableBody.children.length === 0) {
            // Pre-load default standard variations as fully editable fields
            addEditableSizeRow('(Large)', '-L', '', '');
            addEditableSizeRow('(Medium)', '-M', '', '');
            addEditableSizeRow('(Small)', '-S', '', '');
            addEditableSizeRow('', '', '', ''); // Default singular size row
        }

        toggleMainFormFieldsRequired();
    });

    addSizeVariationBtn.addEventListener('click', function() {
        if (!enableVariations.checked) {
            alert('Please enable Size Variation Override first!');
            return;
        }
        // Prepend an empty editable row of inputs at the TOP of the table
        addEditableSizeRow('', '', '', '');
    });

    // Run on initial page load to ensure correct state
    enableVariations.checked = false;
    variationsSection.style.display = 'none';
    toggleMainFormFieldsRequired();
});
</script>
</body>
</html>
