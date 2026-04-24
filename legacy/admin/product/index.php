<?php
require_once('../../utils/config.php');
require_once('../../database/config.php');
require_once('../../database/dbhelper.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Quản Lý Sản Phẩm</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Nav Tabs */
        .nav-tabs {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #495057;
            font-weight: 500;
            border: none;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            background: #e9ecef;
            border-radius: 5px 5px 0 0;
        }

        .nav-tabs .nav-link.active {
            background: #28a745;
            /* màu chủ đạo */
            color: #fff;
            border-radius: 5px 5px 0 0;
        }

        /* Table */
        .table {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            overflow: hidden;
        }

        .table thead {
            background: #28a745;
            /* màu chủ đạo */
            color: #fff;
        }

        .table tbody tr:hover {
            background: #f1f3f5;
            transition: 0.3s;
        }

        .table img {
            border-radius: 6px;
            width: 50px;
            height: auto;
        }

        /* Button */
        .btn {
            font-weight: 500;
            border-radius: 6px;
            padding: 6px 12px;
        }

        .btn-warning {
            background-color: #f39c12;
            border: none;
        }

        .btn-warning:hover {
            background-color: #e67e22;
        }

        .btn-danger {
            background-color: #e74c3c;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Pagination */
        .pagination .page-link {
            color: #28a745;
            font-weight: 500;
        }

        .pagination .active .page-link {
            background: #28a745;
            border: none;
            color: #fff;
        }
    </style>
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
            box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
            background: #fff;
            padding: 20px;
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

        /* Buttons */
        .btn-success {
            border-radius: 20px;
            padding: 8px 20px;
        }
        .btn-warning, .btn-danger {
            border-radius: 8px;
            padding: 5px 12px;
        }
</style>

</head>


<body>
    <ul class="nav nav-tabs" style="padding-left: 21vw;">
        <li class="nav-item">
            <a class="nav-link" href="../index.php">Thống kê</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../category/">Quản lý danh mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="index.php">Quản lý sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../dashboard.php">Quản lý đơn hàng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../user/">Quản lý người dùng</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../logout.php">Đăng xuất</a>
        </li>
    </ul>

    <div class="container  container-custom">
        <div class="card card-custom">
            <div class="panel-heading">
                <h3 class="text-center mb-4">Quản lý Sản Phẩm</h3>
            </div>
            <div class="panel-body">
                <a href="add.php">
                    <button class="btn btn-success" style="margin-bottom: 20px;"> + Thêm Sản Phẩm</button>
                </a>
                <?php
                $limit = 5;
                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                $start = ($page - 1) * $limit;

                $sql = "
                    SELECT 
                        p.id, 
                        p.title, 
                        p.thumbnail, 
                        p.content, 
                        p.id_category, 
                        ps.size, 
                        ps.price,
                        c.name AS category_name
                    FROM 
                        (SELECT * FROM product LIMIT $start, $limit) AS p_ids  -- Truy vấn con để phân trang sản phẩm
                    JOIN product p ON p.id = p_ids.id  -- JOIN lại với bảng product
                    LEFT JOIN product_size ps ON p.id = ps.product_id  -- JOIN với bảng product_size
                    LEFT JOIN category c ON p.id_category = c.id  -- JOIN với bảng category
                    ORDER BY p.id;

                    ";

                $productList = executeResult($sql);

                // Xử lý kết quả để nhóm các size cùng một sản phẩm
                $groupedProducts = [];
                foreach ($productList as $item) {
                    // Nếu sản phẩm chưa có trong mảng thì tạo mới
                    if (!isset($groupedProducts[$item['id']])) {
                        $groupedProducts[$item['id']] = [
                            'id' => $item['id'],
                            'title' => $item['title'],
                            'thumbnail' => $item['thumbnail'],
                            'content' => $item['content'],
                            'id_category' => $item['id_category'],
                            'category_name' => $item['category_name'],
                            'sizes' => [] // Mảng chứa các size của sản phẩm
                        ];
                    }

                    // Thêm size vào sản phẩm
                    $groupedProducts[$item['id']]['sizes'][] = [
                        'size' => $item['size'],
                        'price' => $item['price']
                    ];
                }
                ?>
                <table class="table table-bordered table-hover" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden;">
                    <thead style="background:#343a40 !important; color: #fff;">
                        <tr style="font-weight: 500;">
                            <td width="70px">STT</td>
                            <td>Thumbnail</td>
                            <td>Tên Sản Phẩm</td>
                            <td>Giá (Small)</td>
                            <td>Nội dung</td>
                            <td>Danh Mục</td>
                            <td>Size</td>
                            <td width="50px">Sửa</td>
                            <td width="50px">Xoá</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($groupedProducts)) {
                            $index = $start + 1;


                            foreach ($groupedProducts as $product) {
                                $smallestPrice = min(array_column($product['sizes'], 'price'));
                                echo '<tr>
                                   <td>' . htmlspecialchars($product['id']) . '</td>
                                    <td style="text-align: center;">
                                        <img src="' . htmlspecialchars($product['thumbnail']) . '" alt="" style="width: 50px;">
                                    </td>
                                    <td>' . htmlspecialchars($product['title']) . '</td>
                                     <td>' . number_format($smallestPrice, 0, ',', '.') . ' VNĐ</td> 
                                    <td>' . htmlspecialchars($product['content']) . '</td>
                                    <td>' . htmlspecialchars($product['category_name']) . '</td>
                                    <td>';

                                // Hiển thị tất cả các size cho sản phẩm này
                                foreach ($product['sizes'] as $size) {
                                    echo htmlspecialchars($size['size']) . ' (' . number_format($size['price'], 0, ',', '.') . ' VNĐ)<br>';
                                }

                                echo '</td>
                                    <td>
                                        <a href="add.php?id=' . $product['id'] . '">
                                            <button class="btn btn-warning">Sửa</button>
                                        </a>
                                    </td>
                                    <td>
                                        <button class="btn btn-danger" onclick="deleteProduct(' . $product['id'] . ')">Xoá</button>
                                    </td>
                                </tr>';
                            }
                        } else {
                            echo '<tr><td colspan="9" class="text-center">Không có sản phẩm nào</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <ul class="pagination">
                <?php
                $sql = "SELECT COUNT(*) AS total FROM product";
                $result = executeSingleResult($sql);
                $totalRecords = $result['total'];
                $totalPages = ceil($totalRecords / $limit);

                for ($i = 1; $i <= $totalPages; $i++) {
                    if ($i == $page) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <script type="text/javascript">
        function deleteProduct(id) {
            Swal.fire({
                title: "Bạn có chắc chắn?",
                text: "Sản phẩm sẽ bị xóa vĩnh viễn!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e74c3c",
                cancelButtonColor: "#95a5a6",
                confirmButtonText: "Xóa ngay!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('ajax.php', {
                        id: id,
                        action: 'delete'
                    }, function (data) {
                        if (data.includes('success')) {
                            Swal.fire("Đã xóa!", "Sản phẩm đã được xóa thành công.", "success")
                                .then(() => location.reload());
                        } else {
                            Swal.fire("Lỗi!", "Có lỗi xảy ra: " + data, "error");
                        }
                    }).fail(function () {
                        Swal.fire("Lỗi!", "Không thể kết nối đến server!", "error");
                    });
                }
            });
        }

    </script>
</body>

</html>