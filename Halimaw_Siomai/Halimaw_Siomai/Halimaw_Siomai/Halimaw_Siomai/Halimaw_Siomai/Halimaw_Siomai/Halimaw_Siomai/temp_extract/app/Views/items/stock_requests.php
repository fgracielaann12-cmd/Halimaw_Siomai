<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <title>Stock Requests | Halimaw Siomai</title>
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
            --border-radius: 0.35rem;
        }

        * {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f8f9fc;
            color: #3a3b45;
            margin: 0;
            padding: 0;
            display: flex;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        #sidebar .navbar-brand {
            padding: 1.25rem 1.5rem;
            font-size: 1.15rem;
            font-weight: 700;
            color: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        #sidebar .navbar-brand img {
            width: 40px;
            height: 40px;
            border-radius: 6px;
        }
        #sidebar .nav-link {
            color: var(--sidebar-text);
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: var(--border-radius);
            font-size: 0.9rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
        }
        #sidebar .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: white;
        }
        #sidebar .nav-link.active {
            background: linear-gradient(90deg, var(--sidebar-hover), var(--sidebar-active));
            color: white;
            border-left: 3px solid var(--sidebar-active);
        }
        .nav-link.text-danger {
            color: #ff6b6b !important;
        }
        .nav-link.text-danger:hover {
            background: rgba(231, 74, 59, 0.15);
            color: var(--danger) !important;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* MOBILE MENU */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 998;
            background: var(--sidebar-bg);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
            cursor: pointer;
            box-shadow: var(--card-shadow);
        }

        /* CONTAINER */
        .container {
            max-width: 96%;
            padding: 30px 20px;
        }

        /* CARDS */
        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            padding: 20px;
            margin-bottom: 20px;
            border: none;
        }

        .card h5 {
            font-weight: 600;
            margin-bottom: 4px;
        }
        .card h3 {
            font-weight: 700;
            margin: 0;
        }

        /* BUTTONS */
        .btn-back, .btn-view-logs {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.2s;
        }
        .btn-back {
            background: var(--primary);
            color: white;
        }
        .btn-back:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        .btn-view-logs {
            background: var(--success);
            color: white;
        }
        .btn-view-logs:hover {
            background: #17a673;
            transform: translateY(-2px);
        }

        /* TABLE */
        .table-responsive {
            margin-top: 20px;
        }
        .table {
            min-width: 1000px;
            font-size: 0.9rem;
            margin: 0;
        }
        .table th {
            background: var(--primary);
            color: white;
            font-weight: 600;
            position: sticky;
            top: 0;
            z-index: 2;
        }
        .table tbody tr {
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background-color: #f8f9ff;
        }
        .table .btn {
            font-size: 0.8rem;
            padding: 4px 10px;
            border-radius: 30px;
            font-weight: 500;
        }
        .table .btn-success {
            background: var(--success);
            color: white;
        }
        .table .btn-danger {
            background: var(--danger);
            color: white;
        }

        /* BADGES */
        .badge {
            font-size: 0.75rem;
            padding: 0.4em 0.6em;
            border-radius: 30px;
            font-weight: 500;
        }

        /* ALERTS */
        .alert {
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        /* MODAL */
        .modal-header {
            background: var(--primary);
            color: white;
            border-top-left-radius: var(--border-radius);
            border-top-right-radius: var(--border-radius);
        }
        .modal-header .btn-close {
            filter: invert(1);
        }
        .modal-footer .btn {
            border-radius: 30px;
            font-weight: 600;
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .mobile-menu-toggle { display: block; }
            #sidebar { transform: translateX(-100%); }
            .main-content { margin-left: 0; width: 100%; }
            #sidebar.active { transform: translateX(0); }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            .sidebar-overlay.active { display: block; }

            .container { padding: 20px 15px; }
            .d-md-flex { flex-direction: column; gap: 10px; }
            .btn-back, .btn-view-logs { width: 100%; }
            .table { min-width: 600px; font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<?php
$segments = service('uri')->getSegments();
$seg1 = $segments[0] ?? '';
$seg2 = $segments[1] ?? '';
$currentPath = $seg1 . '/' . $seg2;
?>

<?= view('partials/admin_sidebar') ?>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="container">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Summary Cards -->
        <div class="row text-center mb-4">
            <div class="col-md-4 mb-3">
                <div class="card border-start border-success border-4">
                    <h5 class="text-success">Approved</h5>
                    <h3><?= $approvedCount ?? 0 ?></h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start border-danger border-4">
                    <h5 class="text-danger">Rejected</h5>
                    <h3><?= $rejectedCount ?? 0 ?></h3>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-start border-warning border-4">
                    <h5 class="text-warning">Pending</h5>
                    <h3><?= $pendingCount ?? 0 ?></h3>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-md-flex justify-content-between align-items-center mb-3">
            <a href="<?= site_url('items') ?>" class="btn-back mb-2 mb-md-0">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
            <a href="<?= site_url('admin/stock-request-logs') ?>" class="btn-view-logs mb-2 mb-md-0">
                <i class="bi bi-journal-text me-1"></i> View Request Logs
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Action</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($requests as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td><?= esc($r['user_name'] ?? 'Unknown') ?></td>
                        <td><a href="<?= site_url('items/edit/' . $r['item_id']) ?>" class="text-decoration-none fw-semibold"><?= esc($r['item_name'] ?? 'Unknown') ?></a></td>
                        <td><?= $r['quantity'] ?></td>
                        <td>
                            <?php
                            $actionType = strtolower(trim($r['action'] ?? 'add'));
                            if ($actionType === 'subtract') {
                                echo '<span class="badge bg-info">Subtract</span>';
                            } else {
                                echo '<span class="badge bg-success">Add</span>';
                            }
                            ?>
                        </td>
                        <td><?= esc($r['reason'] ?? '—') ?></td>
                        <td>
                            <?php
                            $status = strtolower(trim($r['status'] ?? ''));
                            if ($status === 'approved')
                                echo '<span class="badge bg-success">Approved</span>';
                            elseif ($status === 'rejected')
                                echo '<span class="badge bg-danger">Rejected</span>';
                            else
                                echo '<span class="badge bg-warning text-dark">Pending</span>';
                            ?>
                        </td>
                        <td>
                            <?php if ($status === 'pending'): ?>
                                <button class="btn btn-success btn-sm me-1" data-bs-toggle="modal"
                                    data-bs-target="#approveModal" data-id="<?= $r['id'] ?>">Approve</button>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal"
                                    data-id="<?= $r['id'] ?>">Reject</button>
                            <?php else: ?>
                                <span class="text-muted">No actions</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Approval</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to approve this request?</div>
            <div class="modal-footer">
                <form id="approveForm" method="post" action="">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Rejection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to reject this request?</div>
            <div class="modal-footer">
                <form id="rejectForm" method="post" action="">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Mobile Menu
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', () => {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    const navLinks = document.querySelectorAll('#sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Modals
    const approveModal = document.getElementById('approveModal');
    if (approveModal) {
        approveModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const requestId = button.getAttribute('data-id');
            document.getElementById('approveForm').action = '<?= base_url("admin/approve-request/") ?>' + requestId;
        });
    }

    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget;
            const requestId = button.getAttribute('data-id');
            document.getElementById('rejectForm').action = '<?= base_url("admin/reject-request/") ?>' + requestId;
        });
    }
});
</script>
</body>
</html>