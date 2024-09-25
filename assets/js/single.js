// window.onload = () => {
//     // Select the button element
//     const addToCartBtn = document.querySelector('.add-to-cart-btn');
    
//     // Check if the button exists
//     if (addToCartBtn) {
//         // Add click event listener
//         addToCartBtn.addEventListener('click', () => {
//             // Call the function to handle adding to cart
//             HienLenGioHang();
//         });
//     } else {
//         // Log an error message to the console
//         console.error('Button with class ".add-to-cart-btn" not found');
        
//         // Show an alert to the user
//         alert('Button with class ".add-to-cart-btn" not found. Please check your HTML.');
//     }
// };

var giohang_CoSL = [];
// for (let i = 0; i <= 48; i++) {
//     giohang_CoSL.push(0);
// }


// function HienLenGioHang() {
//     let SltProLeft = document.getElementById("slt-pro-left");
//     let SltProRight = document.getElementById("slt-pro-right");
//     let btnSoLuong = document.getElementById("btnSoLuong");

//     let single_img = SltProLeft.children[0].children[0].children[0].src;
//     let single_price = SltProRight.children[3].children[1].value;
//     let single_count = btnSoLuong.children[1].value;

//     alert("day la anh trang single");
//     alert(single_img);
//     alert("day la anh trang single");
//     alert(single_price);
//     alert("day la anh trang single");
//     alert(single_count);
//     const fs = require('fs');

//     // Đọc dữ liệu từ tệp JSON
//     fs.readFile('data.json', 'utf8', (err, data) => {
//         if (err) throw err;

//         let jsonData;

//         try {
//             // Phân tích cú pháp dữ liệu JSON
//             jsonData = JSON.parse(data);
//         } catch (e) {
//             console.error('Error parsing JSON:', e);
//             return;
//         }

//         if (Array.isArray(jsonData)) {
//             // Trường hợp dữ liệu là mảng
//             jsonData.push({"img": single_img, "price": single_price, "count": single_count});
//         } else if (typeof jsonData === 'object' && jsonData !== null && jsonData.people) {
//             // Trường hợp dữ liệu là đối tượng với thuộc tính "people" là mảng
//             // jsonData.people.push({"img": single_img, "price": single_price, "count": single_count});
//             jsonData.people.push({"img": "single_img", "price": "single_price", "count": "single_count"});
//         } else {
//             console.error('Unexpected JSON structure');
//             return;
//         }

//         // Ghi dữ liệu trở lại vào tệp JSON
//         fs.writeFile('data.json', JSON.stringify(jsonData, null, 2), (err) => {
//             if (err) throw err;
//             console.log('Data updated successfully.');
//         });
//     });


//     // let single_sp_cosl = [single_img,single_price,single_count];

//     // giohang_CoSL.splice(dem, 1,([single_img,single_price,single_count]));
//     // alert("giohang_CoSL: "+giohang_CoSL);
    
//     // alert(giohang_CoSL.length);
//     // var ThongTinGioHangNho = 
//     // '    <li class="header__cart__notify-item">'+
//     // '        <a href="" class="header__cart__notify-link header__cart__notify-link--viewed">'+
//     // '            <img src="'+(gh[0][0])+'" alt="" class="header__cart__notify-img">'+
//     // '            <div class="header__cart__notify-info">'+
//     // '                <div class="header__cart__notify-name">Gia nhập Shopee thật là thích!</div>'+
//     // '                <div class="header__cart__notify-price"><div>₫</div>'+(gh[0][1])+'</div>'+
//     // '            </div>'+
//     // '        </a>'+
//     // '    </li>'

//     // document.getElementById("header__cart__notify-list").innerHTML = ThongTinGioHangNho;
// }