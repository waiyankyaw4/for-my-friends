<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'db.php';

$loginMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        $loginMessage = 'Prepare failed: (' . $conn->errno . ') ' . $conn->error;
    } else {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userId, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                // Store username and user ID in the session
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $userId;
                header("Location: profile.php");
                exit();
            } else {
                $loginMessage = 'Invalid username or password!';
            }
        } else {
            $loginMessage = 'Invalid username or password!';
        }

        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <div class="login-container">
            <h2>Login</h2>
            <?php
            if ($loginMessage) {
                echo '<div class="message">' . $loginMessage . '</div>';
            }
            ?>
            <form action="index.php" method="post">
                <div class="input-group">
                    <label for="login-username">Username</label>
                    <input type="text" id="login-username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="login-password">Password</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit">Login</button>
                <p>Don't have an account? <a href="register.php">Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>
