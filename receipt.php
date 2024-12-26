<?php
session_start();
include('include/header.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .logo {
            width: 100px;
            margin: 0 auto;
        }

        .logo img {
            width: 100%;
            height: auto;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 10px 0;
            color: #ff7f00;
        }

        .subtitle {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
        }

        .receipt-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #333;
        }

        .receipt-item .price {
            font-weight: bold;
        }

        .total {
            font-size: 1.25rem;
            font-weight: bold;
            color: #ff3d00;
            margin: 20px 0;
        }

        .note {
            font-size: 0.875rem;
            color: #777;
        }

        .back-button {
            background-color: #ffa500;
            color: white;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: bold;
            border: none;
            border-radius: 1.5rem;
            cursor: pointer;
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        .back-button:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>

        <div class="title">MENUKA</div>
        <div class="subtitle">Receipt</div>

        <div class="receipt-item">
            <span>กาแฟ</span>
            <span class="price">฿50</span>
        </div>
        <div class="receipt-item">
            <span>ชาเขียวปั่น</span>
            <span class="price">฿75</span>
        </div>
        <div class="receipt-item">
            <span>แซนวิช</span>
            <span class="price">฿65</span>
        </div>

        <div class="receipt-item total">
            <span>รวมสุทธิ</span>
            <span>฿190</span>
        </div>

        <div class="note">ราคานี้รวมภาษีแล้ว</div>

        <button class="back-button">กลับหน้าเมนู</button>
    </div>
</body>
</html>
