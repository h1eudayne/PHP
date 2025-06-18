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

$stmt = $pdo->prepare("SELECT make FROM autos WHERE auto_id = :id");
$stmt->execute(array(":id" => $_GET['auto_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Bad value for auto_id';
    header('Location: view.php');
    return;
}

if (isset($_POST['delete'])) {
    try {
        $stmt = $pdo->prepare('DELETE FROM autos WHERE auto_id = :id');
        $stmt->execute(array(':id' => $_POST['auto_id']));
        $_SESSION['success'] = 'Record deleted';
        header('Location: view.php');
        return;
    } catch (PDOException $e) {
        error_log('Error deleting record: ' . $e->getMessage());
        $_SESSION['error'] = 'Error deleting record. Please try again. Detail: ' . $e->getMessage();
        header("Location: delete.php?auto_id=" . htmlentities($_POST['auto_id']));
        return;
    }
}

$make = htmlentities($row['make']);
$auto_id = htmlentities($_GET['auto_id']);

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
        <h1>Deleting Automobile</h1>
        <p>Confirm: Deleting <?= $make ?></p>
        <form method="post">
            <input type="hidden" name="auto_id" value="<?= $auto_id ?>">
            <input type="submit" value="Delete" name="delete">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>