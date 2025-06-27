<?php
require_once "pdo.php"; 

if (isLoggedIn()) {
    header('Location: index.php');
    return;
}

if (isset($_POST['email']) && isset($_POST['pass'])) {
    unset($_SESSION['name']);

    $email = $_POST['email'];
    $pass = $_POST['pass'];

    if (strlen($email) < 1 || strlen($pass) < 1) {
        $_SESSION['error'] = "Email and password are required";
        header("Location: login.php");
        return;
    }

    if (strpos($email, '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }

    $check = hash('md5', SALT . $pass); 

    $stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email = :em AND password = :pw');
    $stmt->execute(array(':em' => $email, ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['success'] = "Logged in."; 
        header("Location: index.php");
        return;
    } else {
        $_SESSION['error'] = "Incorrect password";
        header("Location: login.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>d2334fbc</title>
<?php require_once "bootstrap.php"; ?>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}
?>

<form method="POST">
    <label for="email">Email</label>
    <input type="text" name="email" id="email"><br/>
    <label for="id_1723">Password</label>
    <input type="password" name="pass" id="id_1723"><br/>
    <input type="submit" onclick="return doValidate();" value="Log In">
    <a href="index.php">Cancel</a>
</form>

<script>
function doValidate() {
    console.log('Validating...');
    try {
        let email = document.getElementById('email').value;
        let pw = document.getElementById('id_1723').value;

        console.log("Validating email=" + email + " pw=" + pw);

        if (email == null || email == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if (email.indexOf('@') == -1) {
            alert("Email address must contain @");
            return false;
        }
        return true;
    } catch(e) {
        console.error(e);
        return false;
    }
}
</script>

</div>
</body>
</html>