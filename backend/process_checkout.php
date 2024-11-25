<?php
// Include database connection
include '../backend/db_connector.php';
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Decode the JSON input
$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON input']);
    exit;
}

$total_price = $input['total_price'];
$cart_items = $input['cart_items'];
$payment_method = $input['payment_method'];
$discount = $input['discount'] ?? 0;

try {
    // Begin transaction
    $conn->begin_transaction();

    // Insert into the `checkout` table
    $stmt = $conn->prepare("INSERT INTO checkout (user_id, total_price, discount, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idds", $user_id, $total_price, $discount, $payment_method);
    $stmt->execute();
    $checkout_id = $stmt->insert_id;

    // Insert purchased items into the `library` table
    $stmt = $conn->prepare("INSERT INTO library (user_id, product_id) VALUES (?, ?)");
    foreach ($cart_items as $item) {
        $stmt->bind_param("ii", $user_id, $item['product_id']);
        $stmt->execute();
    }

    // Clear the cart
    $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // Commit the transaction
    $conn->commit();

    echo json_encode(["status" => "success", "message" => "Checkout completed successfully"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
