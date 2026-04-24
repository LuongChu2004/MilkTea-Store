<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/details.css') }}">
    <link rel="stylesheet" href="{{ asset('plugin/fontawesome/css/all.css') }}">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="{{ asset('images/favicon/favicon.ico') }}" type="image/x-icon" sizes="128x128">

    <title>CHAGGE</title>

    <!-- Custom CSS cho responsive product cards -->
</head>

<body>
    <div id="wrapper">
        <!-- ===== HEADER START ===== -->
        <header>
            <nav class="navbar navbar-expand-lg ">
                <div class="container d-flex align-items-center justify-content-between">

                    <!-- LOGO -->
                    <a class="navbar-brand" href="{{ url('/') }}" style="padding-left: 10vw;">
                        <img src="{{ asset('images/icon/Chagge-Logo.png') }}" alt="Logo" style="height:60px;">
                    </a>
                    <a href="{{ url('/') }}" style="text-decoration: none; color: #333;padding-left: 0px !important;"><b
                            style="font-size:18px">C H A G G E</b></a>

                    <!-- TOGGLER (Hiện trên mobile) -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <!-- MENU -->
                    <div class="collapse navbar-collapse justify-content-center" id="navbarMain">


                        <!--menu mega-->
                        <ul class="navbar-nav align-items-center gap-3" style="padding-right: 100px;">
                            <!-- HOME-->
                            <li class="nav-item  position-relative menu-wrapper" style="padding: 0px 10px"><a
                                    class="nav-link text-uppercase fw-semibold menu-link" href="{{ url('/') }}">HOME</a>
                            </li>
                            <!--MENU-->
                            <li class="nav-item position-relative menu-wrapper" ; style="padding: 0px 10px"><a
                                    class="nav-link text-uppercase fw-semibold menu-link" href="{{ url('thucdon') }}">MENU</a>
                                <div class="submenu-container">
                                    <div class="submenu">
                                        @foreach ($global_categories->chunk(5) as $chunk)
                                            <ul>
                                            @foreach ($chunk as $c)
                                                <li class="li-row"><a class="text-uppercase fw-semibold" href="{{ url('thucdon?id_category=' . $c->id) }}">{{ $c->name }}</a></li>
                                            @endforeach
                                            </ul>
                                        @endforeach

                                        <ul>

                                        </ul>
                                        <ul>

                                        </ul>
                                        <ul>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item  position-relative menu-wrapper" ; style="padding: 0px 10px"><a
                                    class="nav-link text-uppercase fw-semibold menu-link" href="{{ url('halal') }}">HALAL</a>
                            </li>
                            <li class="nav-item  position-relative menu-wrapper" ; style="padding: 0px 10px"><a
                                    class="nav-link text-uppercase fw-semibold menu-link" href="#">ABOUT US</a>
                                <div class="submenu-container">
                                    <div class="submenu">
                                        <ul>

                                        </ul>
                                        <ul>
                                            <li class="li-row" style="font-weight: bold; cursor: pointer;"
                                                onclick="window.location.href='{{ url('about') }}'">
                                                INTRODUCTION
                                            </li>
                                            <li class="li-row" href="#" style="font-weight: bold;"> HISTORY</li>
                                            <li class="li-row" href="#" style="font-weight: bold;"> INVESTOR RELATION
                                            </li>
                                        </ul>
                                        <ul>

                                        </ul>
                                        <ul>

                                        </ul>
                                        <ul>
                                            <li class="li-row" href="{{ url('about') }}"></li>
                                            <li class="li-row" href="#"> </li>
                                            <li class="li-row" href="#"> </li>
                                        </ul>
                                        <ul>

                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item  position-relative menu-wrapper" ; style="padding: 0px 10px"><a
                                    class="nav-link text-uppercase fw-semibold menu-link"
                                    href="{{ url('contact') }}">CONTACT</a>
                            </li>
                        </ul>


                    </div>
                </div>
                <!-- Cart + User -->
                <div style="display: flex; padding-right: 5vw; gap:30px; height: 5vh;">
                    <a href="{{ url('cart') }}" class="btn btn-outline-primary position-relative rounded-circle p-2">
                        <i class="fas fa-shopping-bag"></i>
                        @php
                            $cart = session()->get('cart', []);
                            $count = 0;
                            foreach ($cart as $it) {
                                $count += $it['num'];
                            }
                        @endphp
                        @if ($count > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $count }}</span>
                        @endif
                    </a>

                    <!-- User -->
                    @if (Auth::check())
                        <div class="dropdown">
                            <button class="btn btn-outline-primary position-relative rounded-circle p-2" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false" style="height: 100%; width: 2.1vw; display: flex; align-items: center; justify-content: center; border: none;">
                                <i class="fas fa-user"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <li><span class="dropdown-item-text fw-bold text-primary">👋 Xin chào, {{ Auth::user()->name ?? Auth::user()->username }}</span></li>
                                <li><hr class="dropdown-divider"></li>
                                @if(session('admin_logged_in') || Auth::user()->username === 'Admin')
                                    <li><a class="dropdown-item" href="{{ url('admin') }}"><i class="fas fa-cog me-2"></i> Quản trị Admin</a></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ url('history') }}"><i class="fas fa-clipboard-list me-2"></i> Lịch sử mua hàng</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ url('login') }}" class="btn btn-primary rounded-pill px-3 py-1">
                            <i class="fas fa-sign-in-alt me-1"></i>
                        </a>
                    @endif
                </div>
            </nav>
        </header>

        <style>
            .navbar-nav {
                flex-grow: 1;
                justify-content: right;
                align-items: center;
            }

            .nav-item a {
                color: black;
                font-size: 12px;
                padding-right: 10px;
            }

            .nav-item a:hover {
                color: #404040;
            }

            .nav-item img {
                height: 18px;
            }

            .nav-item {
                position: relative;
            }

            .nav-item:hover .submenu-container {
                visibility: visible;
                opacity: 1;
                background: rgba(0, 0, 0, 0.6);
            }

            .nav-item:hover .submenu {
                transform: translateY(0);
                opacity: 1;
            }

            .right-icon a i {
                color: black;
                margin: 0 15px;
            }

            .navbar-toggler {
                border: none;
            }

            .navbar-toggler:focus {
                box-shadow: none;
            }

            .navbar-nav.ms-auto {
                margin-left: auto;
                padding-right: 20px;
            }

            .nav-menu a {
                margin-left: 15px;
                text-decoration: none;
                color: black;
                font-size: 14px;
                transition: color 0.3s;
            }

            .nav-menu a:hover {
                color: #0071e3;
            }

            /* Submenu */
            .submenu-container {
                position: fixed;
                top: 10vh;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0);
                /* Ban đầu trong suốt */
                visibility: hidden;
                opacity: 0;
                transition: opacity .3s ease, visibility .3s ease;
            }

            .nav-item:hover .submenu-container {
                visibility: visible;
                opacity: 1;
            }

            .submenu {
                width: 100vw;
                max-height: 70vh;
                background: white;
                position: absolute;
                top: 0;
                left: 0;
                display: grid;
                grid-template-columns: 320px 200px 200px;
                align-items: start;
                justify-items: center;
                transform: translateY(-10%);
                opacity: 0;
                transition: transform 0.4s ease-in-out, opacity 0.4s ease-in-out;
                padding: 30px;
                padding-right: 20px;
                padding-left: 35vw;
                /* padding-right: 13vw; */

            }

            .submenu ul {
                list-style: none;
                display: flex;
                flex-direction: column;
                gap: 5px;
                width: 100%;
                padding-left: 30px;
            }

            .submenu ul li {
                background: white;
                border-radius: 5px;
                cursor: pointer;
                text-align: left;
                width: 100%;
                font-family: "SF Pro Text", "SF Pro Icons", "Helvetica Neue", "Helvetica", "Arial", sans-serif;

            }

            .submenu ul li:hover {
                background: white;
            }

            .header-li {
                font-size: 12px;
                color: #6E6E73;
            }

            .li-rowh {
                font-size: 24px;
                font-weight: bold;
            }

            .li-row {
                font-size: 12px;
            }


            .categories-list {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                /* gap: 10px; */
                padding: 0;
                list-style: none;
                text-align: center;
            }

            .categories-list li {
                flex: 1 1 calc(10% - 20px);
                max-width: 100px;
                padding-top: 10px;
            }

            .categories-list li a {
                display: flex;
                flex-direction: column;
                align-items: center;
                text-decoration: none;
                color: black;
                font-size: 12px;
            }

            .categories-list li img {
                width: 50px;
                height: 56px;
                display: block;
                margin: 0 auto;
            }

            .content-wrapper {
                width: 100%;
                height: 40px;
                background-color: rgb(245, 242, 242);
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
            }

            .content-wrapper p {
                font-size: 14px;
                padding-top: 10px;
            }
        </style>
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
        <style>
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
                background: transparent !important;
                /* trong suốt */
                transition: background 0.3s ease, box-shadow 0.3s ease;
                border: none !important;
                box-shadow: none !important;
                padding: 0;
                margin: 0;
                height: auto;
            }

            header.hide {
                /* slide header up when hiding on scroll */
                transform: translateY(-120%);
                transition: transform 0.25s ease;
            }

            /* Hover link menu */
            header .menu-link:hover {
                color: #023a7dff !important;
                border-bottom: 2px solid;
            }

            /* Giữ hiệu ứng mượt nhưng khi scroll hoặc hover thì đổi nền */
            header.scrolled,
            header:hover {
                background: linear-gradient(90deg, #ebe6e6ff, #ff7b54) !important;
                /* hoặc màu bạn muốn */
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            }

            /* Giữ nguyên đoạn đổi màu chữ nếu thích */
            header.scrolled .menu-link,
            header:hover .menu-link {
                color: #333;
            }

            .menu-link {
                color: #fff;
                text-decoration: none;
                position: relative;
                transition: color 0.3s ease;
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
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const header = document.querySelector("header");
            let lastScrollY = 0;
            window.addEventListener("scroll", () => {
                const currentScrollY = window.scrollY;

                if (currentScrollY > 50) header.classList.add("scrolled");
                else header.classList.remove("scrolled");

                if (currentScrollY > window.innerHeight) {
                    if (currentScrollY > lastScrollY) header.classList.add("hide");
                    else header.classList.remove("hide");
                } else header.classList.remove("hide");

                lastScrollY = currentScrollY;
            });
        });

    </script>



