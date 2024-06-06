<?php
session_start();
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submittedBy = $_POST['submitted_by'];  // Get the ID of the user who submitted the form
    $attendances = $_POST['attendance'];  // Array of student IDs and their attendance status

    // Check if subject_id is set and is not empty
    $subjectId = isset($_POST['subject_id']) ? $_POST['subject_id'] : null;
    if ($subjectId === null) {
        die("Subject ID is required.");
    }

    try {
        $conn->beginTransaction();

        // Prepare the SQL query with placeholders for student ID, status, submitted_by, subject_id, and date
        $query = "INSERT INTO attendance (student_id, status, submitted_by, subject_id, date) VALUES (:studentId, :status, :submittedBy, :subjectId, NOW())";
        $stmt = $conn->prepare($query);

        // Loop through the attendance array and execute the prepared statement for each student
        foreach ($attendances as $studentId => $status) {
            // Bind parameters for each student
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':submittedBy', $submittedBy, PDO::PARAM_INT);
            $stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);  // Bind the subject ID
            // Execute the statement
            $stmt->execute();
        }

        $conn->commit();
        echo "<script>alert('Attendance submitted!')</script>";
        echo "<script>window.location='session.php'</script>";
    } catch (PDOException $e) {
        $conn->rollBack();
        die("Error recording attendance: " . $e->getMessage());
    }
}
?>
