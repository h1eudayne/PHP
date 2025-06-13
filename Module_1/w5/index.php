<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Tên Của Bạn PHP</title>
</head>
<body>
    <h1>Tên Của Bạn PHP</h1>
    <p>
        The SHA256 hash of "Tên Của Bạn" is
        <?php
        echo hash('sha256', 'Tên Của Bạn');
        ?>
    </p>

    <pre>
    AAAAA
    A   A
    A   A
    AAAAA
    A   A
    A   A
    </pre>

    <p>
        <a href="./check.php">Click here to check the error setting</a>
    </p>
    <p>
        <a href="./fail.php">Click here to cause a traceback</a>
    </p>
</body>
</html>