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

$address = isset($_GET['address']) ? htmlspecialchars($_GET['address']) : '';
$location_id = isset($_GET['location_id']) ? (int)$_GET['location_id'] : 0;

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

    window.onload = function() {
        const address = "<?= addslashes($address) ?>";
        const deliveryTypeInputElement = document.getElementById("deliveryTypeInput");

        // ถ้ามี address แต่ยังไม่ได้เลือก delivery type
        if (address && !deliveryTypeInputElement.value) {
            // ตั้งค่า default เป็น 'home'
            deliveryTypeInputElement.value = "home";

            // แสดงปุ่ม home ว่าเลือกแล้ว
            const deliveryButtons = document.querySelectorAll("#selectedDelivery button");
            deliveryButtons.forEach(button => button.classList.remove("selected"));

            const homeButton = document.querySelector("#selectedDelivery .home");
            if (homeButton) {
                homeButton.classList.add("selected");
            }

            // แสดงฟิลด์ address
            document.getElementById('addressContainer').style.display = 'flex';
        }

        // กรอก address ลงในช่อง input
        if (address) {
            document.getElementById('deliveryAddress').value = address;
        }
    };
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
        gap: 10px;
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
        flex: 1;
        border: none;
        outline: none;
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
        text-align: end;
        padding-right: 1rem;
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
        border: 2px solid rgba(255, 224, 102, 0.62);
        background-color: rgba(255, 224, 102, 0.62);
    }

    .button-group .home.selected {
        border: 2px solid rgba(255, 166, 0, 0.63);
        background-color: rgba(255, 166, 0, 0.63);
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

        <div class="address-input" id="addressContainer" style="display: flex;">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" id="deliveryAddress" name="address" placeholder="กรุณาใส่ที่อยู่"
                value="<?= htmlspecialchars($address) ?>" onfocus="redirectToAddressPage()">
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
            <input type="hidden" id="deliveryTypeInput" name="delivery_type" value="">
            <input type="hidden" id="deliveryAddressInput" name="delivery_address">
            <input type="hidden" id="locationIdInput" name="location_id" value="<?= $location_id ?>">

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
        const deliveryInput = document.getElementById("deliveryTypeInput");
        const locationIdInput = document.getElementById("locationIdInput");

        deliveryInput.value = deliveryMethod;

        const deliveryButtons = document.querySelectorAll("#selectedDelivery button");
        deliveryButtons.forEach(button => button.classList.remove("selected"));

        const selectedButton = document.querySelector(`#selectedDelivery button.${method}`);
        if (selectedButton) {
            selectedButton.classList.add("selected");
        }

        const addressContainer = document.getElementById("addressContainer");
        if (method === "home") {
            addressContainer.style.display = "flex";
        } else {
            addressContainer.style.display = "none";

            if (locationIdInput) {
                locationIdInput.value = "";
            }
        }

        console.log(`Delivery Type Selected: ${method}`);
    }

    function validateCheckout() {
        const deliveryTypeInputElement = document.getElementById("deliveryTypeInput");
        const addressInputElement = document.getElementById("deliveryAddress");
        const locationIdInputElement = document.getElementById("locationIdInput");

        const addressInput = addressInputElement ? addressInputElement.value.trim() : "";
        const deliveryTypeInput = deliveryTypeInputElement ? deliveryTypeInputElement.value.trim() : "";
        let locationId = locationIdInputElement ? locationIdInputElement.value.trim() : "0";

        // ตรวจสอบว่าผู้ใช้เลือกประเภทการจัดส่งหรือไม่
        if (!deliveryTypeInput) {
            deliveryTypeInputElement.value = "pickup";
        }

        // ตรวจสอบกรณีเลือก "home" และไม่ได้กรอกที่อยู่
        if (deliveryTypeInputElement.value === "home" && !addressInput) {
            alert("กรุณาใส่ที่อยู่สำหรับการจัดส่ง");
            return false;
        }

        // ตั้งค่าที่อยู่ใน hidden input
        if (deliveryTypeInputElement.value === "home") {
            const deliveryAddressInput = document.getElementById("deliveryAddressInput");
            if (deliveryAddressInput) {
                deliveryAddressInput.value = addressInput;
            }
        }

        // ถ้าเลือกรับที่ร้าน ให้ location_id เป็น null
        if (deliveryTypeInput === "pickup") {
            locationId = null; // ส่งค่า null
        }

        // ตรวจสอบว่าผู้ใช้เลือกวิธีการชำระเงินหรือไม่
        if (!paymentMethod) {
            alert("กรุณาเลือกวิธีการชำระเงิน");
            return false;
        }

        const userId = <?php echo json_encode($_SESSION['user_id']); ?>;
        const total = <?php echo json_encode($total); ?>;

        const orderData = {
            user_id: userId,
            order_date: new Date().toISOString(),
            total_amount: total,
            delivery_type: deliveryTypeInput,
            payment_method: paymentMethod,
            status: "pending",
            order_items: orderItems
        };

        console.log('orderData', orderData);

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
                        alert("กรุณาจ่ายเงินตามกำหนดเวลา");
                        window.location.href = "payment.php";
                    } else if (paymentMethod === "cash") {
                        fetch("process_receipt.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({
                                    order_id: data.order_id,
                                    user_id: userId,
                                    total_amount: total,
                                    location_id: locationId,
                                })
                            })
                            .then(response => response.json())
                            .then(receiptData => {
                                if (receiptData.success) {
                                    window.location.href = "receipt.php";
                                } else {
                                    alert("เกิดข้อผิดพลาด: " + receiptData.message);
                                }
                            })
                            .catch(error => {
                                alert("เกิดข้อผิดพลาดในการสร้างใบเสร็จ");
                            });
                    }
                } else {
                    alert("เกิดข้อผิดพลาด: " + data.message);
                }
            })
            .catch(error => {
                alert("เกิดข้อผิดพลาดในการบันทึกข้อมูล");
                console.error("Error:", error);
            });

        return false;
    }

    function redirectToAddressPage() {
        window.location.href = "address.php";
    }
    </script>

</body>

</html>