<!DOCTYPE html>
<html lang="en">
<head>
<title>Login | Halimaw Siomai</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root { 
    --primary: #0da1f2; 
    --primary-dark: #0a81c2; 
    --secondary: #858796; 
    --dark: #2c3e50; 
    --glass-bg: rgba(255, 255, 255, 0.1);
    --glass-border: rgba(255, 255, 255, 0.2);
    --glass-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
}

body { 
    background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
    background-size: 400% 400%; 
    animation: gradientBG 15s ease infinite;
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

/* Background floating elements for dynamic design */
.bg-shape {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    z-index: 0;
    animation: float 10s infinite ease-in-out alternate;
}
.shape-1 {
    width: 300px; height: 300px;
    background: rgba(13, 161, 242, 0.4);
    top: -50px; left: -50px;
}
.shape-2 {
    width: 400px; height: 400px;
    background: rgba(109, 213, 237, 0.3);
    bottom: -100px; right: -50px;
    animation-delay: -5s;
}

.login-wrapper {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
}

.login-card { 
    background: var(--glass-bg); 
    padding: 45px 40px; 
    border-radius: 24px; 
    box-shadow: var(--glass-shadow); 
    border: 1px solid var(--glass-border); 
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
    opacity: 0;
    transform: translateY(30px);
}

.logo-container { 
    display: flex; 
    justify-content: center; 
    margin-bottom: 15px; 
}
.logo { 
    max-width: 150px; 
    height: auto; 
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));
}
.logo:hover {
    transform: scale(1.08) rotate(-3deg);
}
.login-title { 
    text-align: center; 
    font-size: 1.8rem; 
    font-weight: 800; 
    color: #ffffff; 
    margin-bottom: 5px; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}
.login-subtitle { 
    text-align: center; 
    color: #e0e0e0; 
    margin-bottom: 35px; 
    font-size: 0.95rem; 
    font-weight: 400; 
    letter-spacing: 0.5px;
}

.custom-input-group { 
    position: relative; 
    margin-bottom: 22px; 
    display: flex;
    animation: fadeInRight 0.5s ease forwards;
    opacity: 0;
}
.custom-input-group:nth-of-type(1) { animation-delay: 0.2s; }
.custom-input-group:nth-of-type(2) { animation-delay: 0.4s; }

.form-control { 
    height: 55px; 
    border-radius: 5px; 
    font-size: 15px; 
    border: 1px solid rgba(255,255,255,0.15); 
    padding: 0 25px 0 50px; 
    background: rgba(0, 0, 0, 0.25); 
    color: #ffffff; 
    font-weight: 500; 
    transition: all 0.3s ease; 
}
.form-control:focus { 
    background: rgba(0, 0, 0, 0.4); 
    box-shadow: 0 0 0 3px rgba(13, 161, 242, 0.4); 
    border-color: rgba(13, 161, 242, 0.6);
    outline: none; 
    color: #fff;
}
.form-control::placeholder { 
    color: #a0a0a0; 
    font-weight: 400; 
}
.input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #0da1f2;
    font-size: 1.25rem;
    z-index: 10;
}

input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
    display: none;
}
.password-toggle { 
    position: absolute; 
    right: 18px; 
    top: 50%; 
    transform: translateY(-50%); 
    color: #a0a0a0; 
    font-size: 20px; 
    cursor: pointer; 
    z-index: 10;
    overflow: hidden;
    animation: fadeInRight 0.5s ease forwards;
    opacity: 0;
    animation-delay: 0.6s;
}
.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: all 0.6s ease;
}
.submit-button:hover { 
    transform: translateY(-4px) scale(1.02); 
    box-shadow: 0 12px 25px rgba(13, 161, 242, 0.6);
}
.submit-button:hover::before {
    left: 100%;
}
.submit-button:active {
    transform: translateY(1px);
    box-shadow: 0 4px 10px rgba(13, 161, 242, 0.4);
}

.alert { 
    border-radius: 5px; 
    font-weight: 500; 
    text-align: center; 
    padding: 15px; 
    margin-bottom: 25px; 
    border: 1px solid rgba(255,255,255,0.1); 
    font-size: 0.9rem; 
    backdrop-filter: blur(10px);
}
.alert-danger { background: rgba(220, 53, 69, 0.2); color: #ffb3ba; border-color: rgba(220, 53, 69, 0.3); }
.alert-success { background: rgba(25, 135, 84, 0.2); color: #a8e6cf; border-color: rgba(25, 135, 84, 0.3); }

@keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
@keyframes fadeInUp { to {opacity: 1; transform: translateY(0);} }
@keyframes fadeInRight { from {opacity: 0; transform: translateX(-20px);} to {opacity: 1; transform: translateX(0);} }
@keyframes float { 0% { transform: translateY(0px) scale(1); } 100% { transform: translateY(-30px) scale(1.1); } }
        /* Unified 5px Border Radius for All Buttons System-Wide */
        button, .btn, .btn.rounded-1, .btn.rounded-1, .btn-add-to-cart, .btn, #checkout-btn, #clear-cart, .submit-button, a.btn, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light {
            border-radius: 5px !important;
        }
    </style>
    <!-- UNIFIED 5PX SYSTEM-WIDE RADIUS OVERRIDE -->
    <style>
        :root {
            --border-radius: 5px !important;
        }
        
        /* Buttons */
        button, .btn, .btn-icon, .btn-primary, .btn-secondary, .btn-success, .btn-danger, .btn-warning, .btn-info, .btn-light, .btn-dark, .btn-outline-primary, .btn-outline-secondary, .btn-outline-dark, .btn-outline-light, .btn-add-to-cart, .submit-button, a.btn, .chart-filter-btn,
        
        /* Textboxes / Inputs */
        input, select, textarea, .form-control, .form-select, .custom-input-group,
        
        /* Tables & Wrappers */
        .table, .table-card, .table-responsive, table, .dataTables_wrapper,
        
        /* Cards & Misc UI */
        .card, .pos-item-card, .summary-card, .img-metric-card, .chart-card-premium, .pos-checkout,
        .alert, .badge, .modal-content, .modal-header, .nav-link, .login-card,
        
        /* Bootstrap Overrides */
        .rounded, .rounded-1, .rounded-2, .rounded-3, .rounded-circle, .rounded-pill,
        .rounded-top, .rounded-bottom, .rounded-start, .rounded-end {
            border-radius: 5px !important;
        }
        
        /* Images inside cards */
        .pos-item-card img, .card img {
            border-radius: 5px !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }
    </style>
</head>
<body>

<div class="bg-shape shape-1"></div>
<div class="bg-shape shape-2"></div>

<div class="login-wrapper">
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
                <i class="bi bi-person-fill input-icon"></i>
                <input type="text" name="login" class="form-control" placeholder="User:" required>
            </div>
            <div class="custom-input-group">
                <i class="bi bi-lock-fill input-icon"></i>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password:" required>
                <i class="bi bi-eye-slash-fill password-toggle" id="togglePassword"></i>
            </div>
            <button type="submit" class="submit-button">Log In</button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye-fill');
            this.classList.toggle('bi-eye-slash-fill');
        });
    }
    setTimeout(() => {
        const alertEl = document.querySelector('.alert');
        if (alertEl) { try { new bootstrap.Alert(alertEl).close(); } catch(e) { alertEl.style.display = 'none'; } }
    }, 4000);
</script>
</body>
</html>
