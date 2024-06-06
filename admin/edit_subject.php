<?php
// edit_subject.php
require '../rec.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $subject_name = $_POST['subject_name'];
    $course_name = $_POST['course_name'];
    $year_level = $_POST['year_level'];

    $sql = "UPDATE subjects SET name = :subject_name, course_id = (SELECT id FROM courses WHERE name = :course_name), year_level = :year_level WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['subject_name' => $subject_name, 'course_name' => $course_name, 'year_level' => $year_level, 'id' => $id]);

    header('Location: subject_record.php');
}
?>
