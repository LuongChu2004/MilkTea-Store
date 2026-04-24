<?php
require_once __DIR__ . '/../utils/utility.php';
require_once __DIR__ . '/../utils/config.php';
require_once __DIR__ . '/../database/config.php';
// Ensure session is started before any output (we will include header after processing POST to allow redirects)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_POST["submit"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $username = trim(strip_tags($_POST["username"]));
    $password = trim(strip_tags($_POST["password"]));
    // $password = md5($password); // Thay thế bằng cơ chế mã hóa mạnh hơn nếu cần.

    $con = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
    $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
    $user = mysqli_query($con, $sql);

    if ($username === 'Admin' && $password === '1010') {
        setcookie("username", $username, time() + 30 * 24 * 60 * 60, '/');
        setcookie("password", $password, time() + 30 * 24 * 60 * 60, '/');

        header("Location: /Chagge_Store/admin/index.php");
        exit();

    } else if (mysqli_num_rows($user) > 0) {
        setcookie("username", $username, time() + 30 * 24 * 60 * 60, '/');
        setcookie("password", $password, time() + 30 * 24 * 60 * 60, '/');    // Kiểm tra nếu có redirect URL từ sessionStorage (qua JavaScript)
        // Mặc định redirect về trang chủ
        // Mặc định redirect về trang chủ (absolute path to app root)
        $redirectUrl = '/Chagge_Store/index.php';

        echo '<script>
        // Kiểm tra nếu có URL redirect được lưu trong sessionStorage
        var redirectUrl = sessionStorage.getItem("redirectAfterLogin");
        if (redirectUrl) {
            sessionStorage.removeItem("redirectAfterLogin");
            window.location.href = redirectUrl;
        } else {
            window.location.href = "' . $redirectUrl . '";
        }
    </script>';
        exit();
    } else {
        echo '<script>
        alert("Tài khoản và mật khẩu không chính xác!"); 
        window.location = "login.php";
    </script>';
    }
}
?>
<?php require "../layout/header.php"; ?>
<main>
    <div class="container mt-5">
        <div class="row justify-content-center" style="margin-top: 20vh; padding-bottom: 10vh;">
            <div class="col-md-6">
                <!-- Card Login -->
                <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeInDown">
                    <div class="card-header bg-gradient bg-primary text-white text-center rounded-top-4">
                        <h4 class="mb-0"></i>Đăng nhập</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="">
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Tên đăng nhập</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required
                                        placeholder="Nhập tên đăng nhập">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required
                                        placeholder="Nhập mật khẩu">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" name="submit" style="margin-top: 20px;"
                                class="btn btn-primary w-100 py-2 fw-semibold shadow-sm login-btn">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập
                            </button>

                            <!-- Extra -->
                            <div class="text-center mt-3">
                                <small class="text-muted">Chưa có tài khoản? <a href="reg.php"
                                        class="text-decoration-none fw-semibold">Đăng ký ngay</a></small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Thêm CSS cho hiệu ứng -->
<style>
    .login-btn {
        transition: all 0.3s ease-in-out;
        border-radius: 50px;
    }

    .login-btn:hover {
        background: linear-gradient(45deg, #0d6efd, #6610f2);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
    }
</style>

<!-- Thêm JS để toggle password -->
<script>
    document.getElementById("togglePassword").addEventListener("click", function () {
        const password = document.getElementById("password");
        const icon = this.querySelector("i");
        if (password.type === "password") {
            password.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            password.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
</script>

<!-- Nhúng Animate.css (hiệu ứng animation khi load form) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<?php require_once('../layout/footer.php'); ?>
