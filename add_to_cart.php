<?php
session_start();
include 'includes/pdo.php'; // Kết nối cơ sở dữ liệu từ pdo.php

// Lấy thông tin từ form
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];
$quantity = $_POST['quantity'];

// Kiểm tra xem sản phẩm đã có trong giỏ hàng hay chưa
$checkCart = $pdo->prepare("SELECT * FROM Cart WHERE user_id = :user_id AND product_id = :product_id");
$checkCart->execute(['user_id' => $user_id, 'product_id' => $product_id]);

if ($checkCart->rowCount() > 0) {
    // Nếu sản phẩm đã tồn tại trong giỏ hàng, cập nhật số lượng
    $updateCart = $pdo->prepare("UPDATE Cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id");
    $updateCart->execute(['quantity' => $quantity, 'user_id' => $user_id, 'product_id' => $product_id]);
} else {
    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới
    $addToCart = $pdo->prepare("INSERT INTO Cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
    $addToCart->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
}

// Chuyển hướng về trang single.php sau khi thêm vào giỏ hàng thành công
header("Location: single.php?product_id=$product_id");
exit();  // Dừng quá trình xử lý sau khi chuyển hướng
?>
