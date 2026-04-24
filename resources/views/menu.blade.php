@extends('layouts.app')
@section('content')

<main>
    <style>
        main {
            padding-top: 0px;
        }

        /* thanh tìm kiếm */
        .search-box {
            max-width: 1000px;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 2vh;
        }

        .search-box .input-group-text {
            background-color: #fff;
            border: none;
            padding-left: 25px;
        }

        .search-box input {
            border: none;
            box-shadow: none;
            padding-left: 10px;
        }

        .search-box input:focus {
            outline: none;
            box-shadow: none;
            background-color: #fffaf8;
        }

        .search-box .btn {
            border: none;
            border-radius: 0;
            padding: 8px 20px;
            background: linear-gradient(90deg, #ff7b54, #ff6b6b);
            transition: 0.3s;
            color: #fff;
            font-weight: 500;
        }

        .search-box .btn:hover {
            background: linear-gradient(90deg, #ff6b6b, #ff7b54);
        }

        /* hero section */
        .hero-section {
            position: relative;
            background-size: contain;
            background-repeat: no-repeat;
            width: 100%;
            height: 83vh;
            background-image: url('{{ asset('images/bg/bg-thucdon-1.png') }}');
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section .overlay {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: left;
            padding: 20px;
        }

        .hero-section .title {
            font-size: 62px;
            font-weight: bold;
            margin-bottom: 20px;
            letter-spacing: 3px;
            line-height: 2.0;
            padding-left: 7vw;
            padding-bottom: 1vh;
        }

        /* Sidebar */
        .sidebar {
            position: sticky;
            top: 100px;
            /* khoảng cách so với header, chỉnh tùy chiều cao header */
            align-self: flex-start;
            /* đảm bảo bám theo trên cùng */
            height: fit-content;
            /* cao vừa đủ nội dung */
        }

        .row {
            display: flex;
            align-items: stretch;
            /* các cột sẽ kéo dài bằng nhau */
        }

        /* Card ngang */
        .product-horizontal {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .product-horizontal:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .product-horizontal img {
            object-fit: cover;
            height: 200px;
            width: 100%;
            transition: transform .4s ease;
        }

        .product-horizontal:hover img {
            transform: scale(1.05);
        }

        .product-horizontal .card-title {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .product-horizontal .badge {
            background: linear-gradient(135deg, #f07b67, #fcb29f);
            font-weight: 600;
            padding: .4rem .8rem;
            border-radius: .5rem;
        }

        /* menu tea series */
        .sidebar ul li a {
            position: relative;
            display: inline-block;
            padding-bottom: 4px;
            /* tạo khoảng cách chữ - gạch chân */
            color: #003366;
            font-weight: 500;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        .sidebar ul li a::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0;
            height: 2px;
            /* đềEdày gạch chân */
            background: #918a8aff;
            /* màu gạch chân */
            transition: width 0.3s ease;
        }

        .sidebar ul li a.active::after {
            width: 100%;
            color: black !important;
        }
    </style>

    <!-- Hero -->
    <section class="hero-section">
        <div class="overlay">
            <div class="content text-center text-white">
                <h2 class="title">OUR MENU</h2>
            </div>
        </div>
    </section>

    <!-- Search bar -->
    <div class="container">
        <div id="ant-layout" class="row justify-content-center my-0">
            <div class="col-12 col-md-8 col-lg-6">
                <section class="search-quan">
                    <form action="{{ url('thucdon') }}" method="GET" class="position-relative">
                        <div class="input-group search-box">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input name="search" type="text" class="form-control border-start-0 ps-0"
                                placeholder="    Tìm món hoặc thức ăn..." value="{{ $search ?? '' }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <!-- Layout content -->
    <div class="container-fluid" style="padding-top: 3vh;">
        <div class="row">
            <!-- Menu trái -->
            <div class="col-12 col-md-3 p-3 sidebar">
                <div class="menu-left" style="width: 100%; padding-left: 30%;">
                    <h2 class="mb-4">TEA SERIES</h2>
                    <ul class="list-unstyled sidebar">
                        @foreach($global_categories as $cate)
                        <li class="mb-2" style="font-size: 18px;">
                            <a href="{{ url('thucdon?id_category='.$cate->id) }}" 
                               class="{{ $categoryId == $cate->id ? 'active' : '' }}" style="color: #918484ff;">
                               {{ $cate->name }}
                            </a>
                        </li>
                        @endforeach
                        <li class="mt-4 fw-bold">
                            <a href="{{ url('thucdon?search=') }}" class="text-dark">Search Result</a>
                        </li>
                    </ul>

                </div>
            </div>

            <!-- Sản phẩm phải -->
            <div class="col-12 col-md-9 p-4">
                @if(!empty($search))
                    <h3 class="mb-4">Kết quả cho: "{{ $search }}"</h3>
                @elseif(!empty($currentCategoryName))
                    <h3 class="mb-4" style="padding-left: 5vw">{{ $currentCategoryName }}</h3>
                @endif

                @if($products->count() > 0)
                    @foreach($products as $item)
                        <div class="card mb-3 shadow-sm product-horizontal" style="width:50vw; margin:0 0 0 5vw;">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="{{ asset('admin_assets/product/' . $item->thumbnail) }}" class="img-fluid" alt="{{ $item->title }}">
                                </div>
                                <div class="col-md-8 d-flex flex-column">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $item->title }}</h5>
                                        <p class="card-text"><span class="badge">{{ number_format($item->price, 0, ',', '.') }} VNĐ : Liên hệ</span></p>
                                        <a href="{{ url('details/' . $item->id) }}" class="btn btn-outline-primary mt-auto">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h5>Không có sản phẩm nào</h5>
                @endif
            </div>
        </div>
    </div>
</main>

@endsection
