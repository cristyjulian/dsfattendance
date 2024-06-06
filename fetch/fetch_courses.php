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

$sql = "SELECT id, name FROM courses";
$result = $conn->query($sql);

$courses = array();

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
} else {
    echo "0 results";
}

header('Content-Type: application/json');
echo json_encode($courses);

$conn->close();
?>
