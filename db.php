<?php
$servername = "localhost"; // Replace with your database server name
$username = "wai"; // Replace with your database username
$password = "Wyk"; // Replace with your database password
$dbname = "ok"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
