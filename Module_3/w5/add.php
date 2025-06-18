<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (isset($_POST['add'])) { 
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: add.php");
        return;
    }

    if (!is_numeric($_POST['year'])) {
        $_SESSION['error'] = 'Year must be numeric';
        header("Location: add.php");
        return;
    }

    if (!is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage must be numeric';
        header("Location: add.php");
        return;
    }
    
    try {
        $stmt = $pdo->prepare('INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));

        $last_id = $pdo->lastInsertId();
        $_SESSION['last_auto_id'] = $last_id; 

        $_SESSION['success'] = 'Record added';
        header("Location: view.php"); 
        return;
    } catch (PDOException $e) {
        error_log('Error inserting record: ' . $e->getMessage());
        $_SESSION['error'] = 'Error inserting record. Please try again. Detail: ' . $e->getMessage();
        header("Location: add.php");
        return;
    }
}
if (isset($_POST['cancel'])) {
    header("Location: view.php");
    return;
}
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
        <h1>Adding Automobile for <?php echo htmlentities($_SESSION['name']); ?></h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n";
            unset($_SESSION['error']);
        }
        ?>
        <form method="post">
            <p>Make: <input type="text" name="make" size="60"/></p>
            <p>Model: <input type="text" name="model" size="60"/></p> <p>Year: <input type="text" name="year"/></p>
            <p>Mileage: <input type="text" name="mileage"/></p>
            <input type="submit" value="Add" name="add">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>