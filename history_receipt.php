<?php
session_start();
include('include/header.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: index.php");
    exit; // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Receipt</title>
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
        }

        .receipt-card {
            margin-bottom: 15px;
            border-radius: 10px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .receipt-card:nth-child(2) {
            background-color: #ffe6cc;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .header h3 {
            margin: 0;
            font-size: 1.1rem;
            color: #333;
        }

        .header span {
            font-size: 0.9rem;
            color: #777;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .item span {
            font-size: 0.9rem;
            color: #555;
        }

        .total {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 1rem;
            margin-top: 10px;
            color: #ff3d00;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #fff;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
        }

        .bottom-nav a {
            text-decoration: none;
            font-size: 0.9rem;
            color: #333;
            text-align: center;
            flex: 1;
        }

        .bottom-nav a.active {
            color: #ffa500;
            font-weight: bold;
        }

        .bottom-nav i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="receipt-card">
            <div class="header">
                <h3>พรพรรณ เดชสุพงษ์</h3>
                <span>11:30</span>
            </div>
            <div class="item">
                <span>กาแฟ</span>
                <span>฿50</span>
            </div>
            <div class="item">
                <span>ชาเขียวปั่น</span>
                <span>฿75</span>
            </div>
            <div class="item">
                <span>แซนวิช</span>
                <span>฿65</span>
            </div>
            <div class="total">
                <span>รวมสุทธิ</span>
                <span>฿190</span>
            </div>
        </div>

        <div class="receipt-card">
            <div class="header">
                <h3>กนกกานต์ กลิ่นกุล</h3>
                <span>12:00</span>
            </div>
            <div class="item">
                <span>ลาเต้</span>
                <span>฿60</span>
            </div>
            <div class="item">
                <span>ชาเขียว</span>
                <span>฿60</span>
            </div>
            <div class="item">
                <span>ครัวซอง</span>
                <span>฿65</span>
            </div>
            <div class="total">
                <span>รวมสุทธิ</span>
                <span>฿185</span>
            </div>
        </div>

        <div class="receipt-card">
            <div class="header">
                <h3>แพรวา</h3>
                <span>13:30</span>
            </div>
            <div class="item">
                <span>กาแฟ</span>
                <span>฿50</span>
            </div>
            <div class="item">
                <span>ชาเขียวปั่น</span>
                <span>฿75</span>
            </div>
            <div class="total">
                <span>รวมสุทธิ</span>
                <span>฿125</span>
            </div>
        </div>
    </div>

    <div class="bottom-nav">
        <a href="#" class="active">
            <i class="fa-solid fa-home"></i>
            หน้าหลัก
        </a>
        <a href="#">
            <i class="fa-solid fa-list"></i>
            รายการอาหาร
        </a>
        <a href="#">
            <i class="fa-solid fa-user"></i>
            ข้อมูล User
        </a>
    </div>
</body>
</html>
