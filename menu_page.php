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
    <title>Menuka Home Cafe</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Prompt', sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #ffa500;
            text-align: center;
            padding: 10px;
            position: relative;
        }

        header h1 {
            font-size: 1.75rem;
            margin: 0;
            font-family: 'Pacifico', cursive;
            color: white;
        }

        .menu-bar {
            display: flex;
            justify-content: center;
            gap: 20px;
            padding: 10px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .menu-bar a {
            text-decoration: none;
            font-size: 1rem;
            color: #333;
            font-weight: bold;
        }

        .menu-bar a.active {
            color: #ffa500;
            border-bottom: 2px solid #ffa500;
            padding-bottom: 5px;
        }

        .content {
            padding: 20px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-item img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }

        .product-info {
            flex: 1;
        }

        .product-info h3 {
            margin: 0;
            font-size: 1rem;
            color: #333;
        }

        .product-info p {
            margin: 5px 0 0;
            font-size: 0.875rem;
            color: #777;
        }

        .product-price {
            font-size: 1rem;
            font-weight: bold;
        }

        .cart-button {
            background-color: #ffa500;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 1.25rem;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            margin-top: 20px;
            width: 100%;
            cursor: pointer;
        }

        .cart-button:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <header>
        <h1>Menuka Home Cafe</h1>
    </header>
    <div class="menu-bar">
        <a href="#" class="active">เครื่องดื่ม</a>
        <a href="#">เบเกอรี่</a>
        <a href="#">ขนม</a>
    </div>
    <div class="content">
        <div class="product-item">
            <img src="" alt="กาแฟ">
            <div class="product-info">
                <h3>กาแฟ</h3>
                <p>ทำจากกาแฟแท้ใส่นม</p>
            </div>
            <div class="product-price">฿50</div>
        </div>
        <div class="product-item">
            <img src="" alt="ชาเขียวปั่น">
            <div class="product-info">
                <h3>ชาเขียวปั่น</h3>
                <p>แบบปั่นอร่อยจากยอดชา</p>
            </div>
            <div class="product-price">฿75</div>
        </div>
        <div class="product-item">
            <img src="" alt="แซนวิช">
            <div class="product-info">
                <h3>แซนวิช</h3>
                <p>กินทุกเช้าเถอะ อร่อย</p>
            </div>
            <div class="product-price">฿65</div>
        </div>
        <button class="cart-button">สินค้าในตะกร้า</button>
    </div>
</body>
</html>
