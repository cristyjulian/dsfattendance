<?php
session_start();
require '../rec.php';

if (!isset($_SESSION['username'])) {
    header('Location: ../login.php');
    exit();
}

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = $_GET['id'];
    
    // Delete the user record from the database based on the user ID
    $deleteQuery = "DELETE FROM users WHERE users_id = :id";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindValue(':id', $userId, PDO::PARAM_INT);
    
    if ($deleteStmt->execute()) {
        header('Location: users_record.php');
        exit();
    } else {
        echo "Failed to delete user.";
        exit();
    }
} else {
    echo "User ID not provided.";
    exit();
}
?>