</body>

</html>
<main>
    @yield('content')
</main>
<!-- footer -->
<footer class="footer">
  <div class="footer-top">
    <div class="footer-col">
      <h3>ABOUT US</h3>
      <a href="#">Introduction</a>
      <a href="#">History</a>
      <a href="#">Investor Relations</a>
    </div>
    <div class="footer-col">
      <h3>MENU</h3>
      <a href="#">Milk Tea Series</a>
      <a href="#">Snowy Frappé Series</a>
      <a href="#">Brew Tea Series</a>
      <a href="#">Fruit Tea Series</a>
      <a href="#">Teaspresso · Tea Latte</a>
      <a href="#">Iced Oriental Tea</a>
      <a href="#">Teaspresso · Tea Frappé</a>
    </div>
    <div class="footer-col">
      <h3>GLOBAL STORES</h3>
    </div>
    <div class="footer-col">
      <h3>HALAL</h3>
    </div>
    <div class="footer-col">
      <h3>MEDIA CENTRE</h3>
      <a href="#">Media News</a>
      <a href="#">Brand News</a>
    </div>
    <div class="footer-col">
      <h3>COMMITMENT</h3>
    </div>
    <div class="footer-col">
      <h3>GET IN TOUCH</h3>
    </div>
  </div>

  <div class="social" style="padding-left: 10px;">
    <a href="#"><img src="https://img-prod-chagee-official-mys.chagee.com/web/uploads/20240806/18f2ecd5-03b6-42d8-8964-1b6fe3a2444c.svg" alt="Facebook"></a>
    <a href="#"><img src="https://img-prod-chagee-official-mys.chagee.com/web/uploads/20240806/b63ae334-4712-4a6e-8da8-ac4a53a8184b.svg" alt="Instagram"></a>
    <a href="#"><img src="https://img-prod-chagee-official-mys.chagee.com/web/uploads/20250117/9a72eef8-f946-4c55-a3c9-de425b656a1e.jpg" alt="RedNote"></a>
    <a href="#"><img src="https://img-prod-chagee-official-mys.chagee.com/web/uploads/20240702/148f2b61-9daf-4283-8b45-fa3ec4f91717.svg" alt="TikTok"></a>
  </div>
