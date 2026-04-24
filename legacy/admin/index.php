<?php
require_once('../utils/config.php');
require_once('../database/config.php');
require_once('../database/dbhelper.php');

$view = $_GET['view'] ?? 'year';
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

$data = [];
$labels = [];

// ================= View Year =================
if ($view === 'year') {
    $sql = "SELECT MONTH(o.order_date) AS m, SUM(od.num * od.price) AS revenue
            FROM orders o
            JOIN order_details od ON o.id = od.order_id
            WHERE YEAR(o.order_date) = $year
              AND TRIM(LOWER(o.status)) = 'hoàn thành'
            GROUP BY MONTH(o.order_date)";
    $rows = executeResult($sql);
    $map = [];
    foreach ($rows as $r) {
        $map[(int)$r['m']] = (float)$r['revenue'];
    }
    for ($m = 1; $m <= 12; $m++) {
        $labels[] = "Tháng $m";
        $data[] = $map[$m] ?? 0;
    }

// ================= View Month =================
} elseif ($view === 'month') {
    $sql = "SELECT DAY(o.order_date) AS d, SUM(od.num * od.price) AS revenue
            FROM orders o
            JOIN order_details od ON o.id = od.order_id
            WHERE YEAR(o.order_date) = $year
              AND MONTH(o.order_date) = $month
              AND TRIM(LOWER(o.status)) = 'hoàn thành'
            GROUP BY DAY(o.order_date)";
    $rows = executeResult($sql);
    $map = [];
    foreach ($rows as $r) {
        $map[(int)$r['d']] = (float)$r['revenue'];
    }
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    for ($d = 1; $d <= $daysInMonth; $d++) {
        $labels[] = "Ngày $d";
        $data[] = $map[$d] ?? 0;
    }
}

// ================= Sản phẩm bán ra =================
$productSql = "SELECT 
                   o.id AS order_id,
                   u.username,
                   p.title, 
                   p.thumbnail,
                   SUM(od.num) AS qty, 
                   SUM(od.num * od.price) AS total,
                   MAX(o.order_date) AS last_order_date,
                   MAX(o.payment_method) AS payment_method
               FROM orders o
               JOIN order_details od ON o.id = od.order_id
               JOIN product p ON p.id = od.product_id
               JOIN user u ON u.id_user = o.id_user
               WHERE YEAR(o.order_date) = $year";

if ($view === 'month') {
    $productSql .= " AND MONTH(o.order_date) = $month";
}

$productSql .= " AND TRIM(LOWER(o.status)) = 'hoàn thành'
                 GROUP BY o.id, u.username, p.id 
                 ORDER BY total DESC";

$products = executeResult($productSql);
 $totalOrders = 1250;
$totalRevenue = 350000000;
$newCustomers = 85;

// ================= Thống kê tổng quan =================

// 1. Tổng đơn hàng
$sqlOrders = "SELECT COUNT(*) AS total_orders FROM orders";
$totalOrders = executeResult($sqlOrders)[0]['total_orders'] ?? 0;

// 2. Doanh thu hiện tại
if ($view === 'year') {
    $sqlRevenue = "SELECT SUM(od.num * od.price) AS revenue
                   FROM orders o
                   JOIN order_details od ON o.id = od.order_id
                   WHERE YEAR(o.order_date) = $year
                     AND TRIM(LOWER(o.status)) = 'hoàn thành'";
    $revenue = executeResult($sqlRevenue)[0]['revenue'] ?? 0;

    // Doanh thu năm trước
    $sqlPrev = "SELECT SUM(od.num * od.price) AS revenue
                FROM orders o
                JOIN order_details od ON o.id = od.order_id
                WHERE YEAR(o.order_date) = " . ($year - 1) . "
                  AND TRIM(LOWER(o.status)) = 'hoàn thành'";
    $prevRevenue = executeResult($sqlPrev)[0]['revenue'] ?? 0;
} else { // view = month
    $sqlRevenue = "SELECT SUM(od.num * od.price) AS revenue
                   FROM orders o
                   JOIN order_details od ON o.id = od.order_id
                   WHERE YEAR(o.order_date) = $year
                     AND MONTH(o.order_date) = $month
                     AND TRIM(LOWER(o.status)) = 'hoàn thành'";
    $revenue = executeResult($sqlRevenue)[0]['revenue'] ?? 0;

    // Doanh thu tháng trước
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth <= 0) {
        $prevMonth = 12;
        $prevYear = $year - 1;
    }
    $sqlPrev = "SELECT SUM(od.num * od.price) AS revenue
                FROM orders o
                JOIN order_details od ON o.id = od.order_id
                WHERE YEAR(o.order_date) = $prevYear
                  AND MONTH(o.order_date) = $prevMonth
                  AND TRIM(LOWER(o.status)) = 'hoàn thành'";
    $prevRevenue = executeResult($sqlPrev)[0]['revenue'] ?? 0;
}

