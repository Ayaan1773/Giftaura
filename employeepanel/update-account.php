<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "giftaura";

$con = mysqli_connect($servername, $username, $password, $db_name);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field'] ?? '';
    $value = $_POST['value'] ?? '';
    $loggedInUsername = $_SESSION['username'] ?? '';

    // Allowed fields for security
    $allowedFields = ['username', 'password'];
    if (!in_array($field, $allowedFields)) {
        echo json_encode(['message' => 'Invalid field']);
        exit;
    }

    // Update the database
    $stmt = $con->prepare("UPDATE employee SET $field = ? WHERE username = ?");
    $stmt->bind_param("ss", $value, $loggedInUsername);

    if ($stmt->execute()) {
        // Update session if username changes
        if ($field === 'username') {
            $_SESSION['username'] = $value;
        }
        echo json_encode(['message' => ucfirst($field) . ' updated successfully']);
    } else {
        echo json_encode(['message' => 'Database error: ' . $stmt->error]);
    }
    $stmt->close();
    exit;
}
?>