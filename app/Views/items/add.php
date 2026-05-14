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
            --border-radius: 12px;
        }

        * { font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        
        body { background-color: #f8f9fc; color: #3a3b45; margin: 0; padding: 0; display: flex; overflow-x: clip; }

        /* --- Animations --- */
        @keyframes fadeSlideDown { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeScaleUp { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
        @keyframes navGlow { 0% { box-shadow: 0 0 5px rgba(78,115,223,0.3); filter: brightness(1); } 50% { box-shadow: 0 0 15px rgba(78,115,223,0.9); filter: brightness(1.2); } 100% { box-shadow: 0 0 5px rgba(78,115,223,0.3); filter: brightness(1); } }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1100;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        #sidebar .navbar-brand { padding: 1.25rem 1.5rem; font-size: 1.15rem; font-weight: 700; color: white; display: flex; align-items: center; gap: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        #sidebar .navbar-brand img { width: 44px; height: 44px; border-radius: 6px; background-color: #f0f2f5; padding: 2px; }
        
        #sidebar .nav { width: 100%; min-width: 0; }
        #sidebar .nav-item { width: 100%; min-width: 0; }
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
            white-space: normal; 
            line-height: 1.2; 
            width: calc(100% - 2rem);
        }
        #sidebar .nav-link:hover { transform: translateX(5px); background-color: var(--sidebar-hover); color: white; }
        #sidebar .nav-link.active { background: linear-gradient(90deg, var(--sidebar-hover), var(--sidebar-active)); color: white; animation: navGlow 2s infinite ease-in-out; }

        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .top-navbar { 
            position: sticky; 
            top: 0; 
            z-index: 1000; 
            background: white;
            height: 60px;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--card-shadow);
            animation: fadeSlideDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

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
            transition: all 0.2s;
        }
        .mobile-menu-toggle:hover { background: var(--sidebar-hover); }

        /* FORM STYLES */
        .page-content-container { 
            display: flex; 
            justify-content: center; 
            padding: 30px 20px; 
            flex-grow: 1;
        }
        .form-card { 
            width: 100%; 
            max-width: 550px; 
            padding: 35px; 
            background: white; 
            border-radius: var(--border-radius); 
            box-shadow: var(--card-shadow); 
            animation: fadeScaleUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        h2 { font-weight: 700; text-align: center; margin-bottom: 30px; color: var(--dark); font-size: 1.5rem; }

        .form-control, .form-select { 
            font-size: 0.95rem; 
            border-radius: 8px; 
            border: 1px solid #ddd; 
            padding: 12px 15px; 
            margin-bottom: 15px; 
            transition: all 0.2s; 
        }
        .form-control:focus, .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25); outline: none; }

        .pack-sizes-header { font-size: 0.85rem; font-weight: 600; color: #6c757d; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
        .total-qty-display { background: #f0f4ff; border: 1px solid #d0dbff; border-radius: 8px; padding: 12px 16px; font-size: 0.9rem; color: #4e73df; font-weight: 500; margin-top: 15px; }

        .file-upload-container { 
            margin-top: 15px; 
            padding: 25px; 
            border: 2px dashed #ddd; 
            border-radius: 8px; 
            background-color: #fafafa; 
            text-align: center; 
            transition: all 0.3s; 
            cursor: pointer; 
        }
        .file-upload-container.dragover, .file-upload-container:hover { background-color: #f0f8ff; border-color: var(--primary); }
        .file-upload-label { color: var(--primary); font-weight: 600; }
        
        .submit-button { 
            width: 100%; 
            padding: 14px; 
            background: var(--primary); 
            color: white; 
            border: none; 
            border-radius: 12px; 
            cursor: pointer; 
            margin-top: 25px; 
            font-size: 1.1rem; 
            font-weight: 600; 
            transition: all 0.2s; 
        }
        .submit-button:hover { background: var(--primary-dark); transform: translateY(-2px); }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: flex; }
            #sidebar { transform: translateX(-100%); }
            #sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1090; }
            .sidebar-overlay.active { display: block; }
            .form-card { padding: 25px 20px; }
            .page-content-container { padding: 15px 10px; }
            .top-navbar { padding: 0 15px; }
        }

        /* Radius Override */
        button, .btn, .form-control, .form-select, .card, .alert, .badge, .modal-content {
            border-radius: 12px !important;
        }

        /* Hide global toggle */
        body > #mobileMenuToggle { display: none !important; }
    </style>
    <script>
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

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<?= view('partials/admin_sidebar') ?>

<div class="main-content">
    <div class="top-navbar">
        <div class="d-flex align-items-center gap-3">
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle-fill me-2"></i>Add New Item</h5>
        </div>
        <a href="<?= site_url('items') ?>" class="btn btn-light btn-sm shadow-sm border d-none d-md-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Inventory</span>
        </a>
    </div>

    <div class="page-content-container">
        <div style="width: 100%; max-width: 550px;">
            <div class="d-md-none mb-3" style="animation: fadeSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;">
                <a href="<?= site_url('items') ?>" class="btn btn-light shadow-sm border d-inline-flex align-items-center gap-2" style="border-radius: 12px; padding: 10px 18px; font-weight: 600; color: #4e73df; background: white;">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Inventory</span>
                </a>
            </div>
            <div class="form-card">
            
            <h2 class="mb-4">Product Details</h2>

            <?php
                $errors = session()->getFlashdata('errors');
                $error  = session()->getFlashdata('error');
            ?>
            <?php if ($error): ?>
                <div class="alert alert-danger mb-4">
                    <?= esc($error) ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $e): ?>
                            <li><?= esc($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form id="itemForm" method="post" action="<?= base_url('/index.php/items/store') ?>" enctype="multipart/form-data" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-600 small text-muted">Product ID</label>
                    <input type="text" name="product_id" class="form-control" placeholder="e.g. P001" value="<?= esc($nextProductId ?? old('product_id')) ?>" required>
                    <small class="text-muted d-block mt-n2 mb-2">Auto-generated suggestion.</small>
                </div>

                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="Product Name" value="<?= old('name') ?>" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="sku" class="form-control" placeholder="SKU Code (e.g. PRK-SMAI-S12)" value="<?= old('sku') ?>" required>
                </div>

                <div class="card mb-3 border shadow-sm p-3 bg-light-subtle">
                    <div class="pack-sizes-header">
                        <i class="bi bi-tags-fill me-1"></i> Pack Sizes & Pricing
                    </div>

                    <div class="row mb-2 fw-bold text-secondary small px-1">
                        <div class="col-4">Label</div>
                        <div class="col-3">Qty</div>
                        <div class="col-5">Price (₱)</div>
                    </div>

                    <div id="packRowsWrapper"></div>

                    <button type="button" id="addPackRowBtn" class="btn btn-sm btn-outline-primary mt-2 w-100">
                        <i class="bi bi-plus-lg me-1"></i> Add Another Size
                    </button>

                    <div class="total-qty-display">
                        Total Stock: <span id="totalQtyValue" class="fs-5">0</span>
                        <input type="hidden" name="total_quantity" id="totalQtyHidden" value="0">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-600 small text-muted">Expiration Date (Required for Food)</label>
                    <input type="date" name="expiration_date" id="expiration_date" class="form-control" value="<?= old('expiration_date') ?>">
                </div>

                <div class="mb-3">
                    <select name="category" id="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="Food" <?= old('category') === 'Food' ? 'selected' : '' ?>>Food</option>
                        <option value="Non-Food" <?= old('category') === 'Non-Food' ? 'selected' : '' ?>>Non-Food</option>
                        <option value="Condiments" <?= old('category') === 'Condiments' ? 'selected' : '' ?>>Condiments</option>
                    </select>
                </div>

                <div id="subcategoryContainer" style="display:none;" class="mb-3">
                    <select name="subcategory" id="subcategory" class="form-select">
                        <option value="">Select Subcategory</option>
                        <option value="Expirable" <?= old('subcategory') === 'Expirable' ? 'selected' : '' ?>>Expirable</option>
                        <option value="Non-Expirable" <?= old('subcategory') === 'Non-Expirable' ? 'selected' : '' ?>>Non-Expirable</option>
                    </select>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="auto_delete" id="auto_delete" <?= old('auto_delete') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="auto_delete">Auto Delete on Expiry</label>
                </div>

                <div class="file-upload-container mb-4" id="imageDropArea">
                    <div class="file-upload-label mb-2">
                        <i class="bi bi-cloud-arrow-up fs-3 d-block mb-1"></i>
                        <span>Click or Drag Product Image</span>
                    </div>
                    <small class="text-muted d-block mb-3">JPG, PNG, WEBP (Max 2MB)</small>
                    
                    <input type="file" name="product_image" id="product_image" accept="image/*" style="display:none;">
                    
                    <div id="imagePreviewContainer" class="mb-3" style="display:none;">
                        <img id="imagePreview" src="" style="max-width:100%; max-height:160px; border-radius:8px; border:1px solid #ddd; padding:4px;">
                    </div>
                    
                    <div id="imageNameDisplay" class="bg-primary-subtle p-2 rounded small d-none align-items-center justify-content-between">
                        <span id="imageName" class="text-truncate me-2"></span>
                        <i class="bi bi-x-circle-fill text-danger cursor-pointer" id="removeImageBtn"></i>
                    </div>
                </div>

                <button class="submit-button" type="submit">Create Product</button>
            </form>

            <div class="text-center my-4">
                <span class="px-3 bg-white text-muted small fw-bold">OR BULK IMPORT</span>
                <hr class="mt-n2">
            </div>

            <form id="uploadForm" method="post" action="<?= base_url('/items/bulk-upload') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="file-upload-container py-4" id="dropArea">
                    <i class="bi bi-file-earmark-spreadsheet text-success fs-3 d-block mb-2"></i>
                    <div class="file-upload-label mb-1">Upload CSV Records</div>
                    <small class="text-muted d-block mb-3">Ensure column headers match template</small>
                    
                    <input type="file" name="bulk_file" id="bulk_file" accept=".csv" style="display:none;">
                    <div id="fileNameDisplay" class="bg-success-subtle p-2 rounded small d-none align-items-center justify-content-between mt-2">
                        <span id="fileName" class="text-truncate me-2"></span>
                        <i class="bi bi-x-circle-fill text-danger cursor-pointer" id="removeFileBtn"></i>
                    </div>
                </div>
                <button type="submit" class="btn btn-success w-100 mt-3 fw-bold py-2">Start Bulk Import</button>
            </form>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    /* --- Sidebar Toggle --- */
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle = document.getElementById('mobileMenuToggle');

    if (toggle) {
        toggle.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });
    }
    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    /* --- Pack Rows --- */
    const packRowsWrapper = document.getElementById('packRowsWrapper');
    const addPackRowBtn = document.getElementById('addPackRowBtn');
    const totalQtyValue = document.getElementById('totalQtyValue');
    const totalQtyHidden = document.getElementById('totalQtyHidden');

    function recalcTotal() {
        let total = 0;
        packRowsWrapper.querySelectorAll('.pack-qty-input').forEach(input => {
            total += parseInt(input.value || 0);
        });
        totalQtyValue.textContent = total.toLocaleString();
        totalQtyHidden.value = total;
    }

    function createPackRow(defaultLabel = '') {
        const idx = packRowsWrapper.querySelectorAll('.pack-row').length;
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 pack-row align-items-center';
        row.innerHTML = `
            <div class="col-4">
                <input type="text" name="variations[${idx}][label]" class="form-control mb-0 pack-label-input" placeholder="e.g. Small" value="${defaultLabel}" required>
            </div>
            <div class="col-3">
                <input type="number" name="variations[${idx}][qty]" class="form-control mb-0 pack-qty-input" placeholder="0" min="0" required>
            </div>
            <div class="col-4">
                <input type="number" name="variations[${idx}][price]" class="form-control mb-0 pack-price-input" placeholder="0.00" step="0.01" min="0.01" required>
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn p-0 text-danger remove-pack-row" title="Remove"><i class="bi bi-dash-circle-fill"></i></button>
            </div>`;
        packRowsWrapper.appendChild(row);
        row.querySelector('.pack-qty-input').addEventListener('input', recalcTotal);
    }

    // Default rows
    ['Small', 'Medium', 'Large'].forEach(label => createPackRow(label));

    addPackRowBtn.addEventListener('click', () => createPackRow(''));
    packRowsWrapper.addEventListener('click', e => {
        const btn = e.target.closest('.remove-pack-row');
        if (btn && packRowsWrapper.querySelectorAll('.pack-row').length > 1) {
            btn.closest('.pack-row').remove();
            recalcTotal();
        }
    });

    /* --- Category Logic --- */
    const categorySelect = document.getElementById('category');
    const subContainer = document.getElementById('subcategoryContainer');
    const expirationInput = document.getElementById('expiration_date');

    categorySelect.addEventListener('change', () => {
        subContainer.style.display = (categorySelect.value === 'Non-Food') ? 'block' : 'none';
        if (categorySelect.value === 'Food' || categorySelect.value === 'Condiments') {
            expirationInput.required = true;
        } else {
            expirationInput.required = false;
        }
    });

    /* --- File Uploads --- */
    const handleFile = (areaId, inputId, displayId, textId, removeId, ext) => {
        const area = document.getElementById(areaId);
        const input = document.getElementById(inputId);
        const display = document.getElementById(displayId);
        const text = document.getElementById(textId);
        const remove = document.getElementById(removeId);

        area.addEventListener('click', () => input.click());
        area.addEventListener('dragover', e => { e.preventDefault(); area.classList.add('dragover'); });
        area.addEventListener('dragleave', () => area.classList.remove('dragover'));
        area.addEventListener('drop', e => {
            e.preventDefault();
            area.classList.remove('dragover');
            input.files = e.dataTransfer.files;
            updateDisplay();
        });
        input.addEventListener('change', updateDisplay);
        remove.addEventListener('click', e => { e.stopPropagation(); input.value = ''; display.classList.add('d-none'); if(inputId==='product_image') document.getElementById('imagePreviewContainer').style.display='none'; });

        function updateDisplay() {
            if (input.files.length > 0) {
                const file = input.files[0];
                text.textContent = file.name;
                display.classList.replace('d-none', 'd-flex');
                if (inputId === 'product_image') {
                    const reader = new FileReader();
                    reader.onload = e => {
                        document.getElementById('imagePreview').src = e.target.result;
                        document.getElementById('imagePreviewContainer').style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    };

    handleFile('imageDropArea', 'product_image', 'imageNameDisplay', 'imageName', 'removeImageBtn', 'img');
    handleFile('dropArea', 'bulk_file', 'fileNameDisplay', 'fileName', 'removeFileBtn', 'csv');
});
</script>
</body>
</html>