<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<h1><?= $title ?></h1>

<form action="<?= base_url('admin/users/store') ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-success">Add User</button>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Back</a>
</form>
<?= $this->endSection() ?>
