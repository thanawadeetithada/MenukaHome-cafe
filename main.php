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
    <title>Menu Promotion</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@400;700&display=swap" rel="stylesheet">
    <style>
    body {
        font-family: 'Prompt', sans-serif;
        line-height: 1.6;
    }

    h3 {
        font-weight: bold;
        text-align: center;
        margin: 20px;
    }

    .promotion-section {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 30px 0;
    }

    .promotion-content {
        position: relative;
        background-color: #ffa500;
        padding: 30px 20px;
        border-radius: 40px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 80%;
        max-width: 600px;
    }

    .icon-container {
        position: absolute;
        top: -20px;
        left: -20px;
        z-index: 10;
    }

    .icon-container img {
        width: 60px;
        height: auto;
    }

    .text-dots-container {
        text-align: center;
    }

    .promotion-text {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #000;
    }

    .dots {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .dots span {
        width: 12px;
        height: 12px;
        background-color: #fff;
        border-radius: 50%;
    }

    .promotion-text {
        text-align: center;
        margin: 30px 0;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }


    .carousel-item img {
        width: 100%;
        height: auto;
    }

    .carousel-inner {
        border-radius: 10px;
        overflow: hidden;
    }

    .bottom-image {
        width: 100%;
        height: 300px;
        object-fit: cover;
        margin-top: 40px;
    }

    .text-container {
        margin-top: 20px;
    }

    .text-container p {
        font-size: 1.25rem;
        color: #555;
    }
    </style>
</head>

<body>

    <div class="promotion-section">
        <div class="promotion-content">
            <div class="icon-container">
                <i class="fa-solid fa-bullhorn"></i>
            </div>
            <div class="text-dots-container">
                <div class="promotion-text">ประชาสัมพันธ์</div>
                <div class="dots">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

    <div class="menu-title">
        <h3>เมนูแนะนำแสนอร่อย</h3>
    </div>

    <div id="menuCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://via.placeholder.com/600x300?text=Menu+1" class="d-block w-100" alt="Menu 1">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/600x300?text=Menu+2" class="d-block w-100" alt="Menu 2">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/600x300?text=Menu+3" class="d-block w-100" alt="Menu 3">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/600x300?text=Menu+4" class="d-block w-100" alt="Menu 4">
            </div>
            <div class="carousel-item">
                <img src="https://via.placeholder.com/600x300?text=Menu+5" class="d-block w-100" alt="Menu 5">
            </div>
        </div>
        <a class="carousel-control-prev" href="#menuCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#menuCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <!-- Image at the bottom -->
    <img src="img/cafe.png" class="bottom-image" alt="Bottom Image">

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>