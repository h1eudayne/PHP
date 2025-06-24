<?php
// pdo.php

// ONLY call session_start() if a session is not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('SALT', 'XyZzy12*_'); // Define the salt globally

try {
    $pdo = new PDO('mysql:host=localhost;dbname=misc', 'fred', 'zap');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to validate profile data (for add.php and edit.php)
function validateProfile() {
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
        strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 ||
        strlen($_POST['summary']) < 1) {
        return "All fields are required";
    }

    if (strpos($_POST['email'], '@') === false) {
        return "Email address must contain @";
    }
    return true; // No error
}

// Function to load a profile by profile_id and user_id (for edit and delete ownership check)
function loadProfile($pdo, $profile_id, $user_id) {
    $stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(':pid' => $profile_id, ':uid' => $user_id));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profile;
}

?>