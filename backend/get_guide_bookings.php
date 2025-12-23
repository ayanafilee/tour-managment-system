<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    exit(json_encode(["status" => "error", "message" => "Unauthorized"]));
}

$guide_id = $_SESSION['user_id'];

// Join bookings, tours, and users (to get the tourist's name)
$sql = "SELECT b.booking_id, t.title, u.fullname as tourist_name, b.booking_date, b.status 
        FROM bookings b
        JOIN tours t ON b.tour_id = t.tour_id
        JOIN users u ON b.tourist_id = u.id
        WHERE t.guide_id = '$guide_id'
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);
$bookings = [];

while($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode(["status" => "success", "data" => $bookings]);
?>