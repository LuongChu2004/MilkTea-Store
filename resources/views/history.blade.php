@extends('layouts.app')

@section('content')
<style>
    main {
        padding: 3rem 0;
        background: #f8f9fa;
    }
    .order-card {
        border: 1px solid #e1e1e1;
        border-radius: 10px;
        margin-bottom: 2rem;
        background: #fff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    .order-header {
        background: linear-gradient(45deg, #6c63ff, #4e54c8);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px 10px 0 0;
        font-weight: 600;
    }
    .table thead {
        background: #f1f3f5;
        font-weight: 600;
    }
    .table td {
        vertical-align: middle !important;
    }
    .status {
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        display: inline-block;
    }
    .status.pending { background: #fff3cd; color: #856404; }
    .status.success { background: #d4edda; color: #155724; }
    .status.cancel { background: #f8d7da; color: #721c24; }
    .status.shipping { background: #cce5ff; color: #004085; }
    .status.confirmed { background: #e2e3e5; color: #383d41; }
    img.product-img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<main style="padding-top: 110px !important;">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                <h3 class="mb-0" style="font-size: 20px !important;">🛒 Lịch sử mua hàng</h3>
                <ul class="nav nav-tabs border-0">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('cart') }}">Giỏ hàng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ url('history') }}">Lịch sử mua hàng</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                @if($orders->isEmpty())
                    <div class="alert alert-info text-center">Bạn chưa có đơn hàng nào.</div>
                @else
                    @foreach($orders as $index => $order)
                        <div class="order-card">
                            <div class="order-header">
                                Đơn hàng {{ $index + 1 }} - Mã: #{{ $order->id }} 
                                <span class="ml-3 small">({{ date("d-m-Y H:i:s", strtotime($order->created_at)) }})</span>
                            </div>
                            <div class="p-3">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <td>STT</td>
                                            <td>Ảnh</td>
                                            <td>Sản phẩm</td>
                                            <td>Size</td>
                                            <td>Giá</td>
                                            <td>Số lượng</td>
                                            <td>Tổng cộng</td>
                                            <td>Hình thức</td>
                                            <td>Trạng thái thanh toán</td>
                                            <td>Trạng thái đơn hàng</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->orderDetails as $i => $item)
                                            @php
                                                $status = strtolower($order->status);
                                                $statusClass = 'pending';
                                                if (in_array($status, ['đã xác nhận'])) $statusClass = 'confirmed';
                                                elseif ($status == 'đang giao') $statusClass = 'shipping';
                                                elseif (in_array($status, ['đã thanh toán', 'hoàn thành'])) $statusClass = 'success';
                                                elseif (in_array($status, ['đã huỷ', 'thanh toán thất bại'])) $statusClass = 'cancel';

                                                $paymentStatus = strtolower($order->payment_status);
                                                $payClass = 'pending';
                                                $payText = 'Chưa thanh toán';
                                                if ($paymentStatus == 'completed') {
                                                    $payClass = 'success';
                                                    $payText = 'Đã thanh toán';
                                                } elseif ($paymentStatus == 'confirmed') {
                                                    $payClass = 'confirmed';
                                                    $payText = 'COD';
                                                } elseif ($paymentStatus == 'failed') {
                                                    $payClass = 'cancel';
                                                    $payText = 'Thanh toán thất bại';
                                                }
                                            @endphp
                                            <tr class="text-center">
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    @if($item->product)
                                                        <img src="{{ asset('admin_assets/product/' . $item->product->thumbnail) }}" class="product-img">
                                                    @endif
                                                </td>
                                                <td>{{ $item->product ? $item->product->title : 'Sản phẩm đã xóa' }}</td>
                                                <td>{{ $item->size }}</td>
                                                <td>{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                                <td>{{ $item->num }}</td>
                                                <td class="font-weight-bold text-danger">{{ number_format($item->num * $item->price, 0, ',', '.') }} VNĐ</td>
                                                <td>{{ strtoupper($order->payment_method) }}</td>
                                                <td><span class="status {{ $payClass }}">{{ $payText }}</span></td>
                                                <td><span class="status {{ $statusClass }}">{{ $order->status }}</span></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
