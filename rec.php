<?php
$host = 'localhost'; // This should be 'localhost' if your database server is on the same machine
$db   = 'mydb';      // Ensure that 'mydb' is the correct database name
$user = 'root';      // Default username for MySQL
$pass = '';          // Default is no password for MySQL under root, unless you've set one
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>