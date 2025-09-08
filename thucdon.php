<?php require('layout/header.php') ?>
<main>
    <style>
        /* thanh tìm kiếm */
        .search-box {
            max-width: 1000px;
            /* Giới hạn chiều rộng */
            border-radius: 50px;
            overflow: hidden;
            /* Bo tròn liền khối */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-box .input-group-text {
            background-color: #fff;
            border: none;
            padding-left: 25px;
        }

        .search-box input {
            border: none;
            box-shadow: none;
            padding-left: 10px;
        }

        .search-box input:focus {
            outline: none;
            box-shadow: none;
            background-color: #fffaf8;
            /* highlight khi nhập */
        }

        .search-box .btn {
            border: none;
            border-radius: 0;
            padding: 8px 20px;
            background: linear-gradient(90deg, #ff7b54, #ff6b6b);
            transition: 0.3s;
            color: #fff;
            font-weight: 500;
        }

        .search-box .btn:hover {
            background: linear-gradient(90deg, #ff6b6b, #ff7b54);
        }
    </style>

    <div class="container">
        <div id="ant-layout" class="row justify-content-center my-0">
            <div class="col-12 col-md-8 col-lg-6">
                <section class="search-quan">
                    <form action="thucdon.php" method="GET" class="position-relative">
                        <div class="input-group search-box">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input name="search" type="text" class="form-control border-start-0 ps-0"
                                placeholder="    Tìm món hoặc thức ăn...">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
        <h4 style="text-align: center; padding-top: 30px;">Sản Phẩm Đề Cử</h4>


        <!-- END LAYOUT  -->
        <section class="main">
            <?php
            if (isset($_GET['page'])) {
                $page = trim(strip_tags($_GET['page']));
            } else {
                $page = "";
            }
            switch ($page) {
                case "thucdon":
                    require('menu-con/tratraicay.php');
                    require('menu-con/caphe.php');
                    require('menu-con/monannhe.php');
                    require('menu-con/banhmi.php');
                    break;
                default:
                    break;
            }
            //switch
            if (isset($_GET['id_category'])) {
                $id_category = trim(strip_tags($_GET['id_category']));
            } else {
                $id_category = 0;
            }
            ?>
            <section class="recently">
                <div class="title">
                    <?php
                    $sql = "select * from category where id=$id_category";
                    $name = executeResult($sql);
                    foreach ($name as $ten) {
                        echo '<h1 style="padding-left: 3vw;">' . $ten['name'] . '</h1>';
                    }
                    ?>
                </div>
                <div class="product-recently">
                    <div class="row">
                        <?php
                        try {
                            // Kiểm tra id_category có được truyền vào không
                            if (isset($_GET['id_category'])) {
                                $id_category = $_GET['id_category'];
                            } else {
                                $id_category = 0; // Mặc định nếu không có id_category
                            }

                            // Phân trang
                            if (isset($_GET['page'])) {
                                $page = $_GET['page'];
                            } else {
                                $page = 1;
                            }
                            $limit = 12;
                            $start = ($page - 1) * $limit;

                            // Truy vấn sản phẩm theo danh mục và lấy giá của size nhỏ nhất
                            $sql = "
            SELECT 
                p.id, 
                p.title, 
                p.thumbnail, 
                MIN(ps.price) AS price 
            FROM product p
            LEFT JOIN product_size ps ON p.id = ps.product_id
            WHERE p.id_category = $id_category
            GROUP BY p.id
            LIMIT $start, $limit
            ";

                            // Thực thi truy vấn
                            $productList = executeResult($sql);

                            // Hiển thị sản phẩm
                            foreach ($productList as $item) {
                                echo '
                <div class="col">
                    <a href="details.php?id=' . $item['id'] . '">
                        <img class="thumbnail" src="admin/product/' . $item['thumbnail'] . '" alt="">
                        <div class="title">
                            <p>' . $item['title'] . '</p>
                        </div>
                        <div class="price">
                            <span>' . number_format($item['price'], 0, ',', '.') . ' VNĐ</span>
                        </div>
                    </a>
                </div>
                ';
                            }
                        } catch (Exception $e) {
                            die("Lỗi thực thi sql: " . $e->getMessage());
                        }
                        ?>
                        <?php
                        if (isset($_GET['search'])) {
                            $search = trim(strip_tags($_GET['search']));
                            $sql = "
        SELECT 
            p.id, 
            p.title, 
            p.thumbnail, 
            MIN(ps.price) AS price 
        FROM product p
        LEFT JOIN product_size ps ON p.id = ps.product_id
        WHERE p.title LIKE '%$search%'
        GROUP BY p.id
    ";
                            $listSearch = executeResult($sql);

                            if (count($listSearch) > 0) {
                                foreach ($listSearch as $item) {
                                    $price = isset($item['price']) ? number_format($item['price'], 0, ',', '.') . ' VNĐ' : 'Liên hệ';
                                    echo '
                <div class="col">
                    <a href="details.php?id=' . $item['id'] . '">
                        <img class="thumbnail" src="admin/product/' . $item['thumbnail'] . '" alt="">
                        <div class="title">
                            <p>' . $item['title'] . '</p>
                        </div>
                        <div class="price">
                            <span>' . $price . '</span>
                        </div>
                    </a>
                </div>
            ';
                                }
                            } else {
                                echo '<h3>Không tìm thấy sản phẩm nào phù hợp với từ khóa "' . htmlspecialchars($search) . '"</h3>';
                            }
                        }
                        ?>

                    </div>
                </div>
            </section>
        </section>
    </div>
    <style>


    </style>
    <?php require('layout/footer.php') ?>