<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Get the raw JSON string from the request body
    $json_data = file_get_contents('php://input');
    
    // 2. Decode the JSON string into a PHP associative array
    $data = json_decode($json_data, true);

    // 3. Extract data from the array (instead of $_POST)
    $fullname = isset($data['fullname']) ? mysqli_real_escape_string($conn, $data['fullname']) : null;
    $email    = isset($data['email'])    ? mysqli_real_escape_string($conn, $data['email'])    : null;
    $password = isset($data['password']) ? mysqli_real_escape_string($conn, $data['password']) : null;
    $role     = isset($data['role'])     ? mysqli_real_escape_string($conn, $data['role'])     : null;
    $bio      = isset($data['bio'])      ? mysqli_real_escape_string($conn, $data['bio'])      : "";

    // Validation
    if (!$fullname || !$email || !$password || !$role) {
        echo json_encode(["status" => "error", "message" => "Required fields missing from JSON"]);
        exit;
    }

    // Insert Query
    $sql = "INSERT INTO users (fullname, email, password, role, bio) 
            VALUES ('$fullname', '$email', '$password', '$role', '$bio')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "User registered via JSON!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "DB Error: " . $conn->error]);
    }
}
?>