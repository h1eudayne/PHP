<?php

    require "pdo.php";

    session_start();

    if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password'])) {
        $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";

        $statement = $pdo->prepare($sql);

        $statement->execute(array(
            ":name" => $_POST['name'],
            ":email" => $_POST['email'],
            ":password" => $_POST['password']
        ));

        $_SESSION['success'] = "Record Added";
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
        <h1>Add new user</h1>
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input class="form-control" type="text" name="name" id="name" />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input class="form-control" type="text" name="email" id="email" />

            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control" type="text" name="password" id="password" />
            </div>
            <button type="submit" class="btn btn-primary">Add user</button>
            <a href="index.php">Cancel</a>
        </form>
    </div>
</body>

</html>