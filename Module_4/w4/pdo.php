<?php
// pdo.php - Corrected Database Connection
// This file should only handle the database connection and session start.
// Utility functions like validateProfile() are moved to util.php.

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('SALT', 'XyZzy12*_');

try {
    // Ensure your database credentials (username and password) are correct here.
    // As per your previous confirmation, 'fred' and 'zap' are used.
    $pdo = new PDO('mysql:host=localhost;dbname=misc', 'fred', 'zap');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}

// isLoggedIn() is a simple check and can remain here or be moved to util.php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// validateProfile() and loadProfile() functions have been removed from here
// as they are defined in util.php to avoid redeclaration errors.
?>
