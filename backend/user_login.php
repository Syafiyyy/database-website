<?php
include '../backend/db_connector.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Email and password are required"]);
        exit;
    }

    try {
        // Query to fetch user details
        $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] === 'admin') {
                    echo json_encode(["status" => "success", "message" => "Welcome, Admin!", "redirect" => "/admin/admin_dashboard.php"]);
                } else {
                    echo json_encode(["status" => "success", "message" => "Login successful", "redirect" => "/templates/index.html"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid password"]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "User not found"]);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    } finally {
        $conn->close();
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
