<?php
session_start();
include('include/header.php');
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// ดึงข้อมูลใบเสร็จล่าสุด
$user_id = $_SESSION['user_id'];
$sql_receipt = "SELECT r.receipt_id, r.order_id, r.issued_date, r.total_amount, o.delivery_type, o.payment_method
                FROM receipts r
                JOIN orders o ON r.order_id = o.order_id
                WHERE r.user_id = ? 
                ORDER BY r.issued_date DESC LIMIT 1";
$stmt_receipt = $conn->prepare($sql_receipt);
$stmt_receipt->bind_param("i", $user_id);
$stmt_receipt->execute();
$result_receipt = $stmt_receipt->get_result();
$receipt = $result_receipt->fetch_assoc();

if (!$receipt) {
    echo "<p>ไม่พบข้อมูลใบเสร็จ</p>";
    exit;
}

// ดึงรายการสินค้าจากตาราง order_details
$order_id = $receipt['order_id'];
$sql_items = "SELECT product_name, price, quantity 
              FROM order_details 
              WHERE order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
$items = $result_items->fetch_all(MYSQLI_ASSOC);

$stmt_receipt->close();
$stmt_items->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .logo {
            width: 100px;
            margin: 0 auto;
        }

        .logo img {
            width: 100%;
            height: auto;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0;
            color: #ff7f00;
        }

        .subtitle {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #333;
        }

        .receipt-item .price {
            font-weight: bold;
        }

        .total {
            font-size: 1.25rem;
            font-weight: bold;
            color: #ff3d00;
            margin: 20px 0;
        }

        .note {
            font-size: 0.875rem;
            color: #777;
        }

        .back-button {
            background-color: #ffa500;
            color: white;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: bold;
            border: none;
            border-radius: 1.5rem;
            cursor: pointer;
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>

        <div class="title">MENUKA</div>
        <br>
        <?php foreach ($items as $item): ?>
        <div class="receipt-item">
            <span><?php echo htmlspecialchars($item['product_name']); ?></span>
            <span class="price">฿<?php echo number_format($item['price'], 2); ?></span>
        </div>
        <?php endforeach; ?>

        <hr>

        <div class="receipt-item total">
            <span>Total Amount:</span>
            <span>฿<?php echo number_format($receipt['total_amount'], 2); ?></span>
        </div>

        <div class="note">ราคานี้รวมภาษีแล้ว</div>

        <button class="back-button" onclick="window.location.href='menu_page.php'">กลับหน้าเมนู</button>
    </div>
</body>
</html>
