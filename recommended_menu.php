<?php
session_start();
include('include/header.php');
include('config.php'); 

// ตรวจสอบว่าฟอร์มถูกส่งหรือไม่
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];

    // ตรวจสอบว่า `product_id` ไม่ว่างเปล่า
    if (!empty($product_id)) {
        // สร้างคำสั่ง SQL
        $sql = "INSERT INTO recommended_menu (product_id, created_at) VALUES (?, NOW())";

        // เตรียม statement เพื่อป้องกัน SQL Injection
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $product_id); // ผูกค่ากับตัวแปร
            if ($stmt->execute()) {
                $success_message = "เพิ่มข้อมูลสำเร็จ!";
            } else {
                $error_message = "เกิดข้อผิดพลาด: " . $conn->error;
            }
            $stmt->close();
        }
    } else {
        $error_message = "กรุณาเลือกสินค้า";
    }
}

// ดึงข้อมูลจากตาราง `products` เพื่อแสดงในฟอร์ม
$product_query = "SELECT product_id, product_name FROM products";
$product_result = $conn->query($product_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่ม Recommended Menu</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<div class="container mt-5">
    <h2>เพิ่ม Recommended Menu</h2>

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
            <select name="product_id" id="product_id" class="form-control" required>
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
        <button type="submit" class="btn btn-primary">เพิ่มเมนูแนะนำ</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
