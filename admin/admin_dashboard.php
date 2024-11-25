<?php
// Include admin check and database connection
include '../backend/admin_check.php'; // Ensure the user is an admin
include '../backend/db_connector.php'; // Database connection

// Fetch users from the database
$sql_users = "SELECT id, username, email, role FROM users";
$result_users = $conn->query($sql_users);

$users = [];
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fetch products from the database
$sql_products = "SELECT product_id, product_name, product_price, category, image, rating, release_date FROM products";
$result_products = $conn->query($sql_products);

$products = [];
if ($result_products->num_rows > 0) {
    while ($row = $result_products->fetch_assoc()) {
        $products[] = $row;
    }
}

// Fetch cart data
$sql_cart = "SELECT c.id, u.username, p.product_name, c.quantity 
             FROM cart c
             INNER JOIN users u ON c.user_id = u.id
             INNER JOIN products p ON c.product_id = p.product_id";
$result_cart = $conn->query($sql_cart);

$cart = [];
if ($result_cart->num_rows > 0) {
    while ($row = $result_cart->fetch_assoc()) {
        $cart[] = $row;
    }
}

// Fetch checkout data
$sql_checkout = "SELECT co.id, u.username, co.total_price, co.checkout_date 
                 FROM checkout co
                 INNER JOIN users u ON co.user_id = u.id";
$result_checkout = $conn->query($sql_checkout);

$checkout = [];
if ($result_checkout->num_rows > 0) {
    while ($row = $result_checkout->fetch_assoc()) {
        $checkout[] = $row;
    }
}

// Fetch library data
$sql_library = "SELECT l.id, u.username, p.product_name, l.purchase_date 
                FROM library l
                INNER JOIN users u ON l.user_id = u.id
                INNER JOIN products p ON l.product_id = p.product_id";
$result_library = $conn->query($sql_library);

$library = [];
if ($result_library->num_rows > 0) {
    while ($row = $result_library->fetch_assoc()) {
        $library[] = $row;
    }
}

$conn->close(); // Close the database connection
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="/static/css/admin.css">
</head>

<body>
    <div class="dashboard">
        <h1>Welcome to the Admin Dashboard</h1>

        <!-- User Management Section -->
        <section class="user-management">
            <h2>Manage Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Product Management Section -->
        <section class="product-management">
            <h2>Manage Products</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Category</th>
                        <th>Rating</th>
                        <th>Release Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['product_id']); ?></td>
                            <td>
                                <img src="data:image/jpeg;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 100px; height: auto;">
                            </td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td>RM <?php echo number_format($product['product_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo htmlspecialchars($product['rating']); ?></td>
                            <td><?php echo htmlspecialchars($product['release_date']); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Edit</a> |
                                <a href="delete_product.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Cart Management Section -->
        <section class="cart-management">
            <h2>Manage Carts</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Product</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['username']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                            
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Checkout Management Section -->
        <section class="checkout-management">
            <h2>Manage Checkouts</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Total Price</th>
                        <th>Checkout Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($checkout as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['username']); ?></td>
                            <td>RM <?php echo number_format($item['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['checkout_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- Library Management Section -->
        <section class="library-management">
            <h2>Manage Libraries</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Product</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($library as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['id']); ?></td>
                            <td><?php echo htmlspecialchars($item['username']); ?></td>
                            <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($item['purchase_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <a href="/admin/logout.php">Logout</a>
    </div>
</body>

</html>
