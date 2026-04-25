@extends('admin.layouts.app')

@section('content')
<style>
    .card-custom { border-radius: 12px; box-shadow: 0px 4px 12px rgba(0,0,0,0.1); background: #fff; padding: 20px; }
    .filter-form { border: 1px solid #e0e0e0; border-radius: 8px; }
    table { border-radius: 10px; overflow: hidden; }
    thead { background: #007bff; color: #fff; text-transform: uppercase; }
    .badge { padding: 6px 12px; font-size: 0.85rem; border-radius: 20px; }
</style>

<div class="container-fluid container-custom">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card card-custom">
        <h2 class="text-center mb-4">📑 Quản lý đơn hàng</h2>
        
        <form method="GET" action="{{ url('admin/order') }}" class="filter-form mb-4 p-3 shadow-sm bg-white">
            <div class="form-row align-items-end row">
                <div class="col-md-2 mb-2">
                    <label class="font-weight-bold">Mã đơn</label>
                    <input type="text" name="order_id" class="form-control" placeholder="VD: 123" value="{{ request('order_id') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold">Từ ngày</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold">Đến ngày</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="font-weight-bold">Trạng thái</label>
                    <select name="status" class="form-control">
                        <option value="">--Tất cả--</option>
                        <option value="Chờ xử lý" {{ request('status') == 'Chờ xử lý' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="Đã xác nhận" {{ request('status') == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đang giao" {{ request('status') == 'Đang giao' ? 'selected' : '' }}>Đang giao</option>
                        <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đã hủy" {{ request('status') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-1 mt-3 mb-2 text-end">
                    <button type="submit" class="btn btn-primary w-100">Tìm</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã Đơn</th>
                        <th>User</th>
                        <th>Chi tiết SP</th>
                        <th>Địa chỉ</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $index => $item)
                        @php
                            $statusClass = 'badge bg-secondary';
                            switch ($item->status) {
                                case 'Chờ xử lý': $statusClass = 'badge bg-warning text-dark'; break;
                                case 'Đã xác nhận': $statusClass = 'badge bg-primary'; break;
                                case 'Đang giao': $statusClass = 'badge bg-info text-dark'; break;
                                case 'Hoàn thành': $statusClass = 'badge bg-success'; break;
                                case 'Đã hủy': $statusClass = 'badge bg-danger'; break;
                            }
                        @endphp
                    <tr>
                        <td>{{ $orders->firstItem() + $index }}</td>
                        <td><strong>#{{ $item->id }}</strong></td>
                        <td>{{ $item->fullname }}<br><small>{{ $item->phone }}</small></td>
                        <td class="text-start">
                            @foreach($item->orderDetails as $detail)
                                {{ $detail->product ? $detail->product->title : 'SP Đã Xóa' }} - Size: {{ $detail->size }} (x{{ $detail->num }})<br>
                            @endforeach
                        </td>
                        <td class="text-start">{{ $item->address }}</td>
                        <td class="text-success">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}</td>
                        <td class="text-danger font-weight-bold">
                            {{ number_format($item->orderDetails->sum(function($d) { return $d->num * $d->price; }), 0, ',', '.') }} VNĐ
                        </td>
                        <td><span class="{{ $statusClass }}">{{ $item->status }}</span></td>
                        <td>
                            <a href="{{ url('admin/order/'.$item->id.'/edit') }}" class="btn btn-sm btn-primary">Sửa</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
