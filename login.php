<?php
include_once 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare and execute the SQL statement
    if ($stmt = $con->prepare('SELECT id, password, token, token_expiry FROM users WHERE email = ?')) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $hashed_password, $stored_token, $token_expiry);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Check if the token is valid and not expired
                if ($stored_token && strtotime($token_expiry) > time()) {
                    // Token is valid and not expired, log in user
                    session_start();
                    $_SESSION["name"] = 'sujan';
                    $_SESSION["key"] = '1';
                    $_SESSION["email"] = $email;
                    $_SESSION["token"] = $stored_token;
                    header("Location: profile.php?q=0");
                    exit();
                } else {
                    // Generate a new token
                    $new_token = bin2hex(random_bytes(32));
                    $new_token_expiry = date("Y-m-d H:i:s", strtotime('+7 days'));

                    // Update the token and token expiry in the database
                    if ($update_stmt = $con->prepare("UPDATE users SET token = ?, token_expiry = ? WHERE id = ?")) {
                        $update_stmt->bind_param('ssi', $new_token, $new_token_expiry, $id);
                        if ($update_stmt->execute()) {
                            // Set session variables
                            session_start();
                            $_SESSION["name"] = 'sujan';
                            $_SESSION["key"] = '1';
                            $_SESSION["email"] = $email;
                            $_SESSION["token"] = $new_token;
                            header("Location: profile.php?q=0");
                            exit();
                        } else {
                            header("Location: index.php?w=Error updating token");
                            exit();
                        }
                    } else {
                        header("Location: index.php?w=Error preparing update statement");
                        exit();
                    }
                }
            } else {
                header("Location: index.php?w=Invalid credentials");
                exit();
            }
        } else {
            header("Location: index.php?w=Invalid credentials");
            exit();
        }

        // $stmt->close();
    } else {
        die('Error: Could not prepare statement');
    }
}

$con->close();
?>
