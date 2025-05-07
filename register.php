<?php
session_start();
require_once 'includes/functions.php';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak sama!';
    } elseif (userExists($username, $email)) {
        $error = 'Username atau email sudah terdaftar!';
    } else {
        if (addUser($username, $password, $email)) {
            echo "<script>alert('Registrasi berhasil, silakan login!'); window.location='login.php';</script>";
            exit();
        } else {
            $error = 'Registrasi gagal, silakan coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SmartPage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #22c1c3 0%, #7b2ff2 100%);
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-wrapper {
            display: flex;
            min-height: 100vh;
        }
        .left-panel {
            flex: 1;
            background: transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 0;
        }
        .brand-img {
            width: 170px;
            height: 170px;
            margin-bottom: 24px;
        }
        .brand-title {
            color: #7b2ff2;
            font-size: 44px;
            font-weight: 900;
            letter-spacing: 3px;
            margin-bottom: 12px;
        }
        .brand-desc {
            color: #222;
            font-size: 16px;
            font-weight: 400;
            opacity: 0.7;
        }
        .right-panel {
            flex: 0 0 400px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-width: 340px;
            padding: 0 5vw;
        }
        .register-container {
            background: rgba(255,255,255,0.85);
            border-radius: 18px;
            box-shadow: 0 8px 32px 0 rgba(123,47,242,0.10);
            width: 360px;
            max-width: 95vw;
            padding: 36px 32px 24px 32px;
            position: relative;
            border: 1.5px solid #e0d7fa;
        }
        .register-container::after {
            content: '';
            position: absolute;
            top: -1px; right: -1px;
            height: calc(100% + 2px);
            width: 16px;
            background: linear-gradient(180deg, #7b2ff2 0%, #04c8de 100%);
            border-radius: 0 18px 18px 0;
        }
        .register-title {
            color: #1e90ff;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }
        .form-label {
            font-size: 13px;
            color: #1e90ff;
            font-weight: 600;
            margin-bottom: 5px;
            margin-top: 10px;
            letter-spacing: 1px;
        }
        .form-control {
            width: 100%;
            height: 40px;
            border: none;
            background: #f2f2f2;
            border-radius: 4px;
            padding: 0 16px;
            font-size: 15px;
            color: #222;
            margin-bottom: 0;
            box-sizing: border-box;
        }
        .input-group {
            position: relative;
            margin-bottom: 22px;
        }
        .show-password {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            font-size: 18px;
            z-index: 2;
        }
        .btn-register {
            width: 100%;
            background: #1e90ff;
            color: #fff;
            border: none;
            border-radius: 4px;
            height: 42px;
            font-size: 16px;
            font-weight: 600;
            margin-top: 18px;
            letter-spacing: 1px;
            transition: background 0.2s;
        }
        .btn-register:hover {
            background: #187bcd;
        }
        .login-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #1e90ff;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            pointer-events: auto;
        }
        .login-link:hover {
            text-decoration: underline;
        }
        .alert {
            border-radius: 5px;
            margin-bottom: 18px;
        }
        @media (max-width: 900px) {
            .main-wrapper {
                flex-direction: column;
            }
            .left-panel, .right-panel {
                flex: unset;
                width: 100%;
                min-width: 0;
                justify-content: center;
                padding: 0;
            }
            .right-panel {
                margin-top: 24px;
                padding-bottom: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="left-panel">
            <img src="images/Logo.png" alt="Inspira Logo" class="brand-img" style="width:250px;height:250px;margin-bottom:8px;object-fit:contain;background:transparent;filter:drop-shadow(0 2px 16px rgba(0,0,0,0.08));">
            <div class="brand-title" style="font-size:54px;color:#fff;font-weight:900;letter-spacing:3px;margin-bottom:8px;text-shadow:0 2px 12px rgba(0,0,0,0.10);">Inspira</div>
            <div class="brand-tagline" style="color:#fff;font-size:18px;font-weight:500;opacity:0.98;margin-bottom:10px;text-align:center;max-width:340px;text-shadow:0 2px 12px rgba(0,0,0,0.10);">
                Platform CMS modern untuk mengelola, membagikan, dan menginspirasi dunia dengan konten Anda.
            </div>
        </div>
        <div class="right-panel">
            <div class="register-container">
                <div class="register-title">Register</div>
                <form action="#" method="post" autocomplete="off">
                    <div class="form-label">USERNAME</div>
                    <input type="text" class="form-control" name="username" required autofocus>
                    <div class="form-label">EMAIL</div>
                    <input type="email" class="form-control" name="email" required>
                    <div class="form-label">PASSWORD</div>
                    <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password" required>
                        <span class="show-password" onclick="togglePassword('password', 'eye-icon1')">
                          <i class="fas fa-eye" id="eye-icon1"></i>
                        </span>
                    </div>
                    <div class="form-label">CONFIRM PASSWORD</div>
                    <div class="input-group">
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                        <span class="show-password" onclick="togglePassword('confirm_password', 'eye-icon2')">
                          <i class="fas fa-eye" id="eye-icon2"></i>
                        </span>
                    </div>
                    <button type="submit" class="btn-register">REGISTER</button>
                </form>
                <a href="login.php" class="login-link">Already have an account?</a>
                <?php if ($error): ?>
                    <div class="alert alert-danger" style="margin:24px auto 0 auto;text-align:center;"> <?php echo $error; ?> </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, iconId) {
            var pwd = document.getElementById(inputId);
            var icon = document.getElementById(iconId);
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html> 