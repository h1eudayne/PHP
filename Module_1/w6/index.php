<html>
<head>
<title>0eeebe39</title> 
</head>
<body>
<h1>Welcome to my guessing game</h1>
<p>
<?php
  // Kiểm tra nếu tham số 'guess' chưa có trong URL
  if ( ! isset($_GET['guess']) ) { 
    echo("Missing guess parameter");
  } 
  // Kiểm tra nếu giá trị của 'guess' là một chuỗi rỗng
  else if ( strlen($_GET['guess']) < 1 ) {
    echo("Your guess is too short");
  } 
  // Kiểm tra xem 'guess' có phải là một số hay không
  else if ( ! is_numeric($_GET['guess']) ) {
    echo("Your guess is not a number");
  } 
  // Kiểm tra nếu giá trị đoán quá thấp so với số đúng
  else if ( $_GET['guess'] < 42 ) {
    echo("Your guess is too low");
  } 
  // Kiểm tra nếu giá trị đoán quá cao so với số đúng
  else if ( $_GET['guess'] > 42 ) {
    echo("Your guess is too high");
  } 
  // Nếu giá trị đoán đúng
  else {
    echo("Congratulations - You are right");
  }
?>
</p>
</body>
</html>
