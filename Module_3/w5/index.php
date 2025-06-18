<?php
session_start();
require_once "pdo.php";

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
        <h1>Welcome to the Automobiles Database</h1>

        <?php
        if (isset($_SESSION['success'])) {
            echo '<p style="color:green;">'.htmlentities($_SESSION['success'])."</p>\n";
            unset($_SESSION['success']);
        }

        if (!isset($_SESSION['name'])) {
            echo "<p><a href='login.php' style='color: green;'>Please log in</a></p>";
        } else {
            $name = $_SESSION['name'];
            echo "<h1>Tracking Autos for ".htmlentities($name)."</h1>";

            if (isset($_SESSION['error'])) {
                echo '<p style="color:red">'.htmlentities($_SESSION['error'])."</p>\n";
                unset($_SESSION['error']);
            }
            
            echo "<h2>Automobiles</h2>";

            if ($pdo !== null && isset($_SESSION['last_auto_id'])) {
                $last_auto_id = $_SESSION['last_auto_id'];
                
                try {
                    $stmt = $pdo->prepare("SELECT make, model, year, mileage FROM autos WHERE auto_id = :id");
                    $stmt->execute([':id' => $last_auto_id]);
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($row) {
                        echo '<table border="1" class="table table-striped table-bordered">';
                        echo '<thead><tr><th>Make</th><th>Model</th><th>Year</th><th>Mileage</th><th>Action</th></tr></thead>';
                        echo '<tbody>';
                        echo '<tr><td>' . htmlentities($row['make']) . '</td>';
                        echo '<td>' . htmlentities($row['model']) . '</td>'; 
                        echo '<td>' . htmlentities($row['year']) . '</td>';
                        echo '<td>' . htmlentities($row['mileage']) . '</td>';
                        echo '<td>';
                        echo '<a href="edit.php?auto_id=' . $last_auto_id . '">Edit</a> / ';
                        echo '<a href="delete.php?auto_id=' . $last_auto_id . '">Delete</a>';
                        echo '</td></tr>' . "\n";
                        echo '</tbody>';
                        echo '</table>';
                    } else {
                        echo '<p>No automobiles found in current session.</p>';
                    }
                } catch (PDOException $e) {
                    error_log('Error retrieving last added/updated auto: ' . $e->getMessage());
                    echo '<p style="color: red;">Error retrieving last added/updated automobile. Please try again later.</p>';
                }
                
                unset($_SESSION['last_auto_id']);

            } else {
                echo '<p>No automobiles found.</p>'; 
            }

            echo '<p>';
            echo '<a href="add.php">Add New Entry</a> | ';
            echo '<a href="logout.php">Logout</a>';
            echo '</p>';

            echo '<p><b>Note</b>: Your implementation should retain data across multiple logout/login sessions. This sample implementation clears all its data on logout - which you should not do in your implementation.</p>';
        }
        ?>
    </div>
</body>
</html>