<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SmartPage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #1e90ff 100%);
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
            width: 120px;
            height: 120px;
            margin-bottom: 18px;
        }
        .brand-title {
            color: #1e90ff;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 2px;
            margin-bottom: 8px;
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
        .forgot-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
            width: 340px;
            max-width: 90vw;
            padding: 28px 28px 20px 28px;
            position: relative;
        }
        .forgot-container::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0;
            width: 12px;
            background: #1e90ff;
            border-radius: 0 10px 10px 0;
        }
        .forgot-title {
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
        .btn-forgot {
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
        .btn-forgot:hover {
            background: #187bcd;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 16px;
            color: #1e90ff;
            font-size: 13px;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
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
            <img src="https://cdn-icons-png.flaticon.com/512/2721/2721723.png" alt="SmartPage Logo" class="brand-img">
            <div class="brand-title">SmartPage</div>
            <div class="brand-desc">Content Management System</div>
        </div>
        <div class="right-panel">
            <div class="forgot-container">
                <div class="forgot-title">Forgot Password</div>
                <form action="#" method="post" autocomplete="off">
                    <div class="form-label">EMAIL</div>
                    <input type="email" class="form-control" name="email" required autofocus>
                    <button type="submit" class="btn-forgot">Send Reset Link</button>
                </form>
                <a href="login.php" class="back-link">Back to Login</a>
            </div>
        </div>
    </div>
</body>
</html> 