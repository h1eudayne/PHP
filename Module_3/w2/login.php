<?php
// login.php

// Bắt đầu session ở đầu file.
session_start();

// Không cần require_once "pdo.php"; nữa vì chúng ta không dùng DB cho xác thực đăng nhập.
// require_once "pdo.php"; 

// Kiểm tra xem form có được gửi đi với trường 'who' (email) và 'pass' (mật khẩu) hay không.
if (isset($_POST['who']) && isset($_POST['pass'])) {

    // --- Bắt đầu quá trình kiểm tra và xử lý dữ liệu đầu vào ---

    // 1. Kiểm tra xem email và mật khẩu có bị bỏ trống không.
    if (empty($_POST['who']) || empty($_POST['pass'])) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    } 
    // 2. Kiểm tra định dạng email (phải chứa ký tự '@').
    elseif (strpos($_POST['who'], '@') === false) {
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    } 
    // 3. Nếu dữ liệu đầu vào hợp lệ, tiến hành xác thực mật khẩu hardcoded.
    else {
        // Mật khẩu cứng nhắc được yêu cầu bởi bài tập.
        $salt = 'XyZzy12*_'; // Giá trị salt này thường được cung cấp trong bài tập
        $stored_hash = hash('md5', $salt . 'php123'); // Ví dụ hash của 'php123' với salt

        // So sánh mật khẩu người dùng nhập vào (sau khi được hash với salt)
        // với mật khẩu hash cứng nhắc.
        if (hash('md5', $salt . $_POST['pass']) == $stored_hash) {
            // Đăng nhập thành công.
            error_log("Login success " . $_POST['who']); // Vẫn có thể log
            
            // Lưu tên người dùng (email) vào session.
            $_SESSION['name'] = $_POST['who']; 
            
            // Chuyển hướng đến trang 'autos.php' với email là tham số GET 'name'.
            header("Location: autos.php?name=" . urlencode($_POST['who']));
            return; // Dừng script ngay lập tức.
        } else { 
            // Đăng nhập thất bại (mật khẩu sai).
            // Ghi log lỗi vào file log của server.
            error_log("Login fail " . $_POST['who'] . " - Incorrect password entered.");
            
            // Lưu thông báo lỗi "Incorrect password" vào session.
            $_SESSION['error'] = "Incorrect password"; 
            // Chuyển hướng người dùng trở lại trang đăng nhập.
            header("Location: login.php"); 
            return; 
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>5bd41f22</title> 
</head>
<body>
    <h1>Please Login</h1>

    <?php
    // Hiển thị thông báo lỗi từ session (nếu có).
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red">' . htmlentities($_SESSION['error']) . '</p>';
        unset($_SESSION['error']); 
    }
    // Thông báo thành công không cần thiết trên trang đăng nhập vì sẽ chuyển hướng.
    // if (isset($_SESSION['success'])) {
    //     echo '<p style="color: green">' . htmlentities($_SESSION['success']) . '</p>';
    //     unset($_SESSION['success']);
    // }
    ?>

    <form method="post">
        <p>Email:
            <input type="text" size="40" name="who">
        </p>
        <p>Password:
            <input type="password" size="40" name="pass"> 
        </p>
        <p>
            <input type="submit" value="Log In" />
            <a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">Refresh</a>
        </p>
    </form>
</body>
</html>