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
    </style>
</head>

<body>
    <div class="container">
        <?php
        if ($result && $result->num_rows > 0) {
            $currentReceipt = null;
            while ($row = $result->fetch_assoc()) {
                if ($currentReceipt !== $row['receipt_id']) {
                    if ($currentReceipt !== null) {
                        echo '</div>'; // Close previous items div
                        echo '<div class="total"><span>Total:</span><span>$' . number_format($totalAmount, 2) . '</span></div>';
                        echo '</div>'; // Close previous receipt card
                    }
                    $currentReceipt = $row['receipt_id'];
                    $totalAmount = $row['total_amount'];
                    echo '<div class="receipt-card">';
                    echo '<div class="header">';
                    echo '<span>Receipt ID: ' . $row['receipt_id'] . '</span>';
                    echo '<span>' . $row['issued_date'] . '</span>';
                    echo '</div>';
                    echo '<div>' . $row['username'] . ' ' . $row['surname'] . '</div>';
                    echo '<div><span>Order ID:</span> ' . $row['order_id'] . '</div>';
                    echo '<div class="items">';
                }

                // Display product details
                echo '<div class="item">';
                echo '<span>' . $row['product_name'] . ' x ' . $row['quantity'] . '</span>';
                echo '<span>$' . number_format($row['price'], 2) . '</span>';
                echo '</div>';
            }

            // Close the last receipt card
            echo '</div>'; // Close items div
            echo '<div class="total"><span>Total:</span><span>$' . number_format($totalAmount, 2) . '</span></div>';
            echo '</div>'; // Close receipt card
        } else {
            echo '<p>No receipts found.</p>';
        }
        ?>
    </div>
</body>

</html>
