<?php

include '../backend/db_connector.php';

$product_id = $_GET['id']; // Get product ID from URL

// Delete product from the database
$sql = "DELETE FROM products WHERE id = $product_id";
if ($conn->query($sql) === TRUE) {
    echo "Product deleted successfully!";
    header('Location: admin_dashboard.php'); // Redirect to admin dashboard after delete
} else {
    echo "Error deleting product: " . $conn->error;
}

$conn->close();
?>
