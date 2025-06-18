<?php 
    require_once "pdo.php";
    session_start();
?>
<!DOCTYPE html>
<html>

<head>

    <?php include "header_content.php" ?>

</head>

<body>

    <div class="container pt-3">
        <h1>CRUD Application</h1>

        <?php

            if (isset($_SESSION['error'])) {
                echo "<p style='color: red'>".$_SESSION['error']."</p>";
                unset($_SESSION['error']);
            }
            
            if (isset($_SESSION['success'])) {
                echo "<p style='color: green'>".$_SESSION['success']."</p>";
                unset($_SESSION['success']);
            }

        ?>

        <table class="table table-striped">
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th colspan="2">Action</th>
            </tr>
            <?php

            $statement = $pdo->query("SELECT name, email, password, user_id FROM users ORDER BY user_id desc LIMIT 8");

            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>".htmlentities($row['user_id'])."</td>";
                echo "<td>".htmlentities($row['name'])."</td>";
                echo "<td>".htmlentities($row['email'])."</td>";
                echo "<td>".htmlentities($row['password'])."</td>";
                echo "<td><a href='edit.php?user_id=".$row['user_id']."' >Edit</a></td>";
                echo "<td><a href='delete.php?user_id=".$row['user_id']."' >Delete</a></td>";
                echo "</tr>";
            }

        ?>
        </table>

        <a href="add.php">Add new user</a>
    </div>
</body>

</html>