<?php
// Include database connection
include '../backend/db_connector.php';

// Set response header to JSON
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

try {
    // Fetch cart items joined with product details
    $sql = "
        SELECT 
            c.product_id, 
            p.product_name, 
            p.product_price, 
            c.quantity 
        FROM 
            cart c 
        INNER JOIN 
            products p 
        ON 
            c.product_id = p.product_id 
        WHERE 
            c.user_id = ?
    ";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }

    // Calculate total price
    $totalPrice = array_reduce($cartItems, function ($sum, $item) {
        return $sum + ($item['product_price'] * $item['quantity']);
    }, 0);

    // Log cart data for debugging
    error_log("Cart data: " . json_encode($cartItems));

    // Return response
    echo json_encode([
        "status" => "success",
        "data" => $cartItems,
        "total_price" => $totalPrice
    ]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
