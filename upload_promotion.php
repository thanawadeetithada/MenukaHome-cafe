<?php
include('config.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = 'uploads/'; // โฟลเดอร์สำหรับเก็บรูปภาพ
    $image_url = null;

    // ตรวจสอบว่าอัปโหลดไฟล์สำเร็จ
    if (!empty($image_name)) {
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $image_url = $upload_dir . basename($image_name);
        move_uploaded_file($image_tmp_name, $image_url);
    }

    // บันทึกข้อมูลใหม่หรือแทนที่ข้อมูลเดิม
    $query = "INSERT INTO promotions (id, image_url, created_at)
              VALUES (1, ?, NOW())
              ON DUPLICATE KEY UPDATE image_url = VALUES(image_url), created_at = NOW()";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $image_url);

    if ($stmt->execute()) {
        echo "บันทึกข้อมูลสำเร็จ!";
        header("Location: recommended_menu.php");
        exit(); // หยุดการทำงานหลังจาก redirect
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
    $stmt->close();
}
?>
