<?php

// Include the database connection
include '../backend/db_connector.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set response header to JSON
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit;
}

// Decode the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Log input for debugging
error_log("Input Data: " . print_r($input, true));

// Validate the input
$user_id = $_SESSION['user_id']; // Get the logged-in user ID from session
$product_id = $input['product_id'] ?? null;
$product_name = $input['product_name'] ?? null;
$product_price = $input['product_price'] ?? null;
$quantity = $input['quantity'] ?? 1;

// Ensure all required fields are provided
if (!$product_id || !$product_name || !$product_price || $quantity <= 0) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid or missing input data'
    ]);
    exit;
}

// Insert or update the cart
$sql = "INSERT INTO cart (user_id, product_id, product_name, product_price, quantity)
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Database Error: " . $conn->error);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

// Bind parameters and execute the query
$stmt->bind_param("iisdi", $user_id, $product_id, $product_name, $product_price, $quantity);

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Product added to cart'
    ]);
} else {
    error_log("Execution Error: " . $stmt->error);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to add product to cart: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();

?>
