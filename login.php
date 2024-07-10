<?php
session_start();

// Logout any existing session
if (isset($_SESSION["email"])) {
    session_destroy();
    session_start();
}

include_once 'dbcon.php';

// Get input values
$email = $_POST['email'];
$password = $_POST['password'];

// Validate inputs
if (empty($email) || empty($password)) {
    header("location:index.php?w=Invalid input");
    exit;
}

// Sanitize the email input
$email = $con->real_escape_string($email);

// Construct the SQL query
$sql = "SELECT username, password FROM users WHERE email = '$email'";
$result = $con->query($sql);

if ($result) {
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $hashedPassword = $row['password'];
        
        // Verify the password
        if (password_verify($password, $hashedPassword)) {
            // Successful login: regenerate session ID and set session variables
            session_regenerate_id(true);
            $_SESSION["username"] = $username;
            $_SESSION["email"] = $email; // Store email used for login
            header("location:profile.php");
            exit;
        }
    }
}

// Redirect on failure
header("location:index.php?w=Wrong Email or Password");
exit;

?>
