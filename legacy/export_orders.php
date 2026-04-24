<?php
require_once(__DIR__ . '/utils/config.php');
require_once(__DIR__ . '/database/dbhelper.php');


header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=orders_export_' . date('Ymd_His') . '.csv');

$output = fopen('php://output', 'w');

// Ghi header
fputcsv($output, [
    'Mã đơn', 'User', 'Sản phẩm', 'Size', 'Địa chỉ',
    'Ngày đặt', 'SĐT', 'Ghi chú', 'Tổng tiền',
    'Thanh toán', 'Trạng thái'
]);

// Query dữ liệu
$sql = "SELECT 
           o.id AS order_id,
           o.fullname,
           o.address,
           o.phone_number,
           o.payment_method,
           o.order_date,
           o.note,
           SUM(od.num * od.price) AS total_price,
           GROUP_CONCAT(CONCAT(p.title, ' (', od.num, ')') SEPARATOR '; ') AS product_list,
           GROUP_CONCAT(od.size SEPARATOR ', ') AS sizes,
           o.status AS order_status
        FROM orders o
        JOIN order_details od ON o.id = od.order_id
        JOIN product p ON p.id = od.product_id
        GROUP BY o.id
        ORDER BY o.order_date DESC";

$rows = executeResult($sql);

foreach ($rows as $row) {
    fputcsv($output, [
        $row['order_id'],
        $row['fullname'],
        $row['product_list'],
        $row['sizes'],
        $row['address'],
        $row['order_date'],
        $row['phone_number'],
        $row['note'],
        number_format($row['total_price'], 0, ',', '.') . ' VNĐ',
        $row['payment_method'],
        $row['order_status']
    ]);
}

fclose($output);
exit;
