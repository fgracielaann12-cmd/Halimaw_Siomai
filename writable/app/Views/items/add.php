<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Add Item | Inventa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    body {
      background-color: #d1d5da;
    }

    .navbar-nav .nav-link {
      transition: color 0.3s ease, border-bottom 0.3s ease;
      padding-bottom: 0.25rem;
    }

    .navbar-nav .nav-link:hover {
      color: #ffc107;
      border-bottom: 2px solid #ffc107;
    }

    .page-content-container {
      display: flex;
      justify-content: center;
      align-items: flex-start;
      padding-top: 50px;
      min-height: calc(100vh - 66px);
    }

    .form-card {
      width: 100%;
      max-width: 480px;
      padding: 30px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    input:not([type="checkbox"]):not([type="file"]),
    select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    input:focus:not([type="checkbox"]):not([type="file"]),
    select:focus {
      border-color: #768d87;
      box-shadow: 0 0 0 0.2rem rgba(118, 141, 135, 0.25);
      outline: none;
    }

    .date-wrapper {
      position: relative;
      width: 100%;
      margin: 10px 0;
    }

    .date-wrapper input[type="date"] {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
      background-color: white;
    }

    .date-label {
      position: absolute;
      left: 14px;
      top: 12px;
      color: #888;
      background: white;
      padding: 0 4px;
      transition: 0.2s ease all;
    }

    .date-wrapper input[type="date"]:focus+.date-label,
    .date-wrapper input[type="date"]:valid+.date-label {
      top: -8px;
      left: 10px;
      font-size: 0.8rem;
      color: #2f6355;
    }

    .file-upload-container {
      margin-top: 20px;
      padding: 25px;
      border: 2px dashed #768d87;
      border-radius: 10px;
      background-color: #f9fbfb;
      text-align: center;
      transition: background-color 0.3s, border-color 0.3s;
    }

    .file-upload-container.dragover {
      background-color: #e1f5f0;
      border-color: #2f6355;
    }

    .file-upload-label {
      display: block;
      color: #2f6355;
      cursor: pointer;
      font-weight: bold;
    }

    .file-name-display {
      margin-top: 10px;
      background: #eef5f4;
      padding: 8px 10px;
      border-radius: 6px;
      display: none;
      align-items: center;
      gap: 8px;
      justify-content: center;
    }

    .remove-file-btn {
      color: #dc3545;
      cursor: pointer;
    }

    .submit-button {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #4CAF50, #2f6355);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      margin-top: 30px;
      font-size: 1.1rem;
      transition: background 0.3s ease;
    }

    .submit-button:hover {
      background: linear-gradient(135deg, #2f6355, #768d87);
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center gap-3" href="<?= site_url('items') ?>">
        <img src="<?= base_url('public/Images/Inventa.png') ?>" alt="Inventa Logo" style="width:50px;height:50px;">
        <span class="brand-text">Inventa</span>
      </a>
      <ul class="navbar-nav ms-auto gap-3">
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items') ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/expiringSoon') ?>">Expiring
            Soon</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/deleted') ?>">Expired</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/logs') ?>">Logs</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('/logout') ?>">Logout</a></li>
      </ul>
    </div>
  </nav>

  <div class="container page-content-container">
    <div class="form-card">


      <!-- ✅ Manual Add Form -->
      <form id="itemForm" method="post" action="<?= base_url('/items/store') ?>" enctype="multipart/form-data"
        novalidate>
        <?= csrf_field() ?>
        <input type="text" name="product_id" placeholder="Product ID" required>
        <input type="text" name="name" placeholder="Name" required>
        <input type="number" name="quantity" placeholder="Quantity" min="1" required>
        <input type="number" name="price" placeholder="Price" step="0.01" required>

        <div class="date-wrapper">
          <input type="date" name="expiration_date" id="expiration_date">
          <label class="date-label" for="expiration_date">Expiration Date</label>
        </div>

        <input type="text" name="barcode" placeholder="Barcode" required>

        <select name="category" id="category" required>
          <option value="">Select Category</option>
          <option value="Food">Food</option>
          <option value="Non-Food">Non-Food</option>
        </select>

        <div id="subcategoryContainer" style="display:none;">
          <select name="subcategory" id="subcategory">
            <option value="">Select Subcategory</option>
            <option value="Expirable">Expirable</option>
            <option value="Non-Expirable">Non-Expirable</option>
          </select>
        </div>

        <div class="mt-2">
          <input class="form-check-input" type="checkbox" name="auto_delete" id="auto_delete" disabled>
          <label for="auto_delete">Auto delete on expiry</label>
        </div>

        <button class="submit-button" type="submit"> Add New Item</button>
      </form>

      <hr class="my-4">

      <!-- ✅ Separate Excel Upload Form -->
      <form id="uploadForm" method="post" action="<?= base_url('/items/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="file-upload-container" id="dropArea">
          <label for="bulk_file" class="file-upload-label">
            <i class="bi bi-upload me-2"></i>
            Drag & Drop CSV/XLSX here or <br>
            <span style="text-decoration: underline;">Click here to Browse</span>
          </label>

          <input type="file" name="bulk_file" id="bulk_file" accept=".csv, .xlsx" required>

          <div id="fileNameDisplay" class="file-name-display">
            <span id="fileName"></span>
            <i class="bi bi-x-circle remove-file-btn" id="removeFileBtn"></i>
          </div>

          <div class="form-text mt-1">
            Format: <b>Product ID, Name, Quantity, Price, Expiration Date, Barcode, Category, Subcategory</b>.
          </div>
        </div>

        <button class="submit-button mt-2" type="submit">
          <i class="bi bi-cloud-arrow-up"></i> Upload Excel File
        </button>
      </form>
    </div>
  </div>
  <script>
    // ✅ DOM elements
    const categorySelect = document.getElementById('category');
    const subcategoryContainer = document.getElementById('subcategoryContainer');
    const subcategorySelect = document.getElementById('subcategory');
    const autoDelete = document.getElementById('auto_delete');
    const expirationInput = document.getElementById('expiration_date');
    const itemForm = document.getElementById('itemForm');

    // ✅ Handle category + subcategory changes
    categorySelect.addEventListener('change', handleCategory);
    subcategorySelect.addEventListener('change', handleCategory);

    function handleCategory() {
      // Show/hide subcategory when Non-Food
      if (categorySelect.value === 'Non-Food') {
        subcategoryContainer.style.display = 'block';
      } else {
        subcategoryContainer.style.display = 'none';
        subcategorySelect.value = '';
      }

      // Logic for expiration & auto-delete
      if (categorySelect.value === 'Food') {
        enableExpiration(true);
        enableAutoDelete(true);
      } else if (categorySelect.value === 'Non-Food' && subcategorySelect.value === 'Expirable') {
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

    // ✅ Enable/Disable Helpers
    function enableExpiration(required = false) {
      expirationInput.disabled = false;
      expirationInput.required = required;
      expirationInput.style.backgroundColor = 'white';
      expirationInput.style.cursor = 'auto';
    }

    function disableExpiration() {
      expirationInput.value = '';
      expirationInput.disabled = true;
      expirationInput.required = false;
      expirationInput.placeholder = 'N/A (No Expiration)';
      expirationInput.style.backgroundColor = '#f3f3f3';
      expirationInput.style.cursor = 'not-allowed';
    }

    function enableAutoDelete(enable) {
      autoDelete.disabled = !enable;
      autoDelete.checked = enable;
    }

    // ✅ Validate before submitting
    itemForm.addEventListener('submit', function (e) {
      // Disable empty expiration when not needed
      if (expirationInput.disabled) {
        expirationInput.value = '';
      }

      // Custom check for Expirable items
      if (
        categorySelect.value === 'Non-Food' &&
        subcategorySelect.value === 'Expirable' &&
        !expirationInput.value
      ) {
        e.preventDefault();
        alert('Please select an expiration date for Expirable items.');
        expirationInput.focus();
        return;
      }

      // 🔒 Run HTML5 built-in validation
      if (!itemForm.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
        itemForm.classList.add('was-validated');
        alert('Please fill out all required fields before submitting.');
      }
    });

    // ✅ Initial setup
    handleCategory();

    // ✅ File upload UI
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('bulk_file');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const fileNameText = document.getElementById('fileName');
    const removeFileBtn = document.getElementById('removeFileBtn');

    dropArea.addEventListener('dragover', (e) => { e.preventDefault(); dropArea.classList.add('dragover'); });
    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('dragover'));
    dropArea.addEventListener('drop', (e) => {
      e.preventDefault();
      dropArea.classList.remove('dragover');
      const file = e.dataTransfer.files[0];
      if (file) {
        fileInput.files = e.dataTransfer.files;
        showFileName(file.name);
      }
    });

    fileInput.addEventListener('change', () => {
      if (fileInput.files.length > 0) showFileName(fileInput.files[0].name);
    });

    removeFileBtn.addEventListener('click', () => {
      fileInput.value = '';
      fileNameDisplay.style.display = 'none';
    });

    function showFileName(name) {
      fileNameText.textContent = name;
      fileNameDisplay.style.display = 'inline-flex';
    }

    // ✅ Excel upload validation
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
      if (fileInput.files.length === 0) {
        e.preventDefault();
        alert('Please select a file to upload.');
      }
    });
  </script>





  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>