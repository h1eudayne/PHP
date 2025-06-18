<?php
require_once "pdo.php"; 

echo "<h1>Database Connection Test</h1>";

try {
    $stmt = $pdo->query("SELECT CURRENT_DATE as today");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<p style='color: green;'>Successfully connected to the database!</p>";
    echo "<p>Today's date from DB: " . htmlentities($row['today']) . "</p>";

    $stmt_autos = $pdo->query("SELECT COUNT(*) as total_autos FROM autos");
    $autos_count = $stmt_autos->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total autos in database: " . htmlentities($autos_count['total_autos']) . "</p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>An error occurred during query: " . htmlentities($e->getMessage()) . "</p>";
    error_log('Query error in check_db_connection.php: ' . $e->getMessage());
}

echo "<p style='color: green;'>If you see 'Successfully connected to the database!' above, your pdo.php is likely working.</p>";
?>