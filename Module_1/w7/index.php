<?php
// Kiểm tra nếu có giá trị MD5 được nhập vào
if (isset($_GET['md5'])) {
    // Lấy giá trị MD5 từ tham số GET
    $md5_hash = $_GET['md5'];

    // Bắt đầu tính thời gian
    $start_time = microtime(true);
    $found = false;

    // Lặp qua tất cả các mã PIN từ 0000 đến 9999
    for ($i = 0; $i <= 9999; $i++) {
        // Thêm dẫn số 0 vào trước nếu mã PIN có ít hơn 4 chữ số
        $pin = str_pad($i, 4, "0", STR_PAD_LEFT);

        // Tính băm MD5 của mã PIN
        $hashed_pin = hash('md5', $pin);

        // In ra 15 lần thử đầu tiên để dễ dàng theo dõi
        if ($i < 15) {
            echo "Trying PIN: $pin => Hash: $hashed_pin<br>";
        }

        // Kiểm tra xem băm có khớp với giá trị MD5 đã cho không
        if ($hashed_pin === $md5_hash) {
            echo "PIN Found: $pin<br>";
            $found = true;
            break;
        }
    }

    // Nếu không tìm thấy mã PIN
    if (!$found) {
        echo "PIN: Không tìm thấy<br>";
    }

    // Tính thời gian đã trôi qua và in ra
    $end_time = microtime(true);
    $execution_time = $end_time - $start_time;
    echo "Thời gian đã trôi qua: " . number_format($execution_time, 4) . " giây<br>";
} else {
    echo "Vui lòng nhập một giá trị MD5 trong URL, ví dụ: ?md5=0bd65e799153554726820ca639514029";
}
?>

<html>
    <head>
        <title>MD5 Password Cracking</title>
    </head>
    <body>
        <h1>Crack MD5 Hash</h1>
        <form method="get" action="">
            <label for="md5">Nhập MD5 hash: </label>
            <input type="text" name="md5" size="40" required>
            <input type="submit" value="Crack Hash">
        </form>
    </body>
</html>
