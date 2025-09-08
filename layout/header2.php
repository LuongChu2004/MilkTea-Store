<!DOCTYPE html>
<html lang="en">
<?php
require_once __DIR__ . '/../utils/config.php';
require_once __DIR__ . '/../database/config.php';
require_once __DIR__ . '/../database/dbhelper.php';
require_once __DIR__ . '/../utils/utility.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
//     echo "<script>alert('Welcome back!');</script>";

// } else {
//     echo"<script>alert('Please log in to continue.');</script>";
// }

?>

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/details.css">
    <link rel="stylesheet" href="plugin/fontawesome/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="/Chagge_Store/images/favicon/favicon.ico" type="image/x-icon" sizes="128x128">

    <title>CHAGGE</title>

    <!-- Custom CSS cho responsive product cards -->
    <style>
        /* Product Card Styling */
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .card-img-top-wrapper {
            position: relative;
            overflow: hidden;
            height: 200px;
        }

        .product-thumbnail {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-thumbnail {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1.4;
            margin-bottom: 0.5rem;
            color: #333;
            /* Giới hạn text trong 2 dòng */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .price-badge {
            font-size: 1rem !important;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .card-img-top-wrapper {
                height: 150px;
            }

            .product-title {
                font-size: 1rem;
            }

            .price-badge {
                font-size: 0.9rem !important;
                padding: 0.4rem 0.8rem;
            }
        }

        @media (min-width: 992px) {
            .card-img-top-wrapper {
                height: 220px;
            }
        }

        /* Search section responsive */
        .search-quan {
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
            .search-quan {
                margin: 1rem 0;
            }

            .search-quan input {
                font-size: 14px;
            }
        }

        /* Carousel responsive */
        .program-carousel {
            margin: 2rem 0;
        }

        .program-carousel img {
            border-radius: 12px;
            max-height: 300px;
            object-fit: cover;
        }

        @media (max-width: 768px) {
            .program-carousel img {
                max-height: 200px;
            }
        }

        /* Responsive Pagination */
        .pagination {
            gap: 0.25rem;
        }

        .pagination .page-link {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            color: #495057;
            transition: all 0.2s ease;
        }

        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #adb5bd;
            color: #495057;
        }

        .pagination .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        /* Mobile pagination improvements */
        @media (max-width: 576px) {
            .pagination {
                gap: 0.15rem;
            }

            .pagination .page-link {
                padding: 0.375rem 0.5rem;
                font-size: 0.875rem;
                min-width: 36px;
                text-align: center;
            }

            .pagination .page-item:not(.disabled) .page-link {
                margin: 0 2px;
            }

            /* Ẩn một số trang trên màn hình rất nhỏ */
            .pagination .page-item:nth-child(n+6):nth-last-child(n+4) {
                display: none;
            }
        }

        @media (max-width: 420px) {
            .pagination .page-link {
                padding: 0.25rem 0.4rem;
                font-size: 0.8rem;
                min-width: 32px;
            }
        }

        .nav-link {
            font-size: 16px !important;
        }


        /*header menu*/
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background: transparent;
            /* trong suốt */
            transition: background 0.3s ease, box-shadow 0.3s ease;
            border: none !important;
            /* xóa viền */
            box-shadow: none !important;
            /* xóa bóng */
            padding: 0;
            /* bỏ padding thừa */
            margin: 0;
            /* bỏ margin thừa */
            height: auto;
            /* header cao theo nav */
        }

        header.hide {
            /* slide header up when hiding on scroll */
            transform: translateY(-120%);
            transition: transform 0.25s ease;
        }

        /* Khi scroll hoặc hover đổi chữ đen */
        header.scrolled .menu-link,
        header:hover .menu-link {
            color: #333 !important;
        }

        /* Hover link menu */
        header .menu-link:hover {
            color: #023a7dff !important;
            border-bottom: 2px solid;
        }

        .menu-link {
            position: relative;
            color: #333 !important;
            text-decoration: none;
        }

        .menu-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            background: #02347fff;
            transition: width 0.3s ease;
        }

        .menu-link:hover::after {
            width: 100%;
            /* khi hover thì gạch chạy ra */
        }


        /* Dropdown menu bo góc và hiệu ứng */
        .dropdown-toggle::after {
            display: none !important;
            /* Ẩn mũi tên mặc định */
        }

        /* Ẩn mặc định */
        .dropdown-menu {
            display: none;
            margin-top: 0;
            /* gọn gàng */
        }

        /* Hover vào cha thì hiện menu con */
        .dropdown:hover .dropdown-menu {
            display: block;
            animation: fadeIn 0.3s ease;
            /* Hiệu ứng trượt xuống */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <!-- ===== HEADER START ===== -->
        <header class="shadow-sm">
            <nav class="navbar navbar-expand-lg ">
                <div class="container d-flex align-items-center justify-content-between">

                    <!-- LOGO -->
                    <a class="navbar-brand" href="index.php" style="padding-left: 10vw;">
                        <img src="/Chagge_Store/images/icon/Chagge-Logo.png" alt="Logo" style="height:60px;">
                    </a>
                    <a href="index.php" style="text-decoration: none; color: #333;padding-left: 0px !important;"><b
                            style="font-size:18px">C H A G G E</b></a>

                    <!-- TOGGLER (Hiện trên mobile) -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- MENU -->
                    <div class="collapse navbar-collapse justify-content-center" id="navbarMain">
                        <ul class="navbar-nav align-items-center gap-3">
                            <!-- Home -->
                            <li class="nav-item">
                                <a class="nav-link text-uppercase fw-semibold " href="index.php">Home</a>
                            </li>

                            <!-- Mega Menu -->
                            <li class="nav-item position-relative mega-menu-parent">
                                <a class="nav-link text-uppercase fw-semibold" href="#">Danh Mục</a>
                                <div class="mega-menu-container shadow">
                                    <?php
                                    $cats = executeResult("SELECT * FROM category ORDER BY name");
                                    $perCol = 5;
                                    $chunks = array_chunk($cats, $perCol);
                                    foreach ($chunks as $chunk) {
                                        echo '<ul class="megamenu-col">';
                                        foreach ($chunk as $c) {
                                            echo '<li><a href="thucdon.php?id_category=' . $c['id'] . '">' . htmlspecialchars($c['name']) . '</a></li>';
                                        }
                                        echo '</ul>';
                                    }
                                    ?>
                                </div>
                            </li>

                            <!-- HaLal -->
                            <li class="nav-item">
                                <a class="nav-link text-uppercase fw-semibold" href="halal.php">HALAL</a>
                            </li>

                            <!-- About -->
                            <li class="nav-item">
                                <a class="nav-link text-uppercase fw-semibold" href="about.php">About Us</a>
                            </li>

                            <!-- Contact -->
                            <li class="nav-item">
                                <a class="nav-link text-uppercase fw-semibold" href="sendMail.php">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Cart + User -->
                <div style="display: flex; padding-right: 5vw; gap:30px;">
                    <a href="cart.php" class="btn btn-outline-primary position-relative rounded-circle p-2">
                        <?php echo getShoppingBagIcon(); ?>
                        <?php
                        /*
                        $count = 0;
                        if (isset($_COOKIE['cart'])) {
                            foreach (json_decode($_COOKIE['cart'], true) as $it) $count += $it['num'];
                        }
                        if ($count) {
                            echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">'
                                  .$count.'</span>';
                        }
                        */
                        ?>
                    </a>

                    <!-- User -->
                    <?php if (isset($_COOKIE['username'])): ?>
                        <?php $u = htmlspecialchars($_COOKIE['username']); ?>
                        <div class="dropdown">
                            <a class="btn btn-outline-primary rounded-pill px-3 py-1 dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i> <?= $u ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                                <?php if ($u === 'Admin'): ?>
                                    <li><a class="dropdown-item" href="admin/"><i class="fas fa-user-edit me-1"></i>Admin</a>
                                    </li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="login/changePass.php"><i
                                                class="fas fa-exchange-alt me-1"></i>Đổi mật khẩu</a></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item text-danger" href="login/logout.php"><i
                                            class="fas fa-sign-out-alt me-1"></i>Đăng xuất</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="login/login.php" class="btn btn-primary rounded-pill px-3 py-1">
                            <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                        </a>
                    <?php endif; ?>
                </div>
            </nav>
        </header>
        <!-- ===== HEADER END ===== -->


        <!-- optional: tweak icon size on mobile -->
        <style>
            .navbar-nav .nav-link {
                font-size: 1.5rem;
                padding: 0.5rem 1rem;
                color: #000;
            }

            .navbar-nav .nav-link:hover,
            .navbar-nav .nav-link:focus {
                color: #a05b2a;
                /* Màu nâu cam chủ đạo */
            }

            @media (max-width: 767.98px) {
                .navbar-nav {
                    gap: 1rem !important;
                }

                .navbar-brand img {
                    height: 50px !important;
                }
            }
        </style>
    </div>
    <script>
        const header = document.querySelector("header");
        let lastScrollY = 0;

        window.addEventListener("scroll", function () {
            const currentScrollY = window.scrollY;

            // đổi màu nền khi scroll > 50px
            if (currentScrollY > 50) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }

            // khi scroll vượt quá chiều cao 1 màn hình
            if (currentScrollY > window.innerHeight) {
                if (currentScrollY > lastScrollY) {
                    // đang cuộn xuống -> ẩn
                    header.classList.add("hide");
                } else {
                    // đang cuộn lên -> hiện
                    header.classList.remove("hide");
                }
            } else {
                // chưa hết 1 màn hình -> luôn hiện
                header.classList.remove("hide");
            }

            lastScrollY = currentScrollY;
        });
    </script>
    <style>
        /* Mega menu position */
        .mega-menu-parent {
            position: relative;
        }

        .mega-menu-container {
            position: absolute;
            left: 0;
            top: 100%;
            /* ngay dưới chữ Danh mục */
            width: 100vw;
            /* tuỳ ý */
            background: #fff;
            padding: 1rem 2rem;
            display: none;
            z-index: 999;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        /* Hiện khi hover */
        .mega-menu-parent:hover .mega-menu-container {
            display: flex;
            /* flex cho các ul nằm cạnh nhau */
            gap: 2rem;
        }

        /* Cột danh mục */
        .mega-menu-container ul {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .mega-menu-container li {
            margin-bottom: .5rem;
        }

        .mega-menu-container a {
            color: #333;
            text-decoration: none;
            font-size: 15px;
        }

        .mega-menu-container a:hover {
            color: #023a7d;
        }

        /* Đảm bảo header khi scroll */
        header.scrolled {
            background: #fff !important;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }
    </style>
</body>

</html>