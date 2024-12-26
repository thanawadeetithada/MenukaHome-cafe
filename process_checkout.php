<?php
include 'config.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['user_id'], $data['order_date'], $data['total_amount'], $data['delivery_type'], $data['payment_method'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบถ้วน']);
    exit;
}

$user_id = $data['user_id'];
$order_date = $data['order_date'];
$total_amount = $data['total_amount'];
$delivery_type = $data['delivery_type'];
$payment_method = $data['payment_method'];
$status = $data['status'];
$order_items = $data['order_items'];

$sql = "INSERT INTO orders (user_id, order_date, total_amount, delivery_type, payment_method, status) 
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issdss", $user_id, $order_date, $total_amount, $delivery_type, $payment_method, $status);

if ($stmt->execute()) {
    $order_id = $conn->insert_id;
    $sql_detail = "INSERT INTO order_details (order_id, product_id, product_name, quantity, price) 
                   VALUES (?, ?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);

    foreach ($order_items as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price = $item['price']; 
        $product_name = $item['product_name']; 

        $stmt_detail->bind_param("iisid", $order_id, $product_id, $product_name, $quantity, $price);

        if (!$stmt_detail->execute()) {
            echo json_encode(['success' => false, 'message' => 'ไม่สามารถบันทึกข้อมูล order_details: ' . $conn->error]);
            $stmt_detail->close();
            $conn->close();
            exit;
        }
    }
    $stmt_detail->close();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $conn->error]);
}

$stmt->close();
$conn->close();
?>
