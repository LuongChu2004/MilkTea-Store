@extends('layouts.app')
@section('content')
@if($errors->any())
<div class='alert alert-danger'><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<main>
    <div class="container mt-5">
        <div class="row justify-content-center" style="margin-top: 15vh;">
            <div class="col-md-7" style="padding-bottom: 10vh;">
                <!-- Card Register -->
                <div class="card shadow-lg border-0 rounded-4 animate__animated animate__fadeInUp">
                    <div class="card-header bg-gradient bg-success text-white text-center rounded-top-4">
                        <h4 class="mb-0"></i> Đăng ký tài khoản</h4>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ url('register') }}">
                            @csrf
                            <!-- Full name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Họ và tên</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="name" name="name" required
                                        placeholder="Nhập họ và tên">
                                </div>
                            </div>

                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label fw-semibold">Tên đăng nhập</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-id-card"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" required
                                        placeholder="Nhập tài khoản">
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required
                                        placeholder="Mật khẩu">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Ít nhất 8 ký tự, gồm chữ, số và ký tự đặc biệt</small>
                            </div>

                            <!-- Re-Password -->
                            <div class="mb-3">
                                <label for="repassword" class="form-label fw-semibold">Nhập lại mật khẩu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                    <input type="password" class="form-control" id="repassword" name="password_confirmation"
                                        required placeholder="Nhập lại mật khẩu">
                                    <button type="button" class="btn btn-outline-secondary" id="toggleRePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="phone" name="phone" required
                                        placeholder="Số điện thoại">
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        placeholder="Nhập email">
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" name="submit"
                                class="btn btn-success w-100 py-2 fw-semibold shadow-sm register-btn">
                                <i class="fas fa-user-plus me-2"></i> Đăng ký
                            </button>

                            <!-- Extra -->
                            <div class="text-center mt-3">
                                <small class="text-muted">Bạn đã có tài khoản?
                                    <a href="{{ url('login') }}" class="text-decoration-none fw-semibold">Đăng nhập ngay</a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- CSS hiệu ứng -->
<style>
    .register-btn {
        transition: all 0.3s ease-in-out;
        border-radius: 50px;
    }

    .register-btn:hover {
        background: linear-gradient(45deg, #198754, #20c997);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .form-control:focus {
        border-color: #20c997;
        box-shadow: 0 0 0 0.2rem rgba(32, 201, 151, .25);
    }
</style>

<!-- JS Toggle password -->
<script>
    function togglePassword(inputId, iconBtn) {
        const input = document.getElementById(inputId);
        const icon = iconBtn.querySelector("i");
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    document.getElementById("togglePassword").addEventListener("click", function () {
        togglePassword("password", this);
    });

    document.getElementById("toggleRePassword").addEventListener("click", function () {
        togglePassword("repassword", this);
    });
</script>

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

@endsection