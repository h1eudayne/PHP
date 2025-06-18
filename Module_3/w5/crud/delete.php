<?php

    require_once "pdo.php";

    session_start();

    if (isset($_POST['delete']) && isset($_POST['user_id'])) {
        $sql = "DELETE FROM users WHERE user_id = :id";

        $statement = $pdo->prepare($sql);

        $statement->execute(array(
            ":id" => $_POST["user_id"]
        ));

        $_SESSION['success'] = "Record deleted";
        header("Location: index.php");
        return;
    }

    $statement = $pdo->prepare("SELECT name, user_id FROM users WHERE user_id = :id");

    $statement->execute(array(":id" => $_GET['user_id']));

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    while($row === false) {
        $_SESSION['error'] = "Bad value for user id";
        header("Location: index.php");
        return;
    }

?>

<!DOCTYPE html>
<html>

<head>
    <?php include "header_content.php" ?>
</head>

<body>
    <div class="container">
        <p class="text-danger">Confirm: Deleting
            <b><?php echo htmlentities($row['name']); ?></b>
        </p>
        <form method="POST">
            <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>" />
            <input type="hidden" name="delete" value="delete" />
            <button type="submit" class="btn btn-primary">Delete User</button>
            <a href="index.php">Cancel </a>
        </form>

    </div>
</body>

</html>