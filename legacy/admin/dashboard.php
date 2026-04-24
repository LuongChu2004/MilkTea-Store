<?php
require_once('../utils/config.php');
require_once('../database/dbhelper.php');

// Xử lý phân trang
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 10;
$start = ($page - 1) * $limit;

$conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);

// ----------------- TẠO $where TRƯỚC -----------------
$where = "WHERE 1=1";

if (!empty($_GET['order_id'])) {
    $orderId = intval($_GET['order_id']);
    $where .= " AND o.id = $orderId";
}

if (!empty($_GET['date_from'])) {
    $dateFrom = $_GET['date_from'];
    $where .= " AND DATE(o.order_date) >= '$dateFrom'";
}

if (!empty($_GET['date_to'])) {
    $dateTo = $_GET['date_to'];
    $where .= " AND DATE(o.order_date) <= '$dateTo'";
}

if (!empty($_GET['status'])) {
    $status = mysqli_real_escape_string($conn, $_GET['status']);
    $where .= " AND o.status = '$status'";
}

// ----------------- QUERY CHÍNH -----------------
$sql = "SELECT 
           o.id AS order_id,
           o.fullname,
           o.address,
           o.phone_number,
           o.payment_method,
           o.order_date,
           o.note,
           SUM(od.num * od.price) AS total_price,
           GROUP_CONCAT(CONCAT(p.title, ' (', od.num, ')') SEPARATOR '<br>') AS product_list,
           GROUP_CONCAT(od.size SEPARATOR ', ') AS sizes,
           o.payment_status,
           o.status AS order_status
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN product p ON p.id = od.product_id
        $where
        GROUP BY o.id
        ORDER BY o.order_date DESC
        LIMIT $start, $limit";

$order_details_List = executeResult($sql);

// ----------------- ĐẾM TỔNG BẢN GHI -----------------
$sql_count = "SELECT COUNT(DISTINCT o.id) as total 
              FROM orders o 
              JOIN order_details od ON o.id = od.order_id 
              JOIN product p ON p.id = od.product_id
              $where";
$result = mysqli_query($conn, $sql_count);
$row = mysqli_fetch_assoc($result);
$total_records = $row['total'];
$total_pages = ceil($total_records / $limit);

$count = ($page - 1) * $limit;
?>


<!DOCTYPE html>
<html>

<head>
    <title>Thêm Sản Phẩm</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <!-- summernote -->
    <!-- include summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <style>
        body {
            background: #f8f9fa;
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

        /* Container */
        .container-custom {
            margin-top: 30px;
        }

        /* Card */
        .card-custom {
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            background: #fff;
            padding: 20px;
        }

        /* Table */
        table {
            border-radius: 10px;
            overflow: hidden;
        }

        thead {
            background: #007bff;
            color: #fff;
        }

        tbody tr:hover {
            background: #f1f1f1;
        }

        /* Buttons */
        .btn-success {
            border-radius: 20px;
            padding: 8px 20px;
        }

        .btn-warning,
        .btn-danger {
            border-radius: 8px;
            padding: 5px 12px;
        }

        /* Badge trạng thái */
        .badge {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 20px;
        }

        /* Table style */
        .table th {
            background: #007bff;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }

        .table td {
            vertical-align: middle;
        }

        /* Hover effect */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
            transition: 0.2s;
        }

        /* Card */
        .card-custom {
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .filter-form {
            border: 1px solid #e0e0e0;
        }

        .filter-form label {
            font-size: 0.9rem;
            color: #333;
        }

        .filter-form .form-control,
        .filter-form select {
            border-radius: 8px;
        }

        .filter-form button {
            border-radius: 8px;
            font-weight: 500;
        }

        .filter-form .btn {
            border-radius: 8px;
            font-weight: 500;
        }

        .filter-form .btn i {
            margin-right: 5px;
        }

        .btn {
            border-radius: 6px;
            font-weight: 500;
        }

        .btn i {
            margin-right: 4px;
        }

        /* Khoảng cách đều cho nút */
        .gap-2>* {
            margin-left: 8px;
        }

        /* Nút */
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .btn i {
            margin-right: 6px;
        }

        /* Hiệu ứng hover */
        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #6c757d;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("input[type=date]", {
            dateFormat: "Y-m-d",
            locale: "vn"
        });
    </script>

</head>

