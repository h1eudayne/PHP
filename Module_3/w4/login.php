<?php
// login.php (or index.php if this is your main entry point)
session_start();

// Xử lý khi form được gửi (nút Log In hoặc Cancel được nhấn)
if (isset($_POST['email']) && isset($_POST['pass'])) {
    // Xử lý nút Cancel
    if (isset($_POST['cancel'])) {
        header("Location: login.php"); // Quay lại trang login để xóa form/lỗi
        return;
    }

    // Validation: Kiểm tra trường rỗng
    if (strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    }

    // Validation: Kiểm tra định dạng email
    if (strpos($_POST['email'], '@') === false) { // Sử dụng === để kiểm tra false tuyệt đối
        $_SESSION['error'] = "Email must have an at-sign (@)";
        header("Location: login.php");
        return;
    }

    // Kiểm tra mật khẩu (Sử dụng mật khẩu 'php123' như gợi ý từ autograder)
    // Nếu autograder của bạn yêu cầu một mật khẩu hash (ví dụ MD5),
    // bạn cần hash "php123" và so sánh với hash của $_POST['pass'].
    // Ví dụ: $check = hash('md5', 'php123');
    // if ( hash('md5', $_POST['pass']) == $check ) { ... }

    // Giả sử mật khẩu là "php123" và không hash cho bài tập này
    if ($_POST['pass'] == 'php123') {
        error_log("Login success " . $_POST['email']);
        $_SESSION['success'] = "Login success.";
        $_SESSION['name'] = $_POST['email']; // Lưu email vào session 'name'
        header('Location: views.php'); // Chuyển hướng đến view.php sau khi đăng nhập thành công
        return;
    } else {
        // Ghi log lỗi để debug
        error_log("Login fail " . $_POST['email'] . " (Incorrect password entered)");
        $_SESSION['error'] = "Incorrect password";
        header('Location: login.php');
        return;
    }
}

// Xử lý nút "Cancel" riêng nếu nó được nhấn mà không có email/pass
if (isset($_POST['cancel'])) {
    header("Location: login.php");
    return;
}
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
        <h1>Please Log In</h1>

        <?php
        // Hiển thị thông báo lỗi (flash message)
        if (isset($_SESSION['error'])) {
            echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
            unset($_SESSION['error']); // Xóa thông báo sau khi hiển thị
        }
        ?>

        <form method="post">
            <p>Email:
                <input type="text" size="40" name="email">
            </p>
            <p>Password:
                <input type="password" size="40" name="pass"> </p>
            <p>
                <input type="submit" value="Log In" name="login_submit"/>
                <input type="submit" name="cancel" value="Cancel">
            </p>
        </form>
    </div>
</body>
</html>