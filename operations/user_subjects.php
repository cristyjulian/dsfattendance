<?php
require 'cn.php'; // Assuming 'cn.php' contains the PDO connection setup

// Function to add a subject to a user
function addSubjectToUser($userId, $subjectId) {
    global $conn; // Use the global connection object
    try {
        $query = "INSERT INTO user_subjects (user_id, subject_id) VALUES (:userId, :subjectId)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
        $stmt->execute();
        return true;
    } catch (PDOException $e) {
        error_log("Error adding subject to user: " . $e->getMessage());
        return false;
    }
}

// Function to get subjects by user
function getSubjectsByUser($userId) {
    global $conn;
    try {
        $query = "SELECT s.id, s.name FROM subjects s
                  JOIN user_subjects us ON s.id = us.subject_id
                  WHERE us.user_id = :userId";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error retrieving user subjects: " . $e->getMessage());
        return [];
    }
}

// Example usage (comment out or modify according to your testing setup)
/*
if (addSubjectToUser(1, 2)) {
    echo "Subject added successfully!";
} else {
    echo "Error adding subject.";
}

$subjects = getSubjectsByUser(1);
foreach ($subjects as $subject) {
    echo $subject['name'] . "<br>";
}
*/
?>
