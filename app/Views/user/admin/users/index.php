<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>

<style>
    .table th, .table td {
        white-space: nowrap;
    }
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
        margin: 0 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    @media (max-width: 991px) {
        .table {
            min-width: 700px !important;
            font-size: 0.9rem !important;
        }
        .table th, .table td {
            padding: 0.75rem 0.75rem !important;
        }
    }
        /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-1, .btn.rounded-1, .btn-add-to-cart, .btn, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
        }
    </style>

<?= $this->section('header_actions') ?>
    <a href="<?= site_url('admin/staff/users/create') ?>" class="btn btn-primary d-none d-md-inline-block shadow-sm px-4 py-2 fw-semibold" style="transition: all 0.3s ease; border-radius: 8px !important;">
        <i class="bi bi-person-plus-fill me-2"></i> Add Staff
    </a>
<?= $this->endSection() ?>

<div class="container-fluid">


    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <!-- Mobile Add Staff Button -->
    <div class="d-md-none mb-3">
        <a href="<?= site_url('admin/staff/users/create') ?>" class="btn btn-primary w-100 py-3 fw-bold shadow-sm rounded-3">
            <i class="bi bi-person-plus-fill me-2"></i> Add Staff
        </a>
    </div>

    <div class="table-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                        <tr>
                            <th style="width: 80px;">ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th style="width: 120px;">Role</th>
                            <th style="width: 200px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= esc($user['id']) ?></td>
                                    <td><?= esc($user['username']) ?></td>
                                    <td><?= esc($user['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'secondary' ?>">
                                            <?= esc($user['role'] === 'staff' ? 'Staff' : ucfirst($user['role'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= site_url('admin/staff/users/edit/' . $user['id']) ?>"
                                           class="btn btn-warning btn-sm me-1">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>

                                        <form action="<?= site_url('admin/staff/users/delete/' . $user['id']) ?>"
                                              method="post"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                                    No users found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </div>
</div>

<?= $this->endSection() ?>
