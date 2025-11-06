<?php
// Database connection for XAMPP (MySQL) to the 'petshop' database

if (!isset($pdo)) {
    $dbHost = '127.0.0.1';
    $dbPort = '3306';
    $dbName = 'petshop';
    $dbUser = 'root';
    $dbPass = '';

    // Allow overrides via environment variables if set
    $dbHost = getenv('127.0.0.1') ?: $dbHost;
    $dbPort = getenv('3306') ?: $dbPort;
    $dbName = getenv('petshop') ?: $dbName;
    $dbUser = getenv('root') ?: $dbUser;
    $dbPass = getenv('123456789') ?: $dbPass;

    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    } catch (PDOException $e) {
        error_log('Database Connection Error: ' . $e->getMessage());
        http_response_code(500);
        die('Database connection failed. Check that MySQL is running, the database exists, and credentials/port are correct.');
    }
    try {
        $pdo = new PDO($dsn, $dbUser, $dbPass, $options);
        echo "Database connected successfully!";
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
    
}
?>

