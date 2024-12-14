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
    <title>Order Summary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .button-group button {
            flex: 1;
            padding: 10px;
            margin: 0 5px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 1.5rem;
            border: none;
            cursor: pointer;
        }

        .button-group button:nth-child(1) {
            background-color: #ffe066;
            color: #333;
        }

        .button-group button:nth-child(2) {
            background-color: #ffa500;
            color: white;
        }

        .address-input {
            display: flex;
            align-items: center;
            border: 2px solid #000;
            border-radius: 1.5rem;
            padding: 10px;
            margin-bottom: 20px;
        }

        .address-input i {
            color: #ff3d00;
            font-size: 1.5rem;
            margin-right: 10px;
        }

        .address-input input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
        }

        .product-summary {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .product-summary img {
            width: 60px;
            height: auto;
            margin-right: 10px;
            border-radius: 5px;
        }

        .product-details {
            flex: 1;
        }

        .product-details h3 {
            font-size: 1rem;
            margin: 0;
            color: #333;
        }

        .product-details p {
            font-size: 0.875rem;
            color: #777;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: bold;
            color: #000;
        }

        .summary-details {
            margin-bottom: 20px;
            font-size: 1rem;
            color: #333;
        }

        .summary-details .total {
            font-weight: bold;
            color: #ff3d00;
        }

        .payment-options {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .payment-options button {
            flex: 1;
            padding: 10px;
            margin: 0 5px;
            font-size: 1rem;
            font-weight: bold;
            border: 2px solid #000;
            border-radius: 1.5rem;
            background-color: white;
            cursor: pointer;
        }

        .payment-options button:hover {
            background-color: #f1f1f1;
        }

        .action-buttons {
            display: flex;
            justify-content: space-between;
        }

        .action-buttons button {
            flex: 1;
            padding: 15px;
            margin: 0 5px;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 1.5rem;
            border: none;
            cursor: pointer;
        }

        .action-buttons button.cancel {
            background-color: #ff3d00;
            color: white;
        }

        .action-buttons button.confirm {
            background-color: #4caf50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="button-group">
            <button>รับที่ร้าน</button>
            <button>รับที่บ้าน</button>
        </div>

        <div class="address-input">
            <i class="fa-solid fa-location-dot"></i>
            <input type="text" placeholder="กรุณาใส่ที่อยู่">
        </div>

        <div class="product-summary">
            <img src="https://via.placeholder.com/60x60?text=Coffee" alt="Coffee">
            <div class="product-details">
                <h3>กาแฟ</h3>
                <p>ทำจากกาแฟแท้ใส่นม</p>
            </div>
            <div class="product-price">฿50</div>
        </div>

        <div class="summary-details">
            <p>รายละเอียดค่าใช้จ่าย:</p>
            <p>กาแฟ: ฿50</p>
            <p class="total">รวมสุทธิ: ฿50</p>
        </div>

        <div class="payment-options">
            <button>โอนชำระ</button>
            <button>เงินสด</button>
        </div>

        <div class="action-buttons">
            <button class="cancel">ยกเลิก</button>
            <button class="confirm">ยืนยัน</button>
        </div>
    </div>
</body>
</html>
