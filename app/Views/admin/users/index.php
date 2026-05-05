<?= $this->extend('templates/header') ?>
<?= $this->section('content') ?>

<h1>Staff Management</h1>
<a href="<?= base_url('admin/users/add') ?>" class="btn btn-success mb-3">Add User</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Password</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= esc($user['username']) ?></td>
            <td><?= esc($user['email']) ?></td>
            <td><?= esc($user['role']) ?></td>
            <td style="word-break: break-all; font-size: 12px;"><?= esc($user['password']) ?></td>
            <td class="text-center">
                <a href="<?= base_url('admin/users/edit/'.$user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="<?= base_url('admin/users/delete/'.$user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
