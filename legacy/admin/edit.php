<?php
require_once('../utils/config.php');
require_once('../database/dbhelper.php');

// Lấy order_id từ URL
if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);

    // Lấy thông tin đơn hàng + sản phẩm
    $sql = "SELECT 
                o.id AS order_id, o.fullname, o.address, o.phone_number, o.status,
                p.title, od.num, od.price
            FROM orders o
            JOIN order_details od ON o.id = od.order_id
            JOIN product p ON p.id = od.product_id
            WHERE o.id = $order_id";
    $order_details_List = executeResult($sql);

    // Nếu đơn hàng tồn tại thì lấy trạng thái
    $currentStatus = !empty($order_details_List) ? $order_details_List[0]['status'] : 'Chờ xử lý';
} else {
    die("Thiếu order_id");
}

// Xử lý AJAX update
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['ajax_update'])) {
    header('Content-Type: application/json');

    $status = $_POST['status'];
    $order_id = intval($_POST['order_id']);

    $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";
    $result = execute($sql);

    if ($result !== false) {
        echo json_encode(['success' => true, 'message' => 'Cập nhật trạng thái đơn hàng thành công!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật trạng thái.']);
    }
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
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
        /* Table */
        table {
            border-radius: 10px;
            overflow: hidden;
        }
        thead {
            background: #343a40;
            color: #fff;
        }
        tbody tr:hover {
            background: #f1f1f1;
        }

    </style>
</head>
<body>
    <ul class="nav nav-tabs" style="padding-left: 21vw;">
        <li class="nav-item"><a class="nav-link" href="index.php">Thống kê</a></li>
        <li class="nav-item"><a class="nav-link" href="category/">Quản lý danh mục</a></li>
        <li class="nav-item"><a class="nav-link" href="product/">Quản lý sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link active" href="dashboard.php">Quản lý đơn hàng</a></li>
        <li class="nav-item"><a class="nav-link" href="user/">Quản lý người dùng</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Đăng xuất</a></li>
    </ul>

    <div class="container mt-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="text-center">Chi tiết đơn hàng #<?php echo $order_id; ?></h2>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover  text-center">
                    <thead>
                        <tr style="text-align: center;">
                            <th>STT</th>
                            <th>Tên User</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Địa chỉ</th>
                            <th>Số điện thoại</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $count = 0;
                        foreach ($order_details_List as $item) {
                            echo '
                            <tr style="text-align: center;">
                                <td>' . (++$count) . '</td>
                                <td>' . $item['fullname'] . '</td>
                                <td>' . $item['title'] . ' (x' . $item['num'] . ')</td>
                                <td class="text-danger font-weight-bold">' . number_format($item['price'] * $item['num'], 0, ',', '.') . ' VNĐ</td>
                                <td>' . $item['address'] . '</td>
                                <td>' . $item['phone_number'] . '</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>

                <div class="mt-3">
                    <label><strong>Trạng thái đơn hàng:</strong></label>
                    <select id="status_<?php echo $order_id; ?>" class="form-control" style="width:200px; display:inline-block;">
                        <option value="Chờ xử lý" <?php echo ($currentStatus == 'Chờ xử lý' ? 'selected' : ''); ?>>Chờ xử lý</option>
                        <option value="Đã xác nhận" <?php echo ($currentStatus == 'Đã xác nhận' ? 'selected' : ''); ?>>Đã xác nhận</option>
                        <option value="Đang giao" <?php echo ($currentStatus == 'Đang giao' ? 'selected' : ''); ?>>Đang giao</option>
                        <option value="Hoàn thành" <?php echo ($currentStatus == 'Hoàn thành' ? 'selected' : ''); ?>>Hoàn thành</option>
                        <option value="Đã hủy" <?php echo ($currentStatus == 'Đã hủy' ? 'selected' : ''); ?>>Đã hủy</option>
                    </select>
                    <button type="button" class="btn btn-success ml-2" onclick="updateStatus(<?php echo $order_id; ?>)">Lưu</button>
                </div>

                <a href="dashboard.php" class="btn btn-warning mt-3">Back</a>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(order_id) {
            var statusValue = document.getElementById('status_' + order_id).value;
            $.ajax({
                url: '',
                method: 'POST',
                data: {
                    ajax_update: true,
                    status: statusValue,
                    order_id: order_id
                },
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.href = 'dashboard.php';
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert("Lỗi hệ thống!");
                }
            });
        }
    </script>
</body>
</html>
