<?php
include('config.php'); // เชื่อมต่อฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = htmlspecialchars($_POST['title']);
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

    // บันทึกข้อมูลใหม่ลงฐานข้อมูล
    $query = "INSERT INTO promotions (title, image_url) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $title, $image_url);
    if ($stmt->execute()) {
        // ลบข้อมูลเก่า (ตัวอย่าง: ลบข้อมูลที่เก่ากว่า 30 วัน)
        $delete_query = "DELETE FROM promotions WHERE created_at < NOW() - INTERVAL 30 DAY";
        if ($conn->query($delete_query)) {
            echo "ลบข้อมูลเก่าเรียบร้อย!";
        } else {
            echo "เกิดข้อผิดพลาดในการลบข้อมูลเก่า: " . $conn->error;
        }

        // เมื่อบันทึกสำเร็จและลบข้อมูลเก่าเสร็จแล้ว ให้เปลี่ยนเส้นทางไปยังหน้า recommended_menu.php
        header("Location: recommended_menu.php");
        exit(); // หยุดการทำงานหลังจาก redirect
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
    $stmt->close();
}
?>
