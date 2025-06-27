<?php
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

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function validateProfile() {
    if (strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 ||
        strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 ||
        strlen($_POST['summary']) < 1) {
        return "All fields are required";
    }

    if (strpos($_POST['email'], '@') === false) {
        return "Email address must contain @";
    }
    return true; 
}

function loadProfile($pdo, $profile_id, $user_id) {
    $stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(':pid' => $profile_id, ':uid' => $user_id));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profile;
}

?>