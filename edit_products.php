<?php
session_start();
include('include/header.php');
include('config.php'); 

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$category_sql = "SELECT category_id, category_name FROM categories";
$category_result = $conn->query($category_sql);

$selected_category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : null;

if ($selected_category_id) {
    $product_sql = "SELECT product_id, product_name, description, price, image_url FROM products WHERE category_id = ?";
    $stmt = $conn->prepare($product_sql);
    $stmt->bind_param("i", $selected_category_id);
    $stmt->execute();
    $product_result = $stmt->get_result();
} else {
    $product_sql = "SELECT product_id, product_name, description, price, image_url FROM products";
    $product_result = $conn->query($product_sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menuka Home Cafe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    .menu-bar {
        display: flex;
        justify-content: center;
        gap: 20px;
        padding: 10px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .menu-bar a {
        text-decoration: none;
        font-size: 1rem;
        color: #333;
        font-weight: bold;
    }

    .menu-bar a.active {
        color: #ffa500;
        border-bottom: 2px solid #ffa500;
        padding-bottom: 5px;
    }

    .content {
        padding: 20px;
    }

    .product-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        background: white;
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .product-item img {
        width: 60px;
        height: 60px;
        object-fit: cover;
    }

    .product-info {
        flex: 1;
    }

    .product-info h3 {
        margin: 0;
        font-size: 1rem;
        color: #333;
    }

    .product-info p {
        margin: 5px 0 0;
        font-size: 0.875rem;
        color: #777;
    }

    .product-price {
        font-size: 1rem;
        font-weight: bold;
    }

    .cart-button {
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
        outline: none;
    }

    .cart-button:hover {
        background-color: rgba(255, 166, 0, 0.8);
    }

    .cart-button:focus {
        outline: none;
        border: none;
    }
    </style>
</head>

<body>
    <header>
        <h1>Menuka Home Cafe</h1>
    </header>
    <div class="menu-bar">
        <?php if ($category_result && $category_result->num_rows > 0): ?>
        <?php while ($category = $category_result->fetch_assoc()): ?>
        <a href="?category_id=<?php echo $category['category_id']; ?>"
            class="<?php echo $selected_category_id == $category['category_id'] ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($category['category_name']); ?>
        </a>
        <?php endwhile; ?>
        <?php else: ?>
        <p>No categories found.</p>
        <?php endif; ?>
    </div>
    <div class="content">
        <a href="add_products.php" class="btn btn-outline-primary">Upload</a>
        <?php if ($product_result->num_rows > 0): ?>
        <?php while ($row = $product_result->fetch_assoc()): ?>
        <div class="product-item" onclick="redirectToDetail(<?php echo $row['product_id']; ?>)">
            <img src="<?php echo htmlspecialchars($row['image_url']); ?>"
                alt="<?php echo htmlspecialchars($row['product_name']); ?>">
            <div class="product-info">
                <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
            <div class="product-price">฿<?php echo htmlspecialchars($row['price']); ?></div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <p>No products found for this category.</p>
        <?php endif; ?>
    </div>
    <footer class="footer p-4">
        <a href="main.php">หน้าหลัก</a>
        <a href="edit_products.php">รายการอาหาร</a>
        <a href="user_info.php">ข้อมูล User</a>
    </footer>
    <script>
    function redirectToDetail(productId) {
        window.location.href = `edit_menu_detail.php?product_id=${productId}`;
    }

    function redirectToCart() {
        window.location.href = 'cart_products.php';
    }
    </script>
</body>

</html>

<?php
$conn->close();
?>