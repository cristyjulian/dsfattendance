<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are filled
    if (!empty($_POST['name'])) {
        // Include your database connection
        require_once '../rec.php';

        // Prepare and execute the update query
        $stmt = $pdo->prepare("UPDATE courses SET name = :name WHERE id = :id");
        $stmt->execute([
            ':name' => $_POST['name'],
            ':id' => $_POST['id']
        ]);

        // Redirect back to the previous page after updating
        header("Location: department.php");
        exit();
    } else {
        // Handle errors if required fields are empty
        echo "Name field is required.";
    }
}
?>
