<?php
session_start();
require_once "pdo.php"; 

if (!isset($_SESSION['name'])) {
    die('Not logged in'); 
}

$name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>5bd41f22</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1>Tracking Autos for <?php echo htmlentities($name); ?></h1>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n";
            unset($_SESSION['success']);
        }
        ?>

        <h2>Automobiles</h2>
        <?php
        $stmt_fetched = false; 

        if (isset($pdo) && $pdo instanceof PDO) {
            try {
                $stmt = $pdo->query("SELECT auto_id, make, year, mileage FROM autos ORDER BY make");
                $stmt_fetched = true;
            } catch (PDOException $e) {
                error_log('Error querying autos: ' . $e->getMessage());
                echo '<p style="color: red;">Error retrieving automobiles. Please try again later.</p>';
            }
        } else {
            echo '<p style="color: red;">Database connection issue. Please check configuration.</p>';
        }

        if ($stmt_fetched && $stmt->rowCount() > 0) {
            echo '<ul>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li>' . htmlentities($row['year']) . ' ' . htmlentities($row['make']) . ' / ' . htmlentities($row['mileage']) . '</li>' . "\n";
            }
            echo '</ul>';
        } elseif ($stmt_fetched) { 
            echo '<p>No automobiles found.</p>';
        }
        ?>

        <p>
            <a href="add.php">Add New</a> |
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>
</html>