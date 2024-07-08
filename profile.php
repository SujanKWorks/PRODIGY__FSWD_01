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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Welcome to Your Profile Page</h2>
   
    <div id="logout-popup" class="popup">
    <div class="popup-content">
        <h2>Logout Confirmation</h2>
        <p>Are you sure you want to logout?</p>
        <form id="logout-form" action="logout.php" method="post">
            <button type="submit">Logout</button>
            <button type="button" onclick="hideLogoutPopup()">Cancel</button>
        </form>
    </div>
</div>
<script>
    // Function to show the logout confirmation popup
    function showLogoutPopup() {
        document.getElementById("logout-popup").style.display = "flex";
    }

    // Function to hide the logout confirmation popup
    function hideLogoutPopup() {
        document.getElementById("logout-popup").style.display = "none";
    }
</script>
</body>
</html>
