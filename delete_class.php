<?php
session_start(); // Ensure session is started

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Check if required POST data is set
if (isset($_POST['classId'])) {
    $classId = $_POST['classId'];

    // Prepare and execute the query to delete the class
    $stmt = $conn->prepare("DELETE FROM class_registrations WHERE id = ?");
    $stmt->bind_param("i", $classId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to delete class']);
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}

$conn->close();
?>
