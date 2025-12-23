<?php
// Database credentials for XAMPP
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$dbname = "tourism_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If it fails, stop the script and show the error
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: Set charset to utf8 to avoid character issues
$conn->set_charset("utf8");
?>