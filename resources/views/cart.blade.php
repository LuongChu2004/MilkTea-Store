@extends('layouts.app')
@section('content')
<main style="padding-bottom: 4rem; padding-top: 5vh;">
    <section class="cart">
        <div class="container">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">🛒 Giỏ hàng</h3>
                    <ul class="nav nav-tabs border-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ url('cart') }}">Giỏ hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('history') }}">Lịch sử mua hàng</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    @if(!Auth::check())
                        <div class="alert alert-warning text-center">
                            <h5>🔒 Cần đăng nhập để xem giỏ hàng</h5>
                            <a href="{{ url('login') }}" class="btn btn-primary mt-3">Đăng nhập</a>
                        </div>
                    @else
                        @if(count($cart) > 0)
                            @php $total = 0; @endphp
                            @foreach($cart as $key => $item)
                                @php $total += $item['num'] * $item['price']; @endphp
                                <div class="cart-item card mb-3 border-0 shadow-sm" id="cart-item-{{ $key }}">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-3 col-md-2 p-2 text-center">
                                            <img src="{{ asset('admin_assets/product/' . $item['thumbnail']) }}" class="img-fluid rounded" alt="{{ $item['title'] }}">
                                        </div>
                                        <div class="col-9 col-md-10 p-3">
                                            <div class="d-flex justify-content-between">
                                                <h5 class="mb-1">{{ $item['title'] }}</h5>
                                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart('{{ $key }}')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                            <p class="mb-1 text-muted small">
                                                Size: {{ $item['size'] }} | Đá: {{ $item['ice_level'] ?: 'Không chọn' }} | Đường: {{ $item['sugar_level'] ?: 'Không chọn' }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <div>
                                                    <label class="mb-0 mr-2 small text-muted">Số lượng:</label>
                                                    <input type="number" class="form-control d-inline-block quantity-input"
                                                        style="width: 70px;" min="1" value="{{ $item['num'] }}"
                                                        onchange="updateCart('{{ $key }}', this.value)">
                                                </div>
                                                <div class="text-right">
                                                    <span class="h6 text-success">{{ number_format($item['price'], 0, ',', '.') }} VNĐ</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- Tổng cộng -->
                            <div class="card mt-4 border-0 shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <h4 class="mb-0">Tổng cộng:
                                        <span id="cart-total" class="text-danger">{{ number_format($total, 0, ',', '.') }} VNĐ</span>
                                    </h4>
                                    <div>
                                        <a href="{{ url('/') }}" class="btn btn-outline-secondary mr-2">Tiếp tục mua</a>
                                        <a href="{{ url('checkout') }}" class="btn btn-success">Thanh toán</a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <h5>Giỏ hàng trống!</h5>
                                <a href="{{ url('/') }}" class="btn btn-primary mt-3">Mua sắm ngay</a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    function updateCart(key, quantity) {
        if (quantity < 1) {
            alert("Số lượng phải lớn hơn 0!");
            return;
        }

        $.post('{{ url("cart/update") }}', {
            _token: '{{ csrf_token() }}',
            action: 'update',
            id: key,
            num: quantity
        }, function () {
            location.reload();
        });
    }

    function removeFromCart(key) {
        if (!confirm("Bạn có chắc muốn xoá sản phẩm này?")) return;

        $.post('{{ url("cart/update") }}', {
            _token: '{{ csrf_token() }}',
            action: 'delete',
            id: key
        }, function () {
            location.reload();
        });
    }
</script>

<style>
    .cart-item:hover {
        background: #f9f9f9;
        transition: 0.3s;
    }
    .cart-item img {
        max-height: 80px;
        object-fit: cover;
    }
    .quantity-input {
        text-align: center;
        font-weight: 500;
    }
    .btn-outline-danger i {
        pointer-events: none;
    }
</style>
@endsection