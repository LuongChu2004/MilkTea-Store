<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('utils/config.php');
require_once('database/dbhelper.php');
require_once('utils/utility.php');
require_once('vnpay_php/config.php');
$conn = getDbConnection();

// Kiểm tra giỏ hàng hoặc mua ngay
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
if (!is_array($cart)) {
    $cart = [];
}

// Xử lý "Mua Ngay"
if (isset($_GET['id'], $_GET['num'], $_GET['size'], $_GET['price'])) {
    if (!is_numeric($_GET['id']) || !is_numeric($_GET['num']) || !is_numeric($_GET['price'])) {
        echo '<script>alert("Dữ liệu không hợp lệ!"); window.location="index.php";</script>';
        exit();
    }

    $buyNowItem = [
        'id' => intval($_GET['id']),
        'num' => intval($_GET['num']),
        'size' => $_GET['size'],
        'price' => floatval($_GET['price']),
        'sugar_level' => $_GET['sugar_level'] ?? '',
        'ice_level' => $_GET['ice_level'] ?? ''
    ];

    $cart = [$buyNowItem];
    $isBuyNow = true;

    // 🔥 Lấy thêm thông tin từ DB
    $sql = "SELECT id, title, thumbnail 
            FROM product 
            WHERE id = " . $buyNowItem['id'] . " LIMIT 1";
    $result = executeResult($sql);

    if ($result) {
        $product = $result[0];
        $product['num'] = $buyNowItem['num'];
        $product['size'] = $buyNowItem['size'];
        $product['price'] = $buyNowItem['price'];
        $product['sugar_level'] = $buyNowItem['sugar_level'];
        $product['ice_level'] = $buyNowItem['ice_level'];

        $cartList = [$product]; // ✅ có đủ title, thumbnail, size, num, price...
    } else {
        $cartList = [];
    }
} else {
    $isBuyNow = false;
    if (!empty($cart)) {
        $ids = array_column($cart, 'id');
        if (!empty($ids)) {
            $sql = "SELECT p.id, p.title, p.thumbnail, ps.size, ps.price 
        FROM product p
        JOIN product_size ps ON p.id = ps.product_id
        WHERE p.id IN (" . implode(',', $ids) . ")";
            $cartList = executeResult($sql);

        }
    } else {
        $cartList = [];
    }
}
$total = 0;
if ($isBuyNow && count($cartList) > 0) {
    $total = $cartList[0]['num'] * $cartList[0]['price'];
} else {
    foreach ($cartList as $item) {
        foreach ($cart as $value) {
            if ($value['id'] == $item['id'] && $value['size'] == $item['size']) {
                $total += $value['num'] * $item['price'];
            }
        }
    }
}
// Kiểm tra đăng nhập
$username = $_COOKIE['username'] ?? '';
if (empty($username)) {
    echo '<script>alert("Vui lòng đăng nhập để mua hàng"); window.location="login/login.php";</script>';
    exit();
}

// Lấy ID user
$sqlUser = "SELECT id_user FROM user WHERE username = ? LIMIT 1";
$resultUser = executeResult($sqlUser, [$username]);
if (count($resultUser) == 0) {
    echo '<script>alert("Người dùng không hợp lệ!"); window.location="login/login.php";</script>';
    exit();
}
$id_user = $resultUser[0]['id_user'];

