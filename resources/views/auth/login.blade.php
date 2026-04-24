@extends('layouts.app')
@section('content')
@if(session('error'))
<div class='alert alert-danger text-center'>{{ session('error') }}</div>
@endif
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
                        <form method="POST" action="{{ url('login') }}">
                            @csrf
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
                                <small class="text-muted">Chưa có tài khoản? <a href="{{ url('register') }}"
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

@endsection