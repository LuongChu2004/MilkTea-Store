@extends('admin.layouts.app')
@section('content')
<style>
    .chart-container { width: 90%; max-width: 1000px; margin: 0 auto 40px; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    table { width: 90%; margin: 0 auto; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
    table th, table td { padding: 12px 16px; text-align: center; }
    table th { background: #007bff; color: white; }
    table tr:nth-child(even) { background: #f8f9fa; }
    table tr:hover { background: #eef3ff; }
</style>

<h2>📊 Thống kê doanh thu ({{ ucfirst($view) }})</h2>
<div class="chart-container">
    <canvas id="revenueChart" height="100"></canvas>
</div>

<div class="container my-4">
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>📦 Tổng đơn hàng</h5>
                <h3 class="text-primary">{{ number_format($totalOrders) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>💰 Doanh thu</h5>
                <h3 class="text-success">{{ number_format($revenue, 0, ',', '.') }} VNĐ</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>📈 Tăng trưởng</h5>
                <h3 class="{{ $growth >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ number_format($growth, 2) }} %
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>👤 Khách mới</h5>
                <h3 class="text-warning">{{ number_format($totalUsers) }}</h3>
            </div>
        </div>
    </div>
</div>

<h3>📦 Sản phẩm bán ra</h3>
<table>
    <tr>
        <th>Mã đơn hàng</th>
        <th>User</th>
        <th>Ảnh</th>
        <th>Sản phẩm</th>
        <th>Số lượng</th>
        <th>Ngày đặt</th>
        <th>Phương thức thanh toán</th>
        <th>Tổng tiền</th>
    </tr>
    @foreach ($products as $p)
    <tr>
        <td>{{ $p->order_id }}</td>
        <td>{{ $p->username }}</td>
        <td>
            <img src="{{ asset('admin_assets/product/' . $p->thumbnail) }}" alt="{{ $p->title }}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
        </td>
        <td>{{ $p->title }}</td>
        <td>{{ $p->qty }}</td>
        <td>{{ date("d-m-Y H:i", strtotime($p->last_order_date)) }}</td>
        <td>{{ strtoupper($p->payment_method) }}</td>
        <td class="font-weight-bold text-danger">{{ number_format($p->total, 0, ',', '.') }} VNĐ</td>
    </tr>
    @endforeach
</table>

<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: {!! json_encode($data) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: true, position: 'top' },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".") + ' VNĐ';
                    }
                }
            }
        },
        onClick: (evt) => {
            const points = chart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (points.length > 0) {
                const index = points[0].index;
                if ("{{ $view }}" === "year") {
                    window.location.href = "?view=month&year={{ $year }}&month=" + (index + 1);
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString('vi-VN') + 'đ';
                    }
                }
            }
        }
    }
});
</script>
@endsection