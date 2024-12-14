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
    <title>Menu Detail</title>
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
            text-align: center;
        }

        .product-image img {
            width: 150px;
            height: auto;
            margin: 10px auto;
            border-radius: 10px;
        }

        .product-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-top: 10px;
            color: #333;
        }

        .product-description {
            font-size: 1rem;
            color: #777;
            margin: 5px 0 15px;
        }

        .product-price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            margin: 15px 0;
        }

        .options {
            text-align: left;
            margin: 15px 0;
        }

        .options label {
            display: block;
            margin-bottom: 10px;
            font-size: 1rem;
            color: #555;
        }

        .options input[type="radio"] {
            margin-right: 10px;
        }

        .details {
            margin-top: 20px;
            text-align: center;
        }

        .details textarea {
            width: 100%;
            height: 60px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
            resize: none;
        }

        .add-to-cart-btn {
            background-color: #ffa500;
            color: white;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: bold;
            border: none;
            border-radius: 1.5rem;
            margin-top: 20px;
            cursor: pointer;
            width: 100%;
        }

        .add-to-cart-btn:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="product-image">
            <img src="" alt="Coffee">
        </div>

        <div class="product-title">กาแฟ</div>
        <div class="product-description">ทำจากกาแฟแท้ใส่นม</div>

        <div class="product-price">฿50</div>

        <div class="options">
            <label>
                <input type="radio" name="sweetness" value="25"> หวานน้อย 25%
            </label>
            <label>
                <input type="radio" name="sweetness" value="50"> หวานพอดี 50%
            </label>
            <label>
                <input type="radio" name="sweetness" value="100"> หวานเต็มที่ 100%
            </label>
        </div>

        <div class="details">
            <textarea placeholder="รายละเอียดเพิ่มเติม"></textarea>
        </div>

        <button class="add-to-cart-btn">เพิ่มลงตะกร้า</button>
    </div>
</body>
</html>
