<?php
require "../layout/header.php";
require_once('../database/config.php');
require_once('../database/dbhelper.php');
require_once('../utils/utility.php');

// Import PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../libs/PHPMailer-master/src/PHPMailer.php';
require '../libs/PHPMailer-master/src/SMTP.php';
require '../libs/PHPMailer-master/src/Exception.php';
?>

<main class="d-flex justify-content-center align-items-center min-vh-100">
  <div class="row w-100 shadow-lg rounded-4 overflow-hidden" style="max-width: 900px; background: white;">
    <!-- Cột bên trái -->
    <div class="col-md-5 d-flex flex-column justify-content-center text-white p-4"
         style="background: linear-gradient(135deg,#4facfe,#00f2fe);">
      <h2 class="fw-bold">CHAGGE STORE</h2>
      <p class="mt-3">Hãy điền thông tin để chúng tôi có thể hỗ trợ bạn khôi phục mật khẩu nhanh chóng.</p>
      <img src="https://cdn-icons-png.flaticon.com/512/295/295128.png" 
           class="img-fluid mt-auto" alt="Reset Password Illustration">
    </div>

    <!-- Cột bên phải (form) -->
    <div class="col-md-7 p-5">
      <h3 class="fw-bold text-center mb-4 text-primary">🔑 Khôi phục mật khẩu</h3>
      
      <form method="POST" id="forgetForm">
        <div class="mb-3">
          <label class="form-label">Họ và tên</label>
          <input type="text" class="form-control rounded-pill" name="name" placeholder="Nhập họ tên" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" class="form-control rounded-pill" name="email" placeholder="Nhập email" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Số điện thoại</label>
          <input type="text" class="form-control rounded-pill" name="phone" placeholder="Nhập số điện thoại" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Lý do</label>
          <textarea class="form-control" name="message" rows="3" placeholder="Nhập lý do yêu cầu"></textarea>
        </div>

        <div class="d-grid gap-2">
          <button name="send" class="btn btn-gradient fw-bold">
            📩 Gửi yêu cầu
          </button>
          <a href="javascript:history.back()" class="btn btn-outline-secondary rounded-pill">
            ⬅ Quay lại
          </a>
        </div>
      </form>
    </div>
  </div>
</main>

<style>
body {
  background: linear-gradient(120deg,#fdfbfb,#ebedee);
}
.btn-gradient {
  background: linear-gradient(135deg,#4facfe,#00f2fe);
  color: white;
  border: none;
  border-radius: 50px;
  transition: 0.3s;
}
.btn-gradient:hover {
  background: linear-gradient(135deg,#00f2fe,#4facfe);
}
</style>

<script>
document.getElementById("forgetForm").addEventListener("submit", function(e) {
  let email = document.querySelector("input[name='email']").value.trim();
  let phone = document.querySelector("input[name='phone']").value.trim();
  let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  let phoneRegex = /^[0-9]{10,11}$/;

  if (!emailRegex.test(email)) {
    alert("⚠️ Vui lòng nhập email hợp lệ!");
    e.preventDefault();
  }
  if (!phoneRegex.test(phone)) {
    alert("⚠️ Số điện thoại phải có 10-11 chữ số!");
    e.preventDefault();
  }
});
</script>


<?php require "../layout/footer.php"; ?>
