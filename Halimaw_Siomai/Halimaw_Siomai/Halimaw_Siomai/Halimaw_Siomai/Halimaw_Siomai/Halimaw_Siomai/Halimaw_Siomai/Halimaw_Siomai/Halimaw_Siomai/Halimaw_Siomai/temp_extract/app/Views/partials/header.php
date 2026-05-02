<?php /** Header partial */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= isset($title) ? esc($title) : 'Inventory' ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>body{background:#f8f9fa;} .nav-role {margin-bottom:20px;} .card {border-radius:12px; box-shadow: 0 2px 6px rgba(0,0,0,0.04);}</style>
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
