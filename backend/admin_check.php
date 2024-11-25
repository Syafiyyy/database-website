<?php
// admin_check.php
include '../backend/db_connector.php';

// Check if the user is logged in and has an admin role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Redirect to login page if not logged in or not an admin
    header('Location: ../templates/login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to ensure the user is an admin
$sql = "SELECT role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If the user is not an admin, redirect them to a different page
if ($user['role'] !== 'admin') {
    header('Location: ../templates/index.html');
    exit();
}

$stmt->close();
$conn->close();
?>
