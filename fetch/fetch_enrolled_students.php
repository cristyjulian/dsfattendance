<?php
// Database configuration
$host = 'localhost';
$dbname = 'mydb';
$username = 'root';
$password = '';

$subjectId = $_GET['subjectId'] ?? 0;

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT full_name, email, birthday, parent_contact FROM students WHERE subject_id = :subjectId");
    $stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($students);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
