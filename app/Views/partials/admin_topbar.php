<?php
$db = \Config\Database::connect();
$salesNotif = $db->table('sales')->where('is_seen', 0)->countAllResults();
$stockReqNotif = $db->table('stock_requests')->where('status', 'pending')->countAllResults();
$today = date('Y-m-d');
$expiringDate = date('Y-m-d', strtotime('+10 days'));

// Low Stock Count
$lowStockNotif = $db->table('items')
    ->groupStart()
        ->where('pack_small_qty > 0 AND pack_small_qty <= 10')
        ->orWhere('pack_medium_qty > 0 AND pack_medium_qty <= 10')
        ->orWhere('pack_biggest_qty > 0 AND pack_biggest_qty <= 10')
        ->orWhere('(pack_small_qty = 0 AND pack_medium_qty = 0 AND pack_biggest_qty = 0) AND quantity <= 10')
    ->groupEnd()
    ->countAllResults();

$expiringNotif = $db->table('items')
    ->where('expiration_date IS NOT NULL')
    ->where('expiration_date !=', '0000-00-00')
    ->where('expiration_date <=', $expiringDate)
    ->where('expiration_date >=', $today)
    ->countAllResults();

$expiredNotif = $db->table('items')
    ->where('expiration_date IS NOT NULL')
    ->where('expiration_date !=', '0000-00-00')
    ->where('expiration_date <', $today)
    ->countAllResults();

$totalNotif = $salesNotif + $stockReqNotif + $expiringNotif + $expiredNotif + $lowStockNotif;
?>

