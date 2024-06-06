<?php
session_start();
 $conn = new mysqli('localhost', 'root', '', 'mydb');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $registrationId = $_POST['id'];

    // SQL to delete a record
    $sql = "DELETE FROM class_registrations WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database error: unable to prepare statement']);
        exit;
    }
    
    $stmt->bind_param('i', $registrationId);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing query']);
    }
    
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
