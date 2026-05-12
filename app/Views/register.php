<!DOCTYPE html>
<html>

<head>
  <title>Register | Inventa</title>
  <style>
    
    body {
      font-family: Helvetica;
      background: #d1d5da;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      height: 100vh;
      margin: 0;
      padding-top: 120px;
    }

    .register-container {
      width: 100%;
      max-width: 320px;
      padding: 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #333;
    }

    input {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      box-sizing: border-box;
    }

    .submit-button {
      width: 100%;
      padding: 10px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .submit-button:hover {
      background-color: #45a049;
    }

    .login-link-container {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .login-link {
      color: #2D68C4;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease, text-decoration 0.3s ease;
    }

    .login-link:hover {
      color: #2fb636ff;
      text-decoration: underline;
    }

    .login-link-container p {
      margin: 0;
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

    <script>
        // The user is on the login page. This sets a hard flag globally.
        // Any cached dashboard page will immediately self-destruct if this is set.
        localStorage.setItem('auth_status', 'logged_out');
    </script>
</head>

<body>
  <div class="register-container">
    <img src="<?= base_url('public/Images/Inventa.png') ?>" alt="Inventa Logo" class="logo"
      style="display: block; margin: 0 auto 20px auto; max-width: 100px;">

    <h2>Register Account</h2>

    <?php if (session()->getFlashdata('error')): ?>
      <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
      <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('register/save') ?>">
      <?= csrf_field() ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>

      <button class="submit-button" type="submit">Register</button>
    </form>

    <div class="login-link-container">
      <p>Already have an account? <a href="<?= base_url('') ?>" class="login-link">Login here</a></p>
    </div>
  </div>
</body>

</html>