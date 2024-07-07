<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // Redirect to login page
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
</head>
<body>
    <h2>Welcome to Your Profile Page</h2>
    <!-- Your profile content -->
    
    <!-- Example logout link -->
    <a href="logout.php">Logout</a>
</body>
</html>
