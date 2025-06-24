<?php
require_once "pdo.php";

if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id"; 
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile WHERE profile_id = :pid");
$stmt->execute(array(":pid" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row === false) {
    $_SESSION['error'] = 'Could not load profile';
    header('Location: index.php');
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "head.php"; ?>
    <title>d2334fbc</title> </head>
<body>
<div class="container">
    <h1>Profile information</h1>
    <p>First Name: <?php echo htmlentities($row['first_name']); ?></p>
    <p>Last Name: <?php echo htmlentities($row['last_name']); ?></p>
    <p>Email: <?php echo htmlentities($row['email']); ?></p>
    <p>Headline:<br/> <?php echo htmlentities($row['headline']); ?></p>
    <p>Summary: <br/><?php echo htmlentities($row['summary']); ?></p>
    <a href="index.php">Done</a>
</div>
</body>
</html>