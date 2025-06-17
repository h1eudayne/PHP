<?php
// view.php
session_start();
require_once "pdo.php"; // Include file kết nối PDO

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['name'])) {
    die('Not logged in'); // Nếu chưa đăng nhập, dừng ngay lập tức
}

// Lấy tên người dùng từ session để hiển thị trên tiêu đề
$name = $_SESSION['name'];
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
        <h1>Tracking Autos for <?php echo htmlentities($name); ?></h1>

        <?php
        // HIỂN THỊ THÔNG BÁO THÀNH CÔNG (FLASH MESSAGE) NẾU CÓ (từ add.php)
        if (isset($_SESSION['success'])) {
            echo '<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n";
            unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
        }
        ?>

        <h2>Automobiles</h2>
        <?php
        $stmt_fetched = false; // Biến cờ để kiểm soát việc hiển thị danh sách

        if (isset($pdo) && $pdo instanceof PDO) {
            try {
                // Truy vấn tất cả các xe ô tô, chỉ lấy các cột cần thiết
                $stmt = $pdo->query("SELECT auto_id, make, year, mileage FROM autos ORDER BY make");
                $stmt_fetched = true; // Đánh dấu là truy vấn thành công
            } catch (PDOException $e) {
                // Xử lý lỗi truy vấn nếu có
                error_log('Error querying autos: ' . $e->getMessage());
                echo '<p style="color: red;">Error retrieving automobiles. Please try again later.</p>';
            }
        } else {
            echo '<p style="color: red;">Database connection issue. Please check configuration.</p>';
        }

        // Chỉ hiển thị danh sách nếu truy vấn thành công VÀ có bản ghi
        if ($stmt_fetched && $stmt->rowCount() > 0) {
            echo '<ul>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Đảm bảo định dạng này chính xác: "YEAR MAKE / MILEAGE"
                echo '<li>' . htmlentities($row['year']) . ' ' . htmlentities($row['make']) . ' / ' . htmlentities($row['mileage']) . '</li>' . "\n";
            }
            echo '</ul>';
        } elseif ($stmt_fetched) { // Nếu truy vấn thành công nhưng không có hàng nào
            echo '<p>No automobiles found.</p>';
        }
        ?>

        <p>
            <a href="add.php">Add New</a> |
            <a href="logout.php">Logout</a>
        </p>
    </div>
</body>
</html>