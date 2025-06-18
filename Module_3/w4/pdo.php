<?php
$hostName = "localhost";
$dbName = "misc";
$userName = "fred";   
$password = "zap";

try {
    $pdo = new PDO("mysql:host=$hostName;dbname=$dbName", $userName, $password);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    die('Database connection error. Please try again later.');
}
?>