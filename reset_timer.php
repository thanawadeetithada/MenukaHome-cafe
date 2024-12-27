<?php
session_start();
unset($_SESSION['payment_end_time']); 
echo json_encode(['success' => true]);
exit;
?>