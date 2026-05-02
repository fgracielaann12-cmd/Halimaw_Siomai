<?= $this->extend('templates/header') ?>

<?= $this->section('content') ?>
<h1><?= $title ?></h1>

<form action="<?= base_url('admin/users/update/' . $user['id']) ?>" method="post">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
    </div>
    <div class="mb-3">
        <label>Password (leave blank to keep current)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <button class="btn btn-primary">Update User</button>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Back</a>
</form>
<?= $this->endSection() ?>
