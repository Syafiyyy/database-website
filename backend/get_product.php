<?php

// Include the database connection
include '../backend/db_connector.php';


// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the response header to JSON
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit;
}

// SQL query to fetch product details
$sql = "SELECT 
            product_id AS id, 
            product_name AS name, 
            product_price AS price, 
            category, 
            url, 
            image, 
            rating, 
            release_date 
        FROM products";

$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error: ' . $conn->error
    ]);
    exit;
}

// Check if products exist
if ($result->num_rows > 0) {
    $products = [];

    // Fetch each product
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => number_format((float)$row['price'], 2, '.', ''), // Ensure price is in proper format
            'category' => $row['category'],
            'url' => $row['url'],
            'image' => base64_encode($row['image']), // Convert BLOB to base64 for frontend compatibility
            'rating' => $row['rating'],
            'release_date' => $row['release_date']
        ];
    }

    // Send the JSON response
    echo json_encode([
        'status' => 'success',
        'data' => $products
    ]);
} else {
    // No products found
    echo json_encode([
        'status' => 'error',
        'message' => 'No products found'
    ]);
}

// Close the database connection
$conn->close();
?>
