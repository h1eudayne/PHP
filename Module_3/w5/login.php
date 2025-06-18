<head>
    <title>5bd41f22</title>
</head>

<?php
        session_start();
        
        if ( isset($_POST['email']) && isset($_POST['pass'])  ) {
            if($_POST['email'] == "" || $_POST['pass'] == "") {
                $_SESSION['error'] = "User name and password are required";   
                header("Location: login.php");
                return;    
            } elseif (strpos($_POST['email'], '@') == false) {
                $_SESSION['error'] = "Email must have an at-sign (@)";
                header("Location: login.php");
                return;
            } else {
                if ( $_POST['pass'] == "php123" ) {
                    error_log("Login success ".$_POST['email']);
                    $_SESSION['success'] = "Login success.";
                    $_SESSION['name'] = $_POST['email'];
                    header('Location: index.php');
                    return;
                } else { 
                    $hash = hash('sha256', $_POST['pass']);
                    error_log("Login fail ".$_POST['email']." $hash");
                    $_SESSION['error'] = "Incorrect password";
                    header('Location: login.php');
                    return;
                }
            }
        }
    ?>

<body>

    <h1>Please Log In</h1>

    <?php 

        if ( isset($_SESSION['error']) ) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']);
        }
    ?>

    <form method="post">
        <p>Email:
            <input type="text" size="40" name="email">
        </p>
        <p>Password:
            <input type="text" size="40" name="pass">
        </p>
        <p>
            <input type="submit" value="Log In" />
            <a href="<?php echo($_SERVER['PHP_SELF']);?>">Refresh</a>
        </p>
    </form>
</body>
<html>