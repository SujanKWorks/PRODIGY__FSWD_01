<?php
session_start();

// Logout any existing session
if(isset($_SESSION["email"])){
    session_destroy();
    session_start(); 
}

include_once 'dbcon.php'; 

// Get input values
$username_or_email = "sujan@gmail.com";
$password = '12345';

// Validate inputs
if (empty($username_or_email) || empty($password)) {
    header("location:index.php?w=Invalid input");
    exit;
}


    $sql = "SELECT username, password FROM users WHERE email = 'sujan@gmail.com' or username = ?";


$stmt = $con->prepare($sql);
$stmt->bind_param('s', $username_or_email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($username, $hashedPassword);
    $stmt->fetch();
    
    // Verify the password
    if (password_verify($password, $hashedPassword)) {
        // Regenerate session ID to prevent session fixation
        session_regenerate_id(true);
        $_SESSION["username"] = $username;
        $_SESSION["email"] = $username_or_email; // Store email or username used for login
        header("location:index.php?q=1");
        exit;
    }
}

// Redirect on failure
header("location:index.php?w=Wrong Username or Password");
exit;

?>
