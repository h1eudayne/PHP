<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (!isset($_GET['auto_id'])) {
    $_SESSION['error'] = "Missing auto_id";
    header('Location: view.php');
    return;
}
if (isset($_POST['save'])) {
    if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ) {
        $_SESSION['error'] = 'All fields are required';
        header("Location: edit.php?auto_id=" . htmlentities($_POST['auto_id']));
        return;
    }

    if (!is_numeric($_POST['year'])) {
        $_SESSION['error'] = 'Year must be numeric';
        header("Location: edit.php?auto_id=" . htmlentities($_POST['auto_id']));
        return;
    }

    if (!is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage must be numeric';
        header("Location: edit.php?auto_id=" . htmlentities($_POST['auto_id']));
        return;
    }

    try {
        $stmt = $pdo->prepare('UPDATE autos SET make = :mk, model = :md, year = :yr, mileage = :mi WHERE auto_id = :id');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':md' => $_POST['model'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage'],
            ':id' => $_POST['auto_id'] 
        ));

        $_SESSION['success'] = 'Record updated';
        $_SESSION['last_auto_id'] = $_POST['auto_id'];
        header('Location: view.php');
        return;
    } catch (PDOException $e) {
        error_log('Error updating record: ' . $e->getMessage());
        $_SESSION['error'] = 'Error updating record. Please try again. Detail: ' . $e->getMessage();
        header("Location: edit.php?auto_id=" . htmlentities($_POST['auto_id']));
        return;
    }
}

if (isset($_POST['cancel'])) {
    header('Location: view.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM autos WHERE auto_id = :id");
$stmt->execute(array(":id" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header('Location: view.php');
    return;
}

$make = htmlentities($row['make']);
$model = htmlentities($row['model']);
$year = htmlentities($row['year']);
$mileage = htmlentities($row['mileage']);
$auto_id = htmlentities($row['auto_id']);

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
        <h1>Editing Automobile</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n";
            unset($_SESSION['error']);
        }
        ?>
        <form method="post">
            <p>Make: <input type="text" name="make" value="<?= $make ?>" size="60"/></p>
            <p>Model: <input type="text" name="model" value="<?= $model ?>" size="60"/></p> <p>Year: <input type="text" name="year" value="<?= $year ?>"/></p>
            <p>Mileage: <input type="text" name="mileage" value="<?= $mileage ?>"/></p>
            <input type="hidden" name="auto_id" value="<?= $auto_id ?>"> <input type="submit" value="Save" name="save"> <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>