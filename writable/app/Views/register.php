<!DOCTYPE html>
<html>
<head>
  <title>Register | Inventa</title>
  <style>
    body {
      font-family: Helvetica;
      background: #d1d5da;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      height: 100vh;
      margin: 0;
      padding-top: 120px;
    }

    .register-container {
      width: 100%;
      max-width: 320px;
      padding: 20px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 { 
      text-align: center; 
      color: #333; 
    }

    input { 
      width: 100%; 
      padding: 10px; 
      margin: 10px 0; 
      box-sizing: border-box; 
    }

    .submit-button {
      width: 100%;
      padding: 10px;
      background: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .submit-button:hover {
background-color: #45a049;
    }

    .login-link-container {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    } 

    .login-link {
      color: #2D68C4;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s ease, text-decoration 0.3s ease;
    }

    .login-link:hover {
      color: #2fb636ff;
      text-decoration: underline;
    }

    .login-link-container p {
      margin: 0;
    }

  </style>
</head>

<body>
  <div class="register-container">
    <img src="<?= base_url('public/Images/Inventa.png') ?>" alt="Inventa Logo" class="logo"
         style="display: block; margin: 0 auto 20px auto; max-width: 100px;">

    <h2>Register Account</h2>

    <?php if(session()->getFlashdata('error')): ?>
      <p style="color:red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
      <p style="color:green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/register/save') ?>">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>

      <button class="submit-button" type="submit">Register</button>
    </form>

    <div class="login-link-container">
      <p>Already have an account? <a href="<?= base_url('') ?>" class="login-link">Login here</a></p>
    </div>
  </div>
</body>
</html>
