
<?php
include_once("changepassword.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP verification</title>
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="otp-page">
        <div class="otp-page-content">
        <form action="verifyotp.php" class="otp-page-form" method="POST">
    <input type="hidden" name="email" value="' . $email . '">
    <input type="hidden" name="password" value="' . $_POST['password'] . '">
    <input type="hidden" name="otp_expiry" value="' . $otp_expiry . '">
    <input type="text" class="otp-form-input" name="otp" placeholder="Enter OTP" required>
    <input type="submit" class="otp-form-btn" value="Verify OTP">
  </form>
        </div>
    </div>
</body>
</html>
<?php

