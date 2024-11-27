<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Login Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

    <style>
    * {
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
        padding: 45px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .user-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #ffd740;
        display: inline-block;
    }

    .input-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group input {
        width: 100%;
        padding: 10px 40px;
        border: 1px solid;
        border-radius: 1rem;
        font-size: 16px;
        box-sizing: border-box;
        flex: 1;
    }

    .input-group i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
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

    form .btn {
        width: 50%;
    }

    .login-text {
        font-weight: bold;
    }

    a {
        font-size: 16px;
        color: grey;
    }

    p {
        margin: 0;
    }

    .forgot-btn {
        display: flex;
        justify-content: flex-end;
        gap: 10px; 
        button{
            width: auto;
        }
    }

    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="logo-container mb-3">
                <img src="https://via.placeholder.com/100x100?text=ICON" alt="User Icon" class="user-icon">
            </div>
            <h2 class="login-text">Login</h2>
            <form action="#" method="POST">
                <div class="input-group mt-3">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <div class="input-group mt-3">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary rounded-pill mt-3">Sign in</button>
                <p class="signup-link">
                    <a href="#" data-toggle="modal" data-target="#registerModal">สมัครสมาชิก</a>
                </p>
                <p class="forgot-password-link mt-0">
                    <a href="#" id="forgotPasswordLink" data-toggle="modal" data-target="#forgotPasswordModal">Forget
                        your Password?</a>
                </p>
            </form>
        </div>
    </div>


    <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content registor">
                <div class="modal-header align-items-center">
                    <h5 class="modal-title mx-auto" id="registerModalLabel">Register</h5>
                </div>
                <form method="POST" action="">
                    <div class="modal-body px-4">
                        <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger text-center"><?php echo $error_message; ?></div>
                        <?php endif; ?>
                        <div class="form-group">
                            <input type="text" name="name" id="name" class="form-control rounded-pill"
                                placeholder="Enter your name"
                                value="<?php echo isset($name_input) ? htmlspecialchars($name_input) : ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-control rounded-pill"
                                placeholder="Enter your email"
                                value="<?php echo isset($email_input) ? htmlspecialchars($email_input) : ''; ?>"
                                required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" id="registerPassword"
                                class="form-control rounded-pill" placeholder="Enter your password" required>
                            <i class="fa fa-eye-slash toggle-password" data-target="registerPassword"
                                style="top: 3.1rem;padding-right: 5px;"></i>
                        </div>
                        <div class="form-group">
                            <label class="form-group-label" for="confirmPassword">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirmPassword"
                                class="form-control rounded-pill" placeholder="Confirm your password" required>
                            <i class="fa fa-eye-slash toggle-password" data-target="confirmPassword"
                                style="top: 3.1rem;padding-right: 5px;"></i>
                        </div>
                    </div>
                    <div class="modal-footer registor">
                        <button type="submit" name="register" class="btn btn-primary rounded-pill">Register</button>
                        <p class="text-center">
                            <a href="#" class="login-link" data-dismiss="modal">Have an Account? Login Here</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal">
                <div class="modal-header align-items-center">
                    <h5 class="modal-title mx-auto">Forgot Password</h5>
                </div>
                <div class="modal-body px-4">
                    <form id="forgotPasswordForm" method="POST" action="process_forgot_password.php">
                        <div class="form-group">
                            <input type="email" name="email" class="form-control rounded-pill"
                                placeholder="Enter your email address" required>
                        </div>
                        <div class="forgot-btn">
                            <button type="submit" class="btn btn-primary rounded-pill custom-btn">Send Reset
                                Link</button>
                            <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.querySelectorAll(".toggle-password").forEach(function(icon) {
        icon.addEventListener("click", function() {
            const input = document.getElementById(this.getAttribute("data-target"));
            if (input.type === "password") {
                input.type = "text";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            } else {
                input.type = "password";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            }
        });
    });
    </script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>