<body>
    <ul class="nav nav-tabs" style="padding-left: 21vw;">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Thống kê</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="category/">Quản lý danh mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="product/">Quản lý sản phẩm</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link active" href="dashboard.php">Quản lý đơn hàng</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link " href="user/">Quản lý người dùng</a>
        </li>
        <li class="nav-item ">
            <a class="nav-link " href="logout.php">Đăng xuất</a>
        </li>
    </ul>
    <div class="container-custom">
        <div class="card card-custom">
            <h2 class="text-center mb-4">📑 Quản lý đơn hàng</h2>
            <form method="GET" class="filter-form mb-4 p-3 shadow-sm bg-white rounded">
                <div class="form-row align-items-end">
                    <!-- Mã đơn -->
                    <div class="col-md-2 mb-2">
                        <label class="font-weight-bold">Mã đơn</label>
                        <input type="text" name="order_id" class="form-control" placeholder="VD: 123"
                            value="<?= $_GET['order_id'] ?? '' ?>">
                    </div>

                    <!-- Ngày từ -->
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control"
                            value="<?= $_GET['date_from'] ?? '' ?>">
                    </div>

                    <!-- Ngày đến -->
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="<?= $_GET['date_to'] ?? '' ?>">
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-3 mb-2">
                        <label class="font-weight-bold">Trạng thái</label>
                        <select name="status" class="form-control">
                            <option value="">--Tất cả--</option>
                            <option value="Chờ xử lý" <?= (($_GET['status'] ?? '') == 'Chờ xử lý' ? 'selected' : '') ?>>Chờ
                                xử lý</option>
                            <option value="Đã xác nhận" <?= (($_GET['status'] ?? '') == 'Đã xác nhận' ? 'selected' : '') ?>>Đã xác nhận</option>
                            <option value="Đang giao" <?= (($_GET['status'] ?? '') == 'Đang giao' ? 'selected' : '') ?>>
                                Đang giao</option>
                            <option value="Hoàn thành" <?= (($_GET['status'] ?? '') == 'Hoàn thành' ? 'selected' : '') ?>>
                                Hoàn thành</option>
                            <option value="Đã hủy" <?= (($_GET['status'] ?? '') == 'Đã hủy' ? 'selected' : '') ?>>Đã hủy
                            </option>
                        </select>
                    </div>

                    <!-- Nhóm nút -->
                    <div class="col-md-12 mt-3 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                        <a href="dashboard.php" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset
                        </a>
                        <a href="/Chagge_Store/export_orders.php" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle text-center">
                    <thead class="thead-dark">
                        <tr>
                            <th>STT</th>
                            <th>Mã Đơn</th>
                            <th>User</th>
                            <th>Sản phẩm</th>
                            <th>Size</th>
                            <th>Địa chỉ</th>
                            <th>Ngày đặt</th>
                            <th>SĐT</th>
                            <th>Ghi chú</th>
                            <th>Tổng tiền</th>
                            <th>Thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($order_details_List as $item) {
                            // Xử lý trạng thái đơn hàng (order_status)
                            switch ($item['order_status']) {
                                case 'Chờ xử lý':
                                    $statusText = 'Chờ xử lý';
                                    $statusClass = 'badge badge-warning';
                                    break;
                                case 'Đã xác nhận':
                                    $statusText = 'Đã xác nhận';
                                    $statusClass = 'badge badge-primary';
                                    break;
                                case 'Đang giao':
                                    $statusText = 'Đang giao';
                                    $statusClass = 'badge badge-info';
                                    break;
                                case 'Hoàn thành':
                                    $statusText = 'Hoàn thành';
                                    $statusClass = 'badge badge-success';
                                    break;
                                case 'Đã huỷ': // chú ý dấu ngã
                                case 'Đã hủy': // phòng trường hợp có bản ghi sai dấu
                                    $statusText = 'Đã hủy';
                                    $statusClass = 'badge badge-danger';
                                    break;
                                default:
                                    $statusText = 'Không xác định';
                                    $statusClass = 'badge badge-secondary';
                            }


                            echo '
    <tr>
      <td>' . (++$count) . '</td>
      <td><strong>' . $item['order_id'] . '</strong></td>
      <td>' . htmlspecialchars($item['fullname']) . '</td>
      <td class="text-left">' . $item['product_list'] . '</td>
      <td>' . $item['sizes'] . '</td>
      <td class="text-left">' . htmlspecialchars($item['address']) . '</td>
      <td class="text-success">' . $item['order_date'] . '</td>
      <td>' . htmlspecialchars($item['phone_number']) . '</td>
      <td>' . (!empty($item['note'])
                                ? '<span class="text-dark">' . htmlspecialchars($item['note']) . '</span>'
                                : '<span class="text-warning">Trống</span>'
                            ) . '</td>
      <td class="text-danger font-weight-bold">' . number_format($item['total_price'], 0, ',', '.') . ' VNĐ</td>
      <td>' . htmlspecialchars($item['payment_method']) . '</td>
      <td><span class="' . $statusClass . '">' . $statusText . '</span></td>
      <td>
        <a href="edit.php?order_id=' . $item['order_id'] . '" class="btn btn-sm btn-primary">
         Sửa
        </a>
      </td>
    </tr>
    ';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php
            // Mặc định
            $current_page = 1;

            // Đếm tổng số bản ghi
            $sql = "SELECT COUNT(DISTINCT o.id) as total 
        FROM orders o 
        JOIN order_details od ON o.id = od.order_id 
        JOIN product p ON p.id = od.product_id";
            $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE);
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];

            // Tổng số trang (mỗi trang 10 đơn)
            $current_page = ceil($total_records / 10);
            ?>

            <!-- Pagination -->
            <nav aria-label="Page navigation" class="mt-3">
                <ul class="pagination justify-content-center">
                    <?php
                    if ($total_pages > 0) {
                        for ($i = 1; $i <= $total_pages; $i++) {
                            $active = ($i == $page) ? 'active' : '';
                            echo '<li class="page-item ' . $active . '">
                        <a class="page-link" href="?page=' . $i . '">' . $i . '</a>
                      </li>';
                        }
                    }
                    ?>
                </ul>
            </nav>
        </div>
    </div>

</body>
<style>
    .b-500 {
        font-weight: 500;
    }

    .red {
        color: red;
    }

    .green {
        color: green;
    }
</style>

</html>