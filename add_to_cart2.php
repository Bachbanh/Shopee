<?php
session_start();
include 'includes/pdo.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Bạn cần phải đăng nhập để thêm sản phẩm vào giỏ hàng.'); window.location.href='login.php';</script>";
    exit;
}

// Kiểm tra dữ liệu POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $user_id = $_SESSION['user_id']; // Lấy user_id từ session sau khi người dùng đăng nhập

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $checkCart = $pdo->prepare("SELECT * FROM Cart WHERE user_id = :user_id AND product_id = :product_id");
    $checkCart->execute(['user_id' => $user_id, 'product_id' => $product_id]);
    
    if ($checkCart->rowCount() > 0) {
        // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
        $cartItem = $checkCart->fetch();
        $newQuantity = $cartItem['quantity'] + $quantity;
        $updateCart = $pdo->prepare("UPDATE Cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id");
        $updateCart->execute(['quantity' => $newQuantity, 'user_id' => $user_id, 'product_id' => $product_id]);
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới
        $addToCart = $pdo->prepare("INSERT INTO Cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)");
        $addToCart->execute(['user_id' => $user_id, 'product_id' => $product_id, 'quantity' => $quantity]);
    }

    // Thông báo và chuyển hướng về trang giỏ hàng hoặc trang sản phẩm
    echo "<script>alert('Sản phẩm đã được thêm vào giỏ hàng!');</script>";
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Truy vấn để tính tổng số loại sản phẩm và tổng giá tiền
    $query = $pdo->prepare("
        SELECT 
            COUNT(Cart.product_id) AS total_products,  -- Số loại sản phẩm khác nhau
            SUM(Cart.quantity * Product.price) AS total_price  -- Tổng giá trị giỏ hàng
        FROM Cart 
        JOIN Product ON Cart.product_id = Product.product_id
        WHERE Cart.user_id = :user_id;
    ");

    $query->execute(['user_id' => $user_id]);

    // Lấy kết quả
    $result = $query->fetch();
    $totalProducts = $result['total_products'];  // Số loại sản phẩm khác nhau
    $totalPrice = $result['total_price'];        // Tổng giá trị giỏ hàng

    // Chuyển hướng trở lại trang giỏ hàng
    // header("Location: single.php");
    // exit();
} else {
    $totalProducts = 0;  // Nếu chưa đăng nhập, giỏ hàng trống
    $totalPrice = 0;
    // Chuyển hướng trở lại trang giỏ hàng
    header("Location: login.php");
    exit();  
} 
// Chuyển hướng trở lại trang giỏ hàng
// header("Location: single.php");
// exit();
?>
