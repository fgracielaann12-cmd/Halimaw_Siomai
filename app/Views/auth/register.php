<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Halimaw_Siomai</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        /* ===== 🌿 Brown Premium Gradient Background ===== */
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f7f2ec, #f3e9e2);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            margin: 0;
            animation: fadeBg 1s ease-in;
        }

        @keyframes fadeBg {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* ===== Register Card ===== */
        .register-container {
            width: 100%;
            max-width: 420px;
            padding: 35px 28px;
            background: #fffdf8;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== Heading ===== */
        h2 {
            text-align: center;
            color: #3e2723;
            font-weight: 700;
            margin-bottom: 20px;
        }

        /* ===== Input Styling ===== */
        .input-group-text {
            background: #f1e9e5;
            border: 1px solid #c5b8b2;
            border-right: none;
            color: #5d4037;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d0c5bf;
            padding: 10px 12px;
            transition: 0.3s ease;
        }

        .form-control:focus {
            border-color: #6d4c41;
            box-shadow: 0 0 6px rgba(109, 76, 65, 0.3);
        }

        /* ===== Submit Button ===== */
        .submit-button {
            width: 100%;
            padding: 12px;
            font-weight: 600;
            border: none;
            border-radius: 5px;
            background-color: #6d4c41;
            color: #fff;
            transition: all 0.3s ease;
        }

        .submit-button:hover {
            background-color: #5d4037;
            transform: translateY(-2px);
        }

        /* ===== Flash Messages ===== */
        .alert {
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
        }

        /* ===== Login Link ===== */
        .login-link-container {
            text-align: center;
            margin-top: 18px;
            font-size: 0.9rem;
        }

        .login-link {
            color: #6d4c41;
            font-weight: 600;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .login-link:hover {
            color: #5d4037;
            text-decoration: underline;
        }

        /* ===== Mobile Adjustments ===== */
        @media (max-width: 480px) {
            .register-container {
                padding: 25px;
            }

            h2 {
                font-size: 1.45rem;
            }
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

<body>

    <div class="register-container">

        <h2>Create Account</h2>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger text-center">
                <i class="bi bi-x-circle-fill me-1"></i>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success text-center">
                <i class="bi bi-check-circle-fill me-1"></i>
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <!-- Register Form -->
        <form method="post" action="<?= base_url('/register/save') ?>">

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-shield-lock-fill"></i></span>
                <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password"
                    required>
            </div>

            <button type="submit" class="submit-button">Register</button>
        </form>

        <div class="login-link-container">
            Already have an account?
            <a href="<?= base_url('/') ?>" class="login-link">Login here</a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>