<?php session_start() ?>
<?php require_once('layout/header.php'); ?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }

        .contact-container {
            max-width: 800px;
            margin: 100px auto 50px;
            /* Đẩy form xuống xa hơn header */
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 4%;
        }

        .contact-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .contact-header h2 {
            color: #1d0945ff;
            font-size: 35px;
            font-weight: bold;
            padding-bottom: 40px;
        }

        .contact-header p {
            color: #777;
            font-size: 18px;
            font-weight: normal;
            line-height: 2.0;
        }

        .form-group label {
            font-weight: bold;
            color: #555;
        }

        .form-group input,
        .form-group textarea {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            font-size: 14px;
        }

        .form-group textarea {
            resize: none;
        }

        .form-control {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            font-size: 15px;
        }

        .radio-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .radio-option {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            text-align: center;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .radio-option input {
            display: none;
        }

        .radio-option:hover {
            background-color: #f8f8f8;
        }

        .radio-option input:checked+label {
            background-color: #002855;
            color: #fff;
            border-color: #002855;
        }

        .btn-submit {
            background-color: #002855;
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #003c82;
            cursor: pointer;
        }

        .contact-info {
            margin-top: 40px;
            text-align: center;
        }

        .contact-info h4 {
            color: #555;
            font-size: 18px;
        }

        .contact-info p {
            color: #777;
            font-size: 14px;
        }

        /* hero section */
        .hero-section {
            position: relative;
            background-size: contain;
            background-repeat: no-repeat;
            width: 100%;
            height: 83vh;
            background-image: url('/Chagge_Store/images/bg/bg-sendmail.jpeg');
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-section .overlay {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: flex-end;
            justify-content: left;
            padding: 20px;
        }

        .hero-section .title {
            font-size: 58px;
            font-weight: bold;
            margin-bottom: 20px;
            letter-spacing: 3px;
            line-height: 2.0;
            padding-left: 7vw;
            padding-bottom: 1vh;
        }
    </style>
</head>

<body>
    <section class="hero-section">
        <div class="overlay">
            <div class="content text-center text-white">
                <h2 class="title">LIÊN HỆ VỚI CHÚNG TÔI</h2>
            </div>
        </div>
    </section>
    <div class="contact-header" style="padding-top: 80px;">
        <h2>LIÊN HỆ</h2>
        <p>Bạn có điều gì muốn chia sẻ với chúng tôi? Chúng tôi rất mong được lắng nghe! Hãy chọn bất kỳ tab nào bên
            dưới và gửi tin nhắn cho chúng tôi. <br> customercare@chagee.com.my</p>
    </div>

    <div class="contact-container">

        <form method="POST" action="">
            <!-- Loại tư vấn -->
            <div class="form-group mb-3">
                <label class="form-label">LOẠI TƯ VẤN</label>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="type" id="general" value="general"
                            autocomplete="off" required>
                        <label class="btn btn-outline-secondary w-100" for="general">Yêu Cầu Chung</label>
                    </div>
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="type" id="business" value="business"
                            autocomplete="off">
                        <label class="btn btn-outline-secondary w-100" for="business">Cơ Hội Kinh Doanh</label>
                    </div>
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="type" id="product" value="product"
                            autocomplete="off">
                        <label class="btn btn-outline-secondary w-100" for="product">Sản Phẩm & Dịch Vụ</label>
                    </div>
                    <div class="col-6">
                        <input type="radio" class="btn-check" name="type" id="job" value="job" autocomplete="off">
                        <label class="btn btn-outline-secondary w-100" for="job">Cơ Hội Việc Làm</label>
                    </div>
                </div>
            </div>


            <!-- Các input -->
            <div class="form-group mb-3">
                <label for="name">Tên</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Tên của bạn" required>
            </div>

            <div class="form-group mb-3">
                <label for="phone">Số liên lạc</label>
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Số điện thoại" required>
            </div>

            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Địa chỉ email" required>
            </div>

            <div class="form-group mb-3">
                <label for="subject">Chủ Thể</label>
                <input type="text" class="form-control" id="subject" name="subject" placeholder="Nhập chủ đề" required>
            </div>

            <div class="form-group mb-3">
                <label for="message">Tin nhắn/Thắc mắc</label>
                <textarea class="form-control" id="message" name="message" rows="5" placeholder="Nhập nội dung"
                    required></textarea>
            </div>

            <button type="submit" name="send" class="btn-submit">NỘP</button>
        </form>
    </div>

    <?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'phpmailer/src/Exception.php';
    require 'phpmailer/src/PHPMailer.php';
    require 'phpmailer/src/SMTP.php';
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $type = $_POST['type'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $subject = $_POST['subject'];
        $message = $_POST['message'];

        // Map phòng ban
        $departments = [
            'general' => 'khuong11a11@gmail.com',
            'business' => 'khuong11a11@gmail.com',
            'product' => 'khuong11a11@gmail.com',
            'job' => 'khuong11a11@gmail.com'
        ];

        $toEmail = $departments[$type] ?? 'khuong11a11@gmail.com';

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->Host = 'khuong11a11@gmail.com';
            $mail->SMTPAuth = true;
            $nguoigui = 'khuongdao2k4@gmail.com';
            $matkhau = 'ksvt azaw xqzi thjl';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->Username = $nguoigui;
            $mail->Password = $matkhau;
            $mail->setFrom($nguoigui, "Chagge Store");
            $mail->addAddress($toEmail, "Phòng ban phụ trách");
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "[Liên hệ - $subject]";
            $mail->Body = "
        <b>Bạn có một yêu cầu mới từ khách hàng:</b><br><br>
        <b>Tên:</b> $name <br>
        <b>Email:</b> $email <br>
        <b>SĐT:</b> $phone <br>
        <b>Loại tư vấn:</b> $type <br>
        <b>Nội dung:</b> <br>" . nl2br($message);

            $mail->send();
            echo '<center style="color: green; font-weight: bold;">Yêu cầu đã được gửi thành công!</center>';
        } catch (Exception $e) {
            echo '<center style="color: red;">Mail không gửi được. Lỗi: ' . $mail->ErrorInfo . '</center>';
        }
    }
    ?>
</body>
<?php require_once('layout/footer.php'); ?>

</html>