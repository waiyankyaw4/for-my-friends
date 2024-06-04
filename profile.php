<?php
session_start();
require 'db.php';

// Assuming the user is logged in and user_id is stored in the session
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['profile_photo'])) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["profile_photo"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Attempt to upload file
        if (move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
            $sql = "UPDATE users SET profile_photo = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $user_id);
            if ($stmt->execute()) {
                echo "The file ". htmlspecialchars(basename($_FILES["profile_photo"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error updating your profile photo.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="style3.css">
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <?php
        $profile_image = "uploads/default.png"; // Default profile picture
        if ($user['profile_photo']) {
            $profile_image = htmlspecialchars($user['profile_photo']);
        }
        ?>
        <img src="<?php echo $profile_image; ?>" alt="Profile Picture" class="profile-picture">
        <p>Welcome to your profile, <?php echo htmlspecialchars($user['username']); ?>!</p>
        
        <h3>Upload a new profile photo</h3>
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <label for="profile_photo" class="file-label">Select image to upload:</label>
            <input type="file" name="profile_photo" id="profile_photo">
            <input type="submit" value="Upload Image" name="submit" class="upload-button">
        </form>

        <a href="home.php" class="home-link">Go to homepage</a>
    </div>
</body>
</html>
