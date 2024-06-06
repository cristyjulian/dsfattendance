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
if (isset($_POST['classId'], $_POST['course_id'], $_POST['year_level'], $_POST['subject_id'])) {
    $classId = $_POST['classId'];
    $course_id = $_POST['course_id'];
    $year_level = $_POST['year_level'];
    $subject_id = $_POST['subject_id'];

    // Prepare and execute the query to update class information
    $stmt = $conn->prepare("UPDATE class_registrations SET course_id = ?, year_level = ?, subject_id = ? WHERE id = ?");
    $stmt->bind_param("iiii", $course_id, $year_level, $subject_id, $classId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to update class information']);
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo json_encode(['error' => 'Invalid input']);
}

$conn->close();
?>
