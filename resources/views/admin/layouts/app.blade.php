<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Chagge Store</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('plugin/fontawesome/css/all.css') }}">
    <style>
        body { font-family: "Segoe UI", Tahoma, sans-serif; background: #f4f7fb; color: #333; }
        .nav-tabs { background: #343a40; border-bottom: none; padding: 0.5rem 1rem; }
        .nav-tabs .nav-link { color: #fff; margin-right: 10px; border: none; transition: 0.3s; }
        .nav-tabs .nav-link:hover { background: #495057; border-radius: 5px; }
        .nav-tabs .nav-link.active { background: #007bff; border-radius: 5px; }
        h2, h3 { text-align: center; margin-bottom: 20px; color: #222; }
    </style>
</head>
<body style="padding-bottom: 10vh;">
    <ul class="nav nav-tabs" style="padding-left: 21vw; width: 100vw;">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ url('admin') }}">Thống kê</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/category*') ? 'active' : '' }}" href="{{ url('admin/category') }}">Quản lý danh mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/product*') ? 'active' : '' }}" href="{{ url('admin/product') }}">Quản lý sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/order*') ? 'active' : '' }}" href="{{ url('admin/order') }}">Quản lý đơn hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/user*') ? 'active' : '' }}" href="{{ url('admin/user') }}">Quản lý người dùng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/') }}">Trang chủ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('logout') }}">Đăng xuất</a>
        </li>
    </ul>
    
    <div class="container-fluid mt-4">
        @yield('content')
    </div>
</body>
</html>