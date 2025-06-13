<?php
// Start session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if name or password is empty
    if (empty($_POST["who"]) || empty($_POST["pass"])) {
        $error = "Both fields are required!";
    } else {
        $name = $_POST["who"];
        $password = $_POST["pass"];
        
        // Hardcoded password hash for testing (should be replaced by hashed password in real use)
        $stored_hash = "c4ca4238a0b923820dcc509a6f75849b"; // Hash for '1'
        
        // Check if the entered password matches the stored hash
        if (md5($password) == $stored_hash) {
            $_SESSION["user"] = $name;
            header("Location: game.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>0eeebe39</title>
</head>
<body>
    <!-- Wrap the "Please Log In" text in an anchor tag -->
    <a href="#">Please Log In</a>

    <!-- Display error message if any -->
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>

    <!-- Login form -->
    <form method="POST" action="login.php">
        Name: <input type="text" name="who" required>
        Password: <input type="password" name="pass" required>
        <button type="submit">Log In</button>
    </form>
</body>
</html>
