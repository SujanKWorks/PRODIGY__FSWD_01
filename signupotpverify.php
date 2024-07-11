<?php
session_start();
include_once 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp']);
    
    if (!isset($_SESSION['temp_user'])) {
        echo "<script>showPopup('Session expired. Please register again.');</script>";
        exit();
    }

    $temp_user = $_SESSION['temp_user'];
    $stored_otp = $temp_user['otp'];

    if ($entered_otp == $stored_otp) {
        if ($stmt = $con->prepare("INSERT INTO users (username, email, password, is_active) VALUES (?, ?, ?, ?)")) {
            $is_active = 1; 
            $stmt->bind_param('sssi', $temp_user['username'], $temp_user['email'], $temp_user['password'], $is_active);

            if ($stmt->execute()) {
                unset($_SESSION['temp_user']);
                echo "<script>showPopup('User registered successfully.');</script>";
                header("Location: index.php");
                exit();
            } else {
                echo "<script>showPopup('Failed to register user: " . $stmt->error . "');</script>";
                exit();
            }
        } else {
            echo "<script>showPopup('Failed to prepare the SQL statement.');</script>";
            exit();
        }
    } else {
        echo "<script>showPopup('Invalid OTP. Please try again.');</script>";
        exit();
    }
}

$con->close();
?>
