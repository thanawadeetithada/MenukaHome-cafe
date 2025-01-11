<?php
include('include/header.php');
include('config.php');

$product_result = $conn->query("SELECT product_id, product_name FROM products");

if (!$product_result) {
    die("Error retrieving products: " . $conn->error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $product_id = $_POST['product_id'];

    // ตรวจสอบว่ามีการเลือกสินค้า
    if (!empty($product_id)) {
        // เตรียม query สำหรับการ insert
        $stmt = $conn->prepare("INSERT INTO recommended_menu (product_id, created_at) VALUES (?, NOW())");

        // ผูกค่ากับ parameter
        $stmt->bind_param("i", $product_id);

        // ลอง execute และตรวจสอบผลลัพธ์
        if ($stmt->execute()) {
            header("Location: main.php");
            
        } else {
            $error_message = "เกิดข้อผิดพลาด: " . $stmt->error;
        }

        // ปิด statement
        $stmt->close();
    } else {
        $error_message = "กรุณาเลือกสินค้า";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่ม Recommended Menu</title>
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
    .container {
        max-width: 400px;
        margin: 20px auto;
        padding: 20px;
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
        color: #ffa500;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .form-group select {
        width: 100%;
        padding: 10px;
        border: 2px solid #ffa500;
        border-radius: 10px;
        font-size: 1rem;
        background-color: #fff;
    }

    button {
        width: 100%;
        padding: 10px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 15px;
        border: none;
        background-color: #ffa500;
        color: white;
        cursor: pointer;
    }

    button:hover {
        background-color: #e59400;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 0.9rem;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>เพิ่มเมนูแนะนำ</h2>

        <?php
        // แสดงข้อความสถานะ
        if (isset($success_message)) {
            echo "<div class='alert alert-success'>{$success_message}</div>";
        } elseif (isset($error_message)) {
            echo "<div class='alert alert-danger'>{$error_message}</div>";
        }
        ?>

        <!-- ฟอร์มเพิ่มเมนูแนะนำ -->
        <form action="" method="POST">
            <div class="form-group">
                <label for="product_id">เลือกสินค้า:</label>
                <select name="product_id" id="product_id" required>
                    <option value="">-- เลือกสินค้า --</option>
                    <?php
                    if ($product_result->num_rows > 0) {
                        while ($row = $product_result->fetch_assoc()) {
                            echo "<option value='{$row['product_id']}'>{$row['product_name']}</option>";
                        }
                    } else {
                        echo "<option value=''>ไม่มีสินค้า</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit">เพิ่มเมนูแนะนำ</button>
        </form>
        <br>

        <form action="upload_promotion.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="image">อัปโหลดรูปภาพ:</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>
    <button type="submit">เพิ่มข้อมูลประชาสัมพันธ์</button></form>

    </div>
    <footer class="footer p-4">
        <a href="main.php">หน้าหลัก</a>
        <a href="edit_products.php">รายการอาหาร</a>
        <a href="user_info.php">ข้อมูล User</a>
    </footer>
</body>

</html>