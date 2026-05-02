<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Halimaw Siomai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
/* --- Keep all your existing styles here --- */
:root { --primary: #4e73df; --primary-dark: #2e59d9; --secondary: #858796; --success: #1cc88a; --danger: #e74a3b; --warning: #f6c23e; --info: #36b9cc; --light: #f8f9fc; --dark: #5a5c69; }
body { background: linear-gradient(135deg, #4e73df, #2e59d9); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; position: relative; overflow: hidden; }
.login-card { width: 100%; max-width: 420px; background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 40px 35px; border-radius: 16px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.2); position: relative; z-index: 1; animation: fadeInUp 0.6s ease-out; }
.logo-container { display: flex; justify-content: center; margin-bottom: 25px; position: relative; }
.logo { max-width: 120px; height: auto; border-radius: 12px; box-shadow: 0 8px 25px rgba(78,115,223,0.4); transition: transform 0.3s ease, box-shadow 0.3s ease; border: 3px solid white; }
.logo:hover { transform: scale(1.05); box-shadow: 0 12px 30px rgba(78,115,223,0.6); }
.login-title { text-align: center; font-size: 1.8rem; font-weight: 700; color: var(--dark); margin-bottom: 30px; text-shadow: 0 1px 2px rgba(0,0,0,0.1); letter-spacing: -0.5px; }
.login-subtitle { text-align: center; color: var(--secondary); margin-bottom: 25px; font-size: 0.95rem; font-weight: 500; }
.form-control { height: 52px; border-radius: 12px; font-size: 16px; border: 2px solid #e1e5f1; padding: 0 20px; background: #fafbff; transition: all 0.3s ease; font-weight: 500; }
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(78,115,223,0.2); background: white; outline: none; }
.form-control::placeholder { color: var(--secondary); font-weight: 400; }
.input-group { position: relative; margin-bottom: 20px; }
.input-group i { position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--primary); font-size: 18px; }
.input-group input { padding-left: 50px; }
.submit-button { width: 100%; padding: 14px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; font-size: 16px; letter-spacing: 0.5px; text-transform: uppercase; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(78,115,223,0.4); }
.submit-button:hover { background: linear-gradient(135deg, var(--primary-dark), #1a4bb8); transform: translateY(-2px); box-shadow: 0 6px 20px rgba(78,115,223,0.6); }
.alert { border-radius: 12px; font-weight: 600; text-align: center; padding: 15px 20px; margin-bottom: 25px; border: none; font-size: 0.95rem; animation: slideIn 0.4s ease; }
.alert-danger { background: linear-gradient(135deg, #ffebee, #f8d7da); color: #c62828; border-left: 4px solid #e74a3b; }
.alert-success { background: linear-gradient(135deg, #e8f5e9, #d1e7dd); color: #2e7d32; border-left: 4px solid #1cc88a; }
@keyframes fadeInUp { from {opacity: 0; transform: translateY(30px);} to {opacity: 1; transform: translateY(0);} }
@keyframes slideIn { from {opacity: 0; transform: translateX(-20px);} to {opacity: 1; transform: translateX(0);} }
</style>
</head>
<body>

<div class="login-card">
    <div class="logo-container">
        <img src="<?= base_url('Images/Inventa.png') ?>" class="logo" alt="Halimaw Siomai Logo">
    </div>
    <h2 class="login-title">Halimaw Siomai</h2>
    <p class="login-subtitle">POS Inventory Management System</p>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/authenticate') ?>">
        <?= csrf_field() ?>
        <div class="input-group">
            <i class="bi bi-person"></i>
            <input type="text" name="login" class="form-control" placeholder="Username or Email" required>
        </div>
        <div class="input-group">
            <i class="bi bi-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" class="submit-button">Log In</button>
    </form>
</div>

</body>
</html>
