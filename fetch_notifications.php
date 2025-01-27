<?php
require_once 'config.php';
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

if (!$conn) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// ตรวจสอบว่ามีคำสั่งซื้อที่ยังไม่อ่านอยู่หรือไม่
$sql = "SELECT order_id FROM receipts WHERE status = 'new' ORDER BY issued_date DESC";
$result = $conn->query($sql);

$response = [];

if ($result->num_rows > 0) {
    // ถ้ายังมีสถานะ new ให้แสดงรายการ
    $response['new_orders'] = true;
    while ($row = $result->fetch_assoc()) {
        $response['orders'][] = [
            'order_id' => $row['order_id'],
            'link' => 'history_receipt_all.php'
        ];
    }
} else {
    // ถ้าไม่มีรายการที่เป็น 'new' ให้ตรวจสอบว่าทุก order_id เป็น 'read' หรือไม่
    $sql_check = "SELECT COUNT(*) AS total_orders FROM receipts WHERE status = 'new'";
    $result_check = $conn->query($sql_check);
    $row_check = $result_check->fetch_assoc();

    if ($row_check['total_orders'] == 0) {
        // ดึงรายการคำสั่งซื้อล่าสุด 3 รายการ ถ้าทั้งหมดเป็น 'read'
        $sql_latest = "SELECT order_id FROM receipts ORDER BY issued_date DESC LIMIT 3";
        $result_latest = $conn->query($sql_latest);

        if ($result_latest->num_rows > 0) {
            $response['new_orders'] = false;
            while ($row_latest = $result_latest->fetch_assoc()) {
                $response['orders'][] = [
                    'order_id' => $row_latest['order_id'],
                    'link' => 'history_receipt_all.php'
                ];
            }
        } else {
            $response['new_orders'] = false;
            $response['orders'][] = ['order_id' => 'No orders'];
        }
    } else {
        $response['new_orders'] = false;
        $response['orders'][] = ['order_id' => 'No orders'];
    }
}

$conn->close();

echo json_encode($response);
?>
