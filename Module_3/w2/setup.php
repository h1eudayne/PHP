<?php
    require_once "../pdo.php";

    // Run this once to create the table necessary

    try {
        $sql = "CREATE TABLE autos (
            auto_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            make VARCHAR(128),
            year INTEGER,
            mileage INTEGER,
            PRIMARY KEY (auto_id)
            ) ENGINE=InnoDB CHARSET=utf8";
    
        $pdo->exec($sql);

        $sql2 = "INSERT INTO users (name, email, password) VALUES ('umsi', 'umsi@umich.edu', 'php123')";

        $pdo->exec($sql2);

        echo "<h2>Table created successfully</h2>";
    } catch (PDOException $e) {
        echo "<h2>Error has occured check if database and users have been set up properly </h2>";
        echo "<br />".$e->getMessage()."<br />";
    }
?>