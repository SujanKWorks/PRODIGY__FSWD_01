<?php
// sendOtp.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['email']) || empty($data['email'])) {
        echo json_encode(['success' => false, 'message' => 'Email is required']);
        exit;
    }

    $email = $data['email'];

    // Generate OTP (assuming a 6-digit OTP)
    $otp = mt_rand(100000, 999999);

    // Store OTP in session (for verification)
    session_start();
    $_SESSION['otp'] = $otp;

    // Example: Send OTP via email (you should implement your actual email sending logic here)
    // Replace this with your email sending code
    $to = $email;
    $subject = 'OTP for Password Reset';
    $message = 'Your OTP is: ' . $otp;

    // Example using PHP's mail function
    if (mail($to, $subject, $message)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
