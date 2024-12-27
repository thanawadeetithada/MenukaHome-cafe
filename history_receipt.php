<?php
session_start();
include('include/header.php');
include('config.php'); // เชื่อมต่อฐานข้อมูล

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit;
}

// ดึงข้อมูลใบเสร็จและผู้ใช้ พร้อมรายการสินค้า
$query = "
    SELECT 
        r.receipt_id,
        r.issued_date,
        r.total_amount,
        u.username,
        u.surname,
        od.product_name,
        od.quantity,
        od.price,
        o.order_id
    FROM 
        receipts r
    JOIN 
        orders o ON r.order_id = o.order_id
    JOIN 
        order_details od ON o.order_id = od.order_id
    JOIN 
        users u ON r.user_id = u.user_id
    ORDER BY 
        r.issued_date DESC
";

$result = $conn->query($query);

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

        a {
            font-size: 18px;
            font-weight: bold;
            color: black;
        }
    }

    body {
        font-family: 'Prompt', sans-serif;
        line-height: 1.6;
        height: 100%;
        margin: 0;
        padding-bottom: 60px;
    }

    .container {
        max-width: 400px;
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

    .header h3 {
        margin: 0;
        font-size: 1.1rem;
        color: #333;
    }

    .header span {
        font-size: 0.9rem;
        color: #777;
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
    </style>
</head>

<body>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            // เก็บ order_id ล่าสุดเพื่อจัดกลุ่มใบเสร็จ
            $current_order_id = null;

            while ($row = $result->fetch_assoc()) {
                $username = $row['username'];
                $surname = $row['surname'];
                $issued_date = date("d-m-Y:H:i", strtotime($row['issued_date']));
                $total_amount = number_format($row['total_amount'], 2);
                $product_name = $row['product_name'];
                $quantity = $row['quantity'];
                $price = number_format($row['price'], 2);
                $order_id = $row['order_id'];

                // ตรวจสอบว่ากลุ่มใบเสร็จเปลี่ยนไปหรือไม่
                if ($order_id !== $current_order_id) {
                    // ปิดใบเสร็จเก่า (ถ้าเป็นใบใหม่)
                    if ($current_order_id !== null) {
                        echo "</div>"; // ปิด .receipt-card
                    }

                    // เริ่มใบเสร็จใหม่
                    echo "
                    <div class='receipt-card'>
                        <div class='header'>
                            <h3>{$username} {$surname}</h3>
                            <span>{$issued_date}</span>
                        </div>
                        <div class='items'>
                    ";

                    // อัปเดต order_id ปัจจุบัน
                    $current_order_id = $order_id;
                }

                // แสดงรายการสินค้า
                echo "
                    <div class='item'>
                        <span>{$product_name} x{$quantity}</span>
                        <span>฿{$price}</span>
                    </div>
                ";
            }

            // ปิดใบเสร็จสุดท้าย
            if ($current_order_id !== null) {
                echo "
                    </div> <!-- ปิด .items -->
                    <div class='total'>
                        <span>รวมสุทธิ</span>
                        <span>฿{$total_amount}</span>
                    </div>
                    </div> <!-- ปิด .receipt-card -->
                ";
            }
        } else {
            echo "<p>ไม่มีข้อมูลใบเสร็จ</p>";
        }
        ?>
    </div>
    <footer class="footer p-4">
        <a href="main.php">หน้าหลัก</a>
        <a href="edit_products.php">รายการอาหาร</a>
        <a href="user_info.php">ข้อมูล User</a>
    </footer>
</body>

</html>