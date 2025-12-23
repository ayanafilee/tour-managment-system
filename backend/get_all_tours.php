<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

// Get the current user's ID from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

/**
 * SQL EXPLANATION:
 * We select all tours, but we exclude (NOT IN) any tour_id 
 * that already exists in the bookings table for this specific user,
 * as long as that booking isn't 'cancelled'.
 */
$sql = "SELECT tour_id, title, description, destination, price 
        FROM tours 
        WHERE tour_id NOT IN (
            SELECT tour_id 
            FROM bookings 
            WHERE tourist_id = '$user_id' AND status != 'cancelled'
        ) 
        ORDER BY created_at DESC";

$result = $conn->query($sql);
$tours = [];

if ($result) {
    while($row = $result->fetch_assoc()) {
        $tours[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $tours]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>