<?php
session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['email'])) {
//     header("Location: index.php"); // Redirect to login page
//     exit();
// }

?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
   <div class="profile-page">
    <div class="profile-page-content">
    <h2>Welcome to Your Profile Page</h2>
        <div class="profile">
            <button class="logout-btn" onclick="showLogoutPopup()">
                Log Out</button>
        </div>
    </div>
   </div>
    <div id="logout-popup" class="popup">
    <div class="popup-content">
        <p>Are you sure you want to logout?</p>
        <form class="logout-form" action="logout.php" method="post">
            <button class="logout-btn" type="submit">Logout</button>
            <button class="logout-btn" type="button" onclick="hideLogoutPopup()">Cancel</button>
        </form>
    </div>
</div>
<script src="scripts/script.js"></script>
</body>

</html>
