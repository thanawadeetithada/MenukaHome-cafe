<?php
session_start();
include 'config.php';

$total_price = isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : null; // ดึงค่าจาก session
$current_time = time();
$end_time = $current_time + (15 * 60);

if (!isset($_SESSION['payment_end_time'])) {
    $_SESSION['payment_end_time'] = $end_time;
}

$remaining_time = $_SESSION['payment_end_time'] - time();

if ($remaining_time <= 0) {
    unset($_SESSION['payment_end_time']);
    header("Location: menu_checkout.php");
    exit;
}
$qr_code_image = '/MenukaHome-cafe/img/QR.jpg';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>การชำระเงิน</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0px 20px 10px 20px;
    }

    .container {
        max-width: max-content;
        margin: auto;
        background: #fff;
        padding: 2.5rem;
        border-radius: 8px;
        box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.1);
        margin-top: 6.5rem;
        text-align: center;
    }

    .date-time {
        display: flex;
        justify-content: center;
    }

    p {
        font-weight: 600;
    }

    h2 {
        margin-bottom: 16px;
    }
    </style>
    <script>
    function startCountdown(seconds) {
        const countdownElement = document.getElementById('countdown');
        let remainingSeconds = seconds;

        const interval = setInterval(() => {
            if (remainingSeconds <= 0) {
                clearInterval(interval);
                alert('หมดเวลาชำระเงิน กรุณาทำรายการใหม่');
                window.location.href = 'menu_checkout.php';
            }

            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;

            countdownElement.textContent = `${minutes} นาที ${seconds} วินาที`;
            remainingSeconds--;
        }, 1000);
    }

    function resetCountdownAndRedirect() {
        fetch('reset_timer.php', {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'cart_products.php';
                } else {
                    alert('เกิดข้อผิดพลาดในการรีเซ็ตเวลา');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    </script>
</head>

<body onload="startCountdown(900)">
    <div class="container">
        <h2>การชำระเงิน</h2>
        <div class="date-time">
            <span style="margin-right: 20px;">วันที่: <?= date('d/m/Y') ?></span>
            <span>เวลา: <?= date('H:i:s') ?></span>
        </div>
        <p>ยอดชำระเงิน:
            <?= isset($total_price) ? number_format($total_price, 2) . ' บาท' : 'ไม่สามารถคำนวณยอดได้' ?>
        </p>
        <h6>กรุณาสแกน QR Code เพื่อชำระเงิน</h6>
        <img src="<?= $qr_code_image ?>" alt="QR Code" style="max-width: 300px;">

        <p>เวลาที่เหลือในการชำระเงิน: <span id="countdown"></span></p>

        <form method="POST" action="process_payment.php">
            <button type="submit" name="confirm_payment" class="btn btn-success">ตกลง</button>
            <button type="button" class="btn btn-danger" onclick="resetCountdownAndRedirect()">ยกเลิก</button>
        </form>
    </div>
</body>

</html>