<br>
  <div class="footer-bottom">
    <div class="bottom">
      <a href=""><img src="images/icon/logo-playstore.svg" alt=""></a>
      <a href=""><img src="images/icon/logo-appstore.svg" alt=""></a>
    </div>
    <div>
      <a href="#">Get in touch</a> | 
      <a href="#">Privacy & Legal</a>
    </div>
  </div>
</footer>


  <!-- footer -->
<style>
  footer {
    background-color: rgba(251, 223, 223, 1);
    width: 100%;
    margin: 0px auto;
    margin-top: 1rem;
  }

  footer .container {
    width: 90%;
    margin: 0px auto;
    display: flex;
    flex-flow: column;
  }

  footer .container .logo {
    padding: 20px 0;
    border-bottom: 1px solid white;
    display: flex;
    flex-wrap: wrap-reverse;
  }

  footer .container .logo .time {
    color: white;
    font-family: "Bebas Neue", cursive;
  }

  footer .container .link {
    display: grid;
    grid-template-columns: auto auto auto auto;
    padding: 30px 0;
    border-bottom: 1px solid white;
  }

  footer .container .link .col a:hover {
    cursor: pointer;
    color: brown;
  }

  footer .container .link .col a {
    color: black;
    font-weight: bold;
    text-decoration: none;
    padding: 10px 0;
    font-family: "Encode Sans SC", sans-serif;
  }

  footer .container .link .icon a {
    padding: 10px 10px;
    color: black;
    font-weight: bold;
    text-decoration: none;
  } 

  footer .container .link .icon a i {
    font-size: 40px;
  }

  footer .container .link .col {
    display: flex;
    flex-flow: column;
  }

  footer .container .link .icon {
    display: flex;
  }

  footer .container .bottom {
    padding: 20px 0;
  }

  p {
    font-size: 18px;
    color: brown;
    font-weight: bold;
  }
