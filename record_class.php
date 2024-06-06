<?php
session_start(); 
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in";
    exit;
}
$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "mydb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['user_id']; 
$courseId = $_POST['course'];
$subjectId = $_POST['subject'];
$yearLevel = $_POST['enroll_year'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO class_registrations(user_id, course_id, subject_id, year_level) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiii", $userId, $courseId, $subjectId, $yearLevel);

// Execute and check
if ($stmt->execute()) {
    echo "<script>alert('Record Class Add Successfully!')</script>";
    echo "<script>window.location='add.php'</script>";
    
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
