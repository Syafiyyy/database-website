<?php
include '../backend/admin_check.php';
include '../backend/db_connector.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        die("Invalid library entry ID");
    }

    $sql = "SELECT * FROM library WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $libraryItem = $result->fetch_assoc();
    if (!$libraryItem) {
        die("Library entry not found");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $purchase_date = $_POST['purchase_date'];

    $sql = "UPDATE library SET purchase_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $purchase_date, $id);
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error updating library entry: " . $conn->error;
    }
}
?>
<form method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($libraryItem['id']); ?>">
    <label for="purchase_date">Purchase Date:</label>
    <input type="date" name="purchase_date" value="<?php echo htmlspecialchars($libraryItem['purchase_date']); ?>">
    <button type="submit">Update</button>
</form>
