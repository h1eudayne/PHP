<html>

<head>
    <title>5bd41f22</title>
</head>

<body>
    <?php
        session_start();

        
        require_once "pdo.php";

        if (isset($_SESSION['name'])) {
            echo "<h1>Tracking Autos for ".$_SESSION['name']."</h1>";
        } else {
            die('Not logged in');
        }

        if(isset($_POST['logout'])) {
            header('Location: index.php');
        } else {
         
            if(isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {

                if ($_POST['make'] == "") {
                    $_SESSION['error'] = "Make is required";
                    header('Location: autos.php');
                    return;
                } elseif (is_numeric($_POST['year']) && is_numeric($_POST['mileage'])) {
                        $stmt = $pdo->prepare('INSERT INTO autos
                            (make, year, mileage) VALUES ( :mk, :yr, :mi)');
                            
                        $stmt->execute(array(
                            ':mk' => $_POST['make'],
                            ':yr' => $_POST['year'],
                            ':mi' => $_POST['mileage'])
                        );

                        $_SESSION['success'] = "Record inserted";
                        header('Location: views.php');
                        return;
                } else {
                    $_SESSION['error'] = "Mileage and year must be numeric";
                    header('Location: autos.php');
                    return;
                }
                
            }   
        }

        if (isset($_SESSION['error'])) {
            echo "<p style='color: red'>".$_SESSION['error']."</p>";
            unset($_SESSION['error']);
        }

    ?>

    <form method="post">
        <p>Make:
            <input name="make">
        </p>
        <p>Year:
            <input size="40" name="year">
        </p>
        <p>Mileage:
            <input size="40" name="mileage">
        </p>
        <p>
            <input type="submit" value="Add" name="Add" />
            <a href="logout.php">Logout</a>
        </p>

    </form>
</body>

</html>