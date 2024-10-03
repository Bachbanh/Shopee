<?php
session_start();
include 'includes/pdo.php'; // Kết nối cơ sở dữ liệu từ pdo.php

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang login.php
    header("Location: login.php");
    exit;
}

// Lấy thông tin email từ session để hiển thị
$user_email = $_SESSION['user_email'];

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
} else {
    $totalProducts = 0;  // Nếu chưa đăng nhập, giỏ hàng trống
    $totalPrice = 0;
} 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gio hang</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="assets/css/single.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/cart_details.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets\css\fontawesome-free-6.5.2-web\css\all.css">
    <link rel="icon" type="image/png" sizes="32x32" href="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/icon_favicon_1_32.0Wecxv.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/icon_favicon_1_96.wI1aMs.png">
</head>
<body> 
    <div class="app">
        <!-- Mini chat  -->
        <div id="shopee-mini-chat">
            <div class="shopee-mini-chat-khoi">
                <div class="shopee-mini-chat-box">
                    <i>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill="#ee4d2d" d="M18 6.07a1 1 0 01.993.883L19 7.07v10.365a1 1 0 01-1.64.768l-1.6-1.333H6.42a1 1 0 01-.98-.8l-.016-.117-.149-1.783h9.292a1.8 1.8 0 001.776-1.508l.018-.154.494-6.438H18zm-2.78-4.5a1 1 0 011 1l-.003.077-.746 9.7a1 1 0 01-.997.923H4.24l-1.6 1.333a1 1 0 01-.5.222l-.14.01a1 1 0 01-.993-.883L1 13.835V2.57a1 1 0 011-1h13.22zm-4.638 5.082c-.223.222-.53.397-.903.526A4.61 4.61 0 018.2 7.42a4.61 4.61 0 01-1.48-.242c-.372-.129-.68-.304-.902-.526a.45.45 0 00-.636.636c.329.33.753.571 1.246.74A5.448 5.448 0 008.2 8.32c.51 0 1.126-.068 1.772-.291.493-.17.917-.412 1.246-.74a.45.45 0 00-.636-.637z">
                            </path>
                        </svg>
                    </i>
                    <i style="height: 22px; width: 44px;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 44 22">
                            <path fill="#ee4d2d" d="M9.286 6.001c1.161 0 2.276.365 3.164 1.033.092.064.137.107.252.194.09.085.158.064.203 0 .046-.043.182-.194.251-.26.182-.17.433-.43.752-.752a.445.445 0 00.159-.323c0-.172-.092-.3-.227-.365A7.517 7.517 0 009.286 4C5.278 4 2 7.077 2 10.885s3.256 6.885 7.286 6.885a7.49 7.49 0 004.508-1.484l.022-.043a.411.411 0 00.046-.71v-.022a25.083 25.083 0 00-.957-.946.156.156 0 00-.227 0c-.933.796-2.117 1.205-3.392 1.205-2.846 0-5.169-2.196-5.169-4.885C4.117 8.195 6.417 6 9.286 6zm32.27 9.998h-.736c-.69 0-1.247-.54-1.247-1.209v-3.715h1.96a.44.44 0 00.445-.433V9.347h-2.45V7.035c-.021-.043-.066-.065-.111-.043l-1.603.583a.423.423 0 00-.29.41v1.362h-1.781v1.295c0 .238.2.433.445.433h1.337v4.19c0 1.382 1.158 2.505 2.583 2.505H42v-1.339a.44.44 0 00-.445-.432zm-21.901-6.62c-.739 0-1.41.172-2.013.496V4.43a.44.44 0 00-.446-.43h-1.788v13.77h2.234v-4.303c0-1.076.895-1.936 2.013-1.936 1.117 0 2.01.86 2.01 1.936v4.239h2.234v-4.561l-.021-.043c-.202-2.088-2.012-3.723-4.223-3.723zm10.054 6.785c-1.475 0-2.681-1.12-2.681-2.525 0-1.383 1.206-2.524 2.681-2.524 1.476 0 2.682 1.12 2.682 2.524 0 1.405-1.206 2.525-2.682 2.525zm2.884-6.224v.603a4.786 4.786 0 00-2.985-1.035c-2.533 0-4.591 1.897-4.591 4.246 0 2.35 2.058 4.246 4.59 4.246 1.131 0 2.194-.388 2.986-1.035v.604c0 .237.203.431.453.431h1.356V9.508h-1.356c-.25 0-.453.173-.453.432z" d="M9.286 6.001c1.161 0 2.276.365 3.164 1.033.092.064.137.107.252.194.09.085.158.064.203 0 .046-.043.182-.194.251-.26.182-.17.433-.43.752-.752a.445.445 0 00.159-.323c0-.172-.092-.3-.227-.365A7.517 7.517 0 009.286 4C5.278 4 2 7.077 2 10.885s3.256 6.885 7.286 6.885a7.49 7.49 0 004.508-1.484l.022-.043a.411.411 0 00.046-.71v-.022a25.083 25.083 0 00-.957-.946.156.156 0 00-.227 0c-.933.796-2.117 1.205-3.392 1.205-2.846 0-5.169-2.196-5.169-4.885C4.117 8.195 6.417 6 9.286 6zm32.27 9.998h-.736c-.69 0-1.247-.54-1.247-1.209v-3.715h1.96a.44.44 0 00.445-.433V9.347h-2.45V7.035c-.021-.043-.066-.065-.111-.043l-1.603.583a.423.423 0 00-.29.41v1.362h-1.781v1.295c0 .238.2.433.445.433h1.337v4.19c0 1.382 1.158 2.505 2.583 2.505H42v-1.339a.44.44 0 00-.445-.432zm-21.901-6.62c-.739 0-1.41.172-2.013.496V4.43a.44.44 0 00-.446-.43h-1.788v13.77h2.234v-4.303c0-1.076.895-1.936 2.013-1.936 1.117 0 2.01.86 2.01 1.936v4.239h2.234v-4.561l-.021-.043c-.202-2.088-2.012-3.723-4.223-3.723zm10.054 6.785c-1.475 0-2.681-1.12-2.681-2.525 0-1.383 1.206-2.524 2.681-2.524 1.476 0 2.682 1.12 2.682 2.524 0 1.405-1.206 2.525-2.682 2.525zm2.884-6.224v.603a4.786 4.786 0 00-2.985-1.035c-2.533 0-4.591 1.897-4.591 4.246 0 2.35 2.058 4.246 4.59 4.246 1.131 0 2.194-.388 2.986-1.035v.604c0 .237.203.431.453.431h1.356V9.508h-1.356c-.25 0-.453.173-.453.432z">
                            </path>
                        </svg>
                    </i>
                </div>
            </div>
        </div>

        <!-- Header -->
        <header class="header">
            <div class="gird">
                <nav class="header__navbar">
                    <ul class="header__navbar-list">
                        <li class="header__navbar-item header__navbar-item--gach">
                            Trở thành người Bán Shopee
                        </li>
                        <li class="header__navbar-item header__navbar-item--gach header__navbar-item--qr">
                            Tải ứng dụng
                            <div class="header__qr">
                                <img src="assets/img/header-qr/qrshoppee.png" alt="" class="header__qr_main">
                                <div class="header__qr_apps">
                                    <a href="" class="header__qr-link">
                                        <img src="assets/img/header-qr/ggplay.png" alt="" class="header__qr_apps--store">
                                    </a>
                                    <a href="" class="header__qr-link">
                                        <img src="assets/img/header-qr/appstore.png" alt="" class="header__qr_apps--store">
                                    </a>
                                </div>
                            </div>
                        </li>
                        <li class="header__navbar-item">
                            <span class="header__navbar-item-no-pointer">Kết nối</span>
                            <a href="" class="header__navbar-item-icon-link"><i style="color: white; padding-right: 0;" class="fa-brands fa-facebook"></i></a>
                            <a href="" class="header__navbar-item-icon-link"><i class="fa-brands fa-instagram" style="color: #ffffff; font-size: 1.65rem;"></i></a>
                        </li>
                    </ul>

                    <ul class="header__navbar-list">
                        <li class="header__navbar-item header__navbar-item--has-notify">
                            <a href="" class="header__navbar-item-link">
                                <i class="fa-regular fa-bell" style="color: #ffffff;"></i>
                                Thông báo
                            </a>
                            <div class="header__notify">
                                <header class="header__notify-header">
                                    <h3>Thông báo mới nhận</h3>
                                </header>
                                <ul class="header__notify-list">
                                    <li class="header__notify-item">
                                        <a href="" class="header__notify-link header__notify-link--viewed">
                                            <img src="assets/img/anhthongbao.png" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Gia nhập Shopee thật là thích!</span>
                                                <span class="header__notify-mota">Shopee có quà tặng bất ngờ đây:  </span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="header__notify-item">
                                        <a href="" class="header__notify-link header__notify-link--viewed">
                                            <img src="assets/img/anhthongbao.png" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Gia nhập Shopee thật là thích!</span>
                                                <span class="header__notify-mota">Shopee có quà tặng bất ngờ đây: </span>
                                            </div>
                                        </a>
                                    </li>
                                    <li class="header__notify-item">
                                        <a href="" class="header__notify-link header__notify-link--viewed">
                                            <img src="assets/img/anhthongbao.png" alt="" class="header__notify-img">
                                            <div class="header__notify-info">
                                                <span class="header__notify-name">Gia nhập Shopee thật là thích!</span>
                                                <span class="header__notify-mota">Shopee có quà tặng bất ngờ đây: </span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                                <footer class="header__notify-footer">
                                    <a href="" class="header__notify-footer-btn">Xem tất cả</a>
                                </footer>
                            </div>
                        </li>
                        <li class="header__navbar-item">
                            <a href="" class="header__navbar-item-link">
                                <i class="fa-regular fa-circle-question" style="color: #ffffff;"></i>
                                Trợ giúp
                            </a>
                        </li>
                        <li class="header__navbar-item header__navbar-item--strong header__navbar-item--gach"><a style="color: #fff;" href="logout.php">Đăng xuất</a></li>
                        <li class="header__navbar-item header__navbar-item--strong"><a style="color: #fff;" href=""> <?php echo $user_email; ?> </a></li>
                    </ul>
                </nav>

                <div class="header-width-search">
                    <div class="header__logo">
                        <a href="index.php">

                        <svg class="header__logo-img" viewBox="0 0 300 1" >
                                <img style="width: 150px ; height: 70px" src="assets\img\logo.png" alt="">

                            </svg>
                        </a>
                    </div>

                    <div class="header-with-search-center">
                        <div class="header__search">
                            <div class="header__search-input-wrap">
                                <input type="text" class="header__search-input" placeholder="Nhập để tìm kiếm sản phẩm">
                                <div class="header__search-his">
                                    <h3 class="header__search-his-heading">Lịch sử tìm kiếm</h3>
                                    <ul class="header__search-his-list">
                                        <li class="header__search-his-item">
                                            <a href="">Kem dưỡng da</a>
                                        </li>
                                        <li class="header__search-his-item">
                                            <a href="">Kem dưỡng da</a>
                                        </li>
                                        <li class="header__search-his-item">
                                            <a href="">Kem dưỡng da</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button class="header__search-btn-icon"><i class="fa-solid fa-magnifying-glass"></i></button>
                            <!-- <div class="header__search-select">
                                <span class="header__search-select-lable">Trong shop</span>
                                <i class="fa-solid fa-chevron-down" style="color: #000; font-size: 1rem;"></i>
                                <button class="header__search-btn-icon"><i class="fa-solid fa-magnifying-glass"></i></button>

                                <ul class="header__search-option">
                                    <li class="header__search-option-item">Trong shop <i class="fa-solid fa-check"></i></li>
                                    <li class="header__search-option-item header__search-option-item-no-chossen">Ngoài shop <i class="fa-solid fa-check"></i></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>

                    <div class="header__cart">
                        <div class="header-cart-number">
                            <div class="header-cart-number-text"><?php echo $totalProducts; ?></div>
                        </div>
                        <i class="fa-solid fa-cart-shopping" style="color: #ffffff;"></i>
                        <div class="header__cart-notify">
                            <!-- <img src="assets/img/cart.png" alt=""> -->
                            <header class="header__cart__notify-header">
                                <h3>Sản phẩm mới thêm</h3>
                            </header>
                            <ul id="header__cart__notify-list">
                                <?php 
                                // Truy vấn lấy dữ liệu sản phẩm từ cơ sở dữ liệu
                                $sql = "SELECT * FROM Cart JOIN Product ON Cart.product_id = Product.product_id WHERE Cart.user_id = :user_id";
                                $stmt = $pdo->prepare($sql);  // Sử dụng đúng tên biến $sql
                                $stmt->execute(['user_id' => $user_id]);  // Thêm tham số user_id nếu cần
                                $cart_details = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Đổi thành $cart_details
                                ?>

                                <?php foreach ($cart_details as $cart_detail): ?>  <!-- Sử dụng đúng tên biến $cart_details -->
                                    <li class="header__cart__notify-item">
                                        <a href="" class="header__cart__notify-link header__cart__notify-link--viewed">
                                            <img src="<?php echo $cart_detail['img']; ?>" alt="" class="header__cart__notify-img">
                                            <div class="header__cart__notify-info">
                                                <div class="header__cart__notify-name"><?php echo $cart_detail['description']; ?></div>
                                                <div class="header__cart__notify-price">₫<?php echo number_format($cart_detail['price'], 0, ',', '.'); ?></div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="header__cart__notify-footer">
                                <div class="header__cart__notify-footer-text"><span style="color: #000; font-size: 15px;"><?php echo $totalProducts; ?></span> Thêm Hàng Vào Giỏ</div>
                                <a href="" class="header__cart__notify-footer-btn">Xem giỏ hàng</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
          
        <!-- .cart__details__container -->
        <div class="cart__details__container">
            <main>
                <div class="cart_details__head">
                    <div class="cdh1">Sản Phẩm</div>
                    <div class="cdh2">Đơn Giá</div>
                    <div class="cdh2">Số Lượng</div>
                    <div class="cdh2">Số Tiền</div>
                    <div class="cdh2">Thao Tác</div>
                </div>

                <?php 
                    // Truy vấn lấy dữ liệu sản phẩm từ cơ sở dữ liệu
                    $sql = "SELECT * FROM Cart JOIN Product ON Cart.product_id = Product.product_id WHERE Cart.user_id = :user_id";
                    $stmt = $pdo->prepare($sql);  // Sử dụng đúng tên biến $sql
                    $stmt->execute(['user_id' => $user_id]);  // Thêm tham số user_id nếu cần
                    $cart_details = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Đổi thành $cart_details
                    ?>

                    <?php foreach ($cart_details as $cart_detail): ?>  <!-- Sử dụng đúng tên biến $cart_details -->
                        <section class="cdh-section">
                            <div class="cdh-section-1">
                                <div class="cdh-section-1-item">
                                    <a href="" class="cdh-section-1-item-link">
                                        <i class="fa-solid fa-shop"></i>
                                        <span style="margin-left: 10px;">SHOP HẠT DINH DƯỠNG 68</span>
                                    </a>
                                    <button style="outline: none; border: 0; background-color: #fff; margin-left: 11px;"><i class="fa-brands fa-rocketchat"></i></button>
                                </div>
                                <section class="cdh-section-1-item-box">
                                    <div class="cdh-section-1-item-box-div">
                                        <div class="cdh-section-1-item-box-div1">
                                            <a href="single.php?product_id=<?php echo $product['product_id']; ?>"><div style="display: flex;justify-content: center; width: 70px; padding-left:20px; padding-top: 17px"><img style="width: 100%;" src="<?php echo $cart_detail['img']; ?>" alt=""></div></a>
                                        </div>
                                        <div class="cdh-section-1-item-box-div2"><?php echo $cart_detail['description']; ?></div>
                                        <div class="cdh-section-1-item-box-div3">Miễn phí, giảm giá</div>
                                        <div class="cdh-section-1-item-box-div4">
                                            <div class="header__cart__notify-price">₫<?php echo number_format($cart_detail['price'], 0, ',', '.'); ?></div>
                                        </div>
                                        <div class="cdh-section-1-item-box-div5">
                                            <form action="cart_update.php" method="post"  style="margin-top: 24px;">
                                                <div class="GpmJtT shopee-input-quantity">
                                                    <!-- Nút giảm số lượng -->
                                                    <button type="submit" name="action" value="decrease" class="WNSVcC">
                                                        <svg enable-background="new 0 0 10 10" viewBox="0 0 10 10" x="0" y="0" class="shopee-svg-icon">
                                                            <polygon points="4.5 4.5 3.5 4.5 0 4.5 0 5.5 3.5 5.5 4.5 5.5 10 5.5 10 4.5"></polygon>
                                                        </svg>
                                                    </button>
                                                    
                                                    <!-- Input số lượng sản phẩm -->
                                                    <input type="hidden" name="product_id" value="<?php echo $cart_detail['product_id']; ?>">
                                                    <input type="text" class="WNSVcC g2m9n4" name="quantity" value="<?php echo $cart_detail['quantity']; ?>" readonly>

                                                    <!-- Nút tăng số lượng -->
                                                    <button type="submit" name="action" value="increase" class="WNSVcC">
                                                        <svg enable-background="new 0 0 10 10" viewBox="0 0 10 10" x="0" y="0" class="shopee-svg-icon icon-plus-sign">
                                                            <polygon points="10 4.5 5.5 4.5 5.5 0 4.5 0 4.5 4.5 0 4.5 0 5.5 4.5 5.5 4.5 10 5.5 10 5.5 5.5 10 5.5"></polygon>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="cdh-section-1-item-box-div4">
                                            <div class="header__cart__notify-price">
                                                <?php echo number_format($cart_detail['quantity'] * $cart_detail['price'], 0, ',', '.'); ?> VND

                                            </div>
                                        </div>
                                        <!-- <div class="cdh-section-1-item-box-div6">
                                            <button>Xoá</button>
                                        </div> -->
                                        <div class="cdh-section-1-item-box-div6">
                                            <form action="delete_from_cart.php" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xoá sản phẩm này?');">
                                                <input type="hidden" name="product_id" value="<?php echo $cart_detail['product_id']; ?>">
                                                <button type="submit" class="delete-btn">Xoá</button>
                                            </form>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </section> 
                    <?php endforeach;
                ?>
            </main>
        </div>

        <div class="pay">
            <div class="pay1">
                <div class="pay1__head">Shopee Voucher</div>
                <div class="pay1__body">
                    <label for=""></label>
                    <div class="pay1__body-text">Nhập mã giảm giá hoặc voucher của bạn vào bên dưới</div>
                    <input type="text" name="" id="">
                </div>
                <button class="btn-nhapma" type="sumbit">Nhập Mã</button>
            </div>
            <div class="pay2">
                <div class="pay1__head">Tổng Tiền</div>
                    <div class="sum_price">Tổng thanh toán (<?php echo $totalProducts; ?> sản phẩm): <span style="color: #f53e2d"><?php echo number_format($totalPrice, 0, ',', '.'); ?> VND</span></div>
                    <button class="btn__muahang" type="sumbit">Mua Hàng</button>
            </div>
        </div>

        <!-- Thanh đỏ  -->
        <div class="top-header-border-bottom"></div>

        <!-- Footer  -->
        <div class="wrap-footer">
            <!-- footer danh muc  -->
            <div class="footer-danhmuc">
                <div class="footer-danhmuc-text" style="font-weight: 700; font-size: 1.4rem; color: #0000008A;">DANH MỤC</div>
                <div class="footer-danhmuc-box-tong">
                    <div class="footer-danhmuc-box-khoi">
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-danhmuc-box-khoi">
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-danhmuc-box-khoi">
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-danhmuc-box-khoi">
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="footer-danhmuc-box-khoi">
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-danhmuc-khoi">
                            <div class="footer-danhmuc-khoi-item">
                                <div class="footer-danhmuc-khoi-item_x">
                                    <div class="footer-danhmuc-khoi-item_x-header">THỜI TRANG NAM</div>
                                </div>
                                <div class="footer-danhmuc-khoi-item-content">
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Vest và Blazer</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo Hoodie, Áo Len & Áo Nỉc</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Jeans</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Dài/Quần Âu</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Quần Short</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áo</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Lót</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Ngủ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Bộ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Vớ/Tất</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Truyền Thống</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>

                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Đồ Hóa Trang</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Phục Ngành Nghề</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Khác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Áokhoác</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Trang Sức Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Kính Mắt Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Thắt Lưng Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Cà vạt & Nơ cổ</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                    <div class="footer-danhmuc-khoi-item-contentx">
                                        <a href="">Phụ Kiện Nam</a>
                                        <span>&nbsp;|&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- End  -->
            <div class="wrap-footer-end">
                <div class="footer-end">
                    <div class="footer-end-tong-box-khoi">
                        <!-- cot1  -->
                        <div class="footer-end-tong-khoi">
                            <div class="footer-end-list-header">CHĂM SÓC KHÁCH HÀNG</div>
                            <ul class="footer-end-list">
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Trung Tâm Trợ Giúp</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Trung Tâm Trợ Giúp</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Shopee Blog</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Shopee Mall</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Hướng Dẫn Mua Hàng</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Hướng Dẫn Bán Hàng</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Thanh Toán</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Shopee Xu</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Vận Chuyển</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Trả Hàng & Hoàn Tiền</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Chăm Sóc Khách Hàng</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Chính Sách Bảo Hành</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- cot2  -->
                        <div class="footer-end-tong-khoi">
                            <div class="footer-end-list-header">VỀ SHOPEE</div>
                            <ul class="footer-end-list">
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Giới Thiệu Về Shopee Việt Nam</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Trung Tâm Trợ Giúp</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Tuyển Dụng</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Điều Khoản Shopee</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Chính Sách Bảo Mật</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Chính Hãng</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Kênh Người Bán</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Flash Sales</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Chương Trình Tiếp Thị Liên Kết Shopee</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Liên Hệ Với Truyền Thông</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- cot3  -->
                        <div class="footer-end-tong-khoi">
                            <div class="footer-end-list-header">VỀ SHOPEE</div>
                            <ul class="footer-end-list-cot3">
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/d4bbea4570b93bfd5fc652ca82a262a8" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/a0a9062ebe19b45c1ae0506f16af5c16" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/38fd98e55806c3b2e4535c4e4a6c4c08" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/bc2a874caeee705449c164be385b796c" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/2c46b83d84111ddc32cfd3b5995d9281" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/5e3f0bee86058637ff23cfdf2e14ca09" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/9263fa8c83628f5deff55e2a90758b06" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/0217f1d345587aa0a300e69e2195c492" alt="">
                                    </a>
                                </li>
    
                            </ul>
    
                            <div class="footer-end-list-header">ĐƠN VỊ VẬN CHUYỂN</div>
                            <ul class="footer-end-list-cot3">
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/vn-50009109-159200e3e365de418aae52b840f24185" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/d10b0ec09f0322f9201a4f3daf378ed2" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/vn-50009109-64f0b242486a67a3d29fd4bcf024a8c6" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/59270fb2f3fbb7cbc92fca3877edde3f" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/957f4eec32b963115f952835c779cd2c" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/0d349e22ca8d4337d11c9b134cf9fe63" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/3900aefbf52b1c180ba66e5ec91190e5" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/6e3be504f08f88a15a28a9a447d94d3d" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/b8348201b4611fc3315b82765d35fc63" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/0b3014da32de48c03340a4e4154328f6" alt="">
                                    </a>
                                </li>
                                <li class="footer-end-item-cot3">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/vn-50009109-ec3ae587db6309b791b78eb8af6793fd" alt="">
                                    </a>
                                </li>
    
                            </ul>
                        </div>
                        <!-- cot4  -->
                        <div class="footer-end-tong-khoi">
                            <div class="footer-end-list-header">THEO DÕI CHÚNG TÔI TRÊN</div>
                            <ul style="display: flex; flex-direction: column;" class="footer-end-list">
                                <li style="position: relative;" class="footer-end-item">
                                    <a href="">
                                        <img style="padding-right: 8px;" src="https://down-vn.img.susercontent.com/file/2277b37437aa470fd1c71127c6ff8eb5" alt="">
                                        <span style="position: absolute;">Facebook</span>
                                    </a>
                                </li>
                                <li style="position: relative;" class="footer-end-item">
                                    <a href="">
                                        <img style="padding-right: 8px;" src="https://down-vn.img.susercontent.com/file/5973ebbc642ceee80a504a81203bfb91" alt="">
                                        <span style="position: absolute;">Instagram</span>
                                    </a>
                                </li>
                                <li style="position: relative;" class="footer-end-item">
                                    <a href="">
                                        <img style="padding-right: 8px;" src="https://down-vn.img.susercontent.com/file/f4f86f1119712b553992a75493065d9a" alt="">
                                        <span style="position: absolute;">LinkedIn</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- cot5  -->
                        <div class="footer-end-tong-khoi">
                            <div class="footer-end-list-header">TẢI ỨNG DỤNG SHOPEE NGAY THÔI</div>
                            <div class="footer-end-list-taiungdung">
                                <div class="footer-end-list-taiungdung-qr">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/a5e589e8e118e937dc660f224b9a1472" alt="">
                                    </a>
                                </div>
                                <div class="footer-end-list-taiungdung-apps">
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/ad01628e90ddf248076685f73497c163" alt="">
                                    </a>
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/ae7dced05f7243d0f3171f786e123def" alt="">
                                    </a>
                                    <a href="">
                                        <img src="https://down-vn.img.susercontent.com/file/35352374f39bdd03b25e7b83542b2cb0" alt="">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /* footer 2024 */ -->
            <div class="wrap-footer-2024">
                <div class="footer-2024">
                    <div class="footer-2024-text1">© 2024 Shopee. Tất cả các quyền được bảo lưu.</div>
                    <div class="footer-2024-text-list">
                        <div class="footer-2024-text-item--khac">Quốc gia & Khu vực:
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Singapore</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Indonesia</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Thái Lan</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Malaysia</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Việt Nam</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Philippines</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Brazil</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">México</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Colombia</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Chile</a>
                        </div>
                        <div class="footer-2024-text-item">
                            <a href="">Đài Loan</a>
                        </div>
    
    
                    </div>
                </div>
            </div>

            <!-- footer-last -->
            <div class="wrap-footer-last">
                <div class="footer-last">
                    <div class="footer-last-khoi-1">
                        <div class="footer-last-khoi-1-item">
                            <a href="">Chính sách bảo mật</a>
                        </div>
                        <div class="footer-last-khoi-1-item">
                            <a href="">Quy chế hoạt động</a>
                        </div>
                        <div class="footer-last-khoi-1-item">
                            <a href="">Chính sách vận chuyển</a>
                        </div>
                        <div class="footer-last-khoi-1-item">
                            <a href="">CHÍNH SÁCH TRẢ HÀNG VÀ HOÀN TIỀN</a>
                        </div>
                    </div>
                    <div class="footer-last-khoi-2">
                        <div>
                            <a href="">
                                <img src="assets/img/New folder/Screenshot 2024-05-28 073933.png" alt="">
                            </a>
                        </div>
                        <div>
                            <a href="">
                                <img src="assets/img/New folder/Screenshot 2024-05-28 073933.png" alt="">
                            </a>
                        </div>
                        <div>
                            <a href="">
                                <img src="assets/img/New folder/2.png" alt="">
                            </a>
                        </div>
                    </div>
                    <div class="footer-last-khoi-2-text">Công ty TNHH Shopee</div>
                    <div class="footer-last-khoi-2-text-style2">Địa chỉ: Tầng 4-5-6, Tòa nhà Capital Place, số 29 đường Liễu Giai, Phường Ngọc Khánh, Quận Ba Đình, Thành phố Hà Nội, Việt Nam. Tổng đài hỗ trợ: 19001221 - Email: cskh@hotro.shopee.vn</div>
                    <div class="footer-last-khoi-2-text-style2">Chịu Trách Nhiệm Quản Lý Nội Dung: Nguyễn Đức Trí -  Điện thoại liên hệ: 024 73081221 (ext 4678)</div>
                    <div class="footer-last-khoi-2-text-style2">Mã số doanh nghiệp: 0106773786 do Sở Kế hoạch & Đầu tư TP Hà Nội cấp lần đầu ngày 10/02/2015</div>
                    <div class="footer-last-khoi-2-text-style2">© 2015 - Bản quyền thuộc về Công ty TNHH Shopee</div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script src="assets/js/main.js"></script> -->
    <script>
        // Chọn tất cả các nhóm 'san-pham'
        const sanPham = document.querySelectorAll('.san-pham');

        // Lặp qua từng nhóm
        sanPham.forEach(function(sp) {
            // Chọn nút cộng, trừ và ô input số lượng tương ứng trong nhóm
            const btnCong = sp.querySelector('.btnCong');
            const btnTru = sp.querySelector('.btnTru');
            const soLuong = sp.querySelector('.soLuong');

            // Sự kiện khi bấm nút cộng
            btnCong.addEventListener('click', function() {
                soLuong.value = parseInt(soLuong.value) + 1;
            });

            // Sự kiện khi bấm nút trừ
            btnTru.addEventListener('click', function() {
                if (parseInt(soLuong.value) > 1) {
                    soLuong.value = parseInt(soLuong.value) - 1;
                }
            });
        });
    </script>
    <!-- <script>
        // Bắt sự kiện khi form xóa được submit
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Ngăn chặn hành vi submit form mặc định

                // Lấy form và section cha của nút
                let form = this.closest('form');
                let section = this.closest('section');

                // Gửi form qua AJAX để xóa dữ liệu trong database
                let formData = new FormData(form);

                fetch('delete_from_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Sau khi xóa thành công, ẩn section của sản phẩm
                    section.style.display = 'none';
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script> -->
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault(); // Ngăn chặn hành vi mặc định

                // Lấy form và section cdh-section (là thẻ cha của button "Xoá")
                let form = this.closest('form');
                let sectionToDelete = this.closest('.cdh-section');  // Tìm thẻ cha .cdh-section

                // Gửi form qua AJAX để xóa dữ liệu trong database
                let formData = new FormData(form);

                fetch('delete_from_cart.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    // Sau khi xóa thành công, ẩn thẻ cha .cdh-section
                    sectionToDelete.style.display = 'none';
                })
                .catch(error => console.error('Error:', error));
            });
        });
    </script>

</body>
</html>