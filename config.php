<?php
// ตรวจสอบว่า session ยังไม่ได้เริ่มต้นให้เริ่มต้น session
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // เริ่ม session
}

// กำหนดค่าการเชื่อมต่อฐานข้อมูล
$servername = "localhost"; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$username = "root"; // ชื่อผู้ใช้ฐานข้อมูล
$password = ""; // รหัสผ่านฐานข้อมูล
$dbname = "menuka_cafe"; // ชื่อฐานข้อมูลของคุณ

try {
    // สร้างการเชื่อมต่อกับฐานข้อมูลโดยใช้ MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error); // ใช้ Exception เพื่อจัดการข้อผิดพลาด
    }

    // กำหนด character set เป็น utf8 เพื่อรองรับภาษาไทยและตัวอักษรพิเศษ
    if (!$conn->set_charset("utf8")) {
        throw new Exception("Error setting charset: " . $conn->error);
    }

} catch (Exception $e) {
    // แสดงข้อผิดพลาดและหยุดการทำงาน
    die("Database connection error: " . $e->getMessage());
}

// เปิดการแสดงข้อผิดพลาดสำหรับการดีบัก (development purposes only)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
