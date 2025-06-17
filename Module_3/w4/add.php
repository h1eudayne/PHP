<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (isset($_POST['add'])) {
    // --- Bắt đầu Validation ---
    // CHỈ KIỂM TRA make, year, mileage
    if (strlen($_POST['make']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1) {
        $_SESSION['error'] = 'Make, year, and mileage are required'; // Sửa thông báo lỗi
        header("Location: add.php");
        return;
    }

    if (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = 'Mileage and year must be numeric';
        header("Location: add.php");
        return;
    }
    // --- Kết thúc Validation ---

    try {
        // SỬA CÂU LỆNH INSERT: CHỈ CÓ make, year, mileage
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));
        $_SESSION['success'] = 'Record inserted';
        header("Location: views.php");
        return;
    } catch (PDOException $e) {
        error_log('Error inserting record: ' . $e->getMessage());
        $_SESSION['error'] = 'Error inserting record. Please try again.';
        header("Location: add.php");
        return;
    }
}

if (isset($_POST['cancel'])) {
    header("Location: views.php");
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tên Sinh Viên - Adding Auto</title> <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
        integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <h1>5bd41f22 <?php echo htmlentities($_SESSION['name']); ?></h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n";
            unset($_SESSION['error']);
        }
        ?>
        <form method="post">
            <p>Make: <input type="text" name="make" size="60"/></p>
            <p>Year: <input type="text" name="year"/></p>
            <p>Mileage: <input type="text" name="mileage"/></p>
            <input type="submit" value="Add" name="add">
            <input type="submit" name="cancel" value="Cancel">
        </form>
    </div>
</body>
</html>