<?php
session_start();
include 'db_config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $email    = isset($data['email'])    ? mysqli_real_escape_string($conn, $data['email'])    : null;
    $password = isset($data['password']) ? mysqli_real_escape_string($conn, $data['password']) : null;

    if (!$email || !$password) {
        echo json_encode(["status" => "error", "message" => "Email and Password are required"]);
        exit;
    }

    $sql = "SELECT id, fullname, email, role FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // --- CRITICAL FIX: STORE DATA IN SESSION ---
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['fullname'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];
        // -------------------------------------------

        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user" => [
                "id" => $user['id'],
                "fullname" => $user['fullname'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid email or password"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Please use POST method"]);
}

$conn->close();
?>