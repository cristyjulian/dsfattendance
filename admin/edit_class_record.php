<?php
require '../rec.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $classregId = $_POST['editClassregId'];
    $course = $_POST['editCourse'];
    $yearLevel = $_POST['editYearLevel'];
    $subject = $_POST['editSubject'];

    // Prepare and execute update query
    $sql = "UPDATE class_registrations SET course_id = ?, year_level = ?, subject_id = ? WHERE classreg_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $course, $yearLevel, $subject, $classregId);
    if ($stmt->execute()) {
        // Redirect back to the page where the edit button was clicked
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit();
    } else {
        echo "Error updating class record: " . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Form not submitted.";
}
?>
