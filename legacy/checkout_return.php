<?php
require_once('utils/config.php');
require_once('database/dbhelper.php');
require_once('utils/utility.php');
require_once('vnpay_php/config.php');

// Lấy thông tin từ URL và xử lý chữ ký theo logic của VNPay
$vnp_SecureHash = $_GET['vnp_SecureHash'] ?? '';
$inputData = array();
foreach ($_GET as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

unset($inputData['vnp_SecureHash']);
ksort($inputData);
$i = 0;                                                                                                                                                                                                                                                                                                                                                                                                                 
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

// Lấy thông tin đơn hàng
$orderId = $_GET['vnp_TxnRef'] ?? 0;
$vnp_Amount = $_GET['vnp_Amount'] ?? 0;
$vnp_ResponseCode = $_GET['vnp_ResponseCode'] ?? '';
$vnp_TransactionStatus = $_GET['vnp_TransactionStatus'] ?? '';

// Kết nối database
$conn = getDbConnection();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kết quả thanh toán</title>
    <link href="vnpay_php/assets/bootstrap.min.css" rel="stylesheet" />
    <link href="vnpay_php/assets/jumbotron-narrow.css" rel="stylesheet">
    <script src="vnpay_php/assets/jquery-1.11.3.min.js"></script>
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
        }

        .payment-status {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }

        .success {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
        }

        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
        }

        .actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            margin: 0 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header clearfix">
            <h3 class="text-muted text-center">Kết quả thanh toán VNPAY</h3>
        </div>
        <div class="table-responsive">
            <div class="form-group">
                <label>Mã đơn hàng:</label>
                <label><?php echo $orderId; ?></label>
            </div>
            <div class="form-group">
                <label>Số tiền:</label>
                <label><?php echo number_format($vnp_Amount / 100, 0, ',', '.'); ?> VNĐ</label>
            </div>
            <div class="form-group">
                <label>Nội dung thanh toán:</label>
                <label><?php echo $_GET['vnp_OrderInfo'] ?? ''; ?></label>
            </div>
            <div class="form-group">
                <label>Mã giao dịch VNPAY:</label>
                <label><?php echo $_GET['vnp_TransactionNo'] ?? ''; ?></label>
            </div>
            <div class="form-group">
                <label>Mã ngân hàng:</label>
                <label><?php echo $_GET['vnp_BankCode'] ?? ''; ?></label>
            </div>
            <div class="form-group">
                <label>Thời gian thanh toán:</label>
                <label><?php echo $_GET['vnp_PayDate'] ?? ''; ?></label>
            </div>

            <div
                class="payment-status <?php echo ($secureHash == $vnp_SecureHash && $vnp_ResponseCode == '00') ? 'success' : 'error'; ?>">
                <?php
                if ($secureHash == $vnp_SecureHash) {
                    if ($vnp_ResponseCode == '00') {
                        // Debug thông tin
                        error_log("OrderID: " . $orderId);
                        error_log("Response Code: " . $vnp_ResponseCode);

                        // ✅ Cập nhật trạng thái đơn hàng thành công (chỉ sửa dòng này)
                        $sql = "UPDATE orders SET payment_status = 'completed' WHERE id = ?";
                        error_log("SQL Query: " . $sql . " with ID = " . $orderId);

                        // Kiểm tra đơn hàng tồn tại
                        $check_sql = "SELECT id, payment_status, status FROM orders WHERE id = ?";
                        $check_stmt = $conn->prepare($check_sql);
                        $check_stmt->bind_param('i', $orderId);
                        $check_stmt->execute();
                        $result = $check_stmt->get_result();
                        if ($row = $result->fetch_assoc()) {
                            error_log("Found order: " . print_r($row, true));
                        } else {
                            error_log("Order not found with ID: " . $orderId);
                        }
                        $check_stmt->close();

                        // Thực hiện cập nhật
                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param('i', $orderId);
                            $result = $stmt->execute();

                            // Debug kết quả cập nhật
                            error_log("Update result: " . ($result ? "Success" : "Failed"));
                            error_log("Affected rows: " . $stmt->affected_rows);
                            if (!$result) {
                                error_log("SQL Error: " . $stmt->error);
                            }

                            $stmt->close();

                            // Xóa cookie giỏ hàng
                            setcookie('cart', '', time() - 3600, '/');

                            echo "<h4 style='color: #3c763d;'>✓ Thanh toán thành công</h4>";
                        }
                    } else {
                        // Cập nhật trạng thái đơn hàng thất bại
                        $sql = "UPDATE orders SET payment_status = 'failed' WHERE id = ? LIMIT 1";
                        $stmt = $conn->prepare($sql);
                        if ($stmt) {
                            $stmt->bind_param('i', $orderId);
                            $stmt->execute();
                            $stmt->close();
                        }
                        echo "<h4 style='color: #a94442;'>✗ Thanh toán không thành công</h4>";
                    }
                } else {
                    echo "<h4 style='color: #a94442;'>✗ Chữ ký không hợp lệ</h4>";
                }
                ?>
            </div>

            <div class="actions">
                <a href="history_new.php" class="btn btn-primary">Xem lịch sử đơn hàng</a>
                <a href="index.php" class="btn btn-default">Về trang chủ</a>
            </div>
        </div>
        <footer class="footer">
            <p class="text-center">&copy; <?php echo date('Y'); ?> <?php echo $storeName ?? 'Chagge Store'; ?></p>
        </footer>
    </div>
</body>

</html>
<?php mysqli_close($conn); ?>