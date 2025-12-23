<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

// Check if user is logged in and is a guide
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'guide') {
    echo json_encode(["status" => "error", "message" => "Unauthorized access"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $guide_id    = $_SESSION['user_id']; // From Session
    $title       = mysqli_real_escape_string($conn, $data['title']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $destination = mysqli_real_escape_string($conn, $data['destination']);
    $price       = mysqli_real_escape_string($conn, $data['price']);

    $sql = "INSERT INTO tours (guide_id, title, description, destination, price) 
            VALUES ('$guide_id', '$title', '$description', '$destination', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Tour package created!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
}
?>