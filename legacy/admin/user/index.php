<?php
require_once('../database/dbhelper.php');

?>
<!DOCTYPE html>
<html>

<head>
    <title>Quản Lý Người Dùng</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
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
        <a class="nav-link active" href="../category/">Quản lý danh mục</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../product/">Quản lý sản phẩm</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="../dashboard.php">Quản lý giỏ hàng</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link " href="../user/">Quản lý người dùng</a>
    </li>
    <li class="nav-item ">
        <a class="nav-link " href="../logout.php">Đăng xuất</a>
    </li>
</ul>
<div class="container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h2 class="text-center" style="padding-top: 30px; padding-bottom: 20px;">Quản lý người dùng</h2>
        </div>
        <div class="panel-body"></div>
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td width="70px">STT</td>
                <td>Họ tên</td>
                <td>Tên đăng nhập</td>
                <td>Số điện thoại</td>
                <td>Email</td>
                <td width="50px">Xoá</td>
            </tr>
            </thead>
            <tbody>
            <?php
            // Lấy danh sách danh mục
            $sql = 'select * from user';
            $users = executeResult($sql);
            $index = 1;
            foreach ($users as $item) {
               
                echo '  <tr>
                    <td>' . ($index++) . '</td>
                    <td>' . $item['hoten'] . '</td>
                    <td>' . $item['username'] . '</td>
                    <td>' . $item['phone'] . '</td>
                    <td>' . $item['email'] . '</td>
                   
                    <td>            
                    <button class="btn btn-danger" onclick="deleteCategory('.$item['id_user'].')">Xoá</button>
                    </td>
                </tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<script type="text/javascript">
    function deleteCategory(id) {
        var option = confirm('Bạn có chắc chắn muốn xoá danh mục này không?')
        if(!option) {
            return;
        }
        console.log(id)
        $.post('ajax.php', {
            'id': id,
            'action': 'delete'
        }, function(data) {
            location.reload()
        })
    }
</script>
</body>

</html>