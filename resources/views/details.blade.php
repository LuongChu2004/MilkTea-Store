@extends('layouts.app')
@section('content')
<main style="padding-top: 15vh !important;">
    <div class="container mb-4">
        <div class="row justify-content-center my-0">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="search-quan">
                    <form action="{{ url('thucdon') }}" method="GET" class="position-relative">
                        <div class="input-group search-box">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                            <input name="search" type="text" class="form-control border-start-0 ps-0" placeholder="Tìm món hoặc thức ăn...">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body" style="height: auto;">
                        <h1 class="card-title mb-4" style="color: black !important; padding-top: 0;">{{ $product->title }}</h1>
                        <div class="row">
                            <div class="col-md-6">
                                <img src="{{ asset('admin_assets/product/' . $product->thumbnail) }}" alt="{{ $product->title }}" class="img-fluid rounded mb-3">
                            </div>
                            <div class="col-md-6">
                                <p class="mb-3">{{ $product->content }}</p>

                                <div class="mb-3">
                                    <label class="form-label">Size:</label>
                                    <div class="btn-group d-flex flex-wrap" role="group">
                                        @if ($sizes->count() > 0)
                                            @foreach ($sizes as $index => $size)
                                                <input type="radio" class="btn-check" name="size" id="size{{ $index }}" value="{{ $size->size }}" data-price="{{ $size->price }}" {{ $index === 0 ? 'checked' : '' }}>
                                                <label class="btn btn-outline-primary" for="size{{ $index }}">{{ $size->size }}</label>
                                            @endforeach
                                        @else
                                            <span class="badge badge-secondary text-dark border">No Size</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <label for="ice_level" class="form-label fw-semibold"><i class="fas fa-ice-cream me-1"></i> Mức đá</label>
                                        <select name="ice_level" id="ice_level" class="form-select border-primary shadow-sm rounded">
                                            <option value="0%">0% - Không đá</option>
                                            <option value="25%">25% - Ít đá</option>
                                            <option value="50%">50% - Vừa</option>
                                            <option value="100%">100% - Nhiều đá</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="sugar_level" class="form-label fw-semibold"><i class="fas fa-cube me-1"></i> Mức đường</label>
                                        <select name="sugar_level" id="sugar_level" class="form-select border-success shadow-sm rounded">
                                            <option value="0%">0% - Không đường</option>
                                            <option value="25%">25% - Ít ngọt</option>
                                            <option value="50%">50% - Vừa ngọt</option>
                                            <option value="100%">100% - Ngọt nhiều</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Số lượng:</label>
                                    <input type="number" class="form-control" id="quantity" value="1" min="1" style="max-width: 100px;">
                                </div>

                                <div class="mb-4">
                                    <h4 class="text-primary">
                                        Giá: <span id="price-display">{{ $sizes->count() > 0 ? number_format($sizes[0]->price, 0, ',', '.') : '0' }}</span> VNĐ
                                    </h4>
                                    <span id="hidden-price" style="display: none;">{{ $sizes->count() > 0 ? $sizes[0]->price : '0' }}</span>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" onclick="addToCart({{ $product->id }})"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
                                    <button class="btn btn-success btn-lg" onclick="buyNow({{ $product->id }})"><i class="fas fa-shopping-bag"></i> Mua ngay</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="height: auto; margin-bottom: 30px;">
                    <h3 style="text-align: center; padding-top: 20px;">Đánh Giá</h3>
                    <div class="card-body" style="margin-top:20px; height: auto;">
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v11.0&appId=264339598396676&autoLogAppEvents=1" nonce="8sTfFiF4"></script>
                        <div class="fb-comments" data-href="{{ url()->current() }}" data-width="100%" data-numposts="5"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0">Gợi ý cho bạn</h3>
                    </div>
                    <div class="card-body suggestion-list" style="height: auto;">
                        <div class="row suggest-box">
                            @foreach ($suggestedProducts as $item)
                                <div class="col-6 col-lg-12 mb-3">
                                    <div class="card h-100 product-card" style="background-image: url('{{ asset('admin_assets/product/' . $item->thumbnail) }}');">
                                        <a href="{{ url('details/' . $item->id) }}" class="overlay-link">
                                            <div class="overlay"></div>
                                            <div class="card-body p-3 text-white" style="height: auto; margin-top: 0;">
                                                <h6 class="card-title fw-bold" style="padding-top: 0; color: white !important;">{{ $item->title }}</h6>
                                                <p class="card-text small mb-0">Giá: {{ number_format($item->price, 0, ',', '.') }} VNĐ</p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('quantity').addEventListener('input', function () {
    let value = parseInt(this.value);

    if (isNaN(value) || value < 0) {
        alert("❌ Số lượng không được âm!");
        this.value = 1;
    } else if (value === 0) {
        alert("⚠️ Số lượng sản phẩm không được bằng 0!");
        this.value = 1;
    } else if (value > 100) {
        alert("⚠️ Số lượng không được lớn hơn 100!");
        this.value = 100;
    }
});
 
