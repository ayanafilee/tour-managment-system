<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

// Security Check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    die(json_encode(["error" => "Unauthorized"]));
}

// 1. Get User Counts
$tourists_result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'tourist'");
$tourists = $tourists_result ? $tourists_result->fetch_assoc()['total'] : 0;

$guides_result = $conn->query("SELECT COUNT(*) as total FROM users WHERE role = 'guide'");
$guides = $guides_result ? $guides_result->fetch_assoc()['total'] : 0;

// 2. Get Booking Stats
$bookings_q = $conn->query("SELECT COUNT(*) as total, SUM(total_price) as revenue FROM bookings WHERE status != 'cancelled'");
$b_data = $bookings_q ? $bookings_q->fetch_assoc() : ['total' => 0, 'revenue' => 0];
$bookings_count = $b_data['total'] ?? 0;
$revenue = $b_data['revenue'] ?? 0;

// 3. Recent Bookings with Service Links (Hotel/Taxi)
// Note: Using booking_date instead of created_at since bookings table doesn't have created_at column
$recent_q = $conn->query("SELECT b.booking_id as id, u.fullname as tourist_name, b.total_price as price, 
                          b.status, b.hotel_id_ref as hotel_id, b.taxi_id_ref as taxi_id 
                          FROM bookings b 
                          JOIN users u ON b.tourist_id = u.id 
                          ORDER BY b.booking_date DESC LIMIT 5");

$recent = [];
if ($recent_q) {
    while($row = $recent_q->fetch_assoc()) {
        $recent[] = $row;
    }
}

echo json_encode([
    "tourists" => $tourists,
    "guides" => $guides,
    "bookings" => $bookings_count,
    "revenue" => $revenue,
    "recent" => $recent
]);
?>