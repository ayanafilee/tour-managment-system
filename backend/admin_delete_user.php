<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SESSION['user_role'] !== 'admin') { exit(json_encode(["status" => "error"])); }

$data = json_decode(file_get_contents('php://input'), true);
$user_id = mysqli_real_escape_string($conn, $data['user_id']);

// Prevent admin from deleting themselves
if ($user_id == $_SESSION['user_id']) {
    echo json_encode(["status" => "error", "message" => "You cannot delete yourself!"]);
    exit;
}

$sql = "DELETE FROM users WHERE id = '$user_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "User removed from database."]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>