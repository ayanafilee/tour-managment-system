<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$guide_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tours WHERE guide_id = '$guide_id' ORDER BY created_at DESC";
$result = $conn->query($sql);

$tours = [];
while($row = $result->fetch_assoc()) {
    $tours[] = $row;
}

echo json_encode(["status" => "success", "data" => $tours]);
?>