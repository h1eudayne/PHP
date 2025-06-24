<?php

require_once "util.php"; 

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['cancel'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['email']) && isset($_POST['pass'])) {
    unset($_SESSION['name']); 

    $email = $_POST['email'];
    $pass = $_POST['pass'];

    $salt = 'XyZzy12*_';
    $check = hash('md5', $salt . $pass); 

    $stmt = $pdo->prepare('SELECT user_id, name FROM Users WHERE email = :em AND password = :pw');
    $stmt->execute(array(
        ':em' => $email,
        ':pw' => $check
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        flash_message("Incorrect email or password", "danger");
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name']; 
        flash_message("Logged in successfully!", "success");
        header("Location: index.php");
        exit();
    }
}
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
    <h1 class="text-3xl font-bold mb-6 mt-8 text-center">Please Log In</h1>
    <?php display_flash_message(); // Display any flash messages ?>
    <form method="POST" class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md">
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
            <input type="text" name="email" id="email" class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
        </div>
        <div class="mb-6">
            <label for="id_1723" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
            <input type="password" name="pass" id="id_1723" class="shadow appearance-none border rounded-md w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" />
        </div>
        <div class="flex items-center justify-between">
            <input type="submit" value="Log In" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out mr-2">
            <input type="submit" name="cancel" value="Cancel" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
        </div>
    </form>
    <p class="mt-4 text-center text-gray-600">
        For a password hint, view source and find a password hint
        in the HTML comments. (Hardcoded user: umsi@umich.edu, password: php123)
    </p>
</div>
</body>
</html>
