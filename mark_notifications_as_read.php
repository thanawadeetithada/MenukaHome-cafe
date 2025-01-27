<?php
require_once 'config.php';

if (!$conn) {
    die("Database connection failed.");
}

// อัปเดตสถานะออเดอร์เป็น "read" เมื่อคลิกอ่านแล้ว
$sql = "UPDATE receipts SET status = 'read' WHERE status = 'new'";
if ($conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
