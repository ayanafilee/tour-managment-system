<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'guide') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $tour_id     = mysqli_real_escape_string($conn, $data['tour_id']);
    $guide_id    = $_SESSION['user_id']; // For safety, ensure this guide owns the tour
    $title       = mysqli_real_escape_string($conn, $data['title']);
    $description = mysqli_real_escape_string($conn, $data['description']);
    $destination = mysqli_real_escape_string($conn, $data['destination']);
    $price       = mysqli_real_escape_string($conn, $data['price']);

    $sql = "UPDATE tours SET 
            title='$title', 
            description='$description', 
            destination='$destination', 
            price='$price' 
            WHERE tour_id='$tour_id' AND guide_id='$guide_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Tour updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>