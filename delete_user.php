<?php
session_start();
include('config.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินและมีสิทธิ์ลบหรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1) {
    header("Location: index.php");
    exit;
}

// ตรวจสอบว่ามีการส่ง user_id มาหรือไม่
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    // อัปเดต user_id ในตาราง receipts เป็น NULL
    $update_receipts_sql = "UPDATE receipts SET user_id = NULL WHERE user_id = ?";
    $stmt = $conn->prepare($update_receipts_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // ลบข้อมูลผู้ใช้ในตาราง users
    $delete_user_sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($delete_user_sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // ลบสำเร็จ
        header("Location: user_info.php?message=ลบผู้ใช้สำเร็จ");
    } else {
        // ลบไม่สำเร็จ
        header("Location: user_info.php?message=ไม่สามารถลบผู้ใช้ได้");
    }

    $stmt->close();
} else {
    header("Location: user_info.php?message=ไม่พบข้อมูลผู้ใช้");
}

$conn->close();
?>
