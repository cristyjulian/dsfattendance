<?php
session_start();
require 'connection.php';

// Assuming you have the user ID stored in session upon login
$userId = $_SESSION['user_id'] ?? null;

if (!$userId) {
    echo "User is not logged in!";
    exit;
}

try {
    $query = "SELECT 
                cr.user_id,
                c.name AS course_name,
                s.name AS subject_name,
                cr.year_level
              FROM 
                class_registrations cr
              JOIN courses c ON cr.course_id = c.id
              JOIN subjects s ON cr.subject_id = s.id
              WHERE 
                cr.user_id = :userId";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $registrations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Classes</title>
    <link rel="stylesheet" href="path_to_your_css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>My Classes</h2>
        <?php if (count($registrations) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Course Name</th>
                        <th>Subject Name</th>
                        <th>Year Level</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrations as $registration): ?>
                        <tr>
                            <td><?= htmlspecialchars($registration['user_id']) ?></td>
                            <td><?= htmlspecialchars($registration['course_name']) ?></td>
                            <td><?= htmlspecialchars($registration['subject_name']) ?></td>
                            <td><?= htmlspecialchars($registration['year_level']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No classes found for user.</p>
        <?php endif; ?>
    </div>

    <script src="path_to_your_js/bootstrap.bundle.min.js"></script>
</body>
</html>
