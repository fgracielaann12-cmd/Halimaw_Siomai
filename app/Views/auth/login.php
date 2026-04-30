<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Halimaw Siomai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* --- Keep all your existing styles here --- */
:root { --primary: #0da1f2; --primary-dark: #0a81c2; --secondary: #858796; --dark: #5a5c69; }
body { 
    background: linear-gradient(-45deg, #000428, #004e92, #1e3c72, #6dd5ed);
    background-size: 300% 300%; 
    animation: gradientBG 6s ease infinite;
    display: flex; 
    justify-content: center; 
    align-items: center; 
    min-height: 100vh; 
    margin: 0; 
    padding: 20px; 
    font-family: 'Poppins', sans-serif;
    position: relative; 
    overflow: hidden;
}
.login-card { 
    width: 100%; 
    max-width: 420px; 
    background: #ffffff; 
    padding: 40px 35px; 
    border-radius: 12px; 
    box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
    border: 2px solid #0da1f2; 
    position: relative; 
    z-index: 1; 
    animation: fadeInUp 0.6s ease-out; 
}
.logo-container { 
    display: flex; 
    justify-content: center; 
    margin-bottom: 10px; 
}
.logo { 
    max-width: 180px; 
    height: auto; 
    transition: transform 0.3s ease;
}
.logo:hover {
    transform: scale(1.05);
}
.login-title { 
    text-align: center; 
    font-size: 1.6rem; 
    font-weight: 800; 
    color: #2262a6; 
    margin-bottom: 5px; 
    text-transform: uppercase; 
    letter-spacing: 0.5px; 
}
.login-subtitle { 
    text-align: center; 
    color: #7a7a7a; 
    margin-bottom: 30px; 
    font-size: 0.9rem; 
    font-weight: 600; 
}
.form-control { 
    height: 50px; 
    border-radius: 8px; 
    font-size: 15px; 
    border: none; 
    padding: 0 25px; 
    background: #e6e6e6; 
    color: #555; 
    font-weight: 600; 
    transition: all 0.3s ease; 
}
.form-control:focus { 
    background: #dcdcdc; 
    box-shadow: none; 
    outline: none; 
}
.form-control::placeholder { 
    color: #7a7a7a; 
    font-weight: 600; 
}
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}
.custom-input-group { 
    position: relative; 
    margin-bottom: 15px; 
    display: flex;
}
.password-toggle { 
    position: absolute; 
    right: 20px; 
    top: 50%; 
    transform: translateY(-50%); 
    color: #7a7a7a; 
    font-size: 20px; 
    cursor: pointer; 
    z-index: 10;
}
.password-toggle:hover {
    color: #555;
}
.submit-button { 
    width: 100%; 
    padding: 12px; 
    background: #0da1f2; 
    color: #fff; 
    border: none; 
    border-radius: 8px; 
    font-weight: 700; 
    cursor: pointer; 
    font-size: 16px; 
    text-transform: uppercase; 
    transition: all 0.3s ease; 
    margin-top: 15px; 
}
.submit-button:hover { 
    background: #0a81c2; 
    transform: translateY(-2px); 
}
.alert { 
    border-radius: 12px; 
    font-weight: 600; 
    text-align: center; 
    padding: 12px; 
    margin-bottom: 25px; 
    border: none; 
    font-size: 0.9rem; 
}
.alert-danger { background: #ffebee; color: #c62828; }
.alert-success { background: #e8f5e9; color: #2e7d32; }
@keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
@keyframes fadeInUp { from {opacity: 0; transform: translateY(30px);} to {opacity: 1; transform: translateY(0);} }
</style>
</head>
<body>

<div class="login-card">
    <div class="logo-container">
        <img src="<?= base_url('Images/Inventa.png') ?>" class="logo" alt="Halimaw Siomai Logo">
    </div>
    <h2 class="login-title">HALIMAW SIOMAI</h2>
    <p class="login-subtitle">POS Inventory Management System</p>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="post" action="<?= base_url('/authenticate') ?>">
        <?= csrf_field() ?>
        <div class="custom-input-group">
            <input type="text" name="login" class="form-control" placeholder="User:" required>
        </div>
        <div class="custom-input-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password:" required>
            <i class="bi bi-eye-fill password-toggle" id="togglePassword"></i>
        </div>
        <button type="submit" class="submit-button">Log In</button>
    </form>
</div>

<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');

    togglePassword.addEventListener('click', function (e) {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        this.classList.toggle('bi-eye-fill');
        this.classList.toggle('bi-eye-slash-fill');
    });
</script>

</body>
</html>
