<?php
session_start();
include 'includes/pdo.php'; // Kết nối cơ sở dữ liệu

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Xóa sản phẩm khỏi giỏ hàng dựa trên product_id và user_id
    $stmt = $pdo->prepare("DELETE FROM Cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);

    // Chuyển hướng trở lại trang giỏ hàng
    header("Location: cart_details.php");
    exit();
}
?>
