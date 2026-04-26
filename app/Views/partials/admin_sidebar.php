<?php 
$currentPath = uri_string(); 
if (!function_exists('isActive')) {
    function isActive($paths) {
        if (!is_array($paths)) $paths = [$paths];
        $currentPath = uri_string();
        foreach ($paths as $path) {
            if ($path === '') {
                if ($currentPath === '') return 'active';
                continue;
            }
            if ($currentPath === $path || strpos($currentPath, $path . '/') === 0 || strpos($currentPath, $path) === 0) {
                return 'active';
            }
        }
        return '';
    }
}

// --- NOTIFICATION QUERIES ---
$db = \Config\Database::connect();

// 1. Sales Records (is_seen = 0)
$salesNotif = $db->table('sales')->where('is_seen', 0)->countAllResults();

// 2. Stock Requests (status = 'pending')
$stockReqNotif = $db->table('stock_requests')->where('status', 'pending')->countAllResults();

// 3. Expiring Soon (0 to 10 days away, is_expiring_seen = 0)
$today = date('Y-m-d');
$expiringDate = date('Y-m-d', strtotime('+10 days'));
$expiringNotif = $db->table('items')
    ->where('expiration_date IS NOT NULL')
    ->where('expiration_date !=', '0000-00-00')
    ->where('expiration_date <=', $expiringDate)
    ->where('expiration_date >=', $today)
    ->where('is_expiring_seen', 0)
    ->countAllResults();

// 4. Expired (< today, is_expired_seen = 0)
$expiredNotif = $db->table('items')
    ->where('expiration_date IS NOT NULL')
    ->where('expiration_date !=', '0000-00-00')
    ->where('expiration_date <', $today)
    ->where('is_expired_seen', 0)
    ->countAllResults();
?>

<style>
/* PERFECT NOTIFICATION CIRCLE (GLOBAL INJECTION) */
.badge-dot {
    width: 20px !important;
    height: 20px !important;
    padding: 0 !important;
    border-radius: 50% !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    line-height: 1 !important;
    text-align: center !important;
    font-size: 0.75rem !important;
    font-weight: 700 !important;
}

/* Prevent text wrapping overlapping issues in sidebar navigation */
#sidebar .nav-link {
    white-space: nowrap !important;
}
</style>

<!-- Mobile Menu Toggle -->
<button class="mobile-menu-toggle" id="mobileMenuToggle">
    <i class="bi bi-list"></i>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- SIDEBAR -->
<nav id="sidebar">
    <a class="navbar-brand" href="<?= site_url('items') ?>">
        <img src="<?= base_url('Images/Inventa.png') ?>" alt="Inventa Logo">
        <span>Halimaw Siomai</span>
    </a>
    <ul class="nav flex-column px-2 mt-3">
        <?php
        $isDashboard = isActive(['admin/dashboard']);
        $isInventory = isActive(['items', '']);
        if (strpos(uri_string(), 'items/expiring-soon') === 0 || 
            strpos(uri_string(), 'items/deleted') === 0 || 
            strpos(uri_string(), 'items/logs') === 0) {
            $isInventory = '';
        }
        ?>
        <li class="nav-item">
            <a class="nav-link <?= $isDashboard ?>" href="<?= site_url('admin/dashboard') ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $isInventory ?>" href="<?= site_url('items') ?>">
                <i class="bi bi-box-seam"></i> Inventory
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['admin/pos']) ?>" href="<?= site_url('admin/pos') ?>">
                <i class="bi bi-calculator"></i> POS
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['admin/sales']) ?>" href="<?= site_url('admin/sales') ?>">
                <i class="bi bi-receipt"></i> Sales Records
                <?php if (isset($salesNotif) && $salesNotif > 0): ?>
                    <span class="badge bg-danger badge-dot ms-auto"><?= $salesNotif ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['admin/stock-requests', 'admin/approve-request', 'admin/reject-request', 'admin/stock-request-logs']) ?>" href="<?= site_url('admin/stock-requests') ?>">
                <i class="bi bi-cart-check"></i> Stock Requests
                <?php if (isset($stockReqNotif) && $stockReqNotif > 0): ?>
                    <span class="badge bg-danger badge-dot ms-auto"><?= $stockReqNotif ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['admin/pull-outs']) ?>" href="<?= site_url('admin/pull-outs') ?>">
                <i class="bi bi-trash3"></i> Pull-Outs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['items/expiring-soon']) ?>" href="<?= site_url('items/expiring-soon') ?>">
                <i class="bi bi-clock-history"></i> Expiring Soon
                <?php if (isset($expiringNotif) && $expiringNotif > 0): ?>
                    <span class="badge bg-danger badge-dot ms-auto"><?= $expiringNotif ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['items/deleted']) ?>" href="<?= site_url('items/deleted') ?>">
                <i class="bi bi-x-circle"></i> Expired
                <?php if (isset($expiredNotif) && $expiredNotif > 0): ?>
                    <span class="badge bg-danger badge-dot ms-auto"><?= $expiredNotif ?></span>
                <?php endif; ?>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['admin/staff/users']) ?>" href="<?= site_url('admin/staff/users') ?>">
                <i class="bi bi-people"></i> Staff Management
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= isActive(['items/logs']) ?>" href="<?= site_url('items/logs') ?>">
                <i class="bi bi-journal-text"></i> Audit Logs
            </a>
        </li>

        <li class="nav-item mt-3 mb-2">
            <hr style="border-top: 1px solid rgba(255,255,255,0.3); opacity: 1; margin: 0 1.5rem;">
        </li>
        <li class="nav-item">
            <a class="nav-link text-danger" href="<?= site_url('logout') ?>">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</nav>
