Chagge Store

**Chagge Store** là một dự án website bán hàng giúp quản lý và trưng bày sản phẩm trực tuyến.  
Dự án được xây dựng với **PHP**, **MySQL**, **HTML/CSS/JavaScript** và chạy tốt trên môi trường XAMPP, Laragon hoặc OpenServer.

🎯 Mục tiêu dự án
- Cung cấp nền tảng bán hàng trực tuyến đơn giản cho cửa hàng
- Quản lý danh mục và thông tin sản phẩm
- Hỗ trợ người dùng đăng ký, đăng nhập, đặt hàng và theo dõi đơn

⚙️ Công nghệ sử dụng
- **Ngôn ngữ**: PHP 7.4+, HTML5, CSS3, JavaScript
- **Cơ sở dữ liệu**: MySQL/MariaDB
- **Máy chủ phát triển**: XAMPP / OpenServer / Laragon
- **Trình duyệt hỗ trợ**: Chrome, Edge, Firefox

🔑 Tính năng chính
- Trang chủ hiển thị sản phẩm
- Tìm kiếm, lọc sản phẩm
- Đăng ký, đăng nhập tài khoản
- Giỏ hàng và thanh toán
- Quản lý sản phẩm (thêm, sửa, xóa – dành cho admin)
- Quản lý đơn hàng

📦 Cài đặt

### 1. Chuẩn bị môi trường
- Cài đặt **XAMPP** (hoặc OpenServer, Laragon)
- Bật **Apache** và **MySQL**

### 2. Tải và triển khai dự án
1. Tải dự án từ GitHub:
   git clone https://github.com/<tên-tài-khoản>/<repo>.git
   Hoặc tải file ZIP và giải nén vào thư mục:
   C:\xampp\htdocs\Chagge_Store

2. Tạo cơ sở dữ liệu:
   CREATE DATABASE chagge_store CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

3. Import file SQL (nếu có trong dự án):
   mysql -u root -p chagge_store < database.sql

4. Chỉnh kết nối CSDL trong config.php (hoặc file tương đương):
   $db_host = "localhost";
   $db_user = "root";
   $db_pass = "";
   $db_name = "chagge_store";

▶️ Chạy dự án
1. Khởi động Apache và MySQL.
2. Mở trình duyệt và truy cập:
   http://localhost/Chagge_Store
3. Đăng ký hoặc đăng nhập để trải nghiệm website.

📂 Gợi ý cấu trúc thư mục
Chagge_Store/
├── assets/        # CSS, JS, hình ảnh
├── database/      # File SQL
├── layout/        # Header, Footer
├── pages/         # Các trang giao diện
├── utils/         # Các hàm tiện ích
└── index.php      # Trang chính

📝 Ghi chú
- Điều chỉnh file config.php nếu thay đổi thông tin cơ sở dữ liệu.
- Kiểm tra quyền ghi thư mục nếu phát sinh lỗi upload hoặc lưu trữ.

📜 License
Dự án phát triển phục vụ học tập và thực hành cá nhân.
