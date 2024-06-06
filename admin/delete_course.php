<?php
session_start();
require '../rec.php';



if (isset($_GET['id']) && !empty($_GET['id'])) {
    $userId = $_GET['id'];
    
   
    $deleteQuery = "DELETE FROM courses WHERE id = :id";
    $deleteStmt = $pdo->prepare($deleteQuery);
    $deleteStmt->bindValue(':id', $userId, PDO::PARAM_INT);
    
    if ($deleteStmt->execute()) {
        header('Location: department.php');
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