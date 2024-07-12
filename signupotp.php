<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="signup-otp-page">
        <div class="signup-otp-page-content">
            <h2>VERIFY OTP</h2>
            <form class="signup-otp-page-form" action="signupotpverify.php" method="POST">
                <input type="text" name="otp" placeholder="Enter OTP" required>
                <button class="signup-otp-send-btn" type="submit">Verify OTP</button>
            </form>
        </div>
    </div>
    <div id="error-popup" class="popup">
        <div class="popup-content">
            <p id="error-message"></p>
            <button class="btn" onclick="hidePopup()">Close</button>
        </div>
    </div>
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
</body>

</html>