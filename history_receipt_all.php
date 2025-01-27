<?php
session_start();
include('include/header.php');
include('config.php'); // เชื่อมต่อฐานข้อมูล

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$query = "
    SELECT r.*, l.latitude, l.longitude, u.username, u.surname, u.phone
    FROM receipts r
    LEFT JOIN locations l ON r.location_id = l.location_id
    LEFT JOIN users u ON r.user_id = u.user_id
    ORDER BY r.receipt_id DESC
";
$result = $conn->query($query);
$receipts = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Receipt</title>
    <style>
    footer {
        display: flex;
        justify-content: space-around;
        background-color: #f9c74f;
        padding: 10px 0;
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 1000;
    }

    footer a {
        font-size: 18px;
        font-weight: bold;
        color: black;
    }

    body {
        font-family: 'Prompt', sans-serif;
        line-height: 1.6;
        height: 100%;
        margin: 0;
        padding-bottom: 60px;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: white;
    }

    .receipt-card {
        margin-bottom: 15px;
        border-radius: 10px;
        padding: 15px;
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .header span {
        font-size: 0.9rem;
        color: black;
    }

    .items {
        margin-top: 10px;
    }

    .item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .item span {
        font-size: 0.9rem;
        color: #555;
    }

    .total {
        display: flex;
        justify-content: space-between;
        font-weight: bold;
        font-size: 1rem;
        margin-top: 10px;
        color: #ff3d00;
    }

    h6 {
        margin-bottom: 0px;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container">
        <?php foreach ($receipts as $receipt): ?>
        <div class="receipt-card">
            <div class="header">
                <h6><strong>เลขใบคำสั่งซื้อ : </strong><?= htmlspecialchars($receipt['receipt_id'] ?? '') ?></h6>
                <span><?= htmlspecialchars($receipt['issued_date'] ?? '') ?></span>
            </div>
            <div><strong>ชื่อ : </strong><?= htmlspecialchars($receipt['username'] ?? 'N/A') ?>
                <?= htmlspecialchars($receipt['surname'] ?? 'N/A') ?></div>
            <div><strong>เบอร์โทร : </strong></span><?= htmlspecialchars($receipt['phone'] ?? '') ?></div>
            <div><strong>เลขออเดอร์ : </strong></span><?= htmlspecialchars($receipt['order_id'] ?? '') ?></div>


            <?php if (!empty($receipt['location_id'])): ?>
            <div>
                <span><strong>ที่อยู่ : </strong></span>
                <a href="https://www.google.com/maps?q=<?= htmlspecialchars($receipt['latitude']) ?>,<?= htmlspecialchars($receipt['longitude']) ?>"
                    target="_blank">Google Map
                </a>
            </div>
            <?php else: ?>
            <div><span><strong>ที่อยู่ : </strong></span>รับที่ร้าน</div>
            <?php endif; ?>

            <div class="items">
                <?php
                $order_id = $receipt['order_id'];
                $order_query = "
                    SELECT product_name, quantity, price 
                    FROM order_details 
                    WHERE order_id = ?";
                $stmt = $conn->prepare($order_query);
                $stmt->bind_param("i", $order_id);
                $stmt->execute();
                $order_result = $stmt->get_result();
                while ($order = $order_result->fetch_assoc()):
                ?>
                <div class="item">
                    <span><strong><?= htmlspecialchars($order['product_name']) ?> x
                        <?= htmlspecialchars($order['quantity']) ?></strong></span>
                    <span><strong>฿<?= htmlspecialchars(number_format($order['price'], 2)) ?></strong></span>
                </div>
                <?php endwhile; ?>
            </div>

            <div class="total"><span>Total : </span><?= htmlspecialchars($receipt['total_amount'] ?? '') ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</body>

</html>

<?php

$conn->close();
?>