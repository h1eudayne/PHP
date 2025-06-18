<?php

    require "pdo.php";

    session_start();

    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['user_id'])) {

        $sql = "UPDATE users SET name = :name, password = :password, email = :email WHERE user_id = :user_id";

        $statement = $pdo->prepare($sql);

        $statement->execute(array(
            ":name" => $_POST['name'],
            ":email" => $_POST['email'],
            ":password" => $_POST['password'],
            ":user_id" => $_POST['user_id']
        ));

        $_SESSION['success'] = "Record updated";
        header("Location: index.php");
        return;
    }

    $statement = $pdo->prepare("SELECT * FROM users WHERE user_id = :id");

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
    <div class="container w-50 pt-3">
        <h1>Update user</h1>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" value="<?php echo $row['name']; ?>" />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" id="email" value="<?=  $row['email'] ?>" />

            </div>
            <div class="form-group">
                <label for="text">Password</label>
                <input class="form-control" type="text" name="password" id="password"
                    value="<?php echo $row['password']; ?>" />
            </div>
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $row['user_id']; ?>" />
            <button type="submit" class="btn btn-primary">Update user</button>
            <a href="index.php">Cancel</a>
        </form>
    </div>
</body>

</html>