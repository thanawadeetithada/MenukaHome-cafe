<?php
include 'config.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id'], $data['user_id'], $data['total_amount'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

$order_id = $data['order_id'];
$user_id = $data['user_id'];
$total_amount = $data['total_amount'];
$issued_date = date("Y-m-d H:i:s");

// เพิ่มข้อมูลในตาราง receipts
$sql = "INSERT INTO receipts (order_id, user_id, issued_date, total_amount) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisd", $order_id, $user_id, $issued_date, $total_amount);

if ($stmt->execute()) {
    // เพิ่มข้อมูลในตาราง payments
    $payment_method = 'cash';
    $payment_status = 'completed';
    $payment_date = $issued_date; // ใช้เวลาเดียวกับ issued_date
    $qr_code_url = ''; // ว่างตามที่ระบุ

    $insert_payment_sql = "INSERT INTO payments (order_id, payment_method, payment_status, payment_date, qr_code_url, total_amount) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_payment_stmt = $conn->prepare($insert_payment_sql);
    $insert_payment_stmt->bind_param("issssd", $order_id, $payment_method, $payment_status, $payment_date, $qr_code_url, $total_amount);

    if ($insert_payment_stmt->execute()) {
        // ลบข้อมูลจาก cart_products
        $delete_sql = "DELETE FROM cart_products WHERE user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);

        if ($delete_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Receipt and payment added, cart cleared.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Receipt and payment added but failed to clear cart: ' . $conn->error]);
        }

        $delete_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add payment: ' . $conn->error]);
    }

    $insert_payment_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?>
