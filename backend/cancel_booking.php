<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    
    $booking_id = mysqli_real_escape_string($conn, $data['booking_id']);
    $tourist_id = $_SESSION['user_id'];

    // Ensure the booking belongs to the person trying to cancel it
    $sql = "UPDATE bookings SET status = 'cancelled' WHERE booking_id = '$booking_id' AND tourist_id = '$tourist_id'";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Booking cancelled."]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>