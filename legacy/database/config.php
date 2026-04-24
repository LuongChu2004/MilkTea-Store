<?php 
// Cấu hình kết nối database
define('HOST', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'chage_store');

// Cấu hình VNPay
$vnp_TmnCode    = " KXY0SQZ4"; 
$vnp_HashSecret = "CKU6HA32FLF6F20OUW8CLWZ2R6USRIW5"; 
$vnp_Url        = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; 
$vnp_Returnurl  = "http://localhost/checkout_return.php"; 
?>
