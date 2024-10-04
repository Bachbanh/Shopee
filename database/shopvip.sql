-- Xóa cơ sở dữ liệu nếu tồn tại
-- DROP DATABASE IF EXISTS shopbanhang;

-- CREATE DATABASE shopbanhang;
-- USE shopbanhang;

-- Tạo bảng User
CREATE TABLE User (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tạo bảng Profile với ON DELETE CASCADE
CREATE TABLE Profile (
    profile_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    bank_account VARCHAR(50),
    full_name VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- Tạo bảng Product
CREATE TABLE Product (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    img VARCHAR(255),
    price DECIMAL(10, 2) NOT NULL,
    description TEXT
);

-- Tạo bảng Cart với ON DELETE CASCADE
CREATE TABLE Cart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

-- Thêm dữ liệu mẫu vào bảng User
INSERT INTO User (email, password)
VALUES 
('nguyenvana@gmail.com', '123'),
('tranthib@example.com', 'password456'),
('levanc@example.com', 'password789'),
('phamthid@example.com', 'password1011');

-- Thêm dữ liệu vào bảng Profile
INSERT INTO Profile (user_id, phone, address, bank_account, full_name)
VALUES 
(1, '0123456789', '123 Đường ABC, TP. HCM', '123456789', 'Nguyễn Văn A'),
(2, '0987654321', '456 Đường DEF, Hà Nội', '987654321', 'Trần Thị B'),
(3, '0123498765', '789 Đường GHI, Đà Nẵng', '234567891', 'Lê Văn C'),
(4, '0987612345', '012 Đường JKL, Cần Thơ', '345678912', 'Phạm Thị D');

-- Thêm dữ liệu vào bảng Product
INSERT INTO `Product` (`product_id`, `img`, `price`, `description`) VALUES
(1, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lxnhryzvqztl4d_tn.webp', 150000.00, 'Mô tả sản phẩm 1'),
(2, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lxxzp91bc4wpe1_tn.webp', 200000.00, 'Mô tả sản phẩm 2'),
(3, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lxc208gime3d6e_tn.webp', 300000.00, 'Mô tả sản phẩm 3'),
(4, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lwk7q92gnbh70e_tn.webp', 250000.00, 'Mô tả sản phẩm 4'),
(5, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lvjudiarlevh62_tn.webp', 100000.00, 'Mô tả sản phẩm 5'),
(6, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lv6adns0ofbu18_tn.webp', 150000.00, 'Mô tả sản phẩm 6'),
(7, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lu9h3kkh0ppb56_tn.webp', 200000.00, 'Mô tả sản phẩm 7'),
(8, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lsurz0u47r1516_tn.webp', 300000.00, 'Mô tả sản phẩm 8'),
(9, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-ls2ktavwqw0p94_tn.webp', 250000.00, 'Mô tả sản phẩm 9'),
(10, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-lqva4s80mnixdd_tn.webp', 100000.00, 'Mô tả sản phẩm 10'),
(11, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdyv-lxs2i4yawkwa0d_tn.webp', 150000.00, 'Mô tả sản phẩm 11'),
(12, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdyu-lyump9v6q2yc41_tn.webp', 200000.00, 'Mô tả sản phẩm 12'),
(13, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdyq-lxjewnnq6ahr01_tn.webp', 300000.00, 'Mô tả sản phẩm 13'),
(14, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdya-lylof9djvh8mbb_tn.webp', 250000.00, 'Mô tả sản phẩm 14'),
(15, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdx5-lxy3uvd13o9608_tn.webp', 100000.00, 'Mô tả sản phẩm 15'),
(16, 'https://down-vn.img.susercontent.com/file/vn-11134207-7r98o-m01685abaclp40_tn.webp', 150000.00, 'Mô tả sản phẩm 16'),
(17, 'https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lpnkkux1vf4rab_tn', 200000.00, 'Mô tả sản phẩm 17'),
(18, 'https://down-vn.img.susercontent.com/file/vn-11134233-7r98o-lvtcuk8sstsr65_tn', 300000.00, 'Mô tả sản phẩm 18'),
(19, 'https://down-vn.img.susercontent.com/file/sg-11134201-7qvf2-licxfjp2jdh3c8_tn.webp', 250000.00, 'Mô tả sản phẩm 19'),
(20, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rcdp-lsqot1um0w2t3a_tn.webp', 100000.00, 'Mô tả sản phẩm 20'),
(21, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rceu-ltxgfzh878qd72_tn.webp', 150000.00, 'Mô tả sản phẩm 21'),
(22, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd3l-lwwe0egzupu44b_tn.webp', 200000.00, 'Mô tả sản phẩm 22'),
(23, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd3v-lws7cr0en76ib4_tn.webp', 300000.00, 'Mô tả sản phẩm 23'),
(24, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd4b-lx2l3k5p75ca3d_tn.webp', 250000.00, 'Mô tả sản phẩm 24'),
(25, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd5a-lvv6kxv26rzfe7_tn.webp', 100000.00, 'Mô tả sản phẩm 25'),
(26, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd44-lx95b6uu14o932_tn.webp', 150000.00, 'Mô tả sản phẩm 26'),
(27, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd57-lwp4ypl3kiwx35_tn.webp', 200000.00, 'Mô tả sản phẩm 27'),
(28, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rd64-lvwoo6stvjug4c_tn.webp', 300000.00, 'Mô tả sản phẩm 28'),
(29, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rdx1-lyx3qc0gyoef67_tn.webp', 250000.00, 'Mô tả sản phẩm 29'),
(30, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rdx8-lyu881ga673o0a_tn.webp', 100000.00, 'Mô tả sản phẩm 30'),
(31, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rdxh-ly85ttkd9ru0d4_tn.webp', 150000.00, 'Mô tả sản phẩm 31'),
(32, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rdxz-lyrqfm5hmg3rbb_tn.webp', 200000.00, 'Mô tả sản phẩm 32'),
(33, 'https://down-vn.img.susercontent.com/file/sg-11134201-7rdyh-lxl169rmgl7058_tn.webp', 300000.00, 'Mô tả sản phẩm 33'),
(34, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rcc4-lsi4sxxfsm5kf3_tn.webp', 250000.00, 'Mô tả sản phẩm 34'),
(35, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rccm-lsc4xmcjrqjsd1_tn.webp', 100000.00, 'Mô tả sản phẩm 35'),
(36, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rccy-ltxbhdwr3gdc7e_tn.webp', 150000.00, 'Mô tả sản phẩm 36'),
(37, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rceo-lstlqspu5flnff_tn.webp', 200000.00, 'Mô tả sản phẩm 37'),
(38, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rd3y-lv8emcxvifup2a_tn.webp', 300000.00, 'Mô tả sản phẩm 38'),
(39, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rd6k-lvolm0efh0h941_tn.webp', 250000.00, 'Mô tả sản phẩm 39'),
(40, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rd46-lva83ybriek418_tn.webp', 100000.00, 'Mô tả sản phẩm 40'),
(41, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rd50-lvlpao80xjc1aa_tn.webp', 150000.00, 'Mô tả sản phẩm 41'),
(42, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rd75-lv8uujs9au8990_tn.webp', 200000.00, 'Mô tả sản phẩm 42'),
(43, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdw2-lzswa3oku03q6d_tn.webp', 300000.00, 'Mô tả sản phẩm 43'),
(44, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdwq-ly3kuc3n4q2xd3_tn.webp', 250000.00, 'Mô tả sản phẩm 44'),
(45, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdx3-ly1sh8hnqk3se4_tn.webp', 100000.00, 'Mô tả sản phẩm 45'),
(46, 'https://down-vn.img.susercontent.com/file/sg-11134301-7rdyq-lxjewnnq6ahr01_tn.webp', 150000.00, 'Mô tả sản phẩm 46'),
(47, 'https://down-vn.img.susercontent.com/file/vn-11134201-7r98o-lv1x4xht6rblb2_tn.webp', 200000.00, 'Mô tả sản phẩm 47'),
(48, 'https://down-vn.img.susercontent.com/file/vn-11134201-7r98o-lopnmszriqyzb7_tn.webp', 300000.00, 'Mô tả sản phẩm 48');

-- Thêm dữ liệu vào bảng Cart
INSERT INTO Cart (user_id, product_id, quantity)
VALUES 
(1, 1, 2),
(1, 2, 1),
(2, 3, 1),
(2, 1, 1),
(3, 4, 3),
(4, 5, 2);

-- Tạo bảng Orders
CREATE TABLE Orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_amount DECIMAL(10, 2) NOT NULL,
    status ENUM('Pending', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE
);

-- Thêm dữ liệu vào bảng Orders
INSERT INTO Orders (user_id, total_amount, status)
VALUES 
(1, 350000, 'Pending'),
(2, 450000, 'Shipped'),
(3, 750000, 'Delivered'),
(4, 200000, 'Pending');

-- Tạo bảng OrderDetails
CREATE TABLE OrderDetails (
    order_detail_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id)
);

-- Thêm dữ liệu vào bảng OrderDetails
INSERT INTO OrderDetails (order_id, product_id, quantity, price)
VALUES 
(1, 1, 2, 150000),
(1, 2, 1, 200000),
(2, 3, 1, 300000),
(2, 1, 1, 150000),
(3, 4, 3, 250000),
(4, 5, 2, 100000);

-- Tạo bảng Shipping
CREATE TABLE Shipping (
    shipping_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    shipping_method ENUM('Standard', 'Express') NOT NULL,
    shipping_cost DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- Thêm dữ liệu vào bảng Shipping
INSERT INTO Shipping (order_id, shipping_address, shipping_method, shipping_cost)
VALUES 
(1, '123 Đường ABC, TP. HCM', 'Standard', 50000),
(2, '456 Đường DEF, Hà Nội', 'Express', 70000),
(3, '789 Đường GHI, Đà Nẵng', 'Standard', 60000),
(4, '012 Đường JKL, Cần Thơ', 'Standard', 40000);

-- Tạo bảng Payment
CREATE TABLE Payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('Credit Card', 'PayPal', 'Bank Transfer') NOT NULL,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_amount DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES Orders(order_id) ON DELETE CASCADE
);

-- Thêm dữ liệu vào bảng Payment
INSERT INTO Payment (order_id, payment_method, payment_amount)
VALUES 
(1, 'Credit Card', 350000),
(2, 'PayPal', 450000),
(3, 'Bank Transfer', 750000),
(4, 'Credit Card', 200000);

-- Tạo bảng Review (Đánh giá sản phẩm)
CREATE TABLE Review (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES Product(product_id) ON DELETE CASCADE
);

-- Thêm dữ liệu vào bảng Review
INSERT INTO Review (user_id, product_id, rating, comment)
VALUES 
(1, 1, 5, 'Sản phẩm rất tốt!'),
(2, 3, 4, 'Chất lượng khá ổn, nhưng giao hàng hơi chậm.'),
(3, 4, 3, 'Sản phẩm tạm được, không có gì nổi bật.'),
(4, 5, 5, 'Rất hài lòng với dịch vụ.');
