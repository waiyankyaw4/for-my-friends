<?php
session_start();
require 'db.php';

// Assuming the user is logged in and user_id is stored in the session
$user_id = $_SESSION['user_id'];

$sql = "SELECT username, email, profile_photo FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_photo);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <?php
        $profile_image = "uploads/default.png"; // Default profile image
        if (!empty($profile_photo)) {
            $profile_image = "uploads/" . htmlspecialchars($profile_photo);
        }
        ?>
        <img src="<?php echo $profile_image; ?>" alt="Profile Picture">
        <p>Username: <?php echo htmlspecialchars($username); ?></p>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <a href="upload_form.php">Upload New Profile Picture</a>
        <a href="home.php">Go to homepage</a>
    </div>
</body>
</html>
