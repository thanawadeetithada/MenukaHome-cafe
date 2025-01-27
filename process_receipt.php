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
$location_id = isset($data['location_id']) ? $data['location_id'] : null;
$issued_date = date("Y-m-d H:i:s");

// เพิ่มข้อมูลในตาราง receipts
$sql = "INSERT INTO receipts (order_id, user_id, issued_date, total_amount, location_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisdi", $order_id, $user_id, $issued_date, $total_amount, $location_id);

if ($stmt->execute()) {
    // เพิ่มข้อมูลในตาราง payments
    $payment_method = 'cash';
    $payment_status = 'completed';
    $payment_date = $issued_date;

    $insert_payment_sql = "INSERT INTO payments (order_id, payment_method, payment_status, payment_date, total_amount) VALUES (?, ?, ?, ?, ?)";
    $insert_payment_stmt = $conn->prepare($insert_payment_sql);
    $insert_payment_stmt->bind_param("isssd", $order_id, $payment_method, $payment_status, $payment_date, $total_amount);

    if ($insert_payment_stmt->execute()) {
        // ลบข้อมูลจาก cart_products
        $delete_sql = "DELETE FROM cart_products WHERE user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);

        if ($delete_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'delete cart successfully.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error delete cart: ' . $conn->error]);
        }

        $delete_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add payment: ' . $conn->error]);
    }

    $insert_payment_stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error inserting receipt: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
