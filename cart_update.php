<?php
session_start();
include 'includes/pdo.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];
    $user_id = $_SESSION['user_id'];

    // Lấy số lượng hiện tại từ database
    $stmt = $pdo->prepare("SELECT quantity FROM Cart WHERE user_id = :user_id AND product_id = :product_id");
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    $cart_item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cart_item) {
        $quantity = $cart_item['quantity'];

        // Cập nhật số lượng dựa trên hành động
        if ($action == 'increase') {
            $quantity += 1;
        } elseif ($action == 'decrease' && $quantity > 1) {
            $quantity -= 1;
        }

        // Cập nhật số lượng mới vào database
        $stmt = $pdo->prepare("UPDATE Cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $stmt->execute(['quantity' => $quantity, 'user_id' => $user_id, 'product_id' => $product_id]);

        // Chuyển hướng trở lại trang cart
        header("Location: cart_details.php");
        exit();
    }
}
?>
