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
    <title>เลือกที่อยู่</title>
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

        .search-bar {
            display: flex;
            align-items: center;
            border: 2px solid #000;
            border-radius: 1.5rem;
            padding: 10px;
            margin-bottom: 20px;
        }

        .search-bar i {
            color: #777;
            font-size: 1.25rem;
            margin-right: 10px;
        }

        .search-bar input {
            border: none;
            outline: none;
            width: 100%;
            font-size: 1rem;
        }

        .map-container {
            width: 100%;
            height: 200px;
            background-image: url('https://via.placeholder.com/400x200.png?text=Map');
            background-size: cover;
            background-position: center;
            border-radius: 15px;
            margin-bottom: 20px;
            position: relative;
        }

        .map-pin {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2rem;
            color: #ff3d00;
        }

        .address-details {
            text-align: left;
            margin-bottom: 20px;
            font-size: 1rem;
            color: #333;
        }

        .address-details i {
            color: #ff3d00;
            font-size: 1.25rem;
            margin-right: 10px;
        }

        .address-details p {
            margin: 5px 0;
        }

        .confirm-button {
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
        }

        .confirm-button:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="search-bar">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" placeholder="ค้นหาที่อยู่">
        </div>

        <div class="map-container">
            <i class="fa-solid fa-location-dot map-pin"></i>
        </div>

        <div class="address-details">
            <i class="fa-solid fa-location-dot"></i>
            <p>บ้านเลขที่ 123/45</p>
            <p>ซอยตัวอย่าง ถนนตัวอย่าง</p>
            <p>เขตตัวอย่าง จังหวัดตัวอย่าง</p>
        </div>

        <button class="confirm-button">ยืนยันที่อยู่</button>
    </div>
</body>
</html>
