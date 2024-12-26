<?php
session_start();
include 'include/header.php';
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$order_items = [];
$total = 0;

$sql = "SELECT cp.product_id, cp.quantity, cp.description_cart, cp.added_at,
               p.product_name, p.price, p.image_url
        FROM cart_products cp
        JOIN products p ON cp.product_id = p.product_id
        WHERE cp.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $row['subtotal'] = $row['price'] * $row['quantity'];
    $order_items[] = $row;
    $total += $row['subtotal'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
    // สร้างตัวแปร orderItems จาก PHP
    const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
    const total = <?php echo json_encode($total); ?>;

    // แปลงข้อมูล order_items จาก PHP เป็น JSON
    const orderItems = <?php 
        echo json_encode(array_map(function($item) {
            return [
                "product_id" => $item['product_id'],
                "product_name" => $item['product_name'],
                "quantity" => $item['quantity'],
                "price" => (float)$item['price'] // แปลงเป็น float
            ];
        }, $order_items)); 
    ?>;
    </script>
    <style>
    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f9f9f9;
    }

    .container {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .button-group {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .button-group button {
        flex: 1;
        padding: 10px;
        margin: 0 5px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 1.5rem;
        border: none;
        cursor: pointer;
    }

    .button-group button:nth-child(1) {
        background-color: #ffe066;
        color: #333;
    }

    .button-group button:nth-child(2) {
        background-color: #ffa500;
        color: white;
    }

    .address-input {
        display: flex;
        align-items: center;
        border: 2px solid #000;
        border-radius: 1.5rem;
        padding: 10px;
        margin-bottom: 20px;
    }

    .address-input i {
        color: #ff3d00;
        font-size: 1.5rem;
        margin-right: 10px;
    }

    .address-input input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 1rem;
    }

    .product-summary {
        width: 100%;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        justify-content: center;
        padding: 0 20px;
    }

    .product-summary img {
        width: 60px;
        height: auto;
        margin-right: 10px;
        border-radius: 5px;
    }

    .product-details {
        flex: 1;
        padding: 0 10px;
    }

    .product-details h3 {
        font-size: 1rem;
        margin: 0;
        color: #333;
    }

    .product-details p {
        font-size: 0.875rem;
        color: #777;
        margin: 0;
    }

    .product-price {
        font-size: 1.25rem;
        font-weight: bold;
        color: #000;
    }

    .summary-details {
        margin-bottom: 20px;
        font-size: 1rem;
        color: #333;
    }

    .summary-details .total {
        font-weight: bold;
        color: #ff3d00;
    }

    .payment-options {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .payment-options button {
        flex: 1;
        padding: 10px;
        margin: 0 5px;
        font-size: 1rem;
        font-weight: bold;
        border: 2px solid #000;
        border-radius: 1.5rem;
        background-color: white;
        cursor: pointer;
    }

    .payment-options button:hover {
        background-color: #f1f1f1;
    }

    .action-buttons {
        display: flex;
        justify-content: space-between;
    }

    .action-buttons button {
        flex: 1;
        padding: 15px;
        margin: 0 5px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 1.5rem;
        border: none;
        cursor: pointer;
    }

    .action-buttons-payments {
        display: flex;
        justify-content: space-between;
    }

    .action-buttons-payments button {
        flex: 1;
        padding: 10px;
        margin: 0 5px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 1.5rem;
        border: none;
        cursor: pointer;
    }

    .action-buttons button.cancel {
        background-color: #ff3d00;
        color: white;
    }

    .action-buttons button.confirm {
        background-color: #4caf50;
        color: white;
    }

    button:disabled {
        opacity: 0.5;
    }

    .action-buttons-payments button.selected {
        box-shadow: 0 0 5px #8d8a8a;
    }

    .button-group .pickup.selected {
        border: 2px solid rgb(138, 138, 138);
        box-shadow: 0 0 5px #ffe066;
    }

    .button-group .home.selected {
        border: 2px solid rgb(138, 138, 138);
        box-shadow: 0 0 5px #ffa500;
    }

    .button-group button,
    .action-buttons-payments button,
    .action-buttons button {
        border: none;
        outline: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="button-group" id="selectedDelivery">
            <button class="pickup" onclick="selectDeliveryType('pickup')">รับที่ร้าน</button>
            <button class="home" onclick="selectDeliveryType('home')">รับที่บ้าน</button>
        </div>

        <div class="address-input" id="addressContainer" style="display: none;">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" id="deliveryAddress" placeholder="กรุณาใส่ที่อยู่">
        </div>

        <div class="product-summary">
            <?php if (!empty($order_items)): ?>
            <?php foreach ($order_items as $item): ?>
            <div class="product-summary">
                <img src="<?php echo htmlspecialchars($item['image_url']); ?>"
                    alt="<?php echo htmlspecialchars($item['product_name']); ?>">
                <div class="product-details">
                    <h3><?php echo htmlspecialchars($item['product_name']); ?></h3>
                    <p><?php echo htmlspecialchars($item['description_cart']); ?></p>
                    <p>จำนวน: <?php echo $item['quantity']; ?></p>
                </div>
                <div class="product-price">฿<?php echo number_format($item['subtotal'], 2); ?></div>
            </div>
            <?php endforeach;?>
            <?php else: ?>
            <p>ไม่มีสินค้าในตะกร้า</p>
            <?php endif;?>
        </div>

        <div class="summary-details">
            <p class="total">รวมสุทธิ: ฿<?php echo number_format($total, 2); ?></p>
        </div>

        <div class="action-buttons-payments" id="selectedPayment">
            <button onclick="payments('transfer')">โอนชำระ</button>
            <button onclick="payments('cash')">เงินสด</button>
        </div>
        <br>
        <form onsubmit="return validateCheckout();">
            <!-- Hidden inputs สำหรับเก็บค่า -->
            <input type="hidden" id="paymentMethodInput" name="payment_method" value="transfer">
            <input type="hidden" id="deliveryTypeInput" name="delivery_type" value="pickup">
            <input type="hidden" id="deliveryAddressInput" name="delivery_address">

            <div class="action-buttons">
                <button type="button" class="cancel" onclick="window.location.href='cart_products.php'">ยกเลิก</button>
                <button type="submit" class="confirm">ยืนยัน</button>
            </div>
        </form>

    </div>
    <script>
    let paymentMethod = '';

    function payments(method) {
        paymentMethod = method;
        const paymentInput = document.getElementById('paymentMethodInput');
        if (paymentInput) {
            paymentInput.value = method;
        } else {
            console.error("Element with ID 'paymentMethodInput' not found.");
            return;
        }

        const paymentButtons = document.querySelectorAll("#selectedPayment button");
        paymentButtons.forEach(button => button.classList.remove("selected"));

        const selectedButton = document.querySelector(
            `#selectedPayment button:nth-child(${method === 'transfer' ? 1 : 2})`
        );
        if (selectedButton) {
            selectedButton.classList.add("selected");
        }

        console.log(`คุณเลือกการชำระเงิน: ${method === 'transfer' ? 'โอนชำระ' : 'เงินสด'}`);
    }

    let deliveryMethod = '';

    function selectDeliveryType(method) {
        deliveryMethod = method;
        const deliveryButtons = document.querySelectorAll("#selectedDelivery button");
        deliveryButtons.forEach(button => button.classList.remove("selected"));

        const selectedButton = document.querySelector(
            `#selectedDelivery button:nth-child(${method === 'pickup' ? 1 : 2})`
        );
        if (selectedButton) {
            selectedButton.classList.add("selected");
        }

        const addressContainer = document.getElementById("addressContainer");
        if (method === 'home') {
            addressContainer.style.display = "flex";
        } else {
            addressContainer.style.display = "none";
        }

        console.log(`${method === 'home' ? 'รับที่บ้าน' : 'รับที่ร้าน'}`);
    }

    function validateCheckout() {
        if (!deliveryMethod) {
            alert("กรุณาเลือกประเภทการจัดส่ง");
            return false;
        }

        if (deliveryMethod === "home") {
            const addressInput = document.getElementById("deliveryAddress").value.trim();
            if (!addressInput) {
                alert("กรุณาใส่ที่อยู่สำหรับการจัดส่ง");
                return false;
            }
            document.getElementById("deliveryAddressInput").value = addressInput;
        }

        const deliveryInput = document.getElementById("deliveryTypeInput");
        if (deliveryInput) {
            deliveryInput.value = deliveryMethod;
        }

        if (!paymentMethod) {
            alert("กรุณาเลือกวิธีการชำระเงิน");
            return false;
        }

        // เตรียมข้อมูลสำหรับ POST
        const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
        const total = <?php echo json_encode($total); ?>;
        const orderData = {
            user_id: userId,
            order_date: new Date().toISOString(),
            total_amount: total,
            delivery_type: deliveryMethod,
            payment_method: paymentMethod,
            status: "pending",
            order_items: orderItems
        };

        fetch("process_checkout.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (paymentMethod === "transfer") {
                        alert("สั่งซื้อสำเร็จ");
                        window.location.href = "payment.php";
                    } else if (paymentMethod === "cash") {
                        alert("สั่งซื้อสำเร็จ");
                        window.location.href = "receipt.php";
                    }
                } else {
                    alert("เกิดข้อผิดพลาด: " + data.message);
                }
            })
            .catch(error => {
                alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
            });

        return false;
    }
    </script>

</body>

</html>