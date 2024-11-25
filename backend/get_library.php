<?php
// Include the database connector
include '../backend/db_connector.php';


header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

try {
    // SQL query to fetch purchased items from the library
    $sql = "
        SELECT 
            l.product_id, 
            p.product_name AS name, 
            p.product_price AS price, 
            p.image, 
            p.url 
        FROM 
            library l
        INNER JOIN 
            products p 
        ON 
            l.product_id = p.product_id 
        WHERE 
            l.user_id = ?
    ";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Failed to prepare SQL statement: " . $conn->error);
    }

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $library_items = [];
    while ($row = $result->fetch_assoc()) {
        $library_items[] = $row;
    }

    if (empty($library_items)) {
        echo json_encode([
            "status" => "success",
            "message" => "No items found in your library",
            "data" => []
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "data" => $library_items
        ]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
