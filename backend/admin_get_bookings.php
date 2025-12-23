<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

// Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die(json_encode(["error" => "Unauthorized"]));
}

// Query to get all bookings with tourist name and tour title
// Using booking_date instead of created_at since bookings table doesn't have created_at
$sql = "SELECT b.booking_id, u.fullname as tourist_name, t.title as tour_title, 
               b.hotel_id_ref, b.taxi_id_ref, b.status 
        FROM bookings b
        JOIN users u ON b.tourist_id = u.id
        JOIN tours t ON b.tour_id = t.tour_id
        ORDER BY b.booking_date DESC";

$result = $conn->query($sql);
$bookings = [];

if (!$result) {
    die(json_encode(["error" => "SQL Error: " . $conn->error]));
}

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

echo json_encode($bookings);
?>