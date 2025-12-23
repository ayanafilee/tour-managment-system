<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

// Any user (or even guests) can usually see tours
$sql = "SELECT * FROM tours ORDER BY created_at DESC";
$result = $conn->query($sql);

$tours = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $tours]);
} else {
    echo json_encode(["status" => "success", "data" => [], "message" => "No tours found"]);
}
?>