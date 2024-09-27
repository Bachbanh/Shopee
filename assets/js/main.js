
const todayContentSectionItems = document.querySelectorAll(".today-content-section-item");

todayContentSectionItems.forEach((item, index) => {
    item.addEventListener("click", () => {
        themgiohang(item, index);
    });
});

// Khoi tao mang gio hang chua co so luong hang
var giohang_chuaCoSL = [];
for (let i = 0; i <= 48; i++) {
    giohang_chuaCoSL.push(0);
}

function themgiohang(item, index) {
    var box_sp = item.children[0];
    var box_img = box_sp.children[0].children[0].children[0].children[0].src;
    var box_price = box_sp.children[1].children[2].children[0].innerText;
    box_price = box_price.slice(2);

    // tạo mảng lưu với single_sp
    var single_sp = [];
    for (let i = 0; i <= 48; i++) {
        single_sp.push(0);
    }
    single_sp.splice(index, 1, [box_img, box_price]);
    alert(single_sp[index]);
    
    // giohang_chuaCoSL.push(single_sp)
    giohang_chuaCoSL.splice(index, 1, single_sp[index]);
    // alert("giohang chua co sl " + giohang_chuaCoSL);
    // alert(giohang_chuaCoSL.length);
    
    //luu vao semstr
    // sessionStorage.setItem("cbigiohang", JSON.stringify(giohang_chuaCoSL));
    sessionStorage.setItem("cbigiohang", JSON.stringify(giohang_chuaCoSL[index]));
    // localStorage.setItem("cbigiohang", JSON.stringify(giohang_chuaCoSL[index]));
}
 

var giohang_CoSL = [];
// for (let i = 0; i <= 48; i++) {
//     giohang_CoSL.push(0);
// }


