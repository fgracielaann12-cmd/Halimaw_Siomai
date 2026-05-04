<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login | Inventa</title>

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Helvetica, Arial, sans-serif;
      background: #d1d5da;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      height: 100vh;
      margin: 0;
      padding-top: 120px;
    }

    .login-container {
      width: 100%;
      max-width: 360px;
      padding: 25px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
      animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    h2 { text-align: center; color: #333; margin-bottom: 20px; }

    .submit-button {
      width: 100%;
      padding: 10px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      transition: background-color 0.3s ease;
    }

    .submit-button:hover {
      background-color: #45a049;
    }

    .register-link-container {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .register-link {
      color: #2D68C4;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease, text-decoration 0.3s ease;
    }

    .register-link:hover {
      color: #2fb636;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <img src="<?= base_url('public/Images/Inventa.png') ?>" alt="Inventa Logo" class="logo d-block mx-auto mb-3" style="max-width: 100px;">
    <h2> Inventa </h2>

    <!-- Flash Messages -->
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
        <i class="bi bi-exclamation-circle-fill me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    <?php endif; ?>

    <!-- Admin Login Form -->
    <form method="post" action="<?= base_url('/admin/authenticate') ?>">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Admin Username" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <button class="submit-button" type="submit">Login</button>
    </form>

    <div class="register-link-container">
      <p><a href="<?= base_url('/login') ?>" class="register-link">Back to User Login</a></p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    setTimeout(() => {
      const alert = document.querySelector('.alert');
      if (alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
      }
    }, 4000);
  </script>
</body>
</html>
