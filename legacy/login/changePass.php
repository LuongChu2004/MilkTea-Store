<?php
require "../layout/header.php";
require_once('../database/config.php');
require_once('../database/dbhelper.php');
require_once('../utils/utility.php');

$conn = getDbConnection();

// Kiểm tra đăng nhập
if (!isset($_COOKIE['username'])) {
    echo '<script>alert("Vui lòng đăng nhập trước!"); window.location="login.php";</script>';
    exit();
}

$username = $_COOKIE['username'];

// Xử lý đổi mật khẩu
if (isset($_POST["submit"])) {
    $password      = trim($_POST["password"]);
    $passwordnew   = trim($_POST["password-new"]);
    $repasswordnew = trim($_POST["repassword-new"]);

    if ($password && $passwordnew && $repasswordnew) {
        $sql = "SELECT * FROM user WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$result) {
            echo '<script>alert("Không tìm thấy người dùng!"); window.location="login.php";</script>';
            exit();
        }

        if ($password !== $result['password']) {
            echo '<script>alert("⚠️ Mật khẩu hiện tại không đúng!"); window.location="changePass.php";</script>';
            exit();
        }

        if ($passwordnew !== $repasswordnew) {
            echo '<script>alert("⚠️ Mật khẩu mới nhập lại không khớp!"); window.location="changePass.php";</script>';
            exit();
        }

        $updateSql = "UPDATE user SET password=? WHERE username=?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ss", $passwordnew, $username);
        if ($stmt->execute()) {
            echo '<script>alert("✅ Đổi mật khẩu thành công! Vui lòng đăng nhập lại."); window.location="login.php";</script>';
            setcookie("username", "", time() - 3600, "/");
            setcookie("password", "", time() - 3600, "/");
        } else {
            echo '<script>alert("❌ Đổi mật khẩu thất bại!");</script>';
        }
        $stmt->close();
    }
}
?>

<main class="d-flex align-items-center justify-content-center min-vh-100" 
      style="background: #f9f9f9f0; padding-top: 10vh !important;">
  <div class="card shadow-lg border-0 rounded-4 p-5" style="max-width: 420px; width: 100%;">
    
    <div class="text-center mb-4">
      <img src="https://cdn-icons-png.flaticon.com/512/942/942751.png" 
           alt="Password Icon" width="80" class="mb-3">
      <h3 class="fw-bold text-primary">Đổi mật khẩu</h3>
      <p class="text-muted small">Hãy nhập mật khẩu cũ và mật khẩu mới của bạn.</p>
    </div>

    <form method="POST" id="changePassForm">
      <div class="mb-3">
        <label class="form-label">🔒 Mật khẩu hiện tại</label>
        <input type="password" name="password" class="form-control rounded-pill" required>
      </div>

      <div class="mb-3">
        <label class="form-label">🔑 Mật khẩu mới</label>
        <input type="password" name="password-new" id="password-new" class="form-control rounded-pill" required>
      </div>

      <div class="mb-3">
        <label class="form-label">🔑 Nhập lại mật khẩu mới</label>
        <input type="password" name="repassword-new" id="repassword-new" class="form-control rounded-pill" required>
      </div>

      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="showPassword">
        <label class="form-check-label" for="showPassword">Hiện mật khẩu</label>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" name="submit" class="btn btn-gradient fw-bold rounded-pill">💾 Lưu thay đổi</button>
        <a href="forget.php" class="btn btn-outline-secondary rounded-pill">❓ Quên mật khẩu?</a>
      </div>
    </form>
  </div>
</main>

<style>
.btn-gradient {
  background: #00f2fe;
  color: white;
  border: none;
  transition: 0.3s;
}
.btn-gradient:hover {
  background: linear-gradient(135deg,#764ba2,#667eea);
}
</style>

<script>
document.getElementById("showPassword").addEventListener("change", function() {
  const pw1 = document.getElementById("password-new");
  const pw2 = document.getElementById("repassword-new");
  if (this.checked) {
    pw1.type = "text";
    pw2.type = "text";
  } else {
    pw1.type = "password";
    pw2.type = "password";
  }
});

document.getElementById("changePassForm").addEventListener("submit", function(e) {
  let pw1 = document.getElementById("password-new").value.trim();
  let pw2 = document.getElementById("repassword-new").value.trim();
  if (pw1 !== pw2) {
    alert("⚠️ Mật khẩu mới và nhập lại không khớp!");
    e.preventDefault();
  }
});
</script>

<?php require_once('../layout/footer.php'); ?>
