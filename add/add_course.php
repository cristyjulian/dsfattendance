<?php
$servername = "localhost";
$username = "root";
$password = ""; // Assuming no password for local development
$dbname = "mydb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course name from form
$courseName = $_POST['course_name'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO courses (name) VALUES (?)");
$stmt->bind_param("s", $courseName);

// Execute and check
if ($stmt->execute()) {
    echo "<script>alert('Add Course Successfully!')</script>";
    echo "<script>window.location='../admin/admin.php'</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
