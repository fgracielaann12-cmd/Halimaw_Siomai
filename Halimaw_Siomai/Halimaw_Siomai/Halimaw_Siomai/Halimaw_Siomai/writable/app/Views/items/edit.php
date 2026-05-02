<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
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
            max-width: 800px;
            margin-top: 50px;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control,
        .form-select {
            font-size: 0.95rem;
            border-radius: 8px;
            padding: 10px;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #607d8b, #2e7d32);
            color: white;
            font-weight: 600;
            border-radius: 50px;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #388e3c, #4caf50);
            transform: scale(1.05);
            color: white;
        }

        .btn-back {
            background: linear-gradient(135deg, #616161, #424242);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: linear-gradient(135deg, #333, #000);
            transform: scale(1.05);
            color: white;
        }

        .fade-message {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            transition: opacity 0.8s ease;
            font-weight: 500;
            text-align: center;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const alertBox = document.querySelector('.fade-message');
            if (alertBox) {
                setTimeout(() => {
                    alertBox.style.opacity = '0';
                    setTimeout(() => alertBox.remove(), 800);
                }, 3000);
            }
        });
    </script>
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
                    <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="<?= site_url('items/expiringSoon') ?>">Expiring Soon</a></li>
                    <li class="nav-item"><a class="nav-link text-white"
                            href="<?= site_url('items/deleted') ?>">Expired</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?= site_url('items/logs') ?>">Logs</a>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white" href="<?= base_url('/logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger fade-message">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success fade-message"><?= session()->getFlashdata('success') ?></div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger fade-message"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <form action="<?= base_url('items/update/' . $item['id']) ?>" method="post" class="card p-4">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="product_id" class="form-label">Product ID</label>
                <input type="text" class="form-control" id="product_id" name="product_id"
                    value="<?= esc($item['product_id']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= esc($item['name']) ?>"
                    required>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                    value="<?= esc($item['quantity']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price"
                    value="<?= esc($item['price']) ?>" required>
            </div>

            <div class="mb-3">
                <label for="expiration_date" class="form-label">Expiration Date</label>
                <input type="date" class="form-control" id="expiration_date" name="expiration_date"
                    value="<?= esc($item['expiration_date']) ?>">
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <option value="Food" <?= ($item['category'] === 'Food') ? 'selected' : '' ?>>Food</option>
                    <option value="Non-Food" <?= ($item['category'] === 'Non-Food') ? 'selected' : '' ?>>Non-Food</option>
                </select>
            </div>

            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="auto_delete" name="auto_delete" value="1"
                    <?= $item['auto_delete'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="auto_delete">Auto Delete When Expired</label>
            </div>

            <div class="d-flex justify-content-between">
                <a href="<?= base_url('items') ?>" class="btn-back"><i></i> Back</a>
                <button type="submit" class="btn-gradient"><i></i> Update Item</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>