// Danh sách sản phẩm trong giỏ
$idList = [];
foreach ($cart as $item) {
    if ($item['num'] > 0) {
        $idList[] = $item['id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 🔥 Lấy lại dữ liệu từ form
    $isBuyNow = isset($_POST['is_buy_now']) && $_POST['is_buy_now'] == 1;
    $cart = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];

    // Debug thử
    file_put_contents("debug_total.log", "POST DATA | isBuyNow=" . ($isBuyNow ? "true" : "false") . " | CART=" . json_encode($cart) . "\n", FILE_APPEND);

    $fullname = trim($_POST['fullname']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone_number = preg_match('/^[0-9]{10,11}$/', $_POST['phone_number']) ? $_POST['phone_number'] : '';
    $address = trim($_POST['address']);
    $note = trim($_POST['note']);
    $payment_method = $_POST['payment_method'] ?? '';

    if (!$fullname || !$email || !$phone_number || !$address || !$payment_method) {
        echo '<script>alert("Vui lòng nhập đầy đủ thông tin!"); window.location="checkout.php";</script>';
        exit();
    }

    $payment_status = ($payment_method == 'COD') ? 'confirmed' : 'pending';
    $status = 'Chờ xử lý';

    // Insert đơn hàng
    $orderSql = "INSERT INTO orders (fullname, email, phone_number, address, note, id_user, payment_method, payment_status, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($orderSql);
    if (!$stmt) {
        die("❌ Lỗi prepare SQL: " . $conn->error);
    }
    $stmt->bind_param('sssssssss', $fullname, $email, $phone_number, $address, $note, $id_user, $payment_method, $payment_status, $status);
    if (!$stmt->execute()) {
        die("❌ Lỗi execute SQL: " . $stmt->error);
    }
    $orderId = $stmt->insert_id;
    $stmt->close();

    // ✅ Insert order_details và tính lại tổng
    $total = 0;

    if ($isBuyNow && !empty($cart)) {
        $item = $cart[0];
        $sizeInfo = $item['size'] . " - Đường: " . ($item['sugar_level'] ?? '') . " - Đá: " . ($item['ice_level'] ?? '');
        $sql = "INSERT INTO order_details (order_id, product_id, size, num, price, id_user, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql) or die("❌ SQL Error: " . $conn->error);
        $stmt->bind_param('iisidi', $orderId, $item['id'], $sizeInfo, $item['num'], $item['price'], $id_user);
        $stmt->execute();
        $stmt->close();

        $total = (int) ($item['num'] * $item['price']);
    } else {
        foreach ($cart as $value) {
            $sizeInfo = $value['size'] . " - Đường: " . ($value['sugar_level'] ?? '') . " - Đá: " . ($value['ice_level'] ?? '');
            $sql = "INSERT INTO order_details (order_id, product_id, size, num, price, id_user, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql) or die("❌ SQL Error: " . $conn->error);
            $stmt->bind_param('iisidi', $orderId, $value['id'], $sizeInfo, $value['num'], $value['price'], $id_user);
            $stmt->execute();
            $stmt->close();

            $total += $value['num'] * $value['price'];
        }
        $total = (int) $total;
    }

    // Debug tổng tiền
    file_put_contents("debug_total.log", "AFTER INSERT | TOTAL=" . $total . " | orderId=" . $orderId . "\n", FILE_APPEND);


    // Nếu COD
    if ($payment_method == 'COD') {
        echo '<script>
            alert("Đặt hàng thành công!");
            document.cookie = "cart=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            window.location="history_new.php";
        </script>';
        exit();
    }

    // Nếu VNPAY
    if ($payment_method == 'VNPAY') {
        if ($total < 10000) {
            die("❌ Lỗi: Tổng tiền phải >= 10,000 VND. Hiện tại: " . number_format($total, 0, ',', '.') . " VND");
        }

        if ($orderId <= 0) {
            die("❌ Lỗi: Không tạo được đơn hàng. Vui lòng thử lại!");
        }

        // Tạo URL thanh toán VNPay
        $vnp_TxnRef = $orderId;
        $vnp_OrderInfo = 'Thanh toan don hang #' . $orderId;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $total * 100;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $_SERVER['REMOTE_ADDR'],
            "vnp_Locale" => "vn",
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        header('Location: ' . $vnp_Url);
        die();
    }
}


mysqli_close($conn);
?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="plugin/fontawesome/css/all.css">
    <link rel="stylesheet" href="css/cart.css">
    <title>Thanh toán</title>
</head>


<body>
    <div id="wrapper">
        <?php require_once('layout/header.php'); ?>
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

            .table td,
            .table th {
                vertical-align: middle;
                font-size: 14px;
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
                                    <form action="checkout.php" method="POST" id="checkoutForm">
                                        <div class="form-group mb-3">
                                            <label for="usr">Họ và tên:</label>
                                            <input type="text" class="form-control" id="usr" name="fullname" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="email">Email:</label>
                                            <input type="email" class="form-control" id="email" name="email" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="phone_number">Số điện thoại:</label>
                                            <input type="text" class="form-control" id="phone_number"
                                                name="phone_number" required>
                                        </div>

                                        <!-- Địa chỉ -->
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="province">Tỉnh / Thành phố:</label>
                                                <select class="form-control" id="province" required></select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="district">Quận / Huyện:</label>
                                                <select class="form-control" id="district" required></select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="ward">Phường / Xã:</label>
                                                <select class="form-control" id="ward" required></select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address_detail">Số nhà, tên đường:</label>
                                                <input type="text" class="form-control" id="address_detail" required>
                                            </div>
                                        </div>
                                        <input type="hidden" name="address" id="full_address">

                                        <div class="form-group mb-3">
                                            <label for="note">Ghi chú:</label>
                                            <textarea class="form-control" rows="3" name="note" id="note"></textarea>
                                        </div>

                                        <!-- Thanh toán -->
                                        <div class="form-group mb-3">
                                            <label class="fw-bold">Chọn hình thức thanh toán:</label><br>
                                            <div class="form-check">
                                                <input type="radio" id="cod" name="payment_method" value="COD"
                                                    class="form-check-input" checked>
                                                <label for="cod" class="form-check-label">Thanh toán khi nhận
                                                    hàng</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" id="vnpay" name="payment_method" value="VNPAY"
                                                    class="form-check-input">
                                                <label for="vnpay" class="form-check-label">Thanh toán qua VNPAY</label>
                                            </div>
                                        </div>

                                        <!-- ✅ Thêm hidden input để giữ dữ liệu -->
                                        <input type="hidden" name="is_buy_now" value="<?= $isBuyNow ? 1 : 0 ?>">
                                        <input type="hidden" name="cart_data"
                                            value='<?= json_encode($cart, JSON_UNESCAPED_UNICODE) ?>'>

                                        <!-- ✅ 1 nút duy nhất -->
                                        <button type="submit" class="btn btn-primary w-100">Thanh toán</button>
                                    </form>

                                </div>
                            </div>
                        </div>

                        <!-- Đơn hàng của bạn -->
                        <div class="col-md-6" style="position: sticky;top: 100px; align-self: flex-start;">
                            <div class="card shadow-sm rounded-3">
                                <div class="card-header bg-dark text-white fw-bold">
                                    Đơn hàng của bạn
                                </div>
                                <div class="card-body">
                                    <div class="order-list">
                                        <?php
                                        $count = 0;
                                        if ($isBuyNow && count($cartList) > 0) {
                                            $item = $cartList[0];
                                            $num = $item['num'];
                                            $sugar = $item['sugar_level'] ?? 'Không rõ';
                                            $ice = $item['ice_level'] ?? 'Không rõ';
                                            ?>
                                            <div class="order-item">
                                                <div class="order-img">
                                                    <img src="admin/product/<?= $item['thumbnail'] ?>"
                                                        alt="<?= $item['title'] ?>">
                                                </div>
                                                <div class="order-info">
                                                    <h5><?= $item['title'] ?></h5>
                                                    <p>Size: <?= $item['size'] ?> | Đường: <?= $sugar ?> | Đá: <?= $ice ?>
                                                    </p>
                                                    <p>Giá: <span
                                                            class="price"><?= number_format($item['price'], 0, ',', '.') ?>
                                                            VNĐ</span></p>
                                                    <p>Số lượng: <?= $num ?></p>
                                                    <p class="subtotal">Tổng:
                                                        <?= number_format($num * $item['price'], 0, ',', '.') ?> VNĐ
                                                    </p>
                                                </div>
                                            </div>
                                            <?php
                                        } else {
                                            foreach ($cartList as $item) {
                                                foreach ($cart as $value) {
                                                    if ($value['id'] == $item['id'] && $value['size'] == $item['size']) {
                                                        $num = $value['num'];
                                                        $sugar = $value['sugar_level'] ?? 'Không rõ';
                                                        $ice = $value['ice_level'] ?? 'Không rõ';
                                                        ?>
                                                        <div class="order-item">
                                                            <div class="order-img">
                                                                <img src="admin/product/<?= $item['thumbnail'] ?>"
                                                                    alt="<?= $item['title'] ?>">
                                                            </div>
                                                            <div class="order-info">
                                                                <h5><?= $item['title'] ?></h5>
                                                                <p>Size: <?= $item['size'] ?> | Đường: <?= $sugar ?> | Đá: <?= $ice ?>
                                                                </p>
                                                                <p>Giá: <span
                                                                        class="price"><?= number_format($item['price'], 0, ',', '.') ?>
                                                                        VNĐ</span></p>
                                                                <p>Số lượng: <?= $num ?></p>
                                                                <p class="subtotal">Tổng:
                                                                    <?= number_format($num * $item['price'], 0, ',', '.') ?> VNĐ
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </div>

                                    <h4 class="text-end text-danger fw-bold mt-3">
                                        Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VNĐ
                                    </h4>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                $(document).ready(function () {
                    // Validation form
                    $('form').on('submit', function (e) {
                        var isValid = true;
                        var errors = [];

                        if ($('#usr').val().trim() === '') {
                            errors.push('Vui lòng nhập họ và tên!');
                            isValid = false;
                        }

                        var email = $('#email').val();
                        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(email)) {
                            errors.push('Vui lòng nhập email hợp lệ!');
                            isValid = false;
                        }

                        var phone = $('#phone_number').val();
                        var phoneRegex = /^[0-9]{10,11}$/;
                        if (!phoneRegex.test(phone)) {
                            errors.push('Số điện thoại phải có 10-11 chữ số!');
                            isValid = false;
                        }

                        if ($('#province').val() === '' || $('#district').val() === '' || $('#ward').val() === '') {
                            errors.push('Vui lòng chọn đầy đủ tỉnh/thành, quận/huyện, phường/xã!');
                            isValid = false;
                        }

                        if ($('#address_detail').val().trim() === '') {
                            errors.push('Vui lòng nhập địa chỉ chi tiết!');
                            isValid = false;
                        }

                        if (!isValid) {
                            e.preventDefault();
                            alert(errors.join('\n'));
                            return false;
                        }

                        // Kết hợp địa chỉ
                        const provinceName = $("#province option:selected").text();
                        const districtName = $("#district option:selected").text();
                        const wardName = $("#ward").val();
                        const detail = $("#address_detail").val();

                        const fullAddress = `${detail}, ${wardName}, ${districtName}, ${provinceName}`;
                        $("#full_address").val(fullAddress);
                    });

                    // ❌ Không cần ẩn nút khi chọn VNPAY nữa
                });
            </script>

            <script>
                $(document).ready(function () {
                    // Load tỉnh/thành
                    $.get("https://provinces.open-api.vn/api/?depth=1", function (data) {
                        $("#province").append('<option value="">-- Chọn tỉnh/thành --</option>');
                        data.forEach(function (province) {
                            $("#province").append(
                                `<option value="${province.code}">${province.name}</option>`);
                        });
                    });

                    // Khi chọn tỉnh, load huyện
                    $("#province").on("change", function () {
                        const provinceCode = $(this).val();
                        $("#district").empty().append('<option value="">-- Chọn huyện --</option>');
                        $("#ward").empty().append('<option value="">-- Chọn xã --</option>');

                        if (provinceCode) {
                            $.get(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function (data) {
                                data.districts.forEach(function (district) {
                                    $("#district").append(
                                        `<option value="${district.code}">${district.name}</option>`
                                    );
                                });
                            });
                        }
                    });

                    // Khi chọn huyện, load xã
                    $("#district").on("change", function () {
                        const districtCode = $(this).val();
                        $("#ward").empty().append('<option value="">-- Chọn xã --</option>');

                        if (districtCode) {
                            $.get(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function (data) {
                                data.wards.forEach(function (ward) {
                                    $("#ward").append(
                                        `<option value="${ward.name}">${ward.name}</option>`);
                                });
                            });
                        }
                    });
                });
            </script>
        </main>

        <style>
            .xemlai {
                font-size: 18px;
                font-weight: 500;
                color: blue;
            }


            .b-500 {
                font-weight: 500;
            }


            .bold {
                font-weight: bold;
            }


            .red {
                color: rgba(207, 16, 16, 0.815);
            }
        </style>
        <style>
            .order-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
                max-height: 78vh;
                /* Giới hạn chiều cao */
                overflow-y: auto;
                /* Thanh cuộn khi vượt quá */
                padding-right: 8px;
            }

            .order-item {
                display: flex;
                gap: 1rem;
                border: 1px solid #eee;
                padding: 1rem;
                border-radius: 12px;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
                transition: box-shadow 0.2s ease;
            }

            .order-item:hover {
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
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

            .order-info .subtotal {
                color: #c0392b;
                font-weight: bold;
            }
        </style>

        </main>

        <?php require_once('layout/footer.php'); ?>
    </div>
</body>

</html>