<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<link rel="stylesheet" href="style.css">
<body>
    <!-- Change Password page -->
    <div class="change-password-page">
        <div class="change-password-page-content">
            <form action="changepassword.php" class="change-password-form" method="POST">
                <input type="text" class="form-password-input" placeholder="Email" name="email" required>
                <input type="text" class="form-password-input" placeholder="New Password" name="password" required>
                <input type="submit" class="form-password-btn" value="Confirm">
            </form>
        </div>
    </div>
    <!-- End of Change Password page -->
     
    <?php
// Include the database connection file
include_once 'dbcon.php';

// Function to generate OTP
function generateOTP() {
    return mt_rand(100000, 999999); // Generates a random 6-digit OTP
}

// Function to send OTP via email
function sendOTP($email, $otp) {
    $subject = "Your OTP for Password Change";
    $message = "Your OTP is: " . $otp . "\n\nUse this OTP to change your password.";
    $headers = "From: noreply@yourwebsite.com";

    return mail($email, $subject, $message, $headers);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    // Generate a new OTP
    $otp = generateOTP();
    $otp_expiry = date("Y-m-d H:i:s", strtotime('+10 minutes')); // Adjust expiry time as needed

    // Insert or update OTP in the password_resets table
    if ($insert_stmt = $con->prepare("INSERT INTO password_resets (user_id, token, token_expiry) 
        VALUES ((SELECT id FROM users WHERE email = ?), ?, ?) 
        ON DUPLICATE KEY UPDATE token = VALUES(token), token_expiry = VALUES(token_expiry)")) {
        
        $insert_stmt->bind_param('sis', $email, $otp, $otp_expiry);
        if ($insert_stmt->execute()) {
            // Send OTP via email
            if (sendOTP($email, $otp)) {
                echo "An OTP has been sent to your email address.";
                echo '<form action="verifyotp.php" method="POST">
                        <input type="hidden" name="email" value="'.$email.'">
                        <input type="hidden" name="password" value="'.$_POST['password'].'">
                        <input type="hidden" name="otp_expiry" value="'.$otp_expiry.'">
                        <input type="text" name="otp" placeholder="Enter OTP" required>
                        <input type="submit" value="Verify OTP">
                      </form>';
            } else {
                echo "Failed to send OTP. Please try again.";
            }
        } else {
            echo "Failed to store OTP in the database.";
        }
        $insert_stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }
}

$con->close();
?>



</body>
</html>