<?php
$file = 'app/Views/auth/login.php';
$lines = file($file);
$first_half = array_slice($lines, 5, 263); // from index 5 to 267 (which is line 6 to 268)

$js = <<<EOD
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
EOD;

$content = "<!DOCTYPE html>\n<html lang=\"en\">\n<head>\n" . implode("", $first_half) . $js;
file_put_contents($file, $content);
echo "Fixed auth/login.php";
