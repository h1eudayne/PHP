<?php
session_start();

if (isset($_POST['email']) && isset($_POST['pass'])) {
    if (isset($_POST['cancel'])) {
        header("Location: login.php");
        return;
    }

    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    }

    if (strpos($_POST['email'], '@') === false) { 
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }

    if ($_POST['pass'] == 'php123') {
        error_log("Login success " . $_POST['email']);
        $_SESSION['success'] = "Login success.";
        $_SESSION['name'] = $_POST['email']; 
        header('Location: views.php'); 
        return;
    } else {
        error_log("Login fail " . $_POST['email'] . " (Incorrect password entered)");
        $_SESSION['error'] = "Incorrect password";
        header('Location: login.php');
        return;
    }
}

if (isset($_POST['cancel'])) {
    header("Location: login.php");
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
        <h1>Please Log In</h1>

        <?php
        if (isset($_SESSION['error'])) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        }
        ?>

        <form method="post">
            <p>Email:
                <input type="text" size="40" name="email">
            </p>
            <p>Password:
                <input type="password" size="40" name="pass"> </p>
            <p>
                <input type="submit" value="Log In" name="login_submit"/>
                <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>
    </div>
</body>
</html>