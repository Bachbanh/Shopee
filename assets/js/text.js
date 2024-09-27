// const fs = require('fs');

// // Dữ liệu bạn muốn ghi vào file
// const data = {
//   name: 'John Doe',
//   age: 40,
//   city: 'New York'
// };

// // Chuyển đổi dữ liệu thành chuỗi JSON
// const jsonData = JSON.stringify(data);

// // Đường dẫn đến file bạn muốn ghi
// const filePath = 'data.json';

// // Ghi dữ liệu vào file
// fs.writeFile(filePath, jsonData, (err) => {
//   if (err) {
//     console.error(err);
//   } else {
//     console.log('Data written successfully to file.');
//   }
// });

// fs.readFile(filePath, 'utf8', (err, data) => {
//   if (err) {
//     console.error('Error reading file:', err);
//   } else {
//     // Chuyển đổi chuỗi JSON thành đối tượng JavaScript
//     const jsonData = JSON.parse(data);
//     console.log(jsonData);
//   }
// });

const fs = require('fs');

// Đọc dữ liệu từ tệp JSON
fs.readFile('data.json', 'utf8', (err, data) => {
  if (err) throw err;

  let jsonData;

  try {
    // Phân tích cú pháp dữ liệu JSON
    jsonData = JSON.parse(data);
  } catch (e) {
    console.error('Error parsing JSON:', e);
    return;
  }

  if (Array.isArray(jsonData)) {
    // Trường hợp dữ liệu là mảng
    jsonData.push({"name": "haaa Dretoe", "age": 50, "city": "lát York"});
  } else if (typeof jsonData === 'object' && jsonData !== null && jsonData.people) {
    // Trường hợp dữ liệu là đối tượng với thuộc tính "people" là mảng
    jsonData.people.push({"name": "haaa ghfgh", "age": 4670, "city": "lát York"});
  } else {
    console.error('Unexpected JSON structure');
    return;
  }

  // Ghi dữ liệu trở lại vào tệp JSON
  fs.writeFile('data.json', JSON.stringify(jsonData, null, 2), (err) => {
    if (err) throw err;
    console.log("Data updated successfully.");
  });
});
