<?php
// check_db_connection.php

// Include your pdo.php file to establish the database connection
require_once "pdo.php"; 

echo "<h1>Database Connection Test</h1>";

// If require_once "pdo.php" didn't die(), it means $pdo was successfully created.
// Now, let's try a simple query to ensure everything is working.
try {
    // Attempt a very simple query (e.g., select current date, or a single row from a table)
    $stmt = $pdo->query("SELECT CURRENT_DATE as today");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "<p style='color: green;'>Successfully connected to the database!</p>";
    echo "<p>Today's date from DB: " . htmlentities($row['today']) . "</p>";

    // You can also try selecting from your 'autos' table to ensure it's accessible
    $stmt_autos = $pdo->query("SELECT COUNT(*) as total_autos FROM autos");
    $autos_count = $stmt_autos->fetch(PDO::FETCH_ASSOC);
    echo "<p>Total autos in database: " . htmlentities($autos_count['total_autos']) . "</p>";

} catch (PDOException $e) {
    // This catch block might not be hit if pdo.php's die() already fired.
    // But it's good practice for any other DB operations in this file.
    echo "<p style='color: red;'>An error occurred during query: " . htmlentities($e->getMessage()) . "</p>";
    error_log('Query error in check_db_connection.php: ' . $e->getMessage());
}

echo "<p style='color: green;'>If you see 'Successfully connected to the database!' above, your pdo.php is likely working.</p>";
?>