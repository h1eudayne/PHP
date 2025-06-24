<?php

session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'misc'); // Your database name
define('DB_USER', 'fred'); // Your database username
define('DB_PASS', 'zap');  // Your database password (e.g., 'zap' for MAMP/XAMPP default)

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed: " . $e->getMessage());
}

function flash_message($message, $type = 'success') {
    $_SESSION['flash_message'] = [
        'message' => $message,
        'type' => $type
    ];
}

function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $message = htmlentities($_SESSION['flash_message']['message']);
        $type = htmlentities($_SESSION['flash_message']['type']);
        echo "<div class='alert alert-$type'>$message</div>";
        unset($_SESSION['flash_message']);
    }
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        flash_message("Please log in to access this page.", "danger");
        header('Location: login.php');
        exit();
    }
}

function validateProfile($first_name, $last_name, $email, $headline, $summary) {
    if (strlen($first_name) == 0 || strlen($last_name) == 0 || strlen($email) == 0 ||
        strlen($headline) == 0 || strlen($summary) == 0) {
        return "All fields are required";
    }

    if (strpos($email, '@') === false) {
        return "Email address must contain @";
    }

    return true;
}

function validatePos() {
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i]) && !isset($_POST['desc' . $i])) {
            continue;
        }

        if ((isset($_POST['year' . $i]) && strlen($_POST['year' . $i]) == 0) ||
            (isset($_POST['desc' . $i]) && strlen($_POST['desc' . $i]) == 0)) {
            return "All fields are required";
        }
        
        if (isset($_POST['year' . $i]) && !is_numeric($_POST['year' . $i])) {
            return "Position year must be numeric";
        }
    }
    return true;
}


function loadPos($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute([':pid' => $profile_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertPositions($pdo, $profile_id) {
    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue; 
        if (!isset($_POST['desc' . $i])) continue; 

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc
        ));
        $rank++;
    }
    return true;
}
?>
