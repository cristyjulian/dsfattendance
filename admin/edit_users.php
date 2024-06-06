<?php
session_start();
require '../rec.php'; // This includes your PDO connection setup

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the user data from the form submission
    $userId = $_POST['users_id'];
    $name = $_POST['name'];
    $username = $_POST['username'];
    $userEmail = $_POST['user_email'];

    // Prepare the SQL query to update the user data
    $query = "UPDATE users SET name = :name, username = :username, user_email = :user_email WHERE users_id = :users_id";
    $stmt = $pdo->prepare($query);

    // Bind the parameters
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    $stmt->bindValue(':user_email', $userEmail, PDO::PARAM_STR);
    $stmt->bindValue(':users_id', $userId, PDO::PARAM_INT);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect back to the user records page with a success message
        $_SESSION['success_message'] = 'User updated successfully!';
        header('Location: users_record.php');
        exit();
    } else {
        // Redirect back with an error message
        $_SESSION['error_message'] = 'Failed to update user.';
        header('Location: users_record.php');
        exit();
    }
} else {
    // Redirect back to the user records page if accessed directly
    header('Location: tables.php');
    exit();
}
?>
