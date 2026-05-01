<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Halimaw_Siomai</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
    body {
        background: linear-gradient(-45deg, #000428, #004e92, #1e3c72, #6dd5ed);
        background-size: 300% 300%;
        animation: gradientBG 6s ease infinite;
        font-family: 'Poppins', sans-serif;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        margin: 0;
    }

    @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        background: #ffffff;
        padding: 50px 40px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        animation: fadeUp 0.45s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
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

    .logo-container {
        margin-bottom: 15px;
    }

    .logo {
        max-width: 130px;
        height: auto;
    }

    .login-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2c5282;
        margin-bottom: 5px;
        text-transform: uppercase;
        text-align: center;
        letter-spacing: 0.5px;
    }

    .login-subtitle {
        font-size: 0.85rem;
        color: #858796;
        margin-bottom: 30px;
        text-align: center;
    }

    .login-form {
        width: 100%;
    }

    .input-container {
        position: relative;
        margin-bottom: 15px;
        width: 100%;
    }

    .form-control {
        height: 45px;
        border-radius: 25px;
        font-size: 0.9rem;
        border: none;
        padding: 0 20px;
        background: #e9ecef;
        color: #333;
        width: 100%;
        box-shadow: none;
    }

    .form-control::placeholder {
        color: #6c757d;
    }

    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }

    .form-control:focus {
        outline: none;
        box-shadow: inset 0 0 0 2px #d1d3e2;
        background: #e2e5e9;
    }

    .password-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
        cursor: pointer;
        font-size: 1.1rem;
    }

    .submit-button {
        width: 100%;
        padding: 12px;
        font-weight: 600;
        background: #2c5282;
        border: none;
        color: #fff;
        border-radius: 25px;
        transition: 0.3s;
        margin-top: 10px;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .submit-button:hover {
        background: #1e3c72;
    }

    .back-link {
        color: #858796;
        text-decoration: none;
        font-size: 0.85rem;
        margin-top: 20px;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #2c5282;
        text-decoration: underline;
    }
    </style>
</head>

<body>

    <div class="login-card">

        <!-- Logo -->
        <div class="logo-container">
            <img src="<?= base_url('Images/Inventa.png') ?>" class="logo" alt="Halimaw Siomai Logo">
        </div>

        <h2 class="login-title">Halimaw Siomai</h2>
        <p class="login-subtitle">POS Inventory Management System</p>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="width: 100%; border-radius: 15px; font-size: 0.85rem; padding: 10px;">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="padding: 12px;"></button>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="width: 100%; border-radius: 15px; font-size: 0.85rem; padding: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="padding: 12px;"></button>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form action="<?= base_url('/admin/authenticate') ?>" method="post" class="login-form">

            <div class="input-container">
                <input type="text" name="username" class="form-control" placeholder="User:" required>
            </div>

            <div class="input-container">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password:" required>
                <i class="bi bi-eye password-icon" id="togglePassword"></i>
            </div>

            <button type="submit" class="submit-button">Log In</button>

        </form>

        <a href="<?= base_url('/login') ?>" class="back-link">
            <i class="bi bi-arrow-left me-1"></i> Back to Staff Login
        </a>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Scripts -->
    <script>
        // Toggle Password Visibility
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });

        // Auto-close Alerts
        setTimeout(() => {
            const alertEl = document.querySelector('.alert');
            if (alertEl) new bootstrap.Alert(alertEl).close();
        }, 4000);
    </script>

</body>

</html>