<?php
$start = microtime(true);

$host = '127.0.0.1';
$db   = 'petshop';
$user = 'root';
$pass = '';
$port = '3307';

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Database connected successfully!<br>";
} catch(PDOException $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}

$time = microtime(true) - $start;
echo "⏱️ Time taken: " . round($time, 3) . " seconds";
?>
