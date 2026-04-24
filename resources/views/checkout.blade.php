@extends('layouts.app')

@section('content')
<style>
    .checkout-title {
        text-align: center;
        font-size: 32px;
        font-weight: bold;
        margin-bottom: 2rem;
    }
    .card-header {
        font-size: 18px;
        padding: 0.8rem 1rem;
    }
    .table td, .table th {
        vertical-align: middle;
        font-size: 14px;
    }
    .order-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        max-height: 78vh;
        overflow-y: auto;
        padding-right: 8px;
    }
    .order-item {
        display: flex;
        gap: 1rem;
        border: 1px solid #eee;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.05);
    }
    .order-img img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
    }
    .order-info h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .order-info p {
        margin: 0.2rem 0;
        font-size: 14px;
    }
    .order-info .price {
        color: #d35400;
        font-weight: bold;
    }
</style>

<main style="padding-bottom: 4rem; padding-top: 10vh;">
    <section class="cart">
        <div class="container">
            <h4 class="checkout-title">Tiến hành thanh toán</h4>
            <div class="row g-4">
                <!-- Nhập thông tin mua hàng -->
                <div class="col-md-6">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-header bg-dark text-white fw-bold">
                            Nhập thông tin mua hàng
                        </div>
                        <div class="card-body">
                            <form action="{{ url('checkout') }}" method="POST" id="checkoutForm">
                                @csrf
                                <div class="form-group mb-3">
                                    <label>Họ và tên:</label>
                                    <input type="text" class="form-control" name="fullname" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Email:</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label>Số điện thoại:</label>
                                    <input type="text" class="form-control" name="phone_number" required>
                                </div>

                                <!-- Địa chỉ -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label>Tỉnh / Thành phố:</label>
                                        <select class="form-control" id="province" required></select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Quận / Huyện:</label>
                                        <select class="form-control" id="district" required></select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Phường / Xã:</label>
                                        <select class="form-control" id="ward" required></select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Số nhà, tên đường:</label>
                                        <input type="text" class="form-control" id="address_detail" required>
                                    </div>
                                </div>
                                <input type="hidden" name="address" id="full_address">

                                <div class="form-group mb-3">
                                    <label>Ghi chú:</label>
                                    <textarea class="form-control" rows="3" name="note"></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="fw-bold">Chọn hình thức thanh toán:</label><br>
                                    <div class="form-check">
                                        <input type="radio" id="cod" name="payment_method" value="COD" class="form-check-input" checked>
                                        <label for="cod" class="form-check-label">Thanh toán khi nhận hàng</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="vnpay" name="payment_method" value="VNPAY" class="form-check-input">
                                        <label for="vnpay" class="form-check-label">Thanh toán qua VNPAY</label>
                                    </div>
                                </div>

                                <input type="hidden" name="is_buy_now" value="{{ $isBuyNow ? 1 : 0 }}">
                                @if($isBuyNow)
                                    <input type="hidden" name="cart_data" value="{{ json_encode($cart) }}">
                                @endif

                                <button type="submit" class="btn btn-primary w-100 mt-3">Xác nhận thanh toán</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Đơn hàng của bạn -->
                <div class="col-md-6" style="position: sticky; top: 100px; align-self: flex-start;">
                    <div class="card shadow-sm rounded-3">
                        <div class="card-header bg-dark text-white fw-bold">
                            Đơn hàng của bạn
                        </div>
                        <div class="card-body">
                            <div class="order-list">
                                @foreach($cart as $item)
                                <div class="order-item">
                                    <div class="order-img">
                                        <img src="{{ asset('admin_assets/product/' . $item['thumbnail']) }}" alt="{{ $item['title'] }}">
                                    </div>
                                    <div class="order-info">
                                        <h5>{{ $item['title'] }}</h5>
                                        <p>Size: {{ $item['size'] }} | Đường: {{ $item['sugar_level'] ?: 'Không rõ' }} | Đá: {{ $item['ice_level'] ?: 'Không rõ' }}</p>
                                        <p>Giá: <span class="price">{{ number_format($item['price'], 0, ',', '.') }} VNĐ</span></p>
                                        <p>Số lượng: {{ $item['num'] }}</p>
                                        <p class="subtotal">Tổng: {{ number_format($item['num'] * $item['price'], 0, ',', '.') }} VNĐ</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <h4 class="text-end text-danger fw-bold mt-3">
                                Tổng cộng: {{ number_format($total, 0, ',', '.') }} VNĐ
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $(document).ready(function() {
        $.get("https://provinces.open-api.vn/api/?depth=1", function (data) {
            $("#province").append('<option value="">-- Chọn tỉnh/thành --</option>');
            data.forEach(function (province) {
                $("#province").append(`<option value="${province.code}">${province.name}</option>`);
            });
        });

        $("#province").on("change", function () {
            const provinceCode = $(this).val();
            $("#district").empty().append('<option value="">-- Chọn huyện --</option>');
            $("#ward").empty().append('<option value="">-- Chọn xã --</option>');
            if (provinceCode) {
                $.get(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function (data) {
                    data.districts.forEach(function (district) {
                        $("#district").append(`<option value="${district.code}">${district.name}</option>`);
                    });
                });
            }
        });

        $("#district").on("change", function () {
            const districtCode = $(this).val();
            $("#ward").empty().append('<option value="">-- Chọn xã --</option>');
            if (districtCode) {
                $.get(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function (data) {
                    data.wards.forEach(function (ward) {
                        $("#ward").append(`<option value="${ward.name}">${ward.name}</option>`);
                    });
                });
            }
        });

        $('#checkoutForm').on('submit', function (e) {
            const provinceName = $("#province option:selected").text();
            const districtName = $("#district option:selected").text();
            const wardName = $("#ward").val();
            const detail = $("#address_detail").val();

            if(!$("#province").val() || !$("#district").val() || !wardName) {
                e.preventDefault();
                alert("Vui lòng chọn đầy đủ địa chỉ!");
                return false;
            }

            const fullAddress = `${detail}, ${wardName}, ${districtName}, ${provinceName}`;
            $("#full_address").val(fullAddress);
        });
    });
</script>
@endsection
