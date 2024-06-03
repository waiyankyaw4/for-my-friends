<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .profile-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .profile-container img {
            border-radius: 50%;
            width: 150px;
            height: 150px;
        }
        .profile-container a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            color: #1877f2;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>
        <?php
        $profile_image = "uploads/default.png"; // รูปโปรไฟล์เริ่มต้น
		 if (isset($_GET['img'])) {
            $profile_image = "uploads/" . htmlspecialchars($_GET['img']);
        }
        ?>
        <img src="<?php echo $profile_image; ?>" alt="Profile Picture">
        <p>Welcome to your profile!</p>
       <a href="upload_form.php">Upload New Profile Picture</a>
       <a href="home.php">Go to homepage</a>

    </div>
</body>
</html>
