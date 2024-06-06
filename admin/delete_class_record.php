<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydb";

// Include database connection file
require '../rec.php';

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $classregId = $_GET['id'];

    $sql = "DELETE FROM class_registrations WHERE classreg_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $classregId);
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo "Error deleting class record: " . $conn->error;
    }
} else {
    echo "Class ID not provided.";
}
?>
