<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ตรวจสอบว่าข้อมูลใน session พร้อมหรือไม่
    if (!isset($_SESSION['total_amount'], $_SESSION['order_id'])) {
        echo "ไม่พบข้อมูลที่จำเป็น";
        header("Location: menu_checkout.php");
        exit;
    }

    // ดึงข้อมูลจาก session
    $total_amount = $_SESSION['total_amount'];
    $order_id = $_SESSION['order_id'];

    // กำหนดค่าคงที่สำหรับการชำระเงิน
    $payment_method = 'transfer';
    $payment_status = 'completed';
    $payment_date = date('Y-m-d H:i:s');
    $qr_code_url = ''; // เว้นว่างตามที่ระบุ

    // SQL สำหรับการเพิ่มข้อมูลในตาราง payments
    $sql = "INSERT INTO payments (order_id, payment_method, payment_status, payment_date, total_amount)
            VALUES (?, ?, ?, ?, ?)";

    // เตรียม statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("การเตรียมคำสั่งล้มเหลว: " . $conn->error);
    }

    // ผูกค่าพารามิเตอร์
    $stmt->bind_param("isssd", $order_id, $payment_method, $payment_status, $payment_date, $total_amount);

    if ($stmt->execute()) {
        // หากบันทึกสำเร็จในตาราง payments
        unset($_SESSION['total_amount'], $_SESSION['order_id']); // ลบข้อมูลใน session เพื่อป้องกันการใช้งานซ้ำ
    
        // เตรียมค่าที่จะใช้สำหรับการเพิ่มข้อมูลในตาราง receipts
        $user_id = $_SESSION['user_id']; // สมมติว่ามี user_id เก็บใน session
        $issued_date = date('Y-m-d H:i:s');
    
        // SQL สำหรับการเพิ่มข้อมูลในตาราง receipts
        $sql_receipt = "INSERT INTO receipts (order_id, user_id, issued_date, total_amount)
                        VALUES (?, ?, ?, ?)";
    
        // เตรียม statement สำหรับ receipts
        $stmt_receipt = $conn->prepare($sql_receipt);
        if ($stmt_receipt === false) {
            die("การเตรียมคำสั่งสำหรับ receipts ล้มเหลว: " . $conn->error);
        }
    
        // ผูกค่าพารามิเตอร์สำหรับ receipts
        $stmt_receipt->bind_param("iisd", $order_id, $user_id, $issued_date, $total_amount);
    
        if ($stmt_receipt->execute()) {
            // หากบันทึกในตาราง receipts สำเร็จ
            echo "การชำระเงินและการออกใบเสร็จเสร็จสิ้น";
        
            // SQL สำหรับการลบข้อมูลในตาราง cart_products
            $sql_delete_cart = "DELETE FROM cart_products WHERE user_id = ?";
        
            // เตรียม statement สำหรับการลบข้อมูล
            $stmt_delete_cart = $conn->prepare($sql_delete_cart);
            if ($stmt_delete_cart === false) {
                die("การเตรียมคำสั่งสำหรับการลบข้อมูลใน cart_products ล้มเหลว: " . $conn->error);
            }
        
            // ผูกค่าพารามิเตอร์สำหรับการลบข้อมูล
            $stmt_delete_cart->bind_param("i", $user_id);
        
            if ($stmt_delete_cart->execute()) {
                // ลบข้อมูลสำเร็จ
                echo "ลบข้อมูลใน cart_products เรียบร้อยแล้ว";
            } else {
                // หากลบข้อมูลล้มเหลว
                echo "เกิดข้อผิดพลาดในการลบข้อมูลใน cart_products: " . $stmt_delete_cart->error;
            }
        
            // ปิด statement
            $stmt_delete_cart->close();
        
            header("Location: receipt.php"); // นำผู้ใช้ไปยังหน้าสรุปใบเสร็จ
            exit;
        } else {
            // หากบันทึกในตาราง receipts ล้มเหลว
            echo "เกิดข้อผิดพลาดในการบันทึกใบเสร็จ: " . $stmt_receipt->error;
        }        
    
        $stmt_receipt->close();
    } else {
        // หากบันทึกในตาราง payments ล้มเหลว
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    
} else {
    // หากไม่ใช่ POST
    header("Location: menu_checkout.php");
    exit;
}
?>
