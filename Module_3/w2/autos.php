<?php
// autos.php
session_start(); // Bắt đầu session

require_once "pdo.php"; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập qua session chưa.
if ( ! isset($_SESSION['name']) ) {
    // Nếu không có 'name' trong session (tức là chưa đăng nhập hợp lệ),
    // chuyển hướng về trang đăng nhập.
    header('Location: login.php'); // Autograder của bạn có thể yêu cầu die('Name parameter missing');
                                  // Nhưng tốt nhất là chuyển hướng về login.php
    return;
}

// Xử lý nút "logout"
if (isset($_POST['logout'])) {
    session_unset(); // Xóa tất cả các biến session
    session_destroy(); // Hủy session
    header('Location: index.php'); // Chuyển hướng về trang index.php hoặc login.php
    return;
}

// Khởi tạo biến thông báo lỗi/thành công
$error_message = '';
$success_message = '';

// Lấy thông báo lỗi/thành công từ session (nếu có)
if (isset($_SESSION['error'])) {
    $error_message = '<p style="color: red">' . htmlentities($_SESSION['error']) . '</p>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    $success_message = '<p style="color: green">' . htmlentities($_SESSION['success']) . '</p>';
    unset($_SESSION['success']);
}

// Xử lý khi form "Add" được gửi
if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    $make = $_POST['make'];
    $year = $_POST['year'];
    $mileage = $_POST['mileage'];

    if (empty($make)) {
        $_SESSION['error'] = "Make is required";
        header("Location: autos.php"); // Chuyển hướng để hiển thị lỗi
        return;
    }

    if (!is_numeric($year) || !is_numeric($mileage)) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: autos.php"); // Chuyển hướng để hiển thị lỗi
        return;
    }

    // Nếu không có lỗi, tiến hành thêm vào DB
    try {
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $make,
            ':yr' => $year,
            ':mi' => $mileage
        ));
        $_SESSION['success'] = "Record inserted"; // <-- Thông báo này
    } catch (PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
    }

    // Chuyển hướng sau khi xử lý POST để tránh gửi lại form khi refresh
    header("Location: autos.php");
    return;
}

// Lấy dữ liệu xe hơi để hiển thị
$statement = $pdo->query("SELECT auto_id, make, year, mileage FROM autos");
$rows = $statement->fetchAll(PDO::FETCH_ASSOC); // Fetch tất cả các hàng

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Tracking Autos</title>
</head>
<body>
    <h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>

    <?php
    echo $error_message;
    echo $success_message;
    ?>

    <form method="post">
        <p>Make:
            <input name="make">
        </p>
        <p>Year:
            <input size="40" name="year">
        </p>
        <p>Mileage:
            <input size="40" name="mileage">
        </p>
        <p>
            <input type="submit" value="Add" name="Add" />
            <input type="submit" value="Logout" name="logout" />
        </p>
    </form>

    <h2>Automobiles</h2>
    <ul>
        <?php
            if (empty($rows)) { // Kiểm tra nếu không có hàng nào
                echo "<li>No rows found</li>"; // <-- Thông báo này
            } else {
                foreach ($rows as $row) {
                    echo "<li> ";
                    echo htmlentities($row['year']) . " ";
                    echo htmlentities($row['make']) . " / ";
                    echo htmlentities($row['mileage']);
                    echo "</li>";
                }
            }
        ?>
    </ul>
</body>
</html>