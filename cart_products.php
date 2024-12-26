<?php
session_start();
include('include/header.php');
include('config.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Fetch products in the cart from the database
$cart_products = [];
$sql = "SELECT cp.product_id, cp.quantity, cp.description_cart, cp.customizations, cp.added_at, 
              p.product_name, p.price, p.image_url
       FROM cart_products cp
       JOIN products p ON cp.product_id = p.product_id
       WHERE cp.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $cart_products[] = $row;
}

// Handle removal of products from cart
if (isset($_GET['remove_id'])) {
    $remove_id = intval($_GET['remove_id']);
    $sql = "DELETE FROM cart_products WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $remove_id, $_SESSION['user_id']);
    $stmt->execute();
    header("Location: cart_products.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Menuka Home Cafe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 0;
    }

    header {
        background-color: #ffa500;
        text-align: center;
        padding: 10px;
    }

    header h1 {
        font-size: 1.75rem;
        margin: 0;
        font-family: 'Pacifico', cursive;
        color: white;
    }

    .content {
        padding: 20px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        background: white;
        padding: 10px 20px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .cart-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .cart-info {
        flex: 1;
    }

    .cart-info h3 {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }

    .cart-info p {
        margin: 5px 0 0;
        font-size: 0.875rem;
        color: #777;
    }

    .cart-quantity {
        font-size: 0.875rem;
    }

    .cart-price {
        font-size: 1rem;
        font-weight: bold;
    }

    .remove-button {
        color: #ff4d4d;
        cursor: pointer;
    }

    .checkout-button {
        background-color: #ffa500;
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 1.25rem;
        font-weight: bold;
        border: none;
        border-radius: 1.5rem;
        margin-top: 20px;
        width: 100%;
        cursor: pointer;
    }

    .checkout-button:hover {
        background-color: #e69500;
    }

    .fa-trash-can {
        margin-left: 10px;
    }
    </style>
</head>

<body>
    <header>
        <h1>Cart - Menuka Home Cafe</h1>
    </header>
    <div class="content">
        <?php if (!empty($cart_products)): ?>
        <?php foreach ($cart_products as $product): ?>
        <div class="cart-item">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                alt="<?php echo htmlspecialchars($product['product_name']); ?>">
            <div class="cart-info">
                <h3><?php echo htmlspecialchars($product['product_name']); ?></h3>
                <p class="cart-quantity">จำนวน : <?php echo $product['quantity']; ?></p>
                <?php echo !empty($product['customizations']) ? '<p>ระดับความหวาน : ' . htmlspecialchars($product['customizations']) . '</p>' : ''; ?>
                <?php echo !empty($product['description_cart']) ? '<p>รายละเอียด : ' . htmlspecialchars($product['description_cart']) . '</p>' : ''; ?>
            </div>
            <div class="cart-price">฿<?php echo htmlspecialchars($product['price']); ?></div>
            <a class="remove-button" href="?remove_id=<?php echo $product['product_id']; ?>">
                <i class="fa-regular fa-trash-can" style="color: #df0c0c;"></i>
            </a>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <p>ไม่มีสินค้าในตะกร้า</p>
        <?php endif; ?>
        <button class="checkout-button" onclick="proceedToCheckout()">จ่ายเงิน</button>
    </div>
    <script>
    function proceedToCheckout() {
        window.location.href = 'menu_checkout.php';
    }
    </script>
</body>

</html>
<?php
$conn->close();
?>