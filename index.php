<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f4f4f9;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .logo-container {
            margin-bottom: 20px;
        }

        .user-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #ffd740;
            display: inline-block;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 10px 40px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .login-button {
            width: 100%;
            padding: 10px;
            background-color: #00b0ff;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
        }

        .login-button:hover {
            background-color: #007bb5;
        }

        .signup-link,
        .forgot-password-link {
            font-size: 14px;
            color: #333;
            margin-top: 10px;
            cursor: pointer;
        }

        .signup-link:hover,
        .forgot-password-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 15px;
            }

            .input-group input {
                padding: 10px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container">
                <img src="https://via.placeholder.com/100x100?text=ICON" alt="User Icon" class="user-icon">
            </div>
            <h2>Login</h2>
            <form action="#" method="POST">
                <div class="input-group">
                    <label for="username">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </label>
                </div>
                <div class="input-group">
                    <label for="password">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </label>
                </div>
                <button type="submit" class="login-button">Sign in</button>
                <p class="signup-link">สมัครสมาชิก</p>
                <p class="forgot-password-link">Forget your Password?</p>
            </form>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
