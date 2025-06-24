<?php

require_once "util.php"; 
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    $pageTitle = "d2334fbc";
    require_once "head.php";
    ?>
</head>
<body>
<div class="container">
    <h2>Jared Best | Resume Registry</h2>

    <?php
    display_flash_message();
    ?>

    <?php if (isset($_SESSION['name'])):  ?>
        <p><a href="logout.php">Logout</a></p>
    <?php else:?>
        <p><a href='login.php'>Please log in</a></p>
    <?php endif; ?>

    <table border="1">
        <thead>
            <tr>
                <th>Name</th>
                <th>Headline</th>
                <?php if (isset($_SESSION['user_id'])):?>
                    <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $stmt = $pdo->query("SELECT p.profile_id, p.first_name, p.last_name, p.headline FROM Profile p JOIN Users u ON p.user_id = u.user_id");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    echo "<tr><td>";
                    echo("<a href='view.php?profile_id=" . htmlentities($row['profile_id']) . "'>" . htmlentities($row['first_name'] . " " . $row['last_name']) . "</a>");
                    echo("</td><td>");
                    echo(htmlentities($row['headline']));
                    echo("</td>");
                    if (isset($_SESSION['user_id'])) {
                        echo("<td>");
                        echo('<a href="edit.php?profile_id=' . htmlentities($row['profile_id']) . '">Edit</a> / <a href="delete.php?profile_id=' . htmlentities($row['profile_id']) . '">Delete</a>');
                        echo("</td>");
                    }
                    echo("</tr>\n");
                }
            } else {
                echo "<tr><td colspan='" . (isset($_SESSION['user_id']) ? "3" : "2") . "'>No profiles found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <?php if (isset($_SESSION['user_id'])):  ?>
        <p><a href="add.php">Add New Entry</a></p>
    <?php endif; ?>

    <p>
        <b>Note:</b> Your implementation should retain data across multiple
        logout/login sessions. This sample implementation clears all its
        data periodically - which you should not do in your implementation.
    </p>
</div>
</body>
</html>
