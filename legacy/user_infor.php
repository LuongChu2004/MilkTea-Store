<?php
require "layout/header.php";
require_once('database/config.php');
require_once('database/dbhelper.php');
require_once('utils/utility.php');

$conn = getDbConnection();

// Kiểm tra đăng nhập
if (!isset($_COOKIE['username'])) {
    echo '<script>alert("Vui lòng đăng nhập trước!"); window.location="login/login.php";</script>';
    exit();
}
$username = $_COOKIE['username'];

// Lấy thông tin user
$sql = "SELECT * FROM user WHERE username = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo '<script>alert("Không tìm thấy người dùng!"); window.location="login/login.php";</script>';
    exit();
}

// Cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newFullname = trim($_POST['fullname']);
    $newPhone = trim($_POST['phone']);
    $newEmail = trim($_POST['email']);

    if ($newFullname && $newPhone && $newEmail) {
        // Kiểm tra email trùng
        $checkEmail = $conn->prepare("SELECT id_user FROM user WHERE email=? AND id_user!=?");
        $checkEmail->bind_param("si", $newEmail, $user['id_user']);
        $checkEmail->execute();
        $checkEmail->store_result();

        if ($checkEmail->num_rows > 0) {
            echo '<script>alert("⚠️ Email đã tồn tại!");</script>';
        } else {
            $sqlUpdate = "UPDATE user SET hoten=?, phone=?, email=? WHERE id_user=?";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bind_param("sssi", $newFullname, $newPhone, $newEmail, $user['id_user']);
            if ($stmt->execute()) {
                echo '<script>alert("✅ Cập nhật thông tin thành công!"); window.location="user_infor.php";</script>';
            } else {
                echo '<script>alert("❌ Cập nhật thất bại!");</script>';
            }
            $stmt->close();
        }
        $checkEmail->close();
    }
}
?>

<main class="container py-5" style="max-width: 800px; padding-top: 15vh !important;">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="row g-0">
            <!-- Avatar + Info -->
            <div class="col-md-4 bg-light d-flex flex-column align-items-center justify-content-center p-4">
                <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Avatar"
                    class="rounded-circle mb-3 border border-3 border-primary" width="120" height="120">
                <h5 class="fw-bold text-primary mb-1"><?= htmlspecialchars($user['username']) ?></h5>
                <p class="text-muted small mb-2"><?= htmlspecialchars($user['email']) ?></p>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <a href="login/changePass.php" class="btn btn-danger rounded-circle p-3" title="Đổi mật khẩu">
                        <i class="fas fa-key text-white"></i>
                    </a>
                    <a href="login/logout.php" class="btn btn-dark rounded-circle p-3" title="Đăng xuất"
                        onclick="return confirm('Bạn có chắc chắn muốn đăng xuất không?');">
                        <i class="fas fa-sign-out-alt text-white"></i>
                    </a>
                </div>
            </div>


            <!-- Form -->
            <div class="col-md-8 p-5">
                <h4 class="fw-bold mb-4 text-center text-uppercase text-primary">Thông tin tài khoản</h4>
                <form method="post" id="userForm">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Họ và tên</label>
                        <input type="text" class="form-control shadow-sm" name="fullname"
                            value="<?= htmlspecialchars($user['hoten'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên đăng nhập</label>
                        <input type="text" class="form-control shadow-sm"
                            value="<?= htmlspecialchars($user['username']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" class="form-control shadow-sm" name="phone" id="phone"
                            value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control shadow-sm" name="email" id="email"
                            value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                            💾 Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById("userForm").addEventListener("submit", function (e) {
        let phone = document.getElementById("phone").value.trim();
        let email = document.getElementById("email").value.trim();

        let phoneRegex = /^[0-9]{10,11}$/;
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!phoneRegex.test(phone)) {
            alert("⚠️ Số điện thoại phải có 10-11 chữ số!");
            e.preventDefault();
            return false;
        }

        if (!emailRegex.test(email)) {
            alert("⚠️ Vui lòng nhập email hợp lệ!");
            e.preventDefault();
            return false;
        }
    });
</script>
<style>
.d-flex.gap-2 .btn {
    font-weight: 500;
    transition: all 0.2s ease-in-out;
}
.d-flex.gap-2 .btn:hover {
    transform: translateY(-2px);
}
</style>
<?php require_once('layout/footer.php'); ?>