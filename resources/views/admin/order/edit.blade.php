@extends('admin.layouts.app')

@section('content')
<div class="container mt-4" style="max-width: 600px;">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Cập Nhật Đơn Hàng #{{ $order->id }}</h4>
        </div>
        <div class="card-body">
            <p><strong>Khách hàng:</strong> {{ $order->fullname }}</p>
            <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
            <p><strong>Tổng tiền:</strong> <span class="text-danger font-weight-bold">{{ number_format($order->orderDetails->sum(function($d) { return $d->num * $d->price; }), 0, ',', '.') }} VNĐ</span></p>

            <form action="{{ url('admin/order/'.$order->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-4 mt-3">
                    <label class="font-weight-bold">Trạng thái đơn hàng:</label>
                    <select name="status" class="form-select form-control">
                        <option value="Chờ xử lý" {{ $order->status == 'Chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="Đã xác nhận" {{ $order->status == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đang giao" {{ $order->status == 'Đang giao' ? 'selected' : '' }}>Đang giao</option>
                        <option value="Hoàn thành" {{ $order->status == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đã hủy" {{ $order->status == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success w-100">Cập Nhật Trạng Thái</button>
            </form>
        </div>
    </div>
</div>
@endsection
