<?php
session_start();
require_once 'config/database.php';

$success = '';
$error = '';

if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

// Process forgot password request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (empty($_POST['email'])) {
            throw new Exception("Email harus diisi.");
        }

        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Format email tidak valid.");
        }

        $db = new Database();
        if ($db->emailExists($email)) {
            // Store email in session for reset password page
            $_SESSION['reset_email'] = $email;
            header("Location: reset-password.php");
            exit();
        } else {
            throw new Exception("Email tidak ditemukan dalam sistem.");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Inspira CMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e90ff;
            --primary-hover: #187bcd;
            --error-color: #dc3545;
            --success-color: #28a745;
            --text-color: #333;
            --text-light: #666;
            --border-color: #e0d7fa;
            --bg-gradient: linear-gradient(135deg, #22c1c3 0%, #7b2ff2 100%);
        }

        body {
            min-height: 100vh;
            background: var(--bg-gradient);
            margin: 0;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-color);
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
            width: 180px;
            height: 180px;
            margin-bottom: 20px;
        }

        .brand-title {
            color: #fff;
            font-size: 48px;
            font-weight: 900;
            letter-spacing: 2px;
            margin-bottom: 12px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.10);
            font-family: 'Poppins', sans-serif;
        }

        .brand-tagline {
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            opacity: 0.98;
            margin-bottom: 12px;
            text-align: center;
            max-width: 360px;
            text-shadow: 0 2px 12px rgba(0,0,0,0.10);
        }

        .right-panel {
            flex: 0 0 420px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            min-width: 360px;
            padding: 0 4vw;
        }

        .forgot-container {
            background: rgba(255,255,255,0.85);
            border-radius: 16px;
            box-shadow: 0 8px 32px 0 rgba(123,47,242,0.10);
            width: 380px;
            max-width: 95vw;
            padding: 24px 24px 20px 24px;
            position: relative;
            border: 1.5px solid var(--border-color);
        }

        .forgot-container::after {
            content: '';
            position: absolute;
            top: -1px; right: -1px;
            height: calc(100% + 2px);
            width: 16px;
            background: linear-gradient(180deg, #7b2ff2 0%, #04c8de 100%);
            border-radius: 0 18px 18px 0;
        }

        .forgot-title {
            color: var(--primary-color);
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
        }

        .form-label {
            font-size: 12px;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 4px;
            margin-top: 8px;
            letter-spacing: 0.5px;
            font-family: 'Poppins', sans-serif;
        }

        .form-control {
            width: 100%;
            height: 38px;
            border: none;
            background: #f2f2f2;
            border-radius: 6px;
            padding: 0 14px;
            font-size: 14px;
            color: var(--text-color);
            margin-bottom: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        .input-group {
            position: relative;
            margin-bottom: 16px;
        }

        .btn-submit {
            width: 100%;
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 6px;
            height: 38px;
            font-size: 14px;
            font-weight: 600;
            margin-top: 16px;
            letter-spacing: 0.5px;
            transition: background 0.2s;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .login-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: var(--primary-color);
            font-size: 12px;
            text-decoration: none;
            cursor: pointer;
            pointer-events: auto;
            font-family: 'Inter', sans-serif;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 5px;
            margin-bottom: 18px;
            padding: 10px;
            font-size: 12px;
            font-family: 'Inter', sans-serif;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: var(--error-color);
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: var(--success-color);
            border: 1px solid #c3e6cb;
        }

        .error-message {
            color: var(--error-color);
            font-size: 12px;
            margin-top: 4px;
            display: none;
            font-family: 'Inter', sans-serif;
        }

        .input-group.error input {
            border: 1px solid var(--error-color);
        }

        .input-group.success input {
            border: 1px solid var(--success-color);
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
            <img src="images/Logo.png" alt="Inspira Logo" class="brand-img" style="width:180px;height:180px;margin-bottom:12px;object-fit:contain;background:transparent;filter:drop-shadow(0 2px 16px rgba(0,0,0,0.08));">
            <div class="brand-title">Inspira</div>
            <div class="brand-tagline">
                Platform CMS modern untuk mengelola, membagikan, dan menginspirasi dunia dengan konten Anda.
            </div>
        </div>
        <div class="right-panel">
            <div class="forgot-container">
                <div class="forgot-title">FORGOT PASSWORD</div>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <form action="forgot-password.php" method="post" autocomplete="off" id="forgotForm">
                    <div class="form-label">EMAIL</div>
                    <div class="input-group">
                        <input type="email" class="form-control" name="email" id="email" required autofocus>
                        <div class="error-message" id="email-error"></div>
                    </div>

                    <button type="submit" class="btn-submit">NEW PASSWORD</button>
                </form>
                <a href="login.php" class="login-link">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
    // Real-time validation
    const email = document.getElementById('email');
    const form = document.getElementById('forgotForm');

    // Email validation
    email.addEventListener('input', function() {
        const value = this.value.trim();
        const errorElement = document.getElementById('email-error');
        const inputGroup = this.parentElement;

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            errorElement.textContent = 'Format email tidak valid';
            errorElement.style.display = 'block';
            inputGroup.classList.add('error');
            inputGroup.classList.remove('success');
        } else {
            errorElement.style.display = 'none';
            inputGroup.classList.remove('error');
            inputGroup.classList.add('success');
        }
    });

    // Form submission validation
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Email validation
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
            document.getElementById('email-error').style.display = 'block';
            email.parentElement.classList.add('error');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });
    </script>
</body>
</html> 