</style>

<!-- style footer-->
<style>
  /* Footer Container */
.footer {
  background: #f2f1f0; /* màu xám nhạt */
  padding: 2rem 0;
  font-family: Arial, sans-serif;
  color: #444;
}

/* Flex layout cho phần trên */
.footer .footer-top {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  text-transform: uppercase;
  margin-bottom: 0.5rem;
}

/* Cột trong footer */
.footer .footer-col {
  margin: 1rem;
  min-width: 180px;
}

.footer .footer-col h3 {
  font-size: 0.8rem;
  font-weight: 600;
  margin-bottom: 1.6rem;
}

.footer .footer-col a {
  display: block;
  font-size: 0.8rem;
  color: rgba(0,0,0,0.7);
  text-decoration: none;
  margin-bottom: 1rem;
  transition: color 0.3s ease;
}

.footer .footer-col a:hover {
  color: #b91c1c; /* màu main khi hover */
}

/* Social Icons */
.footer .social {
  display: flex;
  gap: 2rem;
  margin-top: 1rem;
}

.footer .social a img {
  width: 32px;
  filter: grayscale(100%);
  opacity: 0.3;
  transition: all 0.3s ease;
}

.footer .social a img:hover {
  filter: grayscale(0);
  opacity: 1;
}

/* Footer bottom */
.footer-bottom {
  border-top: 1px solid #ddd;
  padding-top: 1rem;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  font-size: 1rem;
  color: #666;
}

.footer-bottom a {
  text-decoration: none;
  color: inherit;
  margin: 0 0.5rem;
  transition: color 0.3s ease;
}

.footer-bottom a:hover {
  color: #b91c1c;
}

</style>
