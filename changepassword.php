<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- Change Password page -->
    <div class="change-password-page">
        <div class="return">
            <a href="index.php" class="arrow">&larr;</a>
        </div>
        <div class="change-password-page-content">
            <form id="changePasswordForm" class="change-password-form" method="post" action="verifyOtp.php">
                <input type="email" class="form-password-input" placeholder="Email" name="email" id="emailInput" required>
                <input type="password" class="form-password-input" placeholder="New Password" name="password" id="passwordInput" required>
                <div class="otp-page">
                    <input type="text" class="otp-form-input" name="otp" id="otpInput" placeholder="Enter OTP" required>
                    <button type="button" class="otp-send-btn" id="sendOtpBtn">Send OTP</button>
                </div>
                <input type="submit" class="form-password-btn" value="Confirm">
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const changePasswordForm = document.getElementById('changePasswordForm');

            sendOtpBtn.addEventListener('click', function () {
                const email = document.getElementById('emailInput').value;

                if (email) {
                    fetch('sendOtp.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: email })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('OTP sent to your email');
                            } else {
                                alert('Failed to send OTP');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    alert('Please enter your email');
                }
            });
        });
    </script>
</body>

</html>
