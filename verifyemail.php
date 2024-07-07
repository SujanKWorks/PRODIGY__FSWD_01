<?php
// Include the database connection file
include_once 'dbcon.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate token and activate user account
    if ($stmt = $con->prepare("SELECT id, token_expiry FROM users WHERE token = ? AND is_active = 0")) {
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $token_expiry);
            $stmt->fetch();

            if (strtotime($token_expiry) > time()) {
                // Activate user account
                if ($update_stmt = $con->prepare("UPDATE users SET is_active = 1, token = NULL, token_expiry = NULL WHERE id = ?")) {
                    $update_stmt->bind_param('i', $user_id);
                    if ($update_stmt->execute()) {
                        echo "Your email has been verified successfully. You can now log in.";
                        header("Location: login.php");
                        exit();
                    } else {
                        echo "Failed to activate account: " . $update_stmt->error;
                    }
                    $update_stmt->close();
                }
            } else {
                echo "Token has expired. Please request a new verification email.";
            }
        } else {
            echo "Invalid or already used token.";
        }
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }
} else {
    echo "No token provided.";
}

$con->close();
?>
