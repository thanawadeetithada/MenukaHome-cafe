<?php
session_start();
include('include/header.php');
include('config.php'); 

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// ตรวจสอบการรับค่า product_id
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;

// หาก product_id ไม่ถูกต้อง
if ($product_id <= 0) {
    echo "Invalid product ID.";
    exit;
}

// ดึงข้อมูลสินค้าเพื่อนำมาแสดงในฟอร์ม
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}
$product = $result->fetch_assoc();

$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}
$product = $result->fetch_assoc();

// ดึงข้อมูลหมวดหมู่
$categories = [];
$sql = "SELECT category_id, category_name FROM categories";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// ตรวจสอบว่าฟอร์มถูกส่ง
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = isset($_POST['product_name']) ? trim($_POST['product_name']) : '';
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00;
    $image_url = isset($_POST['image_url']) ? trim($_POST['image_url']) : null;
    $file_path = null;

    // ตรวจสอบการอัปโหลดไฟล์
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['file_upload']['name']);
        $target_file = $target_dir . time() . "_" . $file_name;

        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_type, $valid_extensions)) {
            if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
                $file_path = $target_file;
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    // ใช้ไฟล์ที่อัปโหลดหรือ URL
    $final_image_url = $file_path ? $file_path : $image_url;

    // ตรวจสอบข้อมูลก่อนอัปเดต
    if ($product_name && $category_id > 0 && $price > 0 && $final_image_url) {
        $sql = "UPDATE products 
                SET product_name = ?, category_id = ?, description = ?, price = ?, image_url = ? 
                WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdsi", $product_name, $category_id, $description, $price, $final_image_url, $product_id);

        if ($stmt->execute()) {
            echo "Product updated successfully.";
            header("Location: edit_products.php");
            exit;
        } else {
            echo "Error updating product: " . $stmt->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลสินค้า</title>
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
        margin: 20px auto;
        padding: 2rem 1rem;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-size: 1.2rem;
        color: #555;
    }

    input,
    textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    button {
        background-color: #28a745;
        color: white;
        padding: 15px;
        font-size: 1.25rem;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

    button:hover {
        background-color: #218838;
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

    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
        outline: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <h4>แก้ไขข้อมูลสินค้า</h4>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="product_name">ชื่อ</label>
            <input type="text" id="product_name" name="product_name"
                value="<?php echo htmlspecialchars($product['product_name']); ?>" required>

            <label for="category_id">Category ID</label>
            <input type="number" id="category_id" name="category_id"
                value="<?php echo htmlspecialchars($product['category_id']); ?>" required>

            <label for="category_id">หมวดหมู่</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- เลือกหมวดหมู่ --</option>
                <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['category_id']; ?>"
                    <?php echo $product['category_id'] == $category['category_id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
                <?php endforeach; ?>
            </select>

            <label for="price">ราคา</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>"
                required>

            <label for="image_url">Image URL</label>
            <input type="text" id="image_url" name="image_url" class="form-control" placeholder="Enter Image URL"
                value="<?php echo isset($product['image_url']) ? htmlspecialchars($product['image_url']) : ''; ?>">

            <label for="file_upload">หรือเลือกไฟล์ภาพ</label>
            <input type="file" id="file_upload" name="file_upload" class="form-control" accept="image/*">


            <button type="submit" class="cart-button">บันทึกการเปลี่ยนแปลง</button>
        </form>

    </div>
    <footer class="footer p-4">
                <a href="main.php">หน้าหลัก</a>
                <a href="edit_products.php">รายการอาหาร</a>
                <a href="user_info.php">ข้อมูล User</a>
        </footer>
</body>

</html>