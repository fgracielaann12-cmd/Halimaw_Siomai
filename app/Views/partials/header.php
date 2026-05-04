<?php /** Header partial */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($title) ? esc($title) : 'Inventory' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>body{background:#f8f9fa;} .nav-role {margin-bottom:20px;} .card {border-radius: 5px; box-shadow: 0 2px 6px rgba(0,0,0,0.04);}</style>
    
    
    
    
    
    
    
    
    
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
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= base_url() ?>">Inventa</a>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
<?php if(session()->get('role') === 'admin'): ?>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('/admin/dashboard') ?>">Admin Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('/admin/expiringSoon') ?>">Expiring</a></li>
<?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('/user/dashboard') ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= site_url('/user/expiring-soon') ?>">Expiring</a></li>
<?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if(session()->get('logged_in')): ?>
            <li class="nav-item"><span class="nav-link">Hello, <?= esc(session()->get('username')) ?></span></li>
            <li class="nav-item"><a class="nav-link" href="<?= site_url(session()->get('role') === 'admin' ? '/admin/logout' : '/user/logout') ?>">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="<?= site_url('/login') ?>">Login</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container mt-4">
