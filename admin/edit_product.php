<?php
// Fetch product data for editing
include '../backend/db_connector.php';

$product_id = $_GET['id'] ?? null; // Get product ID from URL

if (!$product_id) {
    die("Product ID is required");
}

// Fetch the product
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Failed to prepare statement: " . $conn->error);
}

$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found");
}

$product = $result->fetch_assoc();

// Handle the form submission to update product data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $category = $_POST['category'] ?? '';
    $rating = $_POST['rating'] ?? 0;
    $release_date = $_POST['release_date'] ?? '';

    // Update the product in the database
    $update_sql = "UPDATE products SET product_name = ?, product_price = ?, category = ?, rating = ?, release_date = ? WHERE product_id = ?";
    $update_stmt = $conn->prepare($update_sql);

    if (!$update_stmt) {
        die("Failed to prepare update statement: " . $conn->error);
    }

    $update_stmt->bind_param("sdsssi", $name, $price, $category, $rating, $release_date, $product_id);

    if ($update_stmt->execute()) {
        echo "Product updated successfully!";
        header("Location: admin_dashboard.php"); // Redirect after update
        exit;
    } else {
        echo "Error updating product: " . $conn->error;
    }
}

$conn->close();
?>

<!-- HTML form for editing the product -->
<form method="POST">
    <label for="name">Product Name</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

    <label for="price">Price</label>
    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['product_price']); ?>" required>

    <label for="category">Category</label>
    <input type="text" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>

    <label for="rating">Rating</label>
    <input type="number" step="0.1" name="rating" value="<?php echo htmlspecialchars($product['rating']); ?>" required>

    <label for="release_date">Release Date</label>
    <input type="date" name="release_date" value="<?php echo htmlspecialchars($product['release_date']); ?>" required>

    <button type="submit">Update Product</button>
</form>
