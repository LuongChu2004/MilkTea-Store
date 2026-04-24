<?php
$f = 'resources/views/layouts/app.blade.php';
$c = file_get_contents($f);

// 1. Clean all blade asset/url lines. Let's just do a clean cut of lines 1 to 155.
$newHeader = <<<EOT
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
                                        @foreach (\$global_categories->chunk(5) as \$chunk)
                                            <ul>
                                            @foreach (\$chunk as \$c)
                                                <li class="li-row"><a class="text-uppercase fw-semibold" href="{{ url('thucdon?id_category=' . \$c->id) }}">{{ \$c->name }}</a></li>
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
                                                onclick="window.location.href='/Chagge_Store/about.php'">
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
                                            <li class="li-row" href="about.php"></li>
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
                                    href="sendMail.php">CONTACT</a>
                            </li>
                        </ul>


                    </div>
                </div>
                <!-- Cart + User -->
                <div style="display: flex; padding-right: 5vw; gap:30px; height: 5vh;">
                    <a href="{{ url('cart') }}" class="btn btn-outline-primary position-relative rounded-circle p-2">
                        <i class="fas fa-shopping-bag"></i>
                        @php
                            \$cart = session()->get('cart', []);
                            \$count = 0;
                            foreach (\$cart as \$it) {
                                \$count += \$it['num'];
                            }
                        @endphp
                        @if (\$count > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ \$count }}</span>
                        @endif
                    </a>

                    <!-- User -->
                    @if (Auth::check())
                        <div class="dropdown">
                            <a class="btn btn-outline-primary position-relative rounded-circle p-2 " href="{{ url('history') }}" style="height: 100%; width: 2.1vw;">
                                <i class="fas fa-user me-1"></i> 
                            </a>
                        </div>
                    @else
                        <a href="{{ url('login') }}" class="btn btn-primary rounded-pill px-3 py-1">
                            <i class="fas fa-sign-in-alt me-1"></i>
                        </a>
                    @endif
                </div>
            </nav>
        </header>
EOT;

$lines = explode("\n", $c);
$restOfFile = implode("\n", array_slice($lines, 155));

file_put_contents($f, $newHeader . "\n" . $restOfFile);
