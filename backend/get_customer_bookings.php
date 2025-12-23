<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$tourist_id = $_SESSION['user_id'];

// Join bookings with tours to get the Title and Destination
$sql = "SELECT b.booking_id, t.title, t.destination, b.booking_date, b.total_price, b.status, b.hotel_id_ref, b.taxi_id_ref 
        FROM bookings b
        JOIN tours t ON b.tour_id = t.tour_id
        WHERE b.tourist_id = '$tourist_id'
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);
$bookings = [];

while($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode(["status" => "success", "data" => $bookings]);
?>