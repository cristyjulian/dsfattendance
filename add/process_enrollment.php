<?php
session_start();
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? null;
    $birthday = $_POST['birthday'] ?? null;
    $email = $_POST['email'] ?? null;
    $parent_contact = $_POST['parent_contact'] ?? null;
    $subject_id = $_POST['subject'] ?? null; // Ensure this matches your form field names.
    $enroll_year = $_POST['enroll_year'] ?? null;

    if ($full_name && $birthday && $email && $parent_contact && $subject_id && $enroll_year) {
        $query = "INSERT INTO students (full_name, birthday, email, parent_contact, subject_id, enroll_year) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("ssssii", $full_name, $birthday, $email, $parent_contact, $subject_id, $enroll_year);
            if ($stmt->execute()) {
                echo "<script>alert('Endroll Student Successfull!')</script>";
                echo "<script>window.location='../admin/admin.php'</script>";
            } else {
                echo "Error executing query: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    } else {
        echo "All fields are required.";
    }
    $conn->close();
}
?>
