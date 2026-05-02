<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Inventa</title>
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
      padding-top: 120px;
      margin: 0;
    }
    .register-container {
      width: 100%;
      max-width: 380px;
      padding: 25px;
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    h2 { text-align: center; margin-bottom: 20px; color: #333; }
    .submit-button {
      width: 100%;
      padding: 10px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .submit-button:hover { background-color: #45a049; }
    .login-link-container { text-align: center; margin-top: 20px; font-size: 14px; }
    .login-link { color: #2D68C4; font-weight: bold; text-decoration: none; }
    .login-link:hover { text-decoration: underline; color: #2fb636; }
  </style>
</head>
<body>
  <div class="register-container">
    <h2>Create Account</h2>

    <!-- Flash messages -->
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger text-center"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success text-center"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/register/save') ?>">
      <div class="mb-3">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
      </div>
      <div class="mb-3">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>
      <div class="mb-3">
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
      </div>
      <button class="submit-button" type="submit">Register</button>
    </form>

    <div class="login-link-container">
      Already have an account? <a href="<?= base_url('/') ?>" class="login-link">Login here</a>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
