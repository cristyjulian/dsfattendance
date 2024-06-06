<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
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
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id'];

// Prepare a query to fetch all registrations for the logged-in user
$query = "SELECT cr.id, c.name AS course_name, s.name AS subject_name, cr.year_level, cr.subject_id
          FROM class_registrations cr
          JOIN courses c ON cr.course_id = c.id
          JOIN subjects s ON cr.subject_id = s.id
          WHERE cr.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

$registrations = [];

while ($row = $result->fetch_assoc()) {
    $registrations[] = $row;
}

$stmt->close();
$conn->close();

// Output the registrations in JSON format
echo json_encode($registrations);
?>
