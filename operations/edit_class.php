<?php
session_start();
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $_POST['course'];
    $year = $_POST['enroll_year'];
    $subject = $_POST['subject'];

    $sql = "UPDATE class_registrations SET course_id = ?, year_level = ? WHERE subject_id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt->execute([$course, $year, $subject])) {
        echo "Class updated successfully.";
    } else {
        echo "Error updating record.";
    }
}
?>
