<?php
session_start();
include_once 'dbcon.php'; // Include your database connection file

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

$email = $data['email'];

if ($email) {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    $subject = 'Your OTP Code';
    $message = 'Your OTP code is ' . $otp;
    $headers = 'From: your-email@example.com';

    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(['success' => true, 'message' => 'OTP sent successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send OTP']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email is required']);
}
?>
