@extends('layouts.app')
@section('content')

<main style="padding-top: 0px;">
    <style>
        /* ===== VIDEO BACKGROUND ===== */
        .video-container {
            position: relative;
            width: 100%;
            height: 100vh;
            /* full màn hình */
            overflow: hidden;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            color: #fff;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: background 0.3s;
        }

        .control-btn:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* thanh tìm kiếm  */
        .search-box {
            max-width: 1000px;
            /* Giới hạn chiều rộng */
            border-radius: 50px;
            overflow: hidden;
            /* ĐềEbo góc liền khối */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box .input-group-text {
            background-color: #fff;
            border: none;
            padding-left: 15px;
        }

        .search-box input {
            border: none;
            box-shadow: none;
            padding-left: 10px;
        }

        .search-box input:focus {
            outline: none;
            box-shadow: none;
        }

        .search-box .btn {
            border: none;
            border-radius: 0;
            padding: 8px 20px;
            background: linear-gradient(90deg, #ff7b54, #ff6b6b);
            /* Màu gradient */
            transition: 0.3s;
        }

        .search-box .btn:hover {
            background: linear-gradient(90deg, #ff6b6b, #ff7b54);
        }
    </style>

    <!-- Video Background Section -->
    <div class="video-container">
        <video id="bgVideo" autoplay loop playsinline muted>
            <source
                src="https://img-prod-chagee-official-mys.chagee.com/web/uploads/20250820/366bcd69-2fc6-492c-9d39-4390807a5c35.mp4"
                type="video/mp4">
        </video>
        <div class="controls">
            <button id="playPauseBtn" class="control-btn">❚❚</button>
            <button id="muteBtn" class="control-btn">🔊</button>
        </div>
    </div>
    <div class="container" style="margin: 100px auto 0px !important ;">
        <!-- Bootstrap Responsive Search Section -->
        <div class="row justify-content-center my-0">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="search-quan">
                    <form action="{{ url('thucdon') }}" method="GET" class="position-relative">
                        <div class="input-group search-box">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input name="search" type="text" class="form-control border-start-0 ps-0"
                                placeholder="Tìm món hoặc thức ăn...">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Bootstrap Responsive Carousel -->
        <div class="row my-5">
            <div class="col-12">
                <section class="program-carousel">
                    <div id="programCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                        <div class="carousel-inner">
                            <!-- Slide 1 -->
                            <div class="carousel-item active">
                                <a href="link-to-detail-page-1">
                                    <img src="{{ asset('images/icon/ct1.jpeg') }}" class="d-block w-100" alt="Chương trình 1">
                                </a>
                            </div>
                            <!-- Slide 2 -->
                            <div class="carousel-item">
                                <a href="link-to-detail-page-2">
                                    <img src="{{ asset('images/icon/ct4.png') }}" class="d-block w-100 " alt="Chương trình 2">
                                </a>
                            </div>
                            <!-- Slide 3 -->
                            <div class="carousel-item">
                                <a href="link-to-detail-page-3">
                                    <img src="{{ asset('images/icon/ct333.jpeg') }}" class="d-block w-100 " alt="Chương trình 3">
                                </a>
                            </div>
                        </div>
                        <!-- Điều hướng carousel -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#programCarousel"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#programCarousel"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </section>
            </div>
        </div>
        <!-- Bootstrap Responsive Grid for Products -->
        <section class="main py-3">
            <section class="restaurants">
                <div class="container">
                    <div class="row mb-4">
                        <div class="col-12">
                            <h1 class="text-center mb-4">Thực đơn tại quán</h1>
                        </div>
                    </div>

                    <!-- Bootstrap Grid cho sản phẩm -->
                    <div class="row g-4">
                        @foreach($products as $item)
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card h-100 shadow-sm product-card">
            <a href="{{ url('details', $item->id) }}" class="text-decoration-none">
                <div class="card-img-top-wrapper">
                    <img class="card-img-top product-thumbnail" 
                         src="{{ asset('admin_assets/product/' . $item->thumbnail) }}" 
                         alt="{{ $item->title }}">
                </div>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title product-title text-dark">{{ $item->title }}</h5>
                    <div class="mt-auto">
                        <span class="badge bg-primary fs-6 price-badge">{{ number_format($item->price, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
@endforeach
                    </div>
                    <!-- Bootstrap Responsive Pagination -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <nav aria-label="Product pagination">
                                <ul class="pagination justify-content-center flex-wrap">
                                    <div class="d-flex justify-content-center mt-3">{{ $products->links('pagination::bootstrap-5') }}</div>
                            </nav>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </div>
    <!-- Background Section -->
    <section class="hero-section">
        <div class="overlay">
            <div class="content text-center text-white">
                <h2 class="title">GLOBAL GROWTH</h2>
                <p class="description">
                    From Yunnan to the World: <br>
                    CHAGEE has expanded across Asia Pacific - in Malaysia, Singapore, and Thailand
                    to become an international tea beverage company with over 6000+ stores worldwide.
                    <i><br>Trường Xa - Hoàng Xa <br>
                        là của Viet Nam</i>
                </p>
                <a href="{{ url('stores') }}" class="btn-learn">Learn More</a>
            </div>
        </div>
    </section>
    <style>
        /* ===== HERO SECTION ===== */
        .hero-section {
            position: relative;
            width: 100%;
            height: 100vh;
            /* full màn hình */
            background-image: url('{{ asset('images/bg/HS_TS_la_cua_VietNam.jpg') }}');
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section .overlay {
            background: rgba(0, 0, 0, 0.5);
            /* lớp mềE*/
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .hero-section .content {
            max-width: 800px;
        }

        .hero-section .title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            font-style: sans-serif;
            letter-spacing: 0.5px;
            line-height: 2.0;
        }

        .hero-section .description {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
            color: white;
            font-style: sans-serif;
            line-height: 2.0;
            font-weight: normal;
        }

        .hero-section .description i {
            color: red;
        }

        .btn-learn {
            display: inline-block;
            padding: 10px 25px;
            border: 2px solid #fff;
            border-radius: 30px;
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .btn-learn:hover {
            background: #fff;
            color: #000;
        }
    </style>
    <!-- css cho thẻ sản phẩm  -->
    <style>
        /* Card wrapper */
        .product-card {
            border: none;
            transition: transform .3s ease, box-shadow .3s ease;
            overflow: hidden;
            border-radius: 1rem;
            background-color: #fff;
        }

        /* Ảnh sản phẩm */
        .card-img-top-wrapper {
            position: relative;
            padding-top: 100%;
            /* Vuông */
            overflow: hidden;
        }

        .product-thumbnail {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .4s ease;
        }

        /* Hover */
        .product-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .product-card:hover .product-thumbnail {
            transform: scale(1.08);
        }

        /* Tiêu đềE*/
        .product-title {
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Badge giá */
        .price-badge {
            background: linear-gradient(135deg, #f07b67, #fcb29f);
            font-weight: 600;
            padding: .4rem .8rem;
            border-radius: .5rem;
        }

        /* Pagination */
        .pagination .page-link {
            border: none;
            color: #444;
            font-weight: 500;
            transition: background .3s ease, color .3s ease;
        }

        .pagination .page-link:hover {
            background: #f5f5f5;
            color: #e63946;
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #f07b67, #fcb29f);
            color: #fff;
            border-radius: .5rem;
        }

        /* Heading */
        .main h1 {
            font-weight: 700;
            font-size: 2rem;
            letter-spacing: 1px;
        }

        /* Fade-in animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(25px);
            transition: opacity .6s ease, transform .6s ease;
        }

        .fade-in-up.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
    <script>
        const video = document.getElementById("bgVideo");
        const playPauseBtn = document.getElementById("playPauseBtn");
        const muteBtn = document.getElementById("muteBtn");

        playPauseBtn.addEventListener("click", () => {
            if (video.paused) {
                video.play();
                playPauseBtn.textContent = "❚❚"; // pause icon
            } else {
                video.pause();
                playPauseBtn.textContent = "▶"; // play icon
            }
        });

        muteBtn.addEventListener("click", () => {
            video.muted = !video.muted;
            muteBtn.textContent = video.muted ? "🔇" : "🔊";
        });
    </script>
    <!-- js cho thẻ product -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            // Gắn class fade-in
            document.querySelectorAll(".product-card").forEach(card => {
                card.classList.add("fade-in-up");
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("show");
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.2 });

            document.querySelectorAll(".product-card").forEach(card => observer.observe(card));
        });
    </script>

</main>

@endsection
