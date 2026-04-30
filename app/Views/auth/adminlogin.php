<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Halimaw_Siomai</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
    body {
        background: white;
        font-family: Helvetica, Arial, sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .login-card {
        width: 100%;
        max-width: 380px;
        background: #ffffff;
        padding: 35px 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        animation: fadeUp 0.45s ease;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-title {
        text-align: center;
        font-size: 1.4rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }

    .form-control {
        height: 45px;
        border-radius: 6px;
        font-size: 15px;
    }

    .submit-button {
        width: 100%;
        padding: 12px;
        font-weight: 600;
        background: #3c2f2a;
        border: none;
        color: #fff;
        border-radius: 6px;
        transition: 0.25s;
    }

    .submit-button:hover {
        background: #291f1c;
    }

    .register-link {
        color: #313438;
        text-decoration: none;
        font-weight: 500;
    }

    .register-link:hover {
        text-decoration: underline;
        color: #000;
    }

    .logo {
        max-width: 101px;
        background-color: #f0f2f5;
        padding: 2px;
        border-radius: 10px;
    }
    </style>
</head>

<body>
q
    <div class="login-card">

        <!-- Logo -->
        <img src="<?= base_url('Images/Inventa.png') ?>" class="d-block mx-auto mb-3 logo" alt="Logo">

        <h2 class="login-title">Halimaw Siomai (Admin)</h2>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= base_url('/admin/authenticate') ?>" method="post">

            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="submit-button">Login</button>

        </form>

        <div class="text-center mt-3">
            <p class="mt-3 text-center">
                <a href="<?= base_url('/login') ?>" class="btn btn-outline-dark btn-sm px-4 rounded-3"
                    style="font-weight: 600;">
                    <i class="bi bi-shield-lock-fill me-1"></i> Back to Staff Login
                </a>
            </p>
        </div>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Auto-close Alerts -->
    <script>
    setTimeout(() => {
        const alertEl = document.querySelector('.alert');
        if (alertEl) new bootstrap.Alert(alertEl).close();
    }, 4000);
    </script>

</body>

</html>