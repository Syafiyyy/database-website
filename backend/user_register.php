<?php
require_once '../backend/db_connector.php';

// Get raw POST data (JSON)
$data = json_decode(file_get_contents("php://input"), true);  // Added 'true' to decode as associative array

// Debugging - Output the raw input data to check if it's being parsed correctly
error_log(print_r($data, true)); // This will write to the PHP error log

// Extract the fields
$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

// Validate the input
if (!isset($data['username'], $data['email'], $data['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all fields']);
    exit();
}

$username = $data['username'];
$email = $data['email'];
$password = $data['password'];

// Check if the username or email already exists
$query = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
    exit();
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert the user into the database
$query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $username, $email, $hashed_password);
$result = $stmt->execute();

if ($result) {
    echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
}

$stmt->close();
$conn->close();
?>
