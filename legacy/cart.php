<?php
require_once('utils/config.php');
require_once('database/dbhelper.php');
require_once('utils/utility.php');
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

$idList = [];
foreach ($cart as $item) {
    $idList[] = $item['id'];
}

$cartList = [];
if (count($idList) > 0) {
    $idList = implode(',', $idList);
    $sql = "SELECT p.id, p.title, p.thumbnail, ps.size, ps.price FROM product p
            JOIN product_size ps ON p.id = ps.product_id
            WHERE p.id IN ($idList)";
    $cartList = executeResult($sql);
} else {
    $cartList = [];
}
$count = count($cartList);
$total = 0;
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

    <title>Giỏ hàng</title>
</head>

<body>
    <div id="wrapper">
        <?php require_once('layout/header.php'); ?>
        <main style="padding-bottom: 4rem; padding-top: 120px;">
            <section class="cart">
                <div class="container">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">🛒 Giỏ hàng</h3>
                            <ul class="nav nav-tabs border-0">
                                <li class="nav-item">
                                    <a class="nav-link active" href="cart.php">Giỏ hàng</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="history_new.php">Lịch sử mua hàng</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <?php
                            if (!isset($_COOKIE['username'])) {
                                echo '<div class="alert alert-warning text-center">
                <h5>🔒 Cần đăng nhập để xem giỏ hàng</h5>
                <a href="login/login.php" class="btn btn-primary mt-3">Đăng nhập</a>
              </div>';
                            } else {
                                if ($count > 0) {
                                    foreach ($cartList as $item) {
                                        $num = 0;
                                        $ice = 'Không chọn';
                                        $sugar = 'Không chọn';
                                        foreach ($cart as $value) {
                                            if ($value['id'] == $item['id'] && $value['size'] == $item['size']) {
                                                $num = $value['num'];
                                                $ice = $value['ice_level'] ?? 'Không chọn';
                                                $sugar = $value['sugar_level'] ?? 'Không chọn';
                                                break;
                                            }
                                        }
                                        if ($num > 0) {
                                            $total += $num * $item['price'];
                                            ?>
                                            <!-- ✅ HTML hiển thị từng sản phẩm -->
                                            <div class="cart-item card mb-3 border-0 shadow-sm">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col-3 col-md-2 p-2 text-center">
                                                        <img src="admin/product/<?= $item['thumbnail'] ?>" class="img-fluid rounded"
                                                            alt="<?= $item['title'] ?>">
                                                    </div>
                                                    <div class="col-9 col-md-10 p-3">
                                                        <div class="d-flex justify-content-between">
                                                            <h5 class="mb-1"><?= $item['title'] ?></h5>
                                                            <button class="btn btn-sm btn-outline-danger"
                                                                onclick="deleteFromCart(<?= $item['id'] ?>, '<?= $item['size'] ?>', '<?= $sugar ?>', '<?= $ice ?>', this)">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                        <p class="mb-1 text-muted small">
                                                            Size: <?= $item['size'] ?> | Đá: <?= $ice ?> | Đường: <?= $sugar ?>
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                                            <div>
                                                                <label class="mb-0 mr-2 small text-muted">Số lượng:</label>
                                                                <input type="number" class="form-control d-inline-block quantity-input"
                                                                    style="width: 70px;" min="1" value="<?= $num ?>"
                                                                    onchange="updateQuantity(<?= $item['id'] ?>, '<?= $item['size'] ?>', this.value, '<?= $sugar ?>', '<?= $ice ?>')">
                                                            </div>
                                                            <div class="text-right">
                                                                <span
                                                                    class="h6 text-success"><?= number_format($item['price'], 0, ',', '.') ?>
                                                                    VNĐ</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } // end foreach
                                    ?>
                                    <!-- ✅ Tổng cộng -->
                                    <div class="card mt-4 border-0 shadow-sm">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <h4 class="mb-0">Tổng cộng:
                                                <span id="cart-total"
                                                    class="text-danger"><?= number_format($total, 0, ',', '.') ?> VNĐ</span>
                                            </h4>
                                            <div>
                                                <a href="index.php" class="btn btn-outline-secondary mr-2">Tiếp tục mua</a>
                                                <button class="btn btn-success" onclick="handleCheckout()">Thanh toán</button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                } else {
                                    echo '<div class="alert alert-info text-center">
                    <h5>Giỏ hàng trống!</h5>
                    <a href="index.php" class="btn btn-primary mt-3">Mua sắm ngay</a>
                  </div>';
                                }
                            }
                            ?>
                        </div>

                    </div>
                </div>
            </section>
        </main>

        <?php require_once('layout/footer.php'); ?>
    </div>
    <script type="text/javascript">
        function deleteFromCart(id, size, sugar_level, ice_level, btn) {
            if (!confirm("Bạn có chắc muốn xoá sản phẩm này?")) return;

            $.post('api/cookie.php', {
                action: 'delete',
                id: id,
                size: size,
                sugar_level: sugar_level,
                ice_level: ice_level
            }, function (response) {
                const data = JSON.parse(response);
                if (data.status === 'success') {
                    const item = btn.closest('.cart-item'); // ✅ lấy card chứa sản phẩm
                    if (item) {
                        item.style.transition = "opacity 0.4s";
                        item.style.opacity = "0";
                        setTimeout(() => {
                            item.remove();

                            // ✅ cập nhật lại tổng cộng sau khi xoá
                            location.reload(); // nếu muốn tự động tính lại thì thay bằng ajax update total
                        }, 400);
                    }
                }
            });
        }



        function updateQuantity(id, size, quantity, sugar, ice) {
            if (quantity < 1) {
                alert("Số lượng phải lớn hơn 0!");
                return;
            }

            $.post('api/cookie.php', {
                action: 'update',
                id: id,
                size: size,
                num: quantity, // Đúng tên biến mà PHP đang dùng
                sugar: sugar,
                ice: ice
            }, function (response) {
                const data = JSON.parse(response);
                const itemTotal = data.price * quantity;

                // ✅ Cập nhật tổng tiền của từng dòng
                const rowTotal = document.querySelector(`#row-total-${id}-${size}`);
                if (rowTotal) {
                    rowTotal.textContent = itemTotal.toLocaleString('vi-VN') + " VNĐ";
                }

                // ✅ Cập nhật tổng cộng
                const totalElement = document.getElementById('cart-total');
                if (totalElement) {
                    totalElement.textContent = data.total.toLocaleString('vi-VN') + " VNĐ";
                }
            });
        }



        function checkLogin() {
            // Kiểm tra xem người dùng đã đăng nhập chưa bằng cách check cookie username
            var username = getCookie('username');

            if (!username || username === '') {
                return false; // Chưa đăng nhập
            }

            return true; // Đã đăng nhập
        }

        function handleCheckout() {
            // Kiểm tra đăng nhập trước khi thanh toán
            if (!checkLogin()) {
                // Nếu chưa đăng nhập, hiển thị modal xác nhận
                if (confirm(
                    'Bạn cần đăng nhập để thực hiện thanh toán.\n\nChọn "OK" để chuyển đến trang đăng nhập\nChọn "Cancel" để tiếp tục mua sắm'
                )) {
                    // Lưu URL hiện tại để redirect về sau khi đăng nhập
                    sessionStorage.setItem('redirectAfterLogin', window.location.href);
                    window.location.href = 'login/login.php';
                }
                return false;
            }

            // Nếu đã đăng nhập, kiểm tra giỏ hàng có sản phẩm không
            var cart = getCookie('cart');
            if (!cart || cart === '' || cart === '[]') {
                alert('Giỏ hàng của bạn đang trống!');
                return false;
            }

            // Tất cả điều kiện OK, chuyển đến checkout
            window.location.href = 'checkout.php';
        }

        // Hàm tiện ích để đọc cookie
        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }
    </script>
</body>
<style>
    .b-500 {
        font-weight: 500;
    }

    .bold {
        font-weight: bold;
    }

    .red {
        color: rgba(207, 16, 16, 0.815);
    }

    .cart-item:hover {
        background: #f9f9f9;
        transition: 0.3s;
    }

    .cart-item img {
        max-height: 80px;
        object-fit: cover;
    }

    .quantity-input {
        text-align: center;
        font-weight: 500;
    }

    .btn-outline-danger i {
        pointer-events: none;
    }
</style>

</html>