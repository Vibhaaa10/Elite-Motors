<?php
$host = 'localhost';
$dbname = 'database'; // Matches your current database name
$username = 'root'; 
$password = ''; 
$charset = 'utf8mb4'; // Added for better security and character support

// Data Source Name
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Establishing the connection
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // In a production environment, you should log this error instead of showing it
    die("Database Connection Failed: " . $e->getMessage());
}
?>