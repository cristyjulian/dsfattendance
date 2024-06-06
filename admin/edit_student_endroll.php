<?php
session_start();
require '../rec.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['id'];
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $parentContact = $_POST['parent_contact'];
    $birthday = $_POST['birthday'];

    try {
        $updateQuery = "UPDATE students SET full_name = :full_name, email = :email, parent_contact = :parent_contact, birthday = :birthday WHERE id = :id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindValue(':full_name', $fullName);
        $updateStmt->bindValue(':email', $email);
        $updateStmt->bindValue(':parent_contact', $parentContact);
        $updateStmt->bindValue(':birthday', $birthday);
        $updateStmt->bindValue(':id', $userId, PDO::PARAM_INT);

        if ($updateStmt->execute()) {
            header('Location: student_endroll_record.php?message=Student updated successfully');
            exit();
        } else {
            echo "Failed to update student.";
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    echo "Invalid request method.";
    exit();
}
?>
