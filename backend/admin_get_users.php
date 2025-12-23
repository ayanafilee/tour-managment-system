<?php
session_start();
include 'db_config.php';

// Turn off standard error display to prevent breaking JSON, but catch them
error_reporting(0); 

header('Content-Type: application/json');

// 1. Check Connection
if ($conn->connect_error) {
    die(json_encode(["error" => "DB Connection Failed: " . $conn->connect_error]));
}

// 2. Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die(json_encode(["error" => "You are not logged in as an Admin. Current Role: " . ($_SESSION['user_role'] ?? 'None')]));
}

// 3. The Query - We use a TRY/CATCH style check
$sql = "SELECT * FROM users"; // Fetching all to be safe, then we filter
$result = $conn->query($sql);

if (!$result) {
    die(json_encode(["error" => "SQL Error: " . $conn->error . ". Check if table 'users' exists."]));
}

$users = [];
while($row = $result->fetch_assoc()) {
    // Only add non-admins to the list
    if ($row['role'] !== 'admin') {
        $users[] = [
            "id"    => $row['id'] ?? $row['user_id'] ?? 'N/A', // Handles both column names
            "name"  => $row['fullname'] ?? 'No Name',
            "email" => $row['email'] ?? 'No Email',
            "role"  => $row['role'] ?? 'user'
        ];
    }
}

echo json_encode($users);
?>