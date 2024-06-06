<?php
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password, for local development.
$dbname = "mydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$courseId = $_POST['course_id'];
$subjectName = $_POST['subject_name'];
$yearLevel = $_POST['year_level'];

// Sanitize input
$courseId = $conn->real_escape_string($courseId);
$subjectName = $conn->real_escape_string($subjectName);
$yearLevel = $conn->real_escape_string($yearLevel);

// Prepare statement
$stmt = $conn->prepare("INSERT INTO subjects (course_id, name, year_level) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $courseId, $subjectName, $yearLevel);

// Execute and check
if ($stmt->execute()) {
    echo "<script>alert('Add Subject Successfully!')</script>";
    echo "<script>window.location='../admin.php'</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
