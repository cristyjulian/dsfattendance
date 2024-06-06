<?php
// Connect to the database
require 'connection.php';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate form inputs
    $studentId = $_POST['student_id'];
    $courseId = $_POST['course_id'];
    $yearLevel = $_POST['year_level'];
    $subjectId = $_POST['subject_id'];

    // Prepare and execute SQL insert query
    $query = "INSERT INTO students_course (student_id, course_id, year_level, subject_id) VALUES (:studentId, :courseId, :yearLevel, :subjectId)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
    $stmt->bindParam(':courseId', $courseId, PDO::PARAM_INT);
    $stmt->bindParam(':yearLevel', $yearLevel, PDO::PARAM_INT);
    $stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
    $stmt->execute();
}

// Fetch information from the students_course table
$query = "SELECT * FROM students_course";
$stmt = $conn->prepare($query);
$stmt->execute();
$studentsCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students Course Information</title>
</head>
<body>
    <h2>Add Student Course Information</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" required><br><br>
        
        <label for="course_id">Course ID:</label>
        <input type="text" id="course_id" name="course_id" required><br><br>

        <label for="year_level">Year Level:</label>
        <input type="text" id="year_level" name="year_level" required><br><br>

        <label for="subject_id">Subject ID:</label>
        <input type="text" id="subject_id" name="subject_id" required><br><br>

        <input type="submit" value="Submit">
    </form>

    <h2>Student Course Information</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Student ID</th>
            <th>Course ID</th>
            <th>Year Level</th>
            <th>Subject ID</th>
        </tr>
        <?php foreach ($studentsCourses as $row) : ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['course_id']; ?></td>
                <td><?php echo $row['year_level']; ?></td>
                <td><?php echo $row['subject_id']; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>

<?php
// Close the connection
$conn = null;
?>
