<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Danh Mục</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

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

    <!-- NAV -->
    <ul class="nav nav-tabs" style="padding-left: 21vw;">
        <li class="nav-item">
            <a class="nav-link" href="../index.php">Thống kê</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="index.php">Quản lý danh mục</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../product/">Quản lý sản phẩm</a>
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

    <div class="container container-custom">
        <div class="card card-custom">
            <h3 class="text-center mb-4">📂 Quản lý danh mục</h3>
            <a href="add.php" class="mb-3 d-inline-block">
                <button class="btn btn-success">+ Thêm Danh Mục</button>
            </a>

            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th width="70px">STT</th>
                        <th>Tên danh mục</th>
                        <th width="80px">Sửa</th>
                        <th width="80px">Xoá</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once('../../utils/config.php');
                    require_once('../../database/config.php');
                    require_once('../../database/dbhelper.php');
                    $sql = 'select * from category';
                    $categoryList = executeResult($sql);
                    $index = 1;
                    foreach ($categoryList as $item) {
                        echo '
                        <tr>
                            <td>'.$index++.'</td>
                            <td>'.$item['name'].'</td>
                            <td><a href="add.php?id='.$item['id'].'" class="btn btn-warning btn-sm">Sửa</a></td>
                            <td><button class="btn btn-danger btn-sm" onclick="deleteCategory('.$item['id'].')">Xoá</button></td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function deleteCategory(id) {
            if(confirm('Bạn có chắc chắn muốn xoá danh mục này không?')) {
                $.post('ajax.php', {id: id, action: 'delete'}, function(data) {
                    if (data.includes('success')) {
                        alert('Xóa danh mục thành công!');
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra: ' + data);
                    }
                }).fail(function() {
                    alert('Không thể kết nối đến server!');
                });
            }
        }
    </script>

</body>
</html>
