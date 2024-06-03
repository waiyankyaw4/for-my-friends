<?php
session_start(); // Start the session

// Database connection
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ok", "wai", "Wyk");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Check if file is an image
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.<br>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.<br>";
        $uploadOk = 0;
    }

    // Check file size (5MB limit)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.<br>";
        $uploadOk = 0;
    }

    // Allow only certain file formats
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.<br>";
    } else {
        // Attempt to upload file
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            // File uploaded successfully, now insert the file name into the database
            $filename = basename($_FILES["fileToUpload"]["name"]);

            // Check if the user is logged in by verifying the session variable
            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                try {
                    // Update the profile_photo column in the users table based on the username
                    $stmt = $pdo->prepare("UPDATE users SET profile_photo = ? WHERE username = ?");
                    if ($stmt->execute([$filename, $username])) {
                        echo "Profile photo updated successfully.<br>";
                        // Redirect to profile page
                        header("Location: profile.php?img=" . urlencode($filename));
                        exit();
                    } else {
                        echo "Failed to update profile photo.<br>";
                    }
                } catch (PDOException $e) {
                    echo "Database error: " . $e->getMessage();
                }
            } else {
                echo "No user is logged in.<br>";
            }
        } else {
            echo "Sorry, there was an error uploading your file.<br>";
            // Print additional error information
            echo "Error: " . $_FILES["fileToUpload"]["error"] . "<br>";
            // Check if upload directory is writable
            if (!is_writable($target_dir)) {
                echo "Upload directory is not writable.<br>";
            }
        }
    }
}
?>
