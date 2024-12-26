<?php
session_start();
include('include/header.php');
include('config.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $quantity = 1; // คุณสามารถปรับให้เลือกจำนวนได้
    $customizations = isset($_POST['customizations']) ? $_POST['customizations'] : ''; // ใช้ค่าจาก radio button
    $description_cart = isset($_POST['description_cart']) ? $_POST['description_cart'] : '';

    // รวม customizations และ additional_details ไว้ใน description_cart

    // ตรวจสอบว่า product_id ไม่เป็น 0
    if ($product_id > 0) {
        $sql = "INSERT INTO cart_products (user_id, product_id, quantity, description_cart, customizations, added_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiss", $user_id, $product_id, $quantity, $description_cart, $customizations);

        if ($stmt->execute()) {
            echo "Product added to cart successfully.";
            header("Location: menu_page.php"); // เปลี่ยนเส้นทางไปยังหน้า checkout
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid product ID.";
    }
}

// โค้ดเดิมสำหรับแสดงรายละเอียดสินค้า
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

if ($product_id) {
    $sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
    }
} else {
    echo "Invalid product ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Detail</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            padding: 2rem 1rem;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product-image img {
            width: 150px;
            height: auto;
            margin: 20px auto;
            border-radius: 10px;
        }

        .product-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        .product-description {
            font-size: 1rem;
            color: #777;
            margin: 5px 0 15px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            margin: 15px 0;
        }

        .options {
            text-align: left;
            margin: 15px 0;
        }

        .options label {
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #555;
        }

        .details {
            margin-top: 20px;
            text-align: center;
        }

        .details textarea {
            width: 100%;
            height: 20vh;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            resize: none;
        }

        .add-to-cart-btn {
            background-color: #ffa500;
            color: white;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: bold;
            border: none;
            border-radius: 1.5rem;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }

        .add-to-cart-btn:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
        <div class="container">
        <form action="" method="POST">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            </div>

            <div class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></div>
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">

            <div class="product-description"><?php echo htmlspecialchars($product['description']); ?></div>

            <div class="product-price">฿<?php echo htmlspecialchars($product['price']); ?></div>

            <div class="options">
                <label>
                    <input type="radio" name="customizations" value="25" required> หวานน้อย 25%
                </label>
                <label>
                    <input type="radio" name="customizations" value="50"> หวานพอดี 50%
                </label>
                <label>
                    <input type="radio" name="customizations" value="100"> หวานเต็มที่ 100%
                </label>
            </div>

            <div class="details">
                <textarea name="description_cart" placeholder="รายละเอียดเพิ่มเติม"></textarea>
            </div>

            <button type="submit" class="add-to-cart-btn">เพิ่มลงตะกร้า</button>
        </form>
    </div>
</body>
</html>
