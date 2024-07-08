<?php
// Include the database connection file
include_once 'dbcon.php';
include_once("changepassword.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $otp = trim($_POST['otp']);
    $otp_expiry = trim($_POST['otp_expiry']);

    // Validate inputs
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($password) || empty($otp)) {
        die("Invalid input data.");
    }

    // Check if OTP exists and is valid
    if ($stmt = $con->prepare("SELECT user_id, token, token_expiry FROM password_resets WHERE user_id = (SELECT id FROM users WHERE email = ?) AND token = ? AND token_expiry >= NOW()")) {
        $stmt->bind_param('ss', $email, $otp);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // OTP is valid, update the user's password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if ($update_stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?")) {
                $update_stmt->bind_param('ss', $hashed_password, $email);
                if ($update_stmt->execute()) {
                    // Password updated successfully, delete the OTP record
                    if ($delete_stmt = $con->prepare("DELETE FROM password_resets WHERE user_id = (SELECT id FROM users WHERE email = ?)")) {
                        $delete_stmt->bind_param('s', $email);
                        $delete_stmt->execute();

                        echo "Password updated successfully.";
                    } else {
                        echo "Failed to delete OTP record.";
                    }
                } else {
                    echo "Failed to update password.";
                }
            } else {
                echo "Failed to prepare update statement.";
            }
        } else {
            echo "Invalid OTP or OTP has expired.";
        }
    } else {
        echo "Failed to prepare SQL statement.";
    }

    $stmt->close();
    $con->close();
} else {
    die("Invalid request.");
}
?>
