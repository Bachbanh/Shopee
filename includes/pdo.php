<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=shopee2', 'root', ''); // Thay đổi tên người dùng và mật khẩu nếu cần
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>