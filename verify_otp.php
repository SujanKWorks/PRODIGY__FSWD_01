<?php
session_start();
include_once 'dbcon.php'; // Include your database connection file

header('Content-Type: application/json');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);

$email = isset($data['email']) ? $data['email'] : '';
$otp = isset($data['otp']) ? $data['otp'] : '';
$password = isset($data['password']) ? $data['password'] : '';

try {
    if (!empty($_SESSION['otp']) && !empty($_SESSION['email']) && $_SESSION['otp'] == $otp && $_SESSION['email'] == $email) {
        // Hash the new password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['email']);
            echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error updating password']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => true, 'message' => 'Password changed successfully']);
    }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }

$conn->close();
?>
