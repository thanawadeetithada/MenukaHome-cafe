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

$user_id = $_SESSION['user_id'];
$query = "
    SELECT 
        r.receipt_id,
        r.issued_date,
        r.total_amount,
        r.location_id, 
        l.latitude, 
        l.longitude, 
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
    LEFT JOIN 
        locations l ON r.location_id = l.location_id
    WHERE 
        r.user_id = ?
    ORDER BY 
        r.issued_date DESC
";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

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
                    echo '<h6>เลขใบคำสั่งซื้อ : ' . $row['receipt_id'] . '</h6>';
                    echo '<span>' . $row['issued_date'] . '</span>';
                    echo '</div>';
                    echo '<div><span><strong>เลขออเดอร์ : </strong></span> ' . $row['order_id'] . '</div>';
                    echo '<div>';
                    echo '<span><strong>ที่อยู่: </strong></span>';
                    if (!empty($row['location_id'])) {
                        echo '<span><a href="https://www.google.com/maps?q=' . htmlspecialchars($row['latitude']) . ',' . htmlspecialchars($row['longitude']) . '" target="_blank">Google Map</a></span>';
                    } else {
                        echo '<span>รับที่ร้าน</span>';
                    }
                }

                echo '<div class="item">';
                echo '<span><strong>' . $row['product_name'] . ' x ' . $row['quantity'] . '</strong></span>';
                echo '<span><strong>฿' . number_format($row['price'], 2) . '</strong></span>';
                echo '</div>';
            }

            echo '</div>';
            echo '<div class="total"><span>Total:</span><span>$' . number_format($totalAmount, 2) . '</span></div>';
            echo '</div>';
        } else {
            echo '<p>No receipts found.</p>';
        }
        ?>
    </div>
</body>

</html>