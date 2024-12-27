<?php
session_start(); // เริ่มต้นเซสชัน

// ทำลายข้อมูลเซสชันทั้งหมด
session_unset();
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
header("Location: index.php");
exit();
