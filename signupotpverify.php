<?php
session_start();
include_once 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = trim($_POST['otp']);
    $current_time = time();

    if (!isset($_SESSION['temp_user'])) {
        echo "<script>showPopup('Session expired. Please register again.');</script>";
        exit();
    }

    $temp_user = $_SESSION['temp_user'];
    $stored_otp = $temp_user['otp'];
    $otp_time = $temp_user['otp_time'];
    $otp_validity_duration = 5 * 60; // OTP validity duration in seconds (5 minutes)

    if (($current_time - $otp_time) > $otp_validity_duration) {
        unset($_SESSION['temp_user']);
        echo "<script>showPopup('OTP has expired. Please register again.');</script>";
        exit();
    }

    if ($entered_otp == $stored_otp) {
        if ($stmt = $con->prepare("INSERT INTO users (username, email, password, is_active) VALUES (?, ?, ?, ?)")) {
            $is_active = 1; // Default to active

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
<html>
<link rel="stylesheet" href="style.css">

<body>

    <div id="popup" class="popup">
        <div class="popup-content">
            <p id="popup-message"></p>
            <button class="btn" onclick="hidePopup()">Close</button>
        </div>
    </div>
</body>

</html>

<script>
    function showPopup(message) {
        const errorPopup = document.getElementById("error-popup");
        const errorMessage = document.getElementById("error-message");

        errorMessage.textContent = message;
        errorPopup.style.display = "block";
    }

    function hidePopup() {
        document.getElementById("error-popup").style.display = "none";
    }
</script>