</script>
<script>
    // JavaScript cho Bootstrap size selection và cart functionality
    let isAddingToCart = false;

    // Xử lý chọn size với Bootstrap radio buttons
    document.querySelectorAll('input[name="size"]').forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                const price = this.getAttribute('data-price');
                document.getElementById('price-display').innerText = parseInt(price).toLocaleString();
                document.getElementById('hidden-price').innerText = price;
                updatePrice();
            }
        });
    });

    // Cập nhật giá khi thay đổi số lượng
    document.getElementById('quantity').addEventListener('change', updatePrice);

    function updatePrice() {
        const price = parseFloat(document.getElementById('hidden-price').innerText || document.querySelector(
            'input[name="size"]:checked')?.getAttribute('data-price') || 0);
        const quantity = parseInt(document.getElementById('quantity').value) || 1;
        const totalPrice = price * quantity;
        document.getElementById('price-display').innerText = totalPrice.toLocaleString();
    }
function addToCart(id) {
    if (isAddingToCart) return;
    isAddingToCart = true;

    const quantity = document.getElementById('quantity').value;
    const selectedSize = document.querySelector('input[name="size"]:checked');
    const size = selectedSize ? selectedSize.value : 'No Size';
    const price = selectedSize ? selectedSize.getAttribute('data-price') : '0';

    // Lấy mức đường và đá nếu có
    const sugarLevelElement = document.querySelector('select[name="sugar_level"]');
    const iceLevelElement = document.querySelector('select[name="ice_level"]');
    const sugar_level = sugarLevelElement ? sugarLevelElement.value : '';
    const ice_level = iceLevelElement ? iceLevelElement.value : '';

    if (!selectedSize && document.querySelectorAll('input[name="size"]').length > 0) {
        alert("Vui lòng chọn size sản phẩm.");
        isAddingToCart = false;
        return;
    }

    $.post('{{ url("cart/add") }}', { _token: '{{ csrf_token() }}',
        action: 'add',
        id: id,
        num: quantity,
        size: size,
        price: price,
        sugar_level: sugar_level,
        ice_level: ice_level
    }, function (data) {
        alert("Sản phẩm đã được thêm vào giỏ hàng!");
        window.location.href = '{{ url("cart") }}';
    }).fail(function () {
        alert("Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.");
    }).always(function () {
        isAddingToCart = false;
    });

    console.log("Sugar Level:", sugar_level);
    console.log("Ice Level:", ice_level);
}


   function buyNow(id) {
    const quantity = document.getElementById('quantity').value;
    const selectedSize = document.querySelector('input[name="size"]:checked');
    const size = selectedSize ? selectedSize.value : 'No Size';
    const price = selectedSize ? parseInt(selectedSize.getAttribute('data-price')) : 0;


    // Kiểm tra đã chọn size chưa (nếu có nhiều size)
    if (!selectedSize && document.querySelectorAll('input[name="size"]').length > 0) {
        alert("Vui lòng chọn size sản phẩm.");
        return;
    }

    // Lấy mức đường và đá giống hàm addToCart
    const sugarLevelElement = document.querySelector('select[name="sugar_level"]');
    const iceLevelElement = document.querySelector('select[name="ice_level"]');
    const sugar_level = sugarLevelElement ? sugarLevelElement.value : '';
    const ice_level = iceLevelElement ? iceLevelElement.value : '';

    const checkoutUrl = `{{ url("checkout") }}?id=${id}&num=${quantity}&size=${encodeURIComponent(size)}&price=${price}&sugar_level=${encodeURIComponent(sugar_level)}&ice_level=${encodeURIComponent(ice_level)}`;
    window.location.href = checkoutUrl;
}

</script>
<style>
    /* Bổ sung CSS cho Bootstrap components */
    .btn-check:checked+.btn-outline-primary {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
    }

    /* Card hover effect cho sản phẩm gợi ý */
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .card-body {
        margin-top: 50px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem;
        }

        .btn-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 0.75rem;
        }

        .card-title {
            font-size: 1.25rem;
        }

        .d-grid.gap-2>.btn {
            margin-bottom: 0.5rem;
        }
    }
</style>
<style>
    /* RESET & BASE ----------------------------------*/
body {
  font-family: "Roboto", "Helvetica", sans-serif;
  background: #fafafa;
  color: #333;
  line-height: 1.6;
}

