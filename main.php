<?php
session_start();

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
    <style>
        /* Custom Styles */
        .promotion-text {
            text-align: center;
            margin: 30px 0;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
        }

        .menu-title {
            text-align: center;
            font-size: 1.75rem;
            font-weight: bold;
            margin-top: 40px;
            color: #007bff;
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

    <!-- Text for Promotion -->
    <div class="promotion-text">
        <p>โปรโมชั่นพิเศษประจำเดือนนี้!</p>
    </div>

    <!-- Text for Menu -->
    <div class="menu-title">
        <p>เมนูแนะนำแสนอร่อย</p>
    </div>

    <!-- Carousel for Images (5 images) -->
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
    <img src="https://via.placeholder.com/1200x500?text=Delicious+Food+Image" class="bottom-image" alt="Bottom Image">

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