function HienLenGioHang() {
    alert("alo?????");
    let SltProLeft = document.getElementById("slt-pro-left");
    let SltProRight = document.getElementById("slt-pro-right");
    let btnSoLuong = document.getElementById("btnSoLuong");

    let single_img = SltProLeft.children[0].children[0].children[0].src;
    let single_price = SltProRight.children[3].children[1].value;
    let single_count = btnSoLuong.children[1].value;


    // Dữ liệu cần lưu
    let data = {
        img: single_img,
        price: single_price,
        count: single_count
    };

    // Gửi dữ liệu đến máy chủ
    fetch('/save-cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.text())
    .then(result => {
        console.log(result);
        alert("Data saved successfully.");
    })
    .catch(error => {
        console.error('Error saving data:', error);
    });


    // let single_sp_cosl = [single_img,single_price,single_count];

    // giohang_CoSL.splice(dem, 1,([single_img,single_price,single_count]));
    // alert("giohang_CoSL: "+giohang_CoSL);
    
    // alert(giohang_CoSL.length);
    // var ThongTinGioHangNho = 
    // '    <li class="header__cart__notify-item">'+
    // '        <a href="" class="header__cart__notify-link header__cart__notify-link--viewed">'+
    // '            <img src="'+(gh[0][0])+'" alt="" class="header__cart__notify-img">'+
    // '            <div class="header__cart__notify-info">'+
    // '                <div class="header__cart__notify-name">Gia nhập Shopee thật là thích!</div>'+
    // '                <div class="header__cart__notify-price"><div>₫</div>'+(gh[0][1])+'</div>'+
    // '            </div>'+
    // '        </a>'+
    // '    </li>'

    // document.getElementById("header__cart__notify-list").innerHTML = ThongTinGioHangNho;
}


function showTrangSingle(){
    var gh_chuaCoSL = sessionStorage.getItem("cbigiohang");
    // var gh_chuaCoSL = localStorage.getItem("cbigiohang");
    var gh = JSON.parse(gh_chuaCoSL);
    alert("Đã thêm sản phẩm vào giỏ hàng");
    giohang_CoSL.push(gh[0], gh[1]);
    alert(giohang_CoSL);
    alert("gh");
    alert(gh);
    var ThongTinTrangSingle =           
            '    <selection id="slt-pro-left">'+
            '        <div style="display: flex; flex-direction: column;">'+
            '            <div><img style="width: 100%;" src="'+gh[0]+'" alt=""></div>'+
            '            <div style="margin-top: 5px; display: flex;">'+
            '                <div class="slt-pro-left-img-phu"><img src="'+gh[0]+'" alt=""></div>'+
            '               <div class="slt-pro-left-img-phu"><img src="'+gh[0]+'" alt=""></div>'+
            '                <div class="slt-pro-left-img-phu"><img src="'+gh[0]+'" alt=""></div>'+
            '                <div class="slt-pro-left-img-phu"><img src="'+gh[0]+'" alt=""></div>'+
            '                <div class="slt-pro-left-img-phu"><img src="'+gh[0]+'" alt=""></div>'+
            '            </div>'+
            '        </div>'+
            '        <div style="margin-top: 15px; display: flex; justify-content: center; align-items: center;">'+
            '            <div style="padding: 0 30px; display: flex; align-items: center; border-right: 1px solid #ccc;">'+
            '                <div style="color: #222; font-size: 1.6rem;">Chia sẻ:</div>'+
            '                <i class="fa-brands fa-facebook-messenger" style="font-size: 16px;margin-left: 5px;color: #0041b3; padding: 0;"></i>'+
            '                <i class="fa-brands fa-facebook" style="font-size: 16px;margin-left: 5px;color: #0049c7; padding: 0;"></i>'+
            '                <i class="fa-brands fa-pinterest" style="font-size: 16px;margin-left: 5px;color: #940000; padding: 0;"></i>'+
            '                <i class="fa-brands fa-square-x-twitter" style="font-size: 16px;margin-left: 5px;color: #3881ff; padding: 0;"></i>'+
            '            </div>'+
            '            <div style="padding: 0 30px; display: flex; align-items: center;">'+
            '               <i class="fa-regular fa-heart" style="color: #ff1100; font-size: 16px; margin-right: 5px;"></i>'+
            '                <div style="color: #222; font-size: 1.6rem;">Đã thích (956)</div>'+
            '            </div>'+
            '        </div>'+
            '    </selection>'+
            '    <selection id="slt-pro-right">'+
            '        <div style="position: relative;">'+
            '            <div style="padding: 0px 4px; width: 60px;text-align: center;margin-right: 10px; border-radius: 2px;color: #ffffff; background-color: var(--primary-color2);">Yêu thích</div>'+
            '            <span style="font-size: 12px; position: absolute; top: 0; line-height: 22px;"><button style="width: 60px; background-color: transparent; border: 0px;"></button> Ốp Điện Thoại Chống Sốc Nửa Thân Người Nhện Hoạt Hình Hợp Thời Trang Tương Thích Cho iPhone 11 12 13 14 15Pro MAX 7 8 Plus X XR XS MAX SE 2020 Ốp Lưng Silicon Siêu Bảo Vệ Ốp Lưng Mềm</span>'+
            '        </div>'+
            '        <div style="margin-top: 22px; display: flex;">'+
            '            <div style="padding-right: 15px; display: flex; align-items: center;border-right: 1px solid #ccc;">'+
            '                <div style="margin-right: 5px; color: var(--primary-color2); border-bottom: 1px solid var(--primary-color2);">4.9</div>'+
            '                <i class="fa-solid fa-star"></i>'+
            '                <i class="fa-solid fa-star"></i>'+
            '                <i class="fa-solid fa-star"></i>'+
            '                <i class="fa-solid fa-star"></i>'+
            '                <i class="fa-solid fa-star"></i>'+
            '            </div>'+
            '            <div style="padding: 0px 15px; display: flex; align-items: center;border-right: 1px solid #ccc;">'+
            '                <div style="margin-right: 5px; color: #0041b3; border-bottom: 1px solid #0041b3; color: #222;">535</div>'+
            '                <div style="margin-right: 5px; color: #0041b3; color: #6a6a6a;">Đánh giá</div>'+
            '            </div>'+
            '            <div style="padding: 0px 15px; display: flex; align-items: center;border-right: 1px solid #ccc;">'+
            '                <div style="margin-right: 5px; color: #0041b3; color: #222;">3K</div>'+
            '                <div style="margin-right: 5px; color: #0041b3; color: #6a6a6a;">Đã bán</div>'+
            '            </div>'+
            '        </div>'+
            '        <div class="f_sale-main-header">'+
            '            <div class="f_sale-main-header-time">'+
            '                <div class="f_sale-main-header-time-logo"></div>'+
            '               <div class="f_sale-main-header-time-real-block--main">'+
            '                    <div class="f_sale-main-header-time-real-block">00</div>'+
            '                    <div class="f_sale-main-header-time-real-block">34</div>'+
            '                    <div class="f_sale-main-header-time-real-block">37</div>'+
            '                </div>'+
            '            </div>'+
            '            <a href="" class="f_sale-main-header-all">'+
            '                Xem tất cả'+
            '                <svg fill="var(--primary-color)" style="width: 9px; margin: 0px 0px 0px 5px;padding: 0px;" enable-background="new 0 0 11 11" viewBox="0 0 11 11" x="0" y="0" class="shopee-svg-icon icon-arrow-right">'+
            '                    <path d="m2.5 11c .1 0 .2 0 .3-.1l6-5c .1-.1.2-.3.2-.4s-.1-.3-.2-.4l-6-5c-.2-.2-.5-.1-.7.1s-.1.5.1.7l5.5 4.6-5.5 4.6c-.2.2-.2.5-.1.7.1.1.3.2.4.2z"></path>'+
            '                </svg>'+
            '            </a>'+
            '        </div>'+
            '        <div style="padding: 15px 20px; display: flex;">'+
            '            <div class="slt-coat-a">₫30.000</div>'+
            '            <input type="hidden" name="" value="'+gh[1]+'">'+
            '            <div class="slt-coat-b">₫'+gh[1]+'</div>'+
            '            <div class="slt-coat-c">90% giảm</div>'+
            '        </div>'+
            '        <div class="stl-khoi-chose">'+
            '            <section class="chose_1">'+
            '                <div style="text-transform: capitalize; font-weight: 400; color: #757575;">Chính sách trả hàng</div>'+
            '               <div style="display: flex; justify-content: center;">'+
            '                   <img style="width: 30px; margin-left: 15px; margin-right: 8px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/b69402e4275f823f7d47.svg" alt="">'+
            '                    <h3 style="text-transform: capitalize; font-weight: 400; color: #757575; line-height: 38px;">Trả hàng 15 ngày</h3>'+
            '                    <h3 style="text-transform: capitalize; font-weight: 400; color: #757575; line-height: 38px; margin-left: 15px; margin-right: 8px;">Đổi ý miễn phí</h3>'+
            '                    <img style="width: 21px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/be6f27f93268c0f88ded.svg" alt="">'+
            '            </section>'+
            '            <section class="chose_2">'+
            '                <div style="width: 110px; color: #757575;">Vận chuyển</div>'+
            '                <div style="width: 100%;">'+
            '                    <div style="height: 31px; display: flex;">'+
            '                        <img style="width: 30px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/d9e992985b18d96aab90.png" alt="">'+
            '                        <div style="margin-left: 10px; line-height: 31px;">Miễn phí vận chuyển</div>'+
            '                    </div>'+
            '                    <div style="display: flex;">'+
            '                        <img style="width: 30px;" src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/productdetailspage/7b101a24cfe44d8eb45f.svg" alt="">'+
            '                        <div style="display: flex; flex-wrap: wrap;">'+
            '                            <div style="margin-right: 10px; margin-left: 10px;">Vận chuyển từ</div>'+
            '                           <div style="margin-right: 10px;">Nước ngoài tới</div>'+
            '                            <div style="margin-right: 10px;">Phường Yên Nghĩa</div>'+
            '                            <div style="margin-right: 10px;">Quận Hà Đông</div>'+
            '                            <div style="margin-right: 10px;margin-left: 10px;margin-top: 10px;">Phí vận chuyển</div>'+
            '                            <div style="margin-right: 10px;margin-top: 10px;">0đ</div>'+
            '                        </div>'+
            '                    </div>'+
            '               </div>'+
            '            </section>'+
            '            <div style="padding: 4px 4px 15px; ">'+
            '                <div style="height: 40px; margin-bottom: 24px; display: flex;">'+
            '                    <div style="width: 110px; line-height: 40px;">Màu sắc</div>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Blue color</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Yellow color</button>'+
            '                </div>'+
            '                <div style="height: 135px; margin-bottom: 24px; display: flex; flex-wrap: wrap;">'+
            '                    <div style="width: 110px; line-height: 40px;">Kích thước</div>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 7/8 / SE (2020)</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 7plus / 8plus</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip x / xs</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip xr</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip xsmax</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 11</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px; margin-left: 110px;">Ip 11pro</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 11promax</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 12</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 12pro</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 12promax</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 13</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 13pro</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px; margin-left: 110px;">Ip 13promax</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14plus</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14pro</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 14promax</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 15</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 15pro</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px; margin-left: 110px;">Ip 15plus</button>'+
            '                    <button class="btn-chose-mau-prd" style="padding: 0px 4px;">Ip 15promax</button>'+
            '                </div>'+
            '                <div style="margin-top: 16px; display: flex;">'+
            '                    <div style="width: 110px; line-height: 40px;">Số lượng</div>'+
            '                    <div style="display: flex; align-items: center;">'+
            '                        <div style="margin-right: 15px;">'+
            '                            <div id="btnSoLuong" style="display: flex;align-items: center;background: #fff;">'+
            '                                <button id="btnTru">-</button>'+
            '                                <input type="" id="soLuong" value="1">'+
            '                                <button id="btnCong">+</button>'+
            '                            </div>'+
            '                        </div>'+
            '                        <div>578 sản phẩm có sẵn</div>'+
            '                    </div>'+
            '                </div>'+
            '            </div>'+
            '        </div>'+
            '        <div style="margin-top: 15px; padding-bottom: 20px ;display: flex;" class="oder-prod">'+
            '            <div style="padding-left: 20px; display: flex;">'+
            '                <button class="single-add-to-cart" onclick="HienLenGioHang()">'+
            '                    <i class="fa-solid fa-cart-plus" style="color: var(--primary-color2);"></i>'+
            '                    <span>Thêm Vào Giỏ Hàng</span>'+
            '                </button>'+
            '                <div></div>'+
            '            </div>'+
            '            <div style="padding-left: 20px; display: flex;">'+
            '                <button class=single-buy-now >'+
            '                    <span>Mua Ngay</span>'+
            '                </button>'+
            '                <div></div>'+
            '           </div>'+
            '        </div>'+
            '        <div style="height: 0.1px; width: 100%; background-color: #cecece;"></div>'+
            '        <div style="height: 20px; margin-top: 25px; padding: 0 20px; display: flex; font-size: 14px;">'+
            '            <div style="height: 20px; display: flex; width: 200px;">'+
            '                <!-- <img style="height: 20px;" src="assets/img/logo-icon2.png" alt=""> -->'+
            '                <div style="line-height: 20px; margin-left: 5px;">Quick đảm bảo </div>'+
            '            </div>'+
            '            <div style="line-height: 20px; margin-left: 20px;">Trả hàng miễn phí 15 ngày</div>'+
            '        </div>'+
            '    </selection>'

        document.getElementById("slt-pro").innerHTML = ThongTinTrangSingle;
}
