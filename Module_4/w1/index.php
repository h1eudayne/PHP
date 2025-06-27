<?php 
session_start(); 
require_once "pdo.php"; 

$stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline FROM Profile");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>d2334fbc</title>
    <?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
    <h2>Jared Best | Resume Registry</h2>

    <?php
    // Display flash messages
    if (isset($_SESSION['success'])) {
        echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>

    <?php if (!isset($_SESSION['user_id'])): // Check for user_id to determine login status ?>
        <p><a href='login.php' style='color: green;'>Please log in</a></p>
    <?php else: ?>
        <p>Hello, <?= htmlentities($_SESSION['name']); ?> (<a href="logout.php">Logout</a>)</p>
    <?php endif; ?>

    <?php if (empty($rows)): // Check if there are any profiles ?>
        <p>No profiles found</p>
    <?php else: ?>
        <table border='1'>
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
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td>
                            <a href='view.php?profile_id=<?= htmlentities($row['profile_id']) ?>'>
                                <?= htmlentities($row['first_name'] . ' ' . $row['last_name']) ?>
                            </a>
                        </td>
                        <td>
                            <?= htmlentities($row['headline']) ?>
                        </td>
                        <?php if (isset($_SESSION['user_id'])):  ?>
                            <td>
                                <?php if ($row['user_id'] == $_SESSION['user_id']): ?>
                                    <a href="edit.php?profile_id=<?= htmlentities($row['profile_id']) ?>">Edit</a> /
                                    <a href="delete.php?profile_id=<?= htmlentities($row['profile_id']) ?>">Delete</a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <?php if (isset($_SESSION['user_id'])):?>
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