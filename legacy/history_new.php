<?php
require_once('database/dbhelper.php');
require_once('utils/utility.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="plugin/fontawesome/css/all.css">
    <link rel="stylesheet" href="css/cart.css">
    <title>Lịch sử mua hàng</title>
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

        .status.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status.success {
            background: #d4edda;
            color: #155724;
        }

        .status.cancel {
            background: #f8d7da;
            color: #721c24;
        }

        .status.shipping {
            background: #cce5ff;
            color: #004085;
        }

        .status.confirmed {
            background: #e2e3e5;
            color: #383d41;
        }

        img.product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div id="wrapper">
        <?php require('layout/header.php') ?>

        <main style="padding-top: 110px !important;">
            <div class="container">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h3 class="mb-0" style="font-size: 20px !important;">🛒 Lịch sử mua hàng</h3>
                        <ul class="nav nav-tabs border-0">
                            <li class="nav-item">
                                <a class="nav-link" href="cart.php">Giỏ hàng</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="history_new.php">Lịch sử mua hàng</a>
                            </li>
                        </ul>
                    </div>


                    <div class="card-body">
    <?php
    if (!isset($_COOKIE['username'])) {
        echo '<div class="alert alert-warning text-center">
                Bạn chưa đăng nhập. 
                <a href="login/login.php" class="btn btn-sm btn-primary ml-2">Đăng nhập</a> để xem lịch sử mua hàng.
              </div>';
    } else {
        $username = $_COOKIE['username'];

        // Lấy user_id
        $conn = getDbConnection();
        $stmt = $conn->prepare("SELECT id_user FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        if (!$user) {
            echo '<div class="alert alert-warning text-center">
                    Không tìm thấy tài khoản. 
                    Vui lòng <a href="login/login.php" class="btn btn-sm btn-primary ml-2">Đăng nhập</a> lại.
                  </div>';
        } else {
            $userId = $user['id_user'];

            // Lấy danh sách đơn hàng
            $sql = "SELECT 
                        o.id, o.order_date, o.status, o.payment_status, o.payment_method,
                        od.product_id, od.num, od.price, od.size,
                        p.title, p.thumbnail
                    FROM orders o
                    INNER JOIN order_details od ON o.id = od.order_id
                    INNER JOIN product p ON p.id = od.product_id
                    WHERE o.id_user = ?
                    ORDER BY o.id DESC";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $orders = [];
            while ($row = $result->fetch_assoc()) {
                $orderId = $row['id'];
                if (!isset($orders[$orderId])) {
                    $orders[$orderId] = [
                        'order_id' => $orderId,
                        'order_date' => $row['order_date'],
                        'status' => $row['status'],
                        'payment_status' => $row['payment_status'],
                        'payment_method' => $row['payment_method'],
                        'items' => []
                    ];
                }
                $orders[$orderId]['items'][] = [
                    'product_id' => $row['product_id'],
                    'num' => $row['num'],
                    'price' => $row['price'],
                    'size' => $row['size'],
                    'title' => $row['title'],
                    'thumbnail' => $row['thumbnail']
                ];
            }
            $stmt->close();

            if (empty($orders)) {
                echo '<div class="alert alert-info text-center">Bạn chưa có đơn hàng nào.</div>';
            } else {
                $orderCount = 0;
                foreach ($orders as $order) {
                    
                                        echo '<div class="order-card">';
                                        echo '<div class="order-header">Đơn hàng ' . (++$orderCount) . ' - Mã: #' . $order['order_id'] . ' 
                                    <span class="ml-3 small">(' . date("d-m-Y H:i:s", strtotime($order['order_date'])) . ')</span></div>';
                                        echo '<div class="p-3">';
                                        echo '<table class="table table-hover mb-0">
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
                                    <tbody>';

                                        $i = 0;
                                        foreach ($order['items'] as $item) {
                                            // Mapping trạng thái đơn hàng
                                            $status = strtolower($order['status']);
                                            switch ($status) {
                                                case 'chờ xử lý':
                                                    $statusClass = 'pending';
                                                    break;
                                                case 'đã xác nhận':
                                                    $statusClass = 'confirmed';
                                                    break;
                                                case 'đang giao':
                                                    $statusClass = 'shipping';
                                                    break;
                                                case 'đã thanh toán':
                                                case 'hoàn thành':
                                                    $statusClass = 'success';
                                                    break;
                                                case 'đã huỷ':
                                                case 'thanh toán thất bại':
                                                    $statusClass = 'cancel';
                                                    break;
                                                default:
                                                    $statusClass = 'pending';
                                            }

                                            // Mapping trạng thái thanh toán
                                            $paymentStatus = strtolower($order['payment_status']);
                                            switch ($paymentStatus) {
                                                case 'completed':
                                                    $payClass = 'success';
                                                    $payText = 'Đã thanh toán';
                                                    break;
                                                case 'pending':
                                                    $payClass = 'pending';
                                                    $payText = 'Chưa thanh toán';
                                                    break;
                                                case 'confirmed':
                                                    $payClass = 'confirmed';
                                                    $payText = 'COD';
                                                    break;
                                                case 'failed':
                                                    $payClass = 'cancel';
                                                    $payText = 'Thanh toán thất bại';
                                                    break;
                                                default:
                                                    $payClass = 'pending';
                                                    $payText = 'Chưa rõ';
                                            }

                                            echo '<tr class="text-center">
                                        <td>' . (++$i) . '</td>
                                        <td><img src="admin/product/' . htmlspecialchars($item['thumbnail']) . '" class="product-img"></td>
                                        <td>' . htmlspecialchars($item['title']) . '</td>
                                        <td>' . htmlspecialchars($item['size']) . '</td>
                                        <td>' . number_format($item['price'], 0, ',', '.') . ' VNĐ</td>
                                        <td>' . $item['num'] . '</td>
                                        <td class="font-weight-bold text-danger">' . number_format($item['num'] * $item['price'], 0, ',', '.') . ' VNĐ</td>
                                        <td>' . strtoupper($order['payment_method']) . '</td>
                                        <td><span class="status ' . $payClass . '">' . $payText . '</span></td>
                                        <td><span class="status ' . $statusClass . '">' . $order['status'] . '</span></td>
                                    </tr>';
                                        }
                                        echo '</tbody></table></div></div>';
                }
            }
        }
        mysqli_close($conn);
    }
    ?>
</div>

                </div>
            </div>
        </main>

        <?php require_once('layout/footer.php'); ?>
    </div>
</body>

</html>