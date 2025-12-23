<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    $tour_id = mysqli_real_escape_string($conn, $data['tour_id']);
    $guide_id = $_SESSION['user_id'];

    // Verify ownership before deleting
    $sql = "DELETE FROM tours WHERE tour_id = '$tour_id' AND guide_id = '$guide_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Tour deleted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>