
<?php
// Assuming you're using PDO for database connection
$host = 'localhost';
$dbname = 'mydb';
$username = 'root';
$password = ''; // Empty password as specified

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json'); // Set the content type of the response

// Getting courseId from the GET request
$courseId = isset($_GET['courseId']) ? $_GET['courseId'] : 0;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, name, year_level FROM subjects WHERE course_id = :courseId"; // Ensure you have the year_level column
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->execute();

    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Instead of echoing each option, echo the JSON encoded version of $subjects
    echo json_encode($subjects);
} catch(PDOException $e) {
    // It's a good practice to not show detailed errors in production
    // echo "Connection failed: " . $e->getMessage();
    echo json_encode(['error' => 'Database connection failed']); // Example error handling
}
?>
