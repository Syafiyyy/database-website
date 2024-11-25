<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database Configuration
$servername = "localhost";
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "php_market_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
