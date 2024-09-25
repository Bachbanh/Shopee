<?php
session_start();
include 'includes/pdo.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang login.php
    header("Location: login.php");
    exit;
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
} else {
    $totalProducts = 0;  // Nếu chưa đăng nhập, giỏ hàng trống
    $totalPrice = 0;
} 

// Lấy thông tin email từ session để hiển thị
$user_email = $_SESSION['user_email'];

// Lấy thông tin sản phẩm dựa trên product_id từ URL
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM Product WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Kiểm tra xem sản phẩm có tồn tại hay không
    if (!$product) {
        echo "Sản phẩm không tồn tại.";
        exit;
    }
} else {
    echo "Không tìm thấy sản phẩm.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Single</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets\css\fontawesome-free-6.5.2-web\css\all.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/single.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="icon" type="image/png" sizes="32x32" href="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/icon_favicon_1_32.0Wecxv.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/icon_favicon_1_96.wI1aMs.png">
    <!-- <script>showTrangSingle()</script> -->
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
                        <svg class="header__logo-img" viewBox="0 0 192 65" >
                            <g fill-rule="evenodd">
                                <path fill="#ffffff" d="M35.6717403 44.953764c-.3333497 2.7510509-2.0003116 4.9543414-4.5823845 6.0575984-1.4379707.6145919-3.36871.9463856-4.896954.8421628-2.3840266-.0911143-4.6237865-.6708937-6.6883352-1.7307424-.7375522-.3788551-1.8370513-1.1352759-2.6813095-1.8437757-.213839-.1790053-.239235-.2937577-.0977428-.4944671.0764015-.1151823.2172535-.3229831.5286218-.7791994.45158-.6616533.5079208-.7446018.5587128-.8221779.14448-.2217688.3792333-.2411091.6107855-.0588804.0243289.0189105.0243289.0189105.0426824.0333083.0379873.0294402.0379873.0294402.1276204.0990653.0907002.0706996.14448.1123887.166248.1287205 2.2265285 1.7438508 4.8196989 2.7495466 7.4376251 2.8501162 3.6423042-.0496401 6.2615109-1.6873341 6.7308041-4.2020035.5160305-2.7675977-1.6565047-5.1582742-5.9070334-6.4908212-1.329344-.4166762-4.6895175-1.7616869-5.3090528-2.1250697-2.9094471-1.7071043-4.2697358-3.9430584-4.0763845-6.7048539.296216-3.8283059 3.8501677-6.6835796 8.340785-6.702705 2.0082079-.004083 4.0121475.4132378 5.937338 1.2244562.6816382.2873109 1.8987274.9496089 2.3189359 1.2633517.2420093.1777159.2898136.384872.1510957.60836-.0774686.12958-.2055158.3350171-.4754821.7632974l-.0029878.0047276c-.3553311.5640922-.3664286.5817134-.447952.7136572-.140852.2144625-.3064598.2344475-.5604202.0732783-2.0600669-1.3839063-4.3437898-2.0801572-6.8554368-2.130442-3.126914.061889-5.4706057 1.9228561-5.6246892 4.4579402-.0409751 2.2896772 1.676352 3.9613243 5.3858811 5.2358503 7.529819 2.4196871 10.4113092 5.25648 9.869029 9.7292478M26.3725216 5.42669372c4.9022893 0 8.8982174 4.65220288 9.0851664 10.47578358H17.2875686c.186949-5.8235807 4.1828771-10.47578358 9.084953-10.47578358m25.370857 11.57065968c0-.6047069-.4870064-1.0948761-1.0875481-1.0948761h-11.77736c-.28896-7.68927544-5.7774923-13.82058185-12.5059489-13.82058185-6.7282432 0-12.2167755 6.13130641-12.5057355 13.82058185l-11.79421958.0002149c-.59136492.0107446-1.06748731.4968309-1.06748731 1.0946612 0 .0285807.00106706.0569465.00320118.0848825H.99995732l1.6812605 37.0613963c.00021341.1031483.00405483.2071562.01173767.3118087.00170729.0236381.003628.0470614.00554871.0704847l.00362801.0782207.00405483.004083c.25545428 2.5789222 2.12707837 4.6560709 4.67201764 4.7519129l.00576212.0055872h37.4122078c.0177132.0002149.0354264.0004298.0531396.0004298.0177132 0 .0354264-.0002149.0531396-.0004298h.0796027l.0017073-.0015043c2.589329-.0706995 4.6867431-2.1768587 4.9082648-4.787585l.0012805-.0012893.0017073-.0350275c.0021341-.0275062.0040548-.0547975.0057621-.0823037.0040548-.065757.0068292-.1312992.0078963-.1964115l1.8344904-37.207738h-.0012805c.001067-.0186956.0014939-.0376062.0014939-.0565167M176.465457 41.1518926c.720839-2.3512494 2.900423-3.9186779 5.443734-3.9186779 2.427686 0 4.739107 1.6486899 5.537598 3.9141989l.054826.1556978h-11.082664l.046506-.1512188zm13.50267 3.4063683c.014933.0006399.014933.0006399.036906.0008531.021973-.0002132.021973-.0002132.044372-.0008531.53055-.0243144.950595-.4766911.950595-1.0271786 0-.0266606-.000853-.0496953-.00256-.0865936.000427-.0068251.000427-.020262.000427-.0635588 0-5.1926268-4.070748-9.4007319-9.09145-9.4007319-5.020488 0-9.091235 4.2081051-9.091235 9.4007319 0 .3871116.022399.7731567.067838 1.1568557l.00256.0204753.01408.1013102c.250022 1.8683731 1.047233 3.5831812 2.306302 4.9708108-.00064-.0006399.00064.0006399.007253.0078915 1.396026 1.536289 3.291455 2.5833031 5.393601 2.9748936l.02752.0053321v-.0027727l.13653.0228215c.070186.0119439.144211.0236746.243409.039031 2.766879.332724 5.221231-.0661182 7.299484-1.1127057.511777-.2578611.971928-.5423827 1.37064-.8429007.128211-.0968312.243622-.1904632.34346-.2781231.051412-.0452164.092372-.083181.114131-.1051493.468898-.4830897.498124-.6543572.215249-1.0954297-.31146-.4956734-.586228-.9179769-.821744-1.2675504-.082345-.1224254-.154023-.2267215-.214396-.3133151-.033279-.0475624-.033279-.0475624-.054399-.0776356-.008319-.0117306-.008319-.0117306-.013866-.0191956l-.00256-.0038391c-.256208-.3188605-.431565-.3480805-.715933-.0970445-.030292.0268739-.131624.1051493-.14997.1245582-1.999321 1.775381-4.729508 2.3465571-7.455854 1.7760208-.507724-.1362888-.982595-.3094759-1.419919-.5184948-1.708127-.8565509-2.918343-2.3826022-3.267563-4.1490253l-.02752-.1394881h13.754612zM154.831964 41.1518926c.720831-2.3512494 2.900389-3.9186779 5.44367-3.9186779 2.427657 0 4.739052 1.6486899 5.537747 3.9141989l.054612.1556978h-11.082534l.046505-.1512188zm13.502512 3.4063683c.015146.0006399.015146.0006399.037118.0008531.02176-.0002132.02176-.0002132.044159-.0008531.530543-.0243144.950584-.4766911.950584-1.0271786 0-.0266606-.000854-.0496953-.00256-.0865936.000426-.0068251.000426-.020262.000426-.0635588 0-5.1926268-4.070699-9.4007319-9.091342-9.4007319-5.020217 0-9.091343 4.2081051-9.091343 9.4007319 0 .3871116.022826.7731567.068051 1.1568557l.00256.0204753.01408.1013102c.250019 1.8683731 1.04722 3.5831812 2.306274 4.9708108-.00064-.0006399.00064.0006399.007254.0078915 1.396009 1.536289 3.291417 2.5833031 5.393538 2.9748936l.027519.0053321v-.0027727l.136529.0228215c.070184.0119439.144209.0236746.243619.039031 2.766847.332724 5.22117-.0661182 7.299185-1.1127057.511771-.2578611.971917-.5423827 1.370624-.8429007.128209-.0968312.243619-.1904632.343456-.2781231.051412-.0452164.09237-.083181.11413-.1051493.468892-.4830897.498118-.6543572.215246-1.0954297-.311457-.4956734-.586221-.9179769-.821734-1.2675504-.082344-.1224254-.154022-.2267215-.21418-.3133151-.033492-.0475624-.033492-.0475624-.054612-.0776356-.008319-.0117306-.008319-.0117306-.013866-.0191956l-.002346-.0038391c-.256419-.3188605-.431774-.3480805-.716138-.0970445-.030292.0268739-.131623.1051493-.149969.1245582-1.999084 1.775381-4.729452 2.3465571-7.455767 1.7760208-.507717-.1362888-.982582-.3094759-1.419902-.5184948-1.708107-.8565509-2.918095-2.3826022-3.267311-4.1490253l-.027733-.1394881h13.754451zM138.32144123 49.7357905c-3.38129629 0-6.14681004-2.6808521-6.23169343-6.04042014v-.31621743c.08401943-3.35418649 2.85039714-6.03546919 6.23169343-6.03546919 3.44242097 0 6.23320537 2.7740599 6.23320537 6.1960534 0 3.42199346-2.7907844 6.19605336-6.23320537 6.19605336m.00172791-15.67913203c-2.21776751 0-4.33682838.7553485-6.03989586 2.140764l-.19352548.1573553V34.6208558c0-.4623792-.0993546-.56419733-.56740117-.56419733h-2.17651376c-.47409424 0-.56761716.09428403-.56761716.56419733v27.6400724c0 .4539841.10583425.5641973.56761716.5641973h2.17651376c.46351081 0 .56740117-.1078454.56740117-.5641973V50.734168l.19352548.1573553c1.70328347 1.3856307 3.82234434 2.1409792 6.03989586 2.1409792 5.27140956 0 9.54473746-4.2479474 9.54473746-9.48802964 0-5.239867-4.2733279-9.48781439-9.54473746-9.48781439M115.907646 49.5240292c-3.449458 0-6.245805-2.7496948-6.245805-6.1425854 0-3.3928907 2.79656-6.1427988 6.245805-6.1427988 3.448821 0 6.24538 2.7499081 6.24538 6.1427988 0 3.3926772-2.796346 6.1425854-6.24538 6.1425854m.001914-15.5438312c-5.28187 0-9.563025 4.2112903-9.563025 9.4059406 0 5.1944369 4.281155 9.4059406 9.563025 9.4059406 5.281657 0 9.562387-4.2115037 9.562387-9.4059406 0-5.1946503-4.280517-9.4059406-9.562387-9.4059406M94.5919049 34.1890939c-1.9281307 0-3.7938902.6198995-5.3417715 1.7656047l-.188189.1393105V23.2574169c0-.4254677-.1395825-.5643476-.5649971-.5643476h-2.2782698c-.4600414 0-.5652122.1100273-.5652122.5643476v29.2834155c0 .443339.1135587.5647782.5652122.5647782h2.2782698c.4226187 0 .5649971-.1457701.5649971-.5647782v-9.5648406c.023658-3.011002 2.4931278-5.4412923 5.5299605-5.4412923 3.0445753 0 5.516841 2.4421328 5.5297454 5.4630394v9.5430935c0 .4844647.0806524.5645628.5652122.5645628h2.2726775c.481764 0 .565212-.0824666.565212-.5645628v-9.5710848c-.018066-4.8280677-4.0440197-8.7806537-8.9328471-8.7806537M62.8459442 47.7938061l-.0053397.0081519c-.3248668.4921188-.4609221.6991347-.5369593.8179812-.2560916.3812097-.224267.551113.1668119.8816949.91266.7358184 2.0858968 1.508535 2.8774525 1.8955369 2.2023021 1.076912 4.5810275 1.646045 7.1017886 1.6975309 1.6283921.0821628 3.6734936-.3050536 5.1963734-.9842376 2.7569891-1.2298679 4.5131066-3.6269626 4.8208863-6.5794607.4985136-4.7841067-2.6143125-7.7747902-10.6321784-10.1849709l-.0021359-.0006435c-3.7356476-1.2047686-5.4904836-2.8064071-5.4911243-5.0426086.1099976-2.4715346 2.4015793-4.3179454 5.4932602-4.4331449 2.4904317.0062212 4.6923065.6675996 6.8557356 2.0598624.4562232.2767364.666607.2256796.9733188-.172263.035242-.0587797.1332787-.2012238.543367-.790093l.0012815-.0019308c.3829626-.5500403.5089793-.7336731.5403767-.7879478.258441-.4863266.2214903-.6738208-.244985-1.0046173-.459427-.3290803-1.7535544-1.0024722-2.4936356-1.2978721-2.0583439-.8211991-4.1863175-1.2199998-6.3042524-1.1788111-4.8198184.1046878-8.578747 3.2393171-8.8265087 7.3515337-.1572005 2.9703036 1.350301 5.3588174 4.5000778 7.124567.8829712.4661613 4.1115618 1.6865902 5.6184225 2.1278667 4.2847814 1.2547527 6.5186944 3.5630343 6.0571315 6.2864205-.4192725 2.4743234-3.0117991 4.1199394-6.6498372 4.2325647-2.6382344-.0549182-5.2963324-1.0217793-7.6043603-2.7562084-.0115337-.0083664-.0700567-.0519149-.1779185-.1323615-.1516472-.1130543-.1516472-.1130543-.1742875-.1300017-.4705335-.3247898-.7473431-.2977598-1.0346184.1302162-.0346012.0529875-.3919333.5963776-.5681431.8632459"></path></g></svg>
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

                        <ul class="header__muc__product">
                            <li><a href="">Săn IPhone 14 Pro Max 1k</a></li>
                            <li><a href="">Dép 0d</a></li>
                            <li><a href="">Set Đồ Nữ</a></li>
                            <li><a href="">Son</a></li>
                            <li><a href="">Áo phông</a></li>
                            <li><a href="">Đồ Ăn Vặt</a></li>
                            <li><a href="">Đơn hàng 0 đồng</a></li>
                            <li><a href="">Quần Đùi</a></li>
                            <li><a href="">Bình Giữ Nhiệt</a></li>
                            <li><a href="">8 Plus</a></li>
                            <li><a href="">Sữa Tắm</a></li>
                        </ul>
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


        <div style="height: 120px;"></div>

        <!-- Single main  -->
        <div class="single-main">
            <div class="ul-cumb">
                <a href="index.html">Home</a>
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/966fbe37fe1c72e3f2dd.svg" alt="">
                <a href="index.html">Điện thoại và phụ kiện</a>
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/966fbe37fe1c72e3f2dd.svg" alt="">
                <a href="index.html">Ốp Điện Thoại Chống Sốc Nửa Thân Người Nhện Hoạt Hình Hợp Thời Trang Tương Thích Cho iPhone 11 12 13 14 15Pro MAX 7 8 Plus X XR XS MAX SE 2020 Ốp Lưng Silicon Siêu Bảo Vệ Ốp Lưng Mềm</a>
                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/966fbe37fe1c72e3f2dd.svg" alt="">
            </div>
            
            <!-- selection product -->
            <selection id="slt-pro">
                <selection class="slt-pro-left">
                    <div style="display: flex; flex-direction: column;">
                        <div><img style="width: 100%;" src=" <?php echo $product['img'];?>" alt=""></div>
                        <div style="margin-top: 5px; display: flex;">
                            <div class="slt-pro-left-img-phu"><img src=" <?php echo $product['img'];?>" alt=""></div>
                            <div class="slt-pro-left-img-phu"><img src=" <?php echo $product['img'];?>" alt=""></div>
                            <div class="slt-pro-left-img-phu"><img src=" <?php echo $product['img'];?>" alt=""></div>
                            <div class="slt-pro-left-img-phu"><img src=" <?php echo $product['img'];?>" alt=""></div>
                            <div class="slt-pro-left-img-phu"><img src=" <?php echo $product['img'];?>" alt=""></div>
                        </div>
                    </div>

                    <div style="margin-top: 15px; display: flex; justify-content: center; align-items: center;">
                        <div style="padding: 0 30px; display: flex; align-items: center; border-right: 1px solid #ccc;">
                            <div style="color: #222; font-size: 1.6rem;">Chia sẻ:</div>
                            <i class="fa-brands fa-facebook-messenger" style="font-size: 16px;margin-left: 5px;color: #0041b3; padding: 0;"></i>
                            <i class="fa-brands fa-facebook" style="font-size: 16px;margin-left: 5px;color: #0049c7; padding: 0;"></i>
                            <i class="fa-brands fa-pinterest" style="font-size: 16px;margin-left: 5px;color: #940000; padding: 0;"></i>
                            <i class="fa-brands fa-square-x-twitter" style="font-size: 16px;margin-left: 5px;color: #3881ff; padding: 0;"></i>
                        </div>
                        <div style="padding: 0 30px; display: flex; align-items: center;">
                            <i class="fa-regular fa-heart" style="color: #ff1100; font-size: 16px; margin-right: 5px;"></i>
                            <div style="color: #222; font-size: 1.6rem;">Đã thích (956)</div>
                        </div>
                    </div>
                </selection>

                <selection class="slt-pro-right" style="padding-left: 10px">
                    <!-- Mo ta -->
                    <div style="position: relative;">
                        <div style="padding: 0px 4px; width: 60px;text-align: center;margin-right: 10px; border-radius: 2px;color: #ffffff; background-color: var(--primary-color2);">Yêu thích</div>
                        <span style="font-size: 12px; position: absolute; top: 0; line-height: 22px;"><button style="width: 60px; background-color: transparent; border: 0px;"></button> <?php echo $product['description']; ?> </span>
                    </div>
                    
                    <!-- danh gia  -->
                    <div style="margin-top: 22px; display: flex;">
                        <div style="padding-right: 15px; display: flex; align-items: center;border-right: 1px solid #ccc;">
                            <div style="margin-right: 5px; color: var(--primary-color2); border-bottom: 1px solid var(--primary-color2);">4.9</div>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                        </div>
                        <div style="padding: 0px 15px; display: flex; align-items: center;border-right: 1px solid #ccc;">
                            <div style="margin-right: 5px; color: #0041b3; border-bottom: 1px solid #0041b3; color: #222;">535</div>
                            <div style="margin-right: 5px; color: #0041b3; color: #6a6a6a;">Đánh giá</div>
                        </div>
                        <div style="padding: 0px 15px; display: flex; align-items: center;border-right: 1px solid #ccc;">
                            <div style="margin-right: 5px; color: #0041b3; color: #222;">3K</div>
                            <div style="margin-right: 5px; color: #0041b3; color: #6a6a6a;">Đã bán</div>
                        </div>
                    </div>

                    <!-- f-sale  -->
                    <div class="f_sale-main-header">
                        <div class="f_sale-main-header-time">
                            <div class="f_sale-main-header-time-logo"></div>
                            <div class="f_sale-main-header-time-real-block--main">
                                <div class="f_sale-main-header-time-real-block">00</div>
                                <div class="f_sale-main-header-time-real-block">34</div>
                                <div class="f_sale-main-header-time-real-block">37</div>
                            </div>
                        </div>
                        <a href="" class="f_sale-main-header-all">
                            Xem tất cả
                            <svg fill="var(--primary-color)" style="width: 9px; margin: 0px 0px 0px 5px;padding: 0px;" enable-background="new 0 0 11 11" viewBox="0 0 11 11" x="0" y="0" class="shopee-svg-icon icon-arrow-right">
                                <path d="m2.5 11c .1 0 .2 0 .3-.1l6-5c .1-.1.2-.3.2-.4s-.1-.3-.2-.4l-6-5c-.2-.2-.5-.1-.7.1s-.1.5.1.7l5.5 4.6-5.5 4.6c-.2.2-.2.5-.1.7.1.1.3.2.4.2z"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- 90% giam gia  -->
                    <div style="padding: 15px 20px; display: flex;">
                        <div class="slt-coat-a">₫<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                        <div class="slt-coat-b">₫<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                        <div class="slt-coat-c">90% giảm</div>
                    </div>

                    <!-- chinh sach tra hang  -->
                    <div class="stl-khoi-chose">
                        <section class="chose_1">
                            <div style="text-transform: capitalize; font-weight: 400; color: #757575;">Chính sách trả hàng</div>
                            <div style="display: flex; justify-content: center;">
                                <img style="width: 30px; margin-left: 15px; margin-right: 8px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/b69402e4275f823f7d47.svg" alt="">
                                <h3 style="text-transform: capitalize; font-weight: 400; color: #757575; line-height: 38px;">Trả hàng 15 ngày</h3>
                                <h3 style="text-transform: capitalize; font-weight: 400; color: #757575; line-height: 38px; margin-left: 15px; margin-right: 8px;">Đổi ý miễn phí</h3>
                                <img style="width: 21px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/be6f27f93268c0f88ded.svg" alt="">
                        </section>
                        <section class="chose_2">
                            <div style="width: 110px; color: #757575;">Vận chuyển</div>
                            <div style="width: 100%;">
                                <div style="height: 31px; display: flex;">
                                    <img style="width: 30px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/d9e992985b18d96aab90.png" alt="">
                                    <div style="margin-left: 10px; line-height: 31px;">Miễn phí vận chuyển</div>
                                </div>
                                <div style="display: flex;">
                                    <img style="width: 30px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/7b101a24cfe44d8eb45f.svg" alt="">
                                    <div style="display: flex; flex-wrap: wrap;">
                                        <div style="margin-right: 10px; margin-left: 10px;">Vận chuyển từ</div>
                                        <div style="margin-right: 10px;">Nước ngoài tới</div>
                                        <div style="margin-right: 10px;">Phường Yên Nghĩa</div>
                                        <div style="margin-right: 10px;">Quận Hà Đông</div>
                                        <div style="margin-right: 10px;margin-left: 10px;margin-top: 10px;">Phí vận chuyển</div>
                                        <div style="margin-right: 10px;margin-top: 10px;">0đ</div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div style="padding: 4px 4px 15px; ">
                            <div style="height: 40px; margin-bottom: 24px; display: flex;">
                                <div style="width: 110px; line-height: 40px;">Màu sắc</div>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Blue color</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Yellow color</button>
                            </div>

                            <div style="height: 135px; margin-bottom: 24px; display: flex; flex-wrap: wrap;">
                                <div style="width: 110px; line-height: 40px;">Kích thước</div>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 7/8 / SE (2020)</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 7plus / 8plus</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip x / xs</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip xr</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip xsmax</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 11</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px; margin-left: 110px;">Ip 11pro</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 11promax</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 12</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 12pro</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 12promax</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 13</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 13pro</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px; margin-left: 110px;">Ip 13promax</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14plus</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14pro</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14promax</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 15</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px; margin-left: 110px;">Ip 15pro</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 15plus</button>
                                <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 15promax</button>
                            </div>

                        </div>
                    </div>
                    <!-- <div style="margin-top: 16px; display: flex; margin-left: 27px;">
                        <div style="width: 110px; line-height: 40px;">Số lượng</div>
                        <div style="display: flex; align-items: center;">
                            <div style="margin-right: 15px;">
                                <div style="display: flex;align-items: center;background: #fff;">
                                    <button id="btnTru">-</button>
                                    <input type="" id="soLuong" value="0">
                                    <button id="btnCong">+</button>
                                </div>
                            </div>
                            <div>578 sản phẩm có sẵn</div>
                        </div>
                    </div> -->

                    <form action="add_to_cart.php" method="post" style="margin-top: 16px; display: flex; margin-left: 27px;">
                        <label for="quantity" style="width: 110px; line-height: 40px;">Số lượng:</label>
                        <div style="display: flex;align-items: center;background: #fff;">
                            <div id="btnTru">-</div>
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="number" id="soLuong" name="quantity" value="1" min="1" required class="no-arrows">
                            <div id="btnCong">+</div>
                        </div>

                        <!-- them gio hang  -->
                        <div style="margin-top: 15px; padding-bottom: 20px ;display: flex;" class="oder-prod">
                            <div style="padding-left: 20px; display: flex;">
                                <button type="submit" class="single-add-to-cart">
                                    <i class="fa-solid fa-cart-plus" style="color: var(--primary-color2);"></i>
                                    <span>Thêm Vào Giỏ Hàng</span>
                                </button>
                                <div></div>
                            </div>
                            <div style="padding-left: 20px; display: flex;">
                                <button class="single-buy-now">
                                    <span>Mua Ngay</span>
                                </button>
                                <div></div>
                            </div>
                        </div>
                    </form>


                    <div style="height: 0.1px; width: 100%; background-color: #cecece;"></div>

                    <!-- Quick đảm bảo  -->
                    <div style="height: 20px; margin-top: 25px; padding: 0 20px; display: flex; font-size: 14px;">
                        <div style="height: 20px; display: flex; width: 200px;">
                            <!-- <img style="height: 20px;" src="assets/img/logo-icon2.png" alt=""> -->
                            <!-- <svg style="font-size: 25px;" viewBox="0 0 192 65" class="shopee-svg-icon header-with-search__shopee-logo icon-shopee-logo"><g fill-rule="evenodd"><path d="M35.6717403 44.953764c-.3333497 2.7510509-2.0003116 4.9543414-4.5823845 6.0575984-1.4379707.6145919-3.36871.9463856-4.896954.8421628-2.3840266-.0911143-4.6237865-.6708937-6.6883352-1.7307424-.7375522-.3788551-1.8370513-1.1352759-2.6813095-1.8437757-.213839-.1790053-.239235-.2937577-.0977428-.4944671.0764015-.1151823.2172535-.3229831.5286218-.7791994.45158-.6616533.5079208-.7446018.5587128-.8221779.14448-.2217688.3792333-.2411091.6107855-.0588804.0243289.0189105.0243289.0189105.0426824.0333083.0379873.0294402.0379873.0294402.1276204.0990653.0907002.0706996.14448.1123887.166248.1287205 2.2265285 1.7438508 4.8196989 2.7495466 7.4376251 2.8501162 3.6423042-.0496401 6.2615109-1.6873341 6.7308041-4.2020035.5160305-2.7675977-1.6565047-5.1582742-5.9070334-6.4908212-1.329344-.4166762-4.6895175-1.7616869-5.3090528-2.1250697-2.9094471-1.7071043-4.2697358-3.9430584-4.0763845-6.7048539.296216-3.8283059 3.8501677-6.6835796 8.340785-6.702705 2.0082079-.004083 4.0121475.4132378 5.937338 1.2244562.6816382.2873109 1.8987274.9496089 2.3189359 1.2633517.2420093.1777159.2898136.384872.1510957.60836-.0774686.12958-.2055158.3350171-.4754821.7632974l-.0029878.0047276c-.3553311.5640922-.3664286.5817134-.447952.7136572-.140852.2144625-.3064598.2344475-.5604202.0732783-2.0600669-1.3839063-4.3437898-2.0801572-6.8554368-2.130442-3.126914.061889-5.4706057 1.9228561-5.6246892 4.4579402-.0409751 2.2896772 1.676352 3.9613243 5.3858811 5.2358503 7.529819 2.4196871 10.4113092 5.25648 9.869029 9.7292478M26.3725216 5.42669372c4.9022893 0 8.8982174 4.65220288 9.0851664 10.47578358H17.2875686c.186949-5.8235807 4.1828771-10.47578358 9.084953-10.47578358m25.370857 11.57065968c0-.6047069-.4870064-1.0948761-1.0875481-1.0948761h-11.77736c-.28896-7.68927544-5.7774923-13.82058185-12.5059489-13.82058185-6.7282432 0-12.2167755 6.13130641-12.5057355 13.82058185l-11.79421958.0002149c-.59136492.0107446-1.06748731.4968309-1.06748731 1.0946612 0 .0285807.00106706.0569465.00320118.0848825H.99995732l1.6812605 37.0613963c.00021341.1031483.00405483.2071562.01173767.3118087.00170729.0236381.003628.0470614.00554871.0704847l.00362801.0782207.00405483.004083c.25545428 2.5789222 2.12707837 4.6560709 4.67201764 4.7519129l.00576212.0055872h37.4122078c.0177132.0002149.0354264.0004298.0531396.0004298.0177132 0 .0354264-.0002149.0531396-.0004298h.0796027l.0017073-.0015043c2.589329-.0706995 4.6867431-2.1768587 4.9082648-4.787585l.0012805-.0012893.0017073-.0350275c.0021341-.0275062.0040548-.0547975.0057621-.0823037.0040548-.065757.0068292-.1312992.0078963-.1964115l1.8344904-37.207738h-.0012805c.001067-.0186956.0014939-.0376062.0014939-.0565167M176.465457 41.1518926c.720839-2.3512494 2.900423-3.9186779 5.443734-3.9186779 2.427686 0 4.739107 1.6486899 5.537598 3.9141989l.054826.1556978h-11.082664l.046506-.1512188zm13.50267 3.4063683c.014933.0006399.014933.0006399.036906.0008531.021973-.0002132.021973-.0002132.044372-.0008531.53055-.0243144.950595-.4766911.950595-1.0271786 0-.0266606-.000853-.0496953-.00256-.0865936.000427-.0068251.000427-.020262.000427-.0635588 0-5.1926268-4.070748-9.4007319-9.09145-9.4007319-5.020488 0-9.091235 4.2081051-9.091235 9.4007319 0 .3871116.022399.7731567.067838 1.1568557l.00256.0204753.01408.1013102c.250022 1.8683731 1.047233 3.5831812 2.306302 4.9708108-.00064-.0006399.00064.0006399.007253.0078915 1.396026 1.536289 3.291455 2.5833031 5.393601 2.9748936l.02752.0053321v-.0027727l.13653.0228215c.070186.0119439.144211.0236746.243409.039031 2.766879.332724 5.221231-.0661182 7.299484-1.1127057.511777-.2578611.971928-.5423827 1.37064-.8429007.128211-.0968312.243622-.1904632.34346-.2781231.051412-.0452164.092372-.083181.114131-.1051493.468898-.4830897.498124-.6543572.215249-1.0954297-.31146-.4956734-.586228-.9179769-.821744-1.2675504-.082345-.1224254-.154023-.2267215-.214396-.3133151-.033279-.0475624-.033279-.0475624-.054399-.0776356-.008319-.0117306-.008319-.0117306-.013866-.0191956l-.00256-.0038391c-.256208-.3188605-.431565-.3480805-.715933-.0970445-.030292.0268739-.131624.1051493-.14997.1245582-1.999321 1.775381-4.729508 2.3465571-7.455854 1.7760208-.507724-.1362888-.982595-.3094759-1.419919-.5184948-1.708127-.8565509-2.918343-2.3826022-3.267563-4.1490253l-.02752-.1394881h13.754612zM154.831964 41.1518926c.720831-2.3512494 2.900389-3.9186779 5.44367-3.9186779 2.427657 0 4.739052 1.6486899 5.537747 3.9141989l.054612.1556978h-11.082534l.046505-.1512188zm13.502512 3.4063683c.015146.0006399.015146.0006399.037118.0008531.02176-.0002132.02176-.0002132.044159-.0008531.530543-.0243144.950584-.4766911.950584-1.0271786 0-.0266606-.000854-.0496953-.00256-.0865936.000426-.0068251.000426-.020262.000426-.0635588 0-5.1926268-4.070699-9.4007319-9.091342-9.4007319-5.020217 0-9.091343 4.2081051-9.091343 9.4007319 0 .3871116.022826.7731567.068051 1.1568557l.00256.0204753.01408.1013102c.250019 1.8683731 1.04722 3.5831812 2.306274 4.9708108-.00064-.0006399.00064.0006399.007254.0078915 1.396009 1.536289 3.291417 2.5833031 5.393538 2.9748936l.027519.0053321v-.0027727l.136529.0228215c.070184.0119439.144209.0236746.243619.039031 2.766847.332724 5.22117-.0661182 7.299185-1.1127057.511771-.2578611.971917-.5423827 1.370624-.8429007.128209-.0968312.243619-.1904632.343456-.2781231.051412-.0452164.09237-.083181.11413-.1051493.468892-.4830897.498118-.6543572.215246-1.0954297-.311457-.4956734-.586221-.9179769-.821734-1.2675504-.082344-.1224254-.154022-.2267215-.21418-.3133151-.033492-.0475624-.033492-.0475624-.054612-.0776356-.008319-.0117306-.008319-.0117306-.013866-.0191956l-.002346-.0038391c-.256419-.3188605-.431774-.3480805-.716138-.0970445-.030292.0268739-.131623.1051493-.149969.1245582-1.999084 1.775381-4.729452 2.3465571-7.455767 1.7760208-.507717-.1362888-.982582-.3094759-1.419902-.5184948-1.708107-.8565509-2.918095-2.3826022-3.267311-4.1490253l-.027733-.1394881h13.754451zM138.32144123 49.7357905c-3.38129629 0-6.14681004-2.6808521-6.23169343-6.04042014v-.31621743c.08401943-3.35418649 2.85039714-6.03546919 6.23169343-6.03546919 3.44242097 0 6.23320537 2.7740599 6.23320537 6.1960534 0 3.42199346-2.7907844 6.19605336-6.23320537 6.19605336m.00172791-15.67913203c-2.21776751 0-4.33682838.7553485-6.03989586 2.140764l-.19352548.1573553V34.6208558c0-.4623792-.0993546-.56419733-.56740117-.56419733h-2.17651376c-.47409424 0-.56761716.09428403-.56761716.56419733v27.6400724c0 .4539841.10583425.5641973.56761716.5641973h2.17651376c.46351081 0 .56740117-.1078454.56740117-.5641973V50.734168l.19352548.1573553c1.70328347 1.3856307 3.82234434 2.1409792 6.03989586 2.1409792 5.27140956 0 9.54473746-4.2479474 9.54473746-9.48802964 0-5.239867-4.2733279-9.48781439-9.54473746-9.48781439M115.907646 49.5240292c-3.449458 0-6.245805-2.7496948-6.245805-6.1425854 0-3.3928907 2.79656-6.1427988 6.245805-6.1427988 3.448821 0 6.24538 2.7499081 6.24538 6.1427988 0 3.3926772-2.796346 6.1425854-6.24538 6.1425854m.001914-15.5438312c-5.28187 0-9.563025 4.2112903-9.563025 9.4059406 0 5.1944369 4.281155 9.4059406 9.563025 9.4059406 5.281657 0 9.562387-4.2115037 9.562387-9.4059406 0-5.1946503-4.280517-9.4059406-9.562387-9.4059406M94.5919049 34.1890939c-1.9281307 0-3.7938902.6198995-5.3417715 1.7656047l-.188189.1393105V23.2574169c0-.4254677-.1395825-.5643476-.5649971-.5643476h-2.2782698c-.4600414 0-.5652122.1100273-.5652122.5643476v29.2834155c0 .443339.1135587.5647782.5652122.5647782h2.2782698c.4226187 0 .5649971-.1457701.5649971-.5647782v-9.5648406c.023658-3.011002 2.4931278-5.4412923 5.5299605-5.4412923 3.0445753 0 5.516841 2.4421328 5.5297454 5.4630394v9.5430935c0 .4844647.0806524.5645628.5652122.5645628h2.2726775c.481764 0 .565212-.0824666.565212-.5645628v-9.5710848c-.018066-4.8280677-4.0440197-8.7806537-8.9328471-8.7806537M62.8459442 47.7938061l-.0053397.0081519c-.3248668.4921188-.4609221.6991347-.5369593.8179812-.2560916.3812097-.224267.551113.1668119.8816949.91266.7358184 2.0858968 1.508535 2.8774525 1.8955369 2.2023021 1.076912 4.5810275 1.646045 7.1017886 1.6975309 1.6283921.0821628 3.6734936-.3050536 5.1963734-.9842376 2.7569891-1.2298679 4.5131066-3.6269626 4.8208863-6.5794607.4985136-4.7841067-2.6143125-7.7747902-10.6321784-10.1849709l-.0021359-.0006435c-3.7356476-1.2047686-5.4904836-2.8064071-5.4911243-5.0426086.1099976-2.4715346 2.4015793-4.3179454 5.4932602-4.4331449 2.4904317.0062212 4.6923065.6675996 6.8557356 2.0598624.4562232.2767364.666607.2256796.9733188-.172263.035242-.0587797.1332787-.2012238.543367-.790093l.0012815-.0019308c.3829626-.5500403.5089793-.7336731.5403767-.7879478.258441-.4863266.2214903-.6738208-.244985-1.0046173-.459427-.3290803-1.7535544-1.0024722-2.4936356-1.2978721-2.0583439-.8211991-4.1863175-1.2199998-6.3042524-1.1788111-4.8198184.1046878-8.578747 3.2393171-8.8265087 7.3515337-.1572005 2.9703036 1.350301 5.3588174 4.5000778 7.124567.8829712.4661613 4.1115618 1.6865902 5.6184225 2.1278667 4.2847814 1.2547527 6.5186944 3.5630343 6.0571315 6.2864205-.4192725 2.4743234-3.0117991 4.1199394-6.6498372 4.2325647-2.6382344-.0549182-5.2963324-1.0217793-7.6043603-2.7562084-.0115337-.0083664-.0700567-.0519149-.1779185-.1323615-.1516472-.1130543-.1516472-.1130543-.1742875-.1300017-.4705335-.3247898-.7473431-.2977598-1.0346184.1302162-.0346012.0529875-.3919333.5963776-.5681431.8632459"></path></g></svg> -->
                            <div style="line-height: 20px; margin-left: 5px;">Quick đảm bảo </div>
                        </div>
                        <div style="line-height: 20px; margin-left: 20px;">Trả hàng miễn phí 15 ngày</div>
                    </div>
                </selection>
            </selection>

            <!-- thong tin shop  -->
            <section class="info-shop">
                <!-- avatar-chat  -->
                <div style="width: 574px; padding-right: 25px; border-right: 1px solid #ccc; display: flex;">
                    <div style="width: 100px;"><img style="width: 80%;" src="assets/img/logo-icon2.png" alt=""></div>
                    <div style="font-size: 16px;">
                        <div>Quick Cart.vn</div>
                        <div style="font-size: 14px; margin-top: 2px;">Online 1 phút trước</div>
                        <div>
                            <div style="display: flex; margin-top: 10px;">
                                <div style="border: 1px solid var(--primary-color2); color: var(--primary-color2);padding: 10px 13px; font-size: 14px;cursor: pointer;background-color: #FFEEEA; margin-top: 14px; margin-right: 15px;">
                                    <i class="fa-solid fa-comment" style="color: var(--primary-color2);"></i>
                                    <span>Chat Ngay</span>
                                </div>
                                <div style="border: 1px solid #b5b5b5; color: #3d3d3d;padding: 10px 13px; font-size: 14px;cursor: pointer;;background-color: #ffffff; margin-top: 14px;">
                                    <i class="fa-solid fa-shop" style="color: #3e3e3e;"></i>
                                    <span>Xem Shop</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- đánh gá san pham  -->
                <div style="display: flex; justify-content: space-around; width: 100%;">
                    <div style="height: 100px; width: 165px; padding: 18px 0px; display: flex; flex-direction: column;justify-content: space-between;">
                        <div style="display: flex; justify-content: space-between;">
                            <div style="font-size: 14px;color: rgba(0,0,0,.4);">Đánh giá</div>
                            <div style="font-size: 14px;color: var(--primary-color2);">100k</div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <div style="font-size: 14px;color: rgba(0,0,0,.4);">Sản phẩm</div>
                            <div style="font-size: 14px;color: var(--primary-color2);">1000</div>
                        </div>
                    </div>
                    <div style="height: 100px; width: 165px; padding: 18px 0px; display: flex; flex-direction: column;justify-content: space-between;">
                        <div style="display: flex; justify-content: space-between;">
                            <div style="font-size: 14px;color: rgba(0,0,0,.4);">Tỉ lệ phản hồi</div>
                            <div style="font-size: 14px;color: var(--primary-color2);">100%</div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <div style="font-size: 14px;color: rgba(0,0,0,.4);">Thời gian phản hồi</div>
                            <div style="font-size: 14px;color: var(--primary-color2);">Thường vài giờ</div>
                        </div>
                    </div>
                    <div style="height: 100px; width: 165px; padding: 18px 0px; display: flex; flex-direction: column;justify-content: space-between;">
                        <div style="display: flex; justify-content: space-between;">
                            <div style="font-size: 14px;color: rgba(0,0,0,.4);">Tham gia</div>
                            <div style="font-size: 14px;color: var(--primary-color2);">3 năm trước</div>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <div style="font-size: 14px;color: rgba(0,0,0,.4);">Người theo dõi</div>
                            <div style="font-size: 14px;color: var(--primary-color2);">1M</div>
                        </div>
                    </div>
                </div>

            </section>

            <!-- Chi tiet san pham  -->
            <div style="margin-top: 15px; padding: 10px; background-color: #ffffff;">
                <div class="detail-pro">
                    <div class="detail-pro-h">chi tiết sản phẩm</div>
                    <div style="margin: 30px 20px 10px;">
                        <div style="display: flex; margin-bottom: 18px;">
                            <div style="width: 140px; padding-right: 12px; font-size: 14px;">Danh mục</div>
                            <div class="detail-pro-ul">
                                <a href="index.html">Home</a>
                                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/966fbe37fe1c72e3f2dd.svg" alt="">
                                <a href="index.html">Điện thoại và phụ kiện</a>
                                <img src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/966fbe37fe1c72e3f2dd.svg" alt="">
                                <a href="index.html">Ốp Điện Thoại Chống Sốc Nửa Thân Người Nhện Hoạt Hình</a>
                            </div>
                        </div>
                        <div style="display: flex; margin-bottom: 18px;">
                            <div style="width: 140px; padding-right: 12px; font-size: 14px;">Chất liệu</div>
                            <div class="detail-pro-ul">
                                <a href="index.html">silicon, TPU</a>
                            </div>
                        </div>
                        <div style="display: flex; margin-bottom: 18px;">
                            <div style="width: 140px; padding-right: 12px; font-size: 14px;">Thương hiệu điện thoại tương thích</div>
                            <div class="detail-pro-ul">
                                <a href="index.html">Apple</a>
                            </div>
                        </div>
                        <div style="display: flex; margin-bottom: 18px;">
                            <div style="width: 140px; padding-right: 12px; font-size: 14px;">Số lượng hàng khuyến mãi</div>
                            <div class="detail-pro-ul">
                                <a href="index.html">456</a>
                            </div>
                        </div>
                        <div style="display: flex; margin-bottom: 18px;">
                            <div style="width: 140px; padding-right: 12px; font-size: 14px;">Số sản phẩm còn lại</div>
                            <div class="detail-pro-ul">
                                <a href="index.html">91231129</a>
                            </div>
                        </div>
                        <div style="display: flex; margin-bottom: 18px;">
                            <div style="width: 140px; padding-right: 12px; font-size: 14px;">Gửi từ</div>
                            <div class="detail-pro-ul">
                                <a href="index.html">Nước ngoài</a>
                            </div>
                        </div>
                    </div>

                    <div class="detail-pro-h">mô tả sản phẩm</div>
                    <div style="color: rgba(0, 0, 0, .8);font-size: 14px;overflow: hidden;text-overflow: ellipsis;">
                        <div style="margin-top: 34px; margin-left: 20px;">Chúng tôi là nhà cung cấp phụ kiện điện thoại di động 3C chuyên nghiệp.</div>

                        <div style="margin-top: 34px; margin-left: 20px;">Cửa hàng nhà máy. Hàng mới 100! 💕Còn hàng 🚚🚚</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">Giá của chúng tôi là thấp nhất, và chất lượng và dịch vụ là hàng đầu.</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">Thả hỗ trợ vận chuyển! Hỗ trợ màu hỗn hợp! Hỗ trợ bán buôn!</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">Vui lòng kiểm tra mã số trên đầu hình ảnh.</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">✅Chất liệu: Được làm bằng chất liệu TPU dẻo chất lượng cao, không có mùi đặc biệt</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">✅Loại: Vỏ điện thoại mềm hoàn toàn mới, dễ dàng lắp đặt hoặc tháo gỡ, bảo vệ tốt nhất trong thời gian dài cho điện thoại của bạn.</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">✅ Chức năng: Hình thức tuyệt vời, chống xước, chống rơi, chống bụi, chống va đập, chống bụi bẩn</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">🌈 Phong cách: dễ thương, mềm mại, thời trang "</div>
                        
                        <div style="margin-top: 34px; margin-left: 20px;">Gói bao gồm: trường hợp 1pc</div>
                    </div>
                </div>   
            </div>

            <!-- 4.0/5 sao -->
            <div style="margin-top: 15px; padding: 10px; background-color: #ffffff;">
                <div class="detail-wrap">
                    <div class="detail-start">đánh giá sản phẩm</div>

                    <!-- muc sao  -->
                    <div style="display: flex; padding: 30px; margin-bottom: 16px; background-color: #FFFBF8;">
                        <div style="width: 150px;">
                            <div style="display: flex;">
                                <div style="color: var(--primary-color2); font-size: 30px; font-weight: 550px;">4.9 </div>
                                <div style="color: var(--primary-color2); font-size: 18px; margin-left: 10px;"> trên 5</div>
                            </div>    
                            <div style="margin-top: 20px; font-size: 16px;">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                        <div class="5sss" style="font-size: 14px; margin-left: 50px;">
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">Tất cả</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">5 sao(1002)</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">4 sao(300)</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">3 sao(20)</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">2 sao(0)</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">1 sao(10)</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">Có bình luận(200)</button>
                            <button class="btn-chose-mau-prd" style="margin: 5px 8px 5px 0px; padding: 0 10px; font-size: 16px;">Có hình ảnh/ Video(103)</button>
                        </div>
                    </div>

                    <!-- comment  -->
                    <div>
                        <div class="start-cmt">
                            <div class="start-cmt-avatar">
                                <div style="width: 100%;"><img style="width: 100%; border-radius: 50%;" src="https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lm65c4x98den96_tn" alt=""></div>
                            </div>
    
                            <div class="start-cmt-main">
                                <div style="font-size: 12px;">t*****q</div>
                                <div style="display: flex; font-size: 12px;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div style="margin: 4px 0px 15px; font-size: 14px;color: rgba(0, 0, 0, .54);">2024-04-27 11:21 | Phân loại hàng: White-SE006,Ip x / xs</div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px;">Đúng với mô tả: <span style="color: #222; ">dung mo ta</span></div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px; margin-top: 6px;">Chất lượng sản phẩm: <span style="color: #222;">mũ dẻo</span></div>
                                <div style="margin-top: 12px; color: #222; font-size: 14px;">Giao hang nhanh op lưng đẹp mình mua mấy lần rồi lần nào nhận hàng cũng ưng hết mọi ng nên mua nha</div>
                                <div style="display: flex; margin-top: 12px;">
                                    <div style="width: 72px;margin-right: 12px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                    <div style="width: 72px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="start-cmt">
                            <div class="start-cmt-avatar">
                                <div style="width: 100%;"><img style="width: 100%; border-radius: 50%;" src="https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lm65c4x98den96_tn" alt=""></div>
                            </div>
    
                            <div class="start-cmt-main">
                                <div style="font-size: 12px;">t*****q</div>
                                <div style="display: flex; font-size: 12px;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div style="margin: 4px 0px 15px; font-size: 14px;color: rgba(0, 0, 0, .54);">2024-04-27 11:21 | Phân loại hàng: White-SE006,Ip x / xs</div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px;">Đúng với mô tả: <span style="color: #222; ">dung mo ta</span></div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px; margin-top: 6px;">Chất lượng sản phẩm: <span style="color: #222;">mũ dẻo</span></div>
                                <div style="margin-top: 12px; color: #222; font-size: 14px;">Giao hang nhanh op lưng đẹp mình mua mấy lần rồi lần nào nhận hàng cũng ưng hết mọi ng nên mua nha</div>
                                <div style="display: flex; margin-top: 12px;">
                                    <div style="width: 72px;margin-right: 12px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                    <div style="width: 72px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="start-cmt">
                            <div class="start-cmt-avatar">
                                <div style="width: 100%;"><img style="width: 100%; border-radius: 50%;" src="https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lm65c4x98den96_tn" alt=""></div>
                            </div>
    
                            <div class="start-cmt-main">
                                <div style="font-size: 12px;">t*****q</div>
                                <div style="display: flex; font-size: 12px;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div style="margin: 4px 0px 15px; font-size: 14px;color: rgba(0, 0, 0, .54);">2024-04-27 11:21 | Phân loại hàng: White-SE006,Ip x / xs</div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px;">Đúng với mô tả: <span style="color: #222; ">dung mo ta</span></div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px; margin-top: 6px;">Chất lượng sản phẩm: <span style="color: #222;">mũ dẻo</span></div>
                                <div style="margin-top: 12px; color: #222; font-size: 14px;">Giao hang nhanh op lưng đẹp mình mua mấy lần rồi lần nào nhận hàng cũng ưng hết mọi ng nên mua nha</div>
                                <div style="display: flex; margin-top: 12px;">
                                    <div style="width: 72px;margin-right: 12px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                    <div style="width: 72px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="start-cmt">
                            <div class="start-cmt-avatar">
                                <div style="width: 100%;"><img style="width: 100%; border-radius: 50%;" src="https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lm65c4x98den96_tn" alt=""></div>
                            </div>
    
                            <div class="start-cmt-main">
                                <div style="font-size: 12px;">t*****q</div>
                                <div style="display: flex; font-size: 12px;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div style="margin: 4px 0px 15px; font-size: 14px;color: rgba(0, 0, 0, .54);">2024-04-27 11:21 | Phân loại hàng: White-SE006,Ip x / xs</div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px;">Đúng với mô tả: <span style="color: #222; ">dung mo ta</span></div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px; margin-top: 6px;">Chất lượng sản phẩm: <span style="color: #222;">mũ dẻo</span></div>
                                <div style="margin-top: 12px; color: #222; font-size: 14px;">Giao hang nhanh op lưng đẹp mình mua mấy lần rồi lần nào nhận hàng cũng ưng hết mọi ng nên mua nha</div>
                                <div style="display: flex; margin-top: 12px;">
                                    <div style="width: 72px;margin-right: 12px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                    <div style="width: 72px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="start-cmt">
                            <div class="start-cmt-avatar">
                                <div style="width: 100%;"><img style="width: 100%; border-radius: 50%;" src="https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lm65c4x98den96_tn" alt=""></div>
                            </div>
    
                            <div class="start-cmt-main">
                                <div style="font-size: 12px;">t*****q</div>
                                <div style="display: flex; font-size: 12px;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div style="margin: 4px 0px 15px; font-size: 14px;color: rgba(0, 0, 0, .54);">2024-04-27 11:21 | Phân loại hàng: White-SE006,Ip x / xs</div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px;">Đúng với mô tả: <span style="color: #222; ">dung mo ta</span></div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px; margin-top: 6px;">Chất lượng sản phẩm: <span style="color: #222;">mũ dẻo</span></div>
                                <div style="margin-top: 12px; color: #222; font-size: 14px;">Giao hang nhanh op lưng đẹp mình mua mấy lần rồi lần nào nhận hàng cũng ưng hết mọi ng nên mua nha</div>
                                <div style="display: flex; margin-top: 12px;">
                                    <div style="width: 72px;margin-right: 12px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                    <div style="width: 72px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="start-cmt">
                            <div class="start-cmt-avatar">
                                <div style="width: 100%;"><img style="width: 100%; border-radius: 50%;" src="https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lm65c4x98den96_tn" alt=""></div>
                            </div>
    
                            <div class="start-cmt-main">
                                <div style="font-size: 12px;">t*****q</div>
                                <div style="display: flex; font-size: 12px;">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div style="margin: 4px 0px 15px; font-size: 14px;color: rgba(0, 0, 0, .54);">2024-04-27 11:21 | Phân loại hàng: White-SE006,Ip x / xs</div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px;">Đúng với mô tả: <span style="color: #222; ">dung mo ta</span></div>
                                <div style="color: rgba(0, 0, 0, .54); font-size: 14px; margin-top: 6px;">Chất lượng sản phẩm: <span style="color: #222;">mũ dẻo</span></div>
                                <div style="margin-top: 12px; color: #222; font-size: 14px;">Giao hang nhanh op lưng đẹp mình mua mấy lần rồi lần nào nhận hàng cũng ưng hết mọi ng nên mua nha</div>
                                <div style="display: flex; margin-top: 12px;">
                                    <div style="width: 72px;margin-right: 12px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                    <div style="width: 72px;"><img style="width: 100%;" src="https://down-vn.img.susercontent.com/file/sg-11134201-7rcbz-lstaeju991o254_tn" alt=""></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>   
            </div>
            
        </div>

        <!-- Goi y hom nay -->
        <div class="today">
            <div class="today-header">
                <div class="today-header-text">GỢI Ý HÔM NAY</div>
            </div>
            <div class="top-header-border-bottom"></div>
        </div>

        <?php
        // Truy vấn lấy dữ liệu sản phẩm từ cơ sở dữ liệu
        $sql = "SELECT * FROM Product";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div style="width:1200px; height:2700px" class="today-content">
            <section class="today-content-section">
                <div class="today-content-section-khoi">
                    <!-- Hiển thị danh sách sản phẩm -->
                    <?php foreach ($products as $product): ?>
                        <div class="today-content-section-item" onclick="window.location.href='single.php?product_id=<?php echo $product['product_id']; ?>'">
                            <div class="today-content-section-item-top">
                                <a href="" class="f_sale-main-product-item-link">
                                    <div class="f_sale-main-product-item-img2">
                                        <!-- <div class="f_sale-main-product-item-img--png-anh" style="background-image: url(&quot;https://down-vn.img.susercontent.com/file/5eca542ce856a21c345de0755b3b8de1_tn&quot;); background-size: contain; background-repeat: no-repeat; width: 100%; height: 100%;"></div> -->
                                        <div class="f_sale-main-product-item-img--png-anh" style="background-image: url(&quot;&quot;); background-size: contain; background-repeat: no-repeat; width: 100%; height: 100%;">
                                            <img style="width: 100%;" src="<?php echo $product['img']; ?>" alt="">
                                        </div>
                                        <div class="f_sale-main-product-item-img--png" style="background-image: url(&quot;https://down-vn.img.susercontent.com/file/vn-50009109-8f0a785ca4760dd08fe250706df777fd_tn&quot;); background-size: contain; background-repeat: no-repeat; width: 100%; height: 100%;"></div>
                                        <div class="f_sale-icon">
                                            <div class="giam_phantram">-28%</div>
                                            <i class="fa-solid fa-bolt fa-rotate-by" style="color: #F56032; --fa-rotate-angle: -22deg;font-size:24px;margin-top:1px"></i>
                                        </div>
                                    </div>
                                </a>
                                <div class="today-content-section-item-khoi-text">
                                    <div class="today-content-section-item-text">
                                        <?php echo $product['description']; ?>
                                    </div>

                                    <div class="today-content-section-item-giamgia">20% Giảm</div>
                                    <div class="today-content-section-item-luotban" style="height: 20px !important; margin-top: 3px; display: flex; justify-content: space-between;">
                                        <div class="today-content-section-item-luotban-cost" style="color: #f69113; font-size: 1.6rem;">₫<?php echo number_format($product['price'], 0, ',', '.'); ?></div>
                                        <div class="today-content-section-item-luotban-sl" style="color:rgba(0, 0, 0, .54118); font-size: 1.2rem; margin-right: 10px;">Đã bán 30,2k</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <!-- <div class="today-content-xemthem">Xem Thêm</div> -->
            </section>
        </div>

        <!-- Thanh đỏ  -->
        <div class="top-header-border-bottom"></div>

        <!-- Footer  -->
        <div class="wrap-footer">

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
                                        <span>Quick Cart Blog</span>
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Quick Cart Mall</span>
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
                                        <span>Quick Cart Xu</span>
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
                            <div class="footer-end-list-header">VỀ QUICK CART</div>
                            <ul class="footer-end-list">
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Giới Thiệu Về Quick Cart Việt Nam</span>
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
                                        <span>Điều Khoản Quick Cart</span>
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
                                        <span>Chương Trình Tiếp Thị Liên Kết Quick Cart</span>
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
                            <div class="footer-end-list-header">VỀ QUICK CART</div>
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
                            <div class="footer-end-list-header">TẢI ỨNG DỤNG QUICK CART NGAY THÔI</div>
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
                    <div class="footer-2024-text1">© 2024 Quick Cart. Tất cả các quyền được bảo lưu.</div>
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
                    <div class="footer-last-khoi-2-text">Công ty TNHH Quick Cart</div>
                    <div class="footer-last-khoi-2-text-style2">Địa chỉ: Tầng 4-5-6, Tòa nhà Capital Place, số 29 đường Liễu Giai, Phường Ngọc Khánh, Quận Ba Đình, Thành phố Hà Nội, Việt Nam. Tổng đài hỗ trợ: 19001221 - Email: cskh@hotro.quickcart.vn</div>
                    <div class="footer-last-khoi-2-text-style2">Chịu Trách Nhiệm Quản Lý Nội Dung: Nguyễn Đức Trí -  Điện thoại liên hệ: 024 73081221 (ext 4678)</div>
                    <div class="footer-last-khoi-2-text-style2">Mã số doanh nghiệp: 0106773786 do Sở Kế hoạch & Đầu tư TP Hà Nội cấp lần đầu ngày 10/02/2015</div>
                    <div class="footer-last-khoi-2-text-style2">© 2015 - Bản quyền thuộc về Công ty TNHH Quick Cart</div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <!-- <div class="modal">
        <div class="modal__overlay"></div>
        <div class="modal__body">
             Đăng kí
            <div class="auth-form">
                <div class="auth-form__container">
                    <div class="auth-form__header">
                        <h3 class="auth-form__heading">Đăng kí</h3>
                        <span class="auth-form__swich-btn">Đăng nhập</span>
                    </div>

                    <div class="auth-form__form">
                        <div class="auth-form__group">
                            <input type="text" class="auth-form__input" placeholder="Nhập email đăng kí">
                        </div>

                        <div class="auth-form__group">
                            <input type="text" class="auth-form__input" placeholder="Nhập mật khẩu">
                        </div>

                        <div class="auth-form__group">
                            <input type="text" class="auth-form__input" placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>

                    <div class="auth-form__aside">
                        <p class="auth-form-text">
                            Bằng việc đăng kí, bạn đã đồng ý với Shopee về
                            <a href="" class="auth-form-link">Điều khoản dịch vụ</a> &
                            <a href="" class="auth-form-link">Chính sách bảo mật</a>
                        </p>
                    </div>

                    <div class="auth-form__controls">
                        <button class="btn btn-nomal">TRỞ LẠI</button>
                        <button class="btn btn--primary">ĐĂNG KÍ</button>
                    </div>
                </div>

                <div class="auth-form__socials">
                    <a href="" class="btn auth-form__socials--facebook btn--with-icon" id="fb">
                        <i class="fa-brands fa-square-facebook" style="color: #ffffff;"></i>
                        Kết nối với Facebook
                    </a>

                    <a href="" class="btn auth-form__socials--google btn--with-icon">
                        <i class="fa-brands fa-google" style="color: black;"></i>
                        Kết nối với Google
                    </a>
                </div>
            </div>

            Đăng nhập
            <div class="auth-form">

                <div class="auth-form__container">
                    <div class="auth-form__header">
                        <h3 class="auth-form__heading">Đăng nhập</h3>
                        <span class="auth-form__swich-btn">Đăng kí</span>
                    </div>

                    <div class="auth-form__form">
                        <div class="auth-form__group">
                            <input type="text" class="auth-form__input" placeholder="Nhập email">
                        </div>

                        <div class="auth-form__group">
                            <input type="text" class="auth-form__input" placeholder="Nhập mật khẩu">
                        </div>
                    </div>

                    <div class="auth-form__aside">
                        <div class="auth-form__help">
                            <a href="" class="auth-form__help-link">Quên mật khẩu</a>
                            <a href="" class="auth-form__help-link">Cần trợ giúp?</a>
                        </div>
                    </div>

                    <div class="auth-form__controls">
                        <button class="btn btn-nomal">TRỞ LẠI</button>
                        <button class="btn btn--primary">ĐĂNG KÍ</button>
                    </div>
                </div>

                <div class="auth-form__socials">
                    <a href="" class="btn auth-form__socials--facebook btn--with-icon" id="fb">
                        <i class="fa-brands fa-square-facebook" style="color: #ffffff;"></i>
                        Kết nối với Facebook
                    </a>

                    <a href="" class="btn auth-form__socials--google btn--with-icon">
                        <i class="fa-brands fa-google" style="color: black;"></i>
                        Kết nối với Google
                    </a>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <script src="assets/js/main.js"></script> -->
    <!-- <script src="assets/js/single.js"></script> -->
    <!-- <script>showTrangSingle()</script> -->
    <script>
        const btnCong = document.getElementById('btnCong');
        const btnTru = document.getElementById('btnTru');
        const soLuong = document.getElementById('soLuong');

        btnCong.addEventListener('click', function() {
        soLuong.value++;
        });

        btnTru.addEventListener('click', function() {
            if(soLuong.value > 1) {
            soLuong.value--;
            }
        });
        // 
    </script>
</body>
</html>