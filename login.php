<?php
session_start();
include 'includes/pdo.php'; // Kết nối cơ sở dữ liệu từ pdo.php

// Kiểm tra xem người dùng đã submit form chưa
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form mà không sử dụng htmlspecialchars
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
    $sql = "SELECT * FROM User WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    // Nếu tồn tại người dùng với email này
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch();

        // Kiểm tra mật khẩu có đúng không
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công, lưu thông tin vào session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['email'];

            // Chuyển hướng đến trang index.php
            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Mật khẩu không đúng!');</script>";
        }
    } else {
        echo "<script>alert('Email không tồn tại!');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets\css\fontawesome-free-6.5.2-web\css\all.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="icon" type="image/png" sizes="32x32" href="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/icon_favicon_1_32.0Wecxv.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/icon_favicon_1_96.wI1aMs.png">
</head>
<body>
    <div style="padding-top: 30px;" class="login-wrap">
        <div class="login">
            <div class="login-header">
                <div class="login-header-box">
                    <div class="header__logo">

                        <img style="width: 150px ; height: 80px" src="assets\img\logo_dangki.png" alt="">

                    </div>
                    <div class="login-header-box-text">ĐĂNG NHẬP</div>
                    <div class="login-header-box-text-help">Bạn cần giúp đỡ?</div>
                </div>
            </div>
            <div class="login-content">
                <div class="login-content-main">
                    <!-- them from vao day  -->
                    <div class="login-content-main-box">
                        <div class="login-content-main-box-header">
                            <div class="login-content-main-box-header-text">Đăng nhập</div>
                        </div>
                        <div class="login-content-main-box-content">
                            <div class="login-content-main-box-content--khoi">
                                <form action="login.php" method="post">
                                    <div style="margin-bottom: 31px;">
                                        <div class="login-form-input">
                                            <input class="input-ten-dang-nhap" type="text" name="email" placeholder="Email">
                                        </div>
                                    </div>
                                    <div style="margin-bottom: 31px;">
                                        <div class="login-form-input">
                                            <input class="input-ten-dang-nhap" type="password" name="password" placeholder="Mật khẩu">
                                        </div>
                                    </div>
                                    <button style="margin-bottom: 10px;" class="login-dnhap">Đăng nhập</button>
                                    <ul style="display: flex; justify-content: space-between; margin-bottom: 14px;">
                                        <li><a style="font-size: 1.2rem; color: #0055AA;" href="">Quên mật khẩu</a></li>
                                        <li><a style="font-size: 1.2rem; color: #0055AA;" href="">Đăng nhập với SMS</a></li>
                                    </ul>
                                    <div style="display: flex; align-items: center; text-transform: uppercase;">
                                        <div style="background-color: #dbdbdb;flex: 1;height: 1px;width: 100%;"></div>
                                        <div style="color: #ccc;font-size: 12px;padding: 0 16px; text-transform: uppercase;">hoặc</div>
                                        <div style="background-color: #dbdbdb;flex: 1;height: 1px;width: 100%;"></div>
                                    </div>
                                </form>
                                <!-- <div style="margin-top: 15px;" class="login-content-main-box-content--khoi-fb-gg">
                                    <button class="login-fb-gg">
                                        <div style="display: flex; margin: 0px auto; align-items: center;">
                                            <div><i class="fa-brands fa-facebook" style="color: #1f71ff; font-size: 22px; padding-right: 6px;"></i></div>
                                            <div style="font-size: 1.4rem;">Facebook</div>
                                        </div>
                                    </button>
                                    <button class="login-fb-gg">
                                        <div style="display: flex; margin: 0px auto; align-items: center;">
                                            <div><i class="fa-brands fa-google" style="color: #ff4d4d; font-size: 22px; padding-right: 6px;"></i></div>
                                            <div style="font-size: 1.4rem;">Google</div>
                                        </div>
                                    </button>
                                </div> -->
                            </div>
                            <div class="login-chuyen-dki">
<<<<<<< HEAD
                                Bạn mới biết đến Shopee?  
=======
                                Bạn mới biết đến PBA
                                ?  
>>>>>>> ed381f1 (bach)
                                <a style="color: #EE4D2D; font-size: 1.4rem;font-weight: 500; margin-left: 6px;" href="dangki.php"> Đăng kí</a>
                            </div>
                        </div>
                        <div class="login-content-main-box-end"></div>
                    </div>
                </div>
            </div>
            <div style="background-color: #F5F5F5;"  class="login-footer">
                <!-- End  -->
                <div style="background-color: #F5F5F5;" class="footer-end">
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
<<<<<<< HEAD
                                        <span>Shopee Blog</span>
=======
                                        <span>PBA
                                             Blog</span>
>>>>>>> ed381f1 (bach)
                                    </a>
                                </li>
                                <li class="footer-end-item">
                                    <a href="">
<<<<<<< HEAD
                                        <span>Shopee Mall</span>
=======
                                        <span>PBA
                                             Mall</span>
>>>>>>> ed381f1 (bach)
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
<<<<<<< HEAD
                                        <span>Shopee Xu</span>
=======
                                        <span>PBA
                                             Xu</span>
>>>>>>> ed381f1 (bach)
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
<<<<<<< HEAD
                            <div class="footer-end-list-header">VỀ SHOPEE</div>
                            <ul class="footer-end-list">
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Giới Thiệu Về Shopee Việt Nam</span>
=======
                            <div class="footer-end-list-header">VỀ PBA
                                
                            </div>
                            <ul class="footer-end-list">
                                <li class="footer-end-item">
                                    <a href="">
                                        <span>Giới Thiệu Về PBA
                                             Việt Nam</span>
>>>>>>> ed381f1 (bach)
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
<<<<<<< HEAD
                                        <span>Điều Khoản Shopee</span>
=======
                                        <span>Điều Khoản PBA
                                            
                                        </span>
>>>>>>> ed381f1 (bach)
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
<<<<<<< HEAD
                                        <span>Chương Trình Tiếp Thị Liên Kết Shopee</span>
=======
                                        <span>Chương Trình Tiếp Thị Liên Kết PBA
                                            
                                        </span>
>>>>>>> ed381f1 (bach)
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
<<<<<<< HEAD
                            <div class="footer-end-list-header">VỀ SHOPEE</div>
=======
                            <div class="footer-end-list-header">VỀ PBA
                                
                            </div>
>>>>>>> ed381f1 (bach)
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
<<<<<<< HEAD
                            <div class="footer-end-list-header">TẢI ỨNG DỤNG SHOPEE NGAY THÔI</div>
=======
                            <div class="footer-end-list-header">TẢI ỨNG DỤNG PBA
                                 NGAY THÔI</div>
>>>>>>> ed381f1 (bach)
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

                <!-- /* footer 2024 */ -->
                <div style="background-color: #F5F5F5;"  class="footer-2024">
<<<<<<< HEAD
                    <div class="footer-2024-text1">© 2024 Shopee. Tất cả các quyền được bảo lưu.</div>
=======
                    <div class="footer-2024-text1">© 2024 PBA
                        . Tất cả các quyền được bảo lưu.</div>
>>>>>>> ed381f1 (bach)
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

                <!-- footer-last -->
                <div style="background-color: #F5F5F5;"  class="footer-last">
                    <div class="footer-last-khoi">
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
<<<<<<< HEAD
                        <div class="footer-last-khoi-2-text">Công ty TNHH Shopee</div>
                        <div class="footer-last-khoi-2-text-style2">Địa chỉ: Tầng 4-5-6, Tòa nhà Capital Place, số 29 đường Liễu Giai, Phường Ngọc Khánh, Quận Ba Đình, Thành phố Hà Nội, Việt Nam. Tổng đài hỗ trợ: 19001221 - Email: cskh@hotro.shopee.vn</div>
                        <div class="footer-last-khoi-2-text-style2">Chịu Trách Nhiệm Quản Lý Nội Dung: Nguyễn Đức Trí -  Điện thoại liên hệ: 024 73081221 (ext 4678)</div>
                        <div class="footer-last-khoi-2-text-style2">Mã số doanh nghiệp: 0106773786 do Sở Kế hoạch & Đầu tư TP Hà Nội cấp lần đầu ngày 10/02/2015</div>
                        <div class="footer-last-khoi-2-text-style2">© 2015 - Bản quyền thuộc về Công ty TNHH Shopee</div>
=======
                        <div class="footer-last-khoi-2-text">Công ty TNHH PBA
                            
                        </div>
                        <div class="footer-last-khoi-2-text-style2">Địa chỉ: Tầng 4-5-6, Tòa nhà Capital Place, số 29 đường Liễu Giai, Phường Ngọc Khánh, Quận Ba Đình, Thành phố Hà Nội, Việt Nam. Tổng đài hỗ trợ: 19001221 - Email: cskh@hotro.PBA
                            .vn</div>
                        <div class="footer-last-khoi-2-text-style2">Chịu Trách Nhiệm Quản Lý Nội Dung: Nguyễn Đức Trí -  Điện thoại liên hệ: 024 73081221 (ext 4678)</div>
                        <div class="footer-last-khoi-2-text-style2">Mã số doanh nghiệp: 0106773786 do Sở Kế hoạch & Đầu tư TP Hà Nội cấp lần đầu ngày 10/02/2015</div>
                        <div class="footer-last-khoi-2-text-style2">© 2015 - Bản quyền thuộc về Công ty TNHH PBA
                            
                        </div>
>>>>>>> ed381f1 (bach)
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>