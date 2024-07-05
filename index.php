<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SECURE</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Login page -->
    <div class="login-page">
        <div class="login-page-content">
            <form class="login-page-form" action="login.php" method="POST">
                <input type="text" class="form-login-input" placeholder=" UserName or Email " name="username_or_email" required>
                <input type="password" class="form-login-input" placeholder="Password" name="password" required>
                <input type="submit" class="form-login-btn" value="Log In">
                <a href="#" class="form-login-link">Forgot Password?</a>
                <span>or</span>
            </form>
            <button class="form-signup-btn signup">signup</button>
        </div>
    </div>
    <!-- End of Login page -->

    <!-- Sign Up page -->
    <div class="signup-page">
        <div class="signup-page-content">
            <form action="signup.php" class="signup-page-form" method="POST">
                <input type="text" class="form-signup-input" name="username" placeholder="User Name" required>
                <input type="text" class="form-signup-input" name="email" placeholder="Email" required>
                <input type="password" class="form-signup-input" name="password" placeholder="Password" required>
                <input type="submit" class="signup-btn" value="Sign Up">
                <span>Have an Account?</span>
            </form>
            <button class="form-signup-btn signup">Log In</button>

        </div>
    </div>
    <!--End of Sign Up page -->
    
    <div class="profile-page">
        <div class="profile-page-content">

        </div>
    </div>
</body>

</html>