// 3. Tăng trưởng %
$growth = 0;
if ($prevRevenue > 0) {
    $growth = (($revenue - $prevRevenue) / $prevRevenue) * 100;
}

// 4. Khách mới
$sqlUsers = "SELECT COUNT(DISTINCT id_user) AS total_users
             FROM orders
             WHERE TRIM(LOWER(status)) = 'hoàn thành'";
$totalUsers = executeResult($sqlUsers)[0]['total_users'] ?? 0;


?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: #f4f7fb;
            color: #333;
        }
        /* Navbar */
        .nav-tabs {
            background: #343a40;
            border-bottom: none;
            padding: 0.5rem 1rem;
        }
        .nav-tabs .nav-link {
            color: #fff;
            margin-right: 10px;
            border: none;
            transition: 0.3s;
        }
        .nav-tabs .nav-link:hover {
            background: #495057;
            border-radius: 5px;
        }
        .nav-tabs .nav-link.active {
            background: #007bff;
            border-radius: 5px;
        }
        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #222;
        }
        .chart-container {
            width: 90%;
            max-width: 1000px;
            margin: 0 auto 40px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        table {
            width: 90%;
            margin: 0 auto;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        table th, table td {
            padding: 12px 16px;
            text-align: center;
        }
        table th {
            background: #007bff;
            color: white;
        }
        table tr:nth-child(even) {
            background: #f8f9fa;
        }
        table tr:hover {
            background: #eef3ff;
        }
    </style>
</head>
<body style="padding-bottom: 10vh;">
    <!-- NAV -->
    <ul class="nav nav-tabs" style="padding-left: 21vw; width: 100vw;">
        <li class="nav-item">
            <a class="nav-link active" href="/Chagge_store/admin/index.php">Thống kê</a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="/Chagge_store/admin/category/index.php">Quản lý danh mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/Chagge_store/admin/product/index.php">Quản lý sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/Chagge_store/admin/dashboard.php">Quản lý đơn hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/Chagge_store/admin/user/index.php">Quản lý người dùng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/Chagge_store/login/logout.php">Đăng xuất</a>
        </li>
    </ul>
    <h2 style="padding-top: 7vh">📊 Thống kê doanh thu (<?= ucfirst($view) ?>)</h2 >
    <div class="chart-container"  style="padding-bottom: 0px !important; margin-bottom: 0px !important ;">
        <canvas id="revenueChart"></canvas>
    </div>
    <!-- Biểu đồ -->
<canvas id="revenueChart" height="30"></canvas>

<div class="container my-4">
    <div class="row text-center">
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>📦 Tổng đơn hàng</h5>
                <h3 class="text-primary"><?= number_format($totalOrders) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>💰 Doanh thu</h5>
                <h3 class="text-success"><?= number_format($revenue, 0, ',', '.') ?> VNĐ</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>📈 Tăng trưởng</h5>
                <h3 class="<?= $growth >= 0 ? 'text-success' : 'text-danger' ?>">
                    <?= number_format($growth, 2) ?> %
                </h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3">
                <h5>👤 Khách mới</h5>
                <h3 class="text-warning"><?= number_format($totalUsers) ?></h3>
            </div>
        </div>
    </div>
</div>


<!-- Nhận xét / Tóm tắt -->
<div class="alert alert-info mt-4 shadow-sm" style="justify-content: center; text-align: center;">
  📊 <?= $summaryText ?? "Tháng này doanh thu ổn định, cần theo dõi thêm xu hướng." ?>
</div>

<!-- Nút thao tác -->

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
    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= (int)$p['order_id'] ?></td>
        <td><?= htmlspecialchars($p['username']) ?></td>
        <td>
            <img src="../admin/product/<?= htmlspecialchars($p['thumbnail']) ?>" 
                 alt="<?= htmlspecialchars($p['title']) ?>" 
                 style="width:60px;height:60px;object-fit:cover;border-radius:6px;">
        </td>
        <td><?= htmlspecialchars($p['title']) ?></td>
        <td><?= (int)$p['qty'] ?></td>
        <td><?= date("d-m-Y H:i", strtotime($p['last_order_date'])) ?></td>
        <td><?= strtoupper(htmlspecialchars($p['payment_method'])) ?></td>
        <td class="font-weight-bold text-danger"><?= number_format($p['total'], 0, ',', '.') ?> VNĐ</td>
    </tr>
    <?php endforeach; ?>
</table>



<script>
const ctx = document.getElementById('revenueChart').getContext('2d');
const chart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels, JSON_UNESCAPED_UNICODE) ?>,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: <?= json_encode($data) ?>,
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
                if ("<?= $view ?>" === "year") {
                    window.location.href = "?view=month&year=<?= $year ?>&month=" + (index + 1);
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
</body>
</html>
