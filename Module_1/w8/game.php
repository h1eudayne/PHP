<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // The user's choice from the form
    $user_choice = $_POST["choice"];
    // Randomly generate the computer's choice (1 = Rock, 2 = Paper, 3 = Scissors)
    $computer_choice = rand(1, 3);
    
    // Check who wins
    $result = check_result($user_choice, $computer_choice);
}

function check_result($user, $computer) {
    if ($user == $computer) {
        return "Tie";
    }
    if (($user == 1 && $computer == 3) || ($user == 2 && $computer == 1) || ($user == 3 && $computer == 2)) {
        return "You Win";
    }
    return "You Lose";
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
    <h2>Welcome, <?php echo $_SESSION["user"]; ?>!</h2>

    <!-- Play form with choices for Rock, Paper, or Scissors -->
    <form method="POST" action="game.php">
        <label>Select your choice:</label><br>
        <input type="radio" name="choice" value="1" required> Rock
        <input type="radio" name="choice" value="2" required> Paper
        <input type="radio" name="choice" value="3" required> Scissors
        <br><br>
        <button type="submit">Play</button>
    </form>

    <?php if (isset($result)) { echo "<p>Result: $result</p>"; } ?>

    <p><a href="logout.php">Logout</a></p>
</body>
</html>
