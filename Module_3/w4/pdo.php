<?php
$hostName = "localhost";
$dbName = "misc";
$userName = "fred";   // Tên người dùng MySQL
$password = "zap";

try {
    $pdo = new PDO("mysql:host=$hostName;dbname=$dbName", $userName, $password);

    //ERRMODE_SILENT is default.
    //ERRMODE_WARNING will still keep executing code.
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Ghi lỗi chi tiết vào error log của server để debug
    error_log('Database connection failed: ' . $e->getMessage());
    // Dừng chương trình và hiển thị thông báo lỗi thân thiện cho người dùng
    die('Database connection error. Please try again later.');
}
?>