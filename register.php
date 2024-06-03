<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

$registerMessage = '';

function isPasswordStrong($password) {
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if password is strong
    if (!isPasswordStrong($password)) {
        $registerMessage = 'Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.';
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Check if username or email already exists
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $registerMessage = 'Prepare failed: (' . $conn->errno . ') ' . $conn->error;
        } else {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $registerMessage = 'Username or email already exists!';
            } else {
                $stmt->close();

                // Insert new user
                $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                if (!$stmt) {
                    $registerMessage = 'Prepare failed: (' . $conn->errno . ') ' . $conn->error;
                } else {
                    $stmt->bind_param("sss", $username, $email, $passwordHash);

                    if ($stmt->execute()) {
                        $registerMessage = 'Registration successful!';
                    } else {
                        $registerMessage = 'Error: ' . $stmt->error;
                    }
                }
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <div class="register-container">
            <h2>Register</h2>
            <?php
            if ($registerMessage) {
                echo '<div class="message">' . $registerMessage . '</div>';
            }
            ?>
            <form action="register.php" method="post">
                <div class="input-group">
                    <label for="register-username">Username</label>
                    <input type="text" id="register-username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="register-email">Email</label>
                    <input type="email" id="register-email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="register-password">Password</label>
                    <input type="password" id="register-password" name="password" required>
                </div>
                <button type="submit">Register</button>
                <p>Already have an account? <a href="index.php">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>
