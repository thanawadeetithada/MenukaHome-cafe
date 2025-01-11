<?php
include('config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['product_id'])) {
        $product_id = intval($input['product_id']);

        $delete_sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $conn->prepare($delete_sql);

        if ($stmt) {
            $stmt->bind_param("i", $product_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error executing query']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error preparing query']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Product ID not provided']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>
