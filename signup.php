<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
</head>
<body>
    <div class="signup-page">
        <div class="signup-page-content">
            <form action="signup.php" class="signup-page-form" method="POST">
                <input type="text" class="form-signup-input" name="username" placeholder="User Name" required>
                <input type="email" class="form-signup-input" name="email" placeholder="Email" required>
                <input type="password" class="form-signup-input" name="password" id="password" placeholder="Password" required>
                <span class="password-strength" id="password-strength"></span>
                <input type="submit" class="signup-btn" value="Sign Up">
            </form>
            <button class="form-signup-btn signup" onclick="location.href='index.php';">Log In</button>
        </div>
    </div>

    <?php
    session_start();
    

    // Include the database connection file
    include_once 'dbcon.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Validate input
        if (empty($name) || empty($email) || empty($password)) {
            die("All fields are required.");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format.");
        }

        // Check if email already exists
        if ($stmt = $con->prepare("SELECT id FROM users WHERE email = ?")) {
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                die("Email is already registered.");
            }
            $stmt->close();
        }

        // Check if name already exists
        if ($stmt = $con->prepare("SELECT id FROM users WHERE username = ?")) {
            $stmt->bind_param('s', $name);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                die("Name is already taken. Please choose another name.");
            }
            $stmt->close();
        }

        // // Password strength check
        // $password_strength = password_strength($password);
        // if ($password_strength < 3) {
        //     die("Password is too weak. Please choose a stronger password.");
        // }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data with inactive status (email verification optional)
        if ($stmt = $con->prepare("INSERT INTO users (username, email, password, is_active) VALUES (?, ?, ?, ?)")) {
            $is_active = 1; // Default to active

            $stmt->bind_param('sssi', $name, $email, $hashed_password, $is_active);

            if ($stmt->execute()) {
                echo "User registered successfully.";
                // Redirect to prevent form resubmission on refresh
                header("Location: index.php");
                exit();
            } else {
                echo "Failed to register user: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to prepare the SQL statement.";
        }
    }

    $con->close();

    // Function to check password strength
    // function password_strength($password) {
    //     $strength = 0;
    //     $password_info = zxcvbn($password);
    //     $strength = $password_info['score']; // zxcvbn score ranges from 0 to 4
    //     return $strength;
    // }
    ?>
    
    <script>
        // Password strength meter using zxcvbn library
        const passwordInput = document.getElementById('password');
        const passwordStrengthText = document.getElementById('password-strength');

        passwordInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const passwordInfo = zxcvbn(password);
            const strength = passwordInfo.score; 

            switch (strength) {
                case 0:
                    passwordStrengthText.textContent = 'Very Weak';
                    break;
                case 1:
                    passwordStrengthText.textContent = 'Weak';
                    break;
                case 2:
                    passwordStrengthText.textContent = 'Fair';
                    break;
                case 3:
                    passwordStrengthText.textContent = 'Strong';
                    break;
                case 4:
                    passwordStrengthText.textContent = 'Very Strong';
                    break;
                default:
                    passwordStrengthText.textContent = '';
            }
        });
    </script>
</body>
</html>
