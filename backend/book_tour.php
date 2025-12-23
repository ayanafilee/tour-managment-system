<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

// Check if user is logged in and is a tourist
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Please login first"]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    // Get IDs from session and JSON input
    $tourist_id   = $_SESSION['user_id']; 
    $tour_id      = mysqli_real_escape_string($conn, $data['tour_id']);
    $hotel_ref    = isset($data['hotel_id_ref']) ? mysqli_real_escape_string($conn, $data['hotel_id_ref']) : null;
    $taxi_ref     = isset($data['taxi_id_ref']) ? mysqli_real_escape_string($conn, $data['taxi_id_ref']) : null;
    $total_price  = mysqli_real_escape_string($conn, $data['total_price']);
    $booking_date = date('Y-m-d'); 

    // SQL match your specific table columns
    $sql = "INSERT INTO bookings (tour_id, tourist_id, booking_date, hotel_id_ref, taxi_id_ref, status, total_price) 
            VALUES ('$tour_id', '$tourist_id', '$booking_date', '$hotel_ref', '$taxi_ref', 'confirmed', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode([
            "status" => "success", 
            "message" => "Booking confirmed!",
            "booking_id" => $conn->insert_id
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
    }
}
?>