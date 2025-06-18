<?php

    $hostName = "localhost";
    $userName = "alan";
    $password = "alan";
    $port = "3306";
    $dbName = "misc";

    try {
        $pdo = new PDO("mysql:host=$hostName;dbname=$dbName",$userName,$password);
        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>