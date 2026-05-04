<!DOCTYPE html>
<html>
<head>
  <title>Register | Inventa</title>
  <style>
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
    h2 { text-align: center; color: #333; }
    input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
    .submit-button { width: 100%; padding: 10px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer; }
    .submit-button:hover { background-color: #45a049; }
    .login-link-container { text-align: center; margin-top: 20px; font-size: 14px; }
    .login-link { color: #2D68C4; text-decoration: none; font-weight: bold; transition: color 0.3s ease, text-decoration 0.3s ease; }
    .login-link:hover { color: #2fb636ff; text-decoration: underline; }
    .login-link-container p { margin: 0; }
          /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-1, .btn.rounded-1, .btn-add-to-cart, .btn, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
        }
    </style>
    <!-- UNIFIED 5PX SYSTEM-WIDE RADIUS OVERRIDE -->
    <style>
        :root {
            --border-radius: 5px !important;
        }
        
        /* Buttons */
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn, .chart-filter-btn,
        
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
            border-radius: 5px !important;
        }
        
        /* Images inside cards */
        .pos-item-card img, .card img {
            border-radius: 5px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    </style>
</head>
<body>
  <div class="register-container">
    <img src="<?= base_url('Images/Inventa.png') ?>" alt="Inventa Logo" style="display: block; margin: 0 auto 20px auto; max-width: 100px;">
    <h2>Register Account</h2>

    <?php if (session()->getFlashdata('error')): ?>
      <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <?php if (session()->getFlashdata('success')): ?>
      <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('admin/users/store') ?>">
      <?= csrf_field() ?>
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <button class="submit-button" type="submit">Register</button>
    </form>

    <div class="login-link-container">
      <p><a href="<?= base_url('admin/users') ?>" class="login-link">Back to Staff Management</a></p>
    </div>
  </div>
</body>
</html>
