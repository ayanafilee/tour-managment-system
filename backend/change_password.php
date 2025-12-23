<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$password = $data['password'];
$user_id = $_SESSION['user_id'];

if (strlen($password) < 6) {
    echo json_encode(["status" => "error", "message" => "Password too short"]);
    exit;
}

// Securely hash the new password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$sql = "UPDATE users SET password = '$hashed_password' WHERE id = '$user_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Password updated"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error"]);
}
?>