<div class="top-navbar" style="padding-left: 20px; padding-right: 20px;">
    <div class="d-flex align-items-center gap-3">
        <?php if (!(isset($hide_toggle) && $hide_toggle)): ?>
            <button class="mobile-menu-toggle d-lg-none" id="mobileMenuToggleInline">
                <i class="bi bi-list"></i>
            </button>
        <?php endif; ?>
        <h5 class="mb-0">
            <?php if (isset($icon)): ?>
                <i class="<?= $icon ?> me-2" style="font-size: 1.25rem;"></i>
            <?php endif; ?>
            <?= $title ?? 'Admin' ?>
        </h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        <!-- Notification Bell -->
        <div class="dropdown">
            <button class="btn btn-light position-relative shadow-sm border" type="button" id="notifBell" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-bell-fill" style="font-size: 1.2rem; color: #4e73df;"></i>
                <?php if ($totalNotif > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem; padding: 0.35em 0.6em;">
                        <?= $totalNotif > 99 ? '99+' : $totalNotif ?>
                    </span>
                <?php endif; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-0 notification-dropdown" aria-labelledby="notifBell">
                <li class="p-3 border-bottom bg-light">
                    <h6 class="mb-0 fw-bold">Notifications</h6>
                </li>
                <?php if ($totalNotif == 0): ?>
                    <li class="p-4 text-center text-muted">
                        <i class="bi bi-bell-slash d-block mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                        No new notifications
                    </li>
                <?php else: ?>
                    <?php if ($lowStockNotif > 0): ?>
                        <li><a class="dropdown-item p-3 d-flex align-items-center gap-3 border-bottom notification-item" href="<?= site_url('items?filter=low_stock') ?>">
                            <div class="notification-icon bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="fw-bold"><?= $lowStockNotif ?> Low Stock Items</div>
                                <small class="text-muted">Items needing replenishment</small>
                            </div>
                        </a></li>
                    <?php endif; ?>
                    <?php if ($salesNotif > 0): ?>
                        <li><a class="dropdown-item p-3 d-flex align-items-center gap-3 border-bottom notification-item" href="<?= site_url('admin/sales') ?>">
                            <div class="notification-icon bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div class="notification-content">
                                <div class="fw-bold"><?= $salesNotif ?> New Sales</div>
                                <small class="text-muted">Unviewed transaction records</small>
                            </div>
                        </a></li>
                    <?php endif; ?>
                    <?php if ($stockReqNotif > 0): ?>
                        <li><a class="dropdown-item p-3 d-flex align-items-center gap-3 border-bottom notification-item" href="<?= site_url('admin/stock-requests') ?>">
                            <div class="notification-icon bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="notification-content">
                                <div class="fw-bold"><?= $stockReqNotif ?> Pending Requests</div>
                                <small class="text-muted">Stock adjustments needing approval</small>
                            </div>
                        </a></li>
                    <?php endif; ?>
                    <?php if ($expiringNotif > 0): ?>
                        <li><a class="dropdown-item p-3 d-flex align-items-center gap-3 border-bottom notification-item" href="<?= site_url('items/expiring-soon') ?>">
                            <div class="notification-icon bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="notification-content">
                                <div class="fw-bold"><?= $expiringNotif ?> Expiring Soon</div>
                                <small class="text-muted">Items expiring within 10 days</small>
                            </div>
                        </a></li>
                    <?php endif; ?>
                    <?php if ($expiredNotif > 0): ?>
                        <li><a class="dropdown-item p-3 d-flex align-items-center gap-3 border-bottom notification-item" href="<?= site_url('items/deleted') ?>">
                            <div class="notification-icon bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center">
                                <i class="bi bi-x-circle"></i>
                            </div>
                            <div class="notification-content">
                                <div class="fw-bold"><?= $expiredNotif ?> Expired Items</div>
                                <small class="text-muted">Items past their expiration date</small>
                            </div>
                        </a></li>
                    <?php endif; ?>
                <?php endif; ?>
                <li class="bg-light p-2 text-center">
                    <small class="text-muted">System Updates & Alerts</small>
                </li>
            </ul>
        </div>

        <?php if (isset($extra_buttons)) echo $extra_buttons; ?>

        <!-- User Profile (Optional inclusion) -->
        <?php if (isset($show_profile) && $show_profile): ?>
            <div class="user-profile border-start ps-3 ms-2">
                <div class="profile-initial">
                    <?php 
                    $username = session()->get('username') ?? 'User';
                    echo strtoupper(substr($username, 0, 1));
                    ?>
                </div>
                <div class="hide-mobile">
                    <div class="profile-name fw-bold" style="font-size: 0.9rem; line-height: 1;"><?= esc($username) ?></div>
                    <small class="profile-role text-muted" style="font-size: 0.75rem;"><?= esc(session()->get('role') ?? 'Admin') ?></small>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Notification Dropdown Enhancements */
.notification-dropdown {
    width: 320px !important;
    max-width: calc(100vw - 30px) !important;
    border-radius: 12px !important;
    overflow: hidden;
    /* OVERRIDE: Prevent slideDownFade from interfering with Popper.js positioning */
    animation: none !important;
    transform: none !important;
    top: 100% !important; 
    margin-top: 0.5rem !important;
}

.notification-item {
    white-space: normal !important;
    word-break: break-word !important;
    transition: all 0.2s ease;
}

.notification-item:hover {
    background-color: #f8f9fc !important;
}

.notification-icon {
    width: 40px !important;
    height: 40px !important;
    flex-shrink: 0 !important;
    border-radius: 50% !important; /* Force circle if needed */
}

.notification-content {
    flex: 1 !important;
    min-width: 0 !important;
}

.bg-primary-subtle { background-color: rgba(78, 115, 223, 0.1) !important; }
.bg-warning-subtle { background-color: rgba(246, 194, 62, 0.1) !important; }
.bg-info-subtle { background-color: rgba(54, 185, 204, 0.1) !important; }
.bg-danger-subtle { background-color: rgba(231, 74, 59, 0.1) !important; }

/* Ensure dropdown works on mobile */
@media (max-width: 576px) {
    .notification-dropdown {
        position: fixed !important;
        top: 60px !important;
        left: 15px !important;
        right: 15px !important;
        width: auto !important;
        margin-top: 0 !important;
    }
}
</style>