/* HEADER ----------------------------------------*/
.navbar {
  background: linear-gradient(90deg, #ebe6e6ff, #ff7b54);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

/* SEARCH BOX ------------------------------------*/
.search-box {
  max-width: 800px;
  border-radius: 50px;
  overflow: hidden;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
  background: #fff;
  transition: transform 0.3s ease;
}

.search-box:hover {
  transform: translateY(-2px);
}

.search-box .input-group-text {
  background: transparent;
  border: none;
  padding-left: 18px;
  font-size: 1.2rem;
  color: #ff6b6b;
}

.search-box input {
  border: none;
  box-shadow: none;
  padding: 10px 14px;
}

.search-box input:focus {
  outline: none;
}

.search-box .btn {
  border: none;
  background: linear-gradient(90deg, #ff7b54, #ff6b6b);
  color: #fff;
  padding: 10px 24px;
  font-weight: 500;
  transition: background 0.3s;
}

.search-box .btn:hover {
  background: linear-gradient(90deg, #ff6b6b, #ff7b54);
}

/* PRODUCT CARD ----------------------------------*/
.card {
  border: none;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  transition: all 0.25s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

.card-title {
  font-weight: 600;
  color: #444;
}

/* PRICE -----------------------------------------*/
#price-display {
  color: #e74c3c;
  font-size: 1.4rem;
  font-weight: 600;
}

/* SIZE BUTTONS ----------------------------------*/
.btn-check:checked + .btn-outline-primary {
  background: #ff6b6b;
  border-color: #ff6b6b;
  color: #fff;
}

.btn-outline-primary {
  border-color: #ddd;
  color: #333;
  transition: all 0.25s;
}

.btn-outline-primary:hover {
  background: #f8f8f8;
}

/* FORM ELEMENTS ---------------------------------*/
.form-select {
  border-radius: 8px;
  border-color: #ddd;
  box-shadow: none;
}

.form-label {
  font-weight: 500;
}

/* ACTION BUTTONS --------------------------------*/
.btn-primary.btn-lg,
.btn-success.btn-lg {
  border: none;
  padding: 0.9rem 1.2rem;
  font-size: 1.1rem;
  font-weight: 500;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.btn-primary.btn-lg {
  background: linear-gradient(90deg, #ff7b54, #ff6b6b);
}

.btn-primary.btn-lg:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(255, 107, 107, 0.3);
}

.btn-success.btn-lg {
  background: linear-gradient(90deg, #4caf50, #43a047);
}

.btn-success.btn-lg:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(76, 175, 80, 0.3);
}

/* SIDEBAR PRODUCT CARD --------------------------*/
.card .card-img-top {
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
  object-fit: cover;
}

.card .card-body h6 {
  font-weight: 500;
  color: #333;
}

/* FACEBOOK COMMENTS CARD -----------------------*/
.card h3 {
  font-size: 1.4rem;
  color: #555;
  font-weight: 600;
}

/* RESPONSIVE ------------------------------------*/
@media (max-width: 768px) {
  main {
    padding-top: 14vh;
  }

  .btn-lg {
    font-size: 1rem;
    padding: 0.7rem 1rem;
  }
}
</style>
<style>
    .product-card .thumb {
  height: 150px;              /* hoặc chiều cao bạn muốn */
  background-size: cover;     /* phủ toàn bộ div */
  background-position: center;
  border-radius: 6px 6px 0 0;  /* bo góc đồng bộ với card */
  transition: transform 0.3s ease, filter 0.3s ease;
}

/* Hiệu ứng hover */
.product-card:hover .thumb {
  transform: scale(1.05);
  filter: brightness(1.05);
}

/* Card chung */
.product-card {
  border: none;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  transition: box-shadow 0.3s ease, transform 0.3s ease;
}
.product-card:hover {
  box-shadow: 0 6px 14px rgba(0,0,0,0.15);
  transform: translateY(-3px);
}
</style>
<style>
    /* Khung gợi ý cuộn độc lập */
.card-body.suggestion-list {
  max-height: 400px;    /* chiều cao tùy ý */
  overflow-y: auto;
  scrollbar-width: thin; /* Firefox */
}
.card-body.suggestion-list::-webkit-scrollbar {
  width: 6px;
}
.card-body.suggestion-list::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.2);
  border-radius: 3px;
}

/* Card product */
.product-card {
  background-size: cover;
  background-position: center;
  border: none;
  position: relative;
  height: 25vh !important;
  display: flex;          /* bật flexbox */
  flex-direction: column; /* sắp xếp theo cột */
  justify-content: flex-end; /* đẩy nội dung xuống cuối */
  border-radius: 8px;
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 6px 14px rgba(0, 0, 0, 0.25);
}

/* Overlay tối giúp text nổi */
.product-card .overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.55), rgba(0,0,0,0.15));
}

.overlay-link {
  display: block;
  color: inherit;
  text-decoration: none;
  position: relative;
  z-index: 2;
  width: 100%;
  height: 100%;
}

.product-card .card-body {
  position: relative;
  z-index: 3;
}
.product-card .card-title,
.product-card .card-text {
  color: #fff;
  text-shadow: 0 2px 4px rgba(45, 41, 41, 0.6);
}
.product-card .card-title{
    padding-top: 20%;
    color: #d1c8c8ff !important;
}
.card-body{
    margin-top:20px;
    height: 90vh;
}
/* phần gợi ý cho bạn */
.suggest-box {
    height: 100%; /* bạn chỉnh cao tùy ý */
    overflow-y: auto; /* bật cuộn dọc */
    padding-right: 6px; /* chừa chỗ scrollbar */
}

/* Tùy chỉnh scrollbar cho đẹp */
.suggest-box::-webkit-scrollbar {
    width: 6px;
}
.suggest-box::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
}
.suggest-box::-webkit-scrollbar-track {
    background: transparent;
}

</style>
</body>
</html>

@endsection