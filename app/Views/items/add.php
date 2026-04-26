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
            --border-radius: 0.65rem;
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
            width: 44px;
            height: 44px;
            border-radius: 6px;
            background-color: #f0f2f5;
            padding: 2px;
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
            border-radius: var(--border-radius);
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
            border-radius: var(--border-radius);
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
            border-radius: 10px;
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
            border-radius: var(--border-radius);
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

            .form-card { padding: 20px; }
        }
    </style>
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
                <input type="text" name="product_id" class="form-control" placeholder="Product ID" value="<?= esc($nextProductId ?? '') ?>" required>
                <input type="text" name="name" class="form-control" placeholder="Name" required>
                <input type="number" name="quantity" class="form-control" placeholder="General Quantity" min="0" required>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price (₱)" min="0" required>

                <input type="date" name="expiration_date" id="expiration_date" class="form-control">

                <select name="category" id="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <option value="Food">Food</option>
                    <option value="Non-Food">Non-Food</option>
                </select>

                <div id="subcategoryContainer" style="display:none;">
                    <select name="subcategory" id="subcategory" class="form-select">
                        <option value="">Select Subcategory</option>
                        <option value="Expirable">Expirable</option>
                        <option value="Non-Expirable">Non-Expirable</option>
                    </select>
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="auto_delete" id="auto_delete" disabled>
                    <label class="form-check-label" for="auto_delete">Auto delete on expiry</label>
                </div>

                <button class="submit-button" type="submit">Add New Item</button>
            </form>

            <hr class="my-4">

            <!-- BULK UPLOAD -->
            <h2>Bulk Upload</h2>
            <form id="uploadForm" method="post" action="<?= base_url('/items/bulk-upload') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="file-upload-container" id="dropArea">
                    <label for="bulk_file" class="file-upload-label">
                        Drag & Drop CSV here or <br>
                        <span style="text-decoration: underline;">Click to Browse</span>
                    </label>
                    <input type="file" name="bulk_file" id="bulk_file" accept=".csv" style="display:none;">
                    <div id="fileNameDisplay" class="file-name-display">
                        <span id="fileName"></span>
                        <i class="bi bi-x-circle remove-file-btn" id="removeFileBtn"></i>
                    </div>
                    <div class="form-text mt-2 text-muted">
                        Format: <b>Product ID, Name, Quantity, Expiration Date, Category, Subcategory</b><br>
                        <small>Download <a href="<?= base_url('SampleBulkUpload.csv') ?>" class="text-primary">sample template</a></small>
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
        autoDelete.disabled = !enable;
        autoDelete.checked = enable;
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
});
</script>
</body>
</html>
