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

if (isset($_GET['classId'])) {
    $classId = $_GET['classId'];

    // Prepare and execute the query to fetch class information
    $stmt = $conn->prepare("SELECT course_id, year_level, subject_id FROM class_registrations WHERE id = ?");
    $stmt->bind_param("i", $classId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $classInfo = $result->fetch_assoc();
        // Return the class information as JSON
        echo json_encode($classInfo);
    } else {
        // If no class is found, return an error message
        echo json_encode(['error' => 'Class not found']);
    }

    // Close the database connection
    $stmt->close();
} else {
    // If no classId is provided, return an error message
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
