<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
</head>
<body>
    <div class="signup-page">
        <div class="signup-page-content">
            <form action="signup.php" class="signup-page-form" method="POST">
                <input type="text" class="form-signup-input" name="username" placeholder="User Name" required>
                <input type="email" class="form-signup-input" name="email" placeholder="Email" required>
                <input type="password" class="form-signup-input" name="password" id="password" placeholder="Password" required>
                <span class="password-strength" id="password-strength"></span>
                <input type="submit" class="signup-btn" value="Sign Up">
                <span>or</span>
            </form>
            <button class="form-signup-btn signup" onclick="location.href='index.php';">Log In</button>
        </div>
    </div>
    <div id="error-popup" class="popup">
    <div class="popup-content">
        <p id="error-message"></p>
        <button class="btn" onclick="hidePopup()">Close</button>
    </div>
</div>
<script src="scripts/script.js"></script>
    <script src="scripts/password.js"></script>
</body>
</html>
<?php
session_start();
include_once 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>showPopup('All fields are required.');</script>";
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>showPopup('Invalid email format.');</script>";
        exit();
    }

    if ($stmt = $con->prepare("SELECT id FROM users WHERE email = ?")) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>showPopup('Email is already registered.');</script>";
            exit();
        }
        $stmt->close();
    }

    if ($stmt = $con->prepare("SELECT id FROM users WHERE username = ?")) {
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>showPopup('Name is already taken. Please choose another name.');</script>";
            exit();
        }
        $stmt->close();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $otp = rand(100000, 999999); // Generate a 6-digit OTP

    $_SESSION['temp_user'] = [
        'username' => $name,
        'email' => $email,
        'password' => $hashed_password,
        'otp' => $otp
    ];

    $subject = "Your OTP Code";
    $message = "Your OTP code is: $otp";
    $headers = "From: no-reply@example.com";

    if (mail($email, $subject, $message, $headers)) {
        header("Location: signupotp.php");
        exit();
    } else {
        echo "<script>showPopup('Failed to send OTP. Please try again.');</script>";
        exit();
    }
}
$con->close();
?>