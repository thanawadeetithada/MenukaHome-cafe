<?php
session_start();
include('include/header.php');
include('config.php');

$is_admin = false;

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id']; // ดึง user_id จาก session

// ดึง is_admin จากฐานข้อมูล
$query = "SELECT user_role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();


$query = "
    SELECT p.product_name, p.image_url
    FROM 
        recommended_menu rm
    JOIN 
        products p ON rm.product_id = p.product_id
    ORDER BY rm.created_at DESC
";
$result = $conn->query($query);
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
footer {
    display: flex;
    justify-content: space-around;
    background-color: #f9c74f;
    padding: 10px 0;
    position: fixed;
    bottom: 0;
    width: 100%;
    z-index: 1000;
    a {
        font-size: 18px;
        font-weight: bold;
        color: black;
    }
}

body {
    font-family: 'Prompt', sans-serif;
    line-height: 1.6;
    height: 100%;
    margin: 0;
    padding-bottom: 60px; 
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
        height: 245px;
        object-fit: contain;
        border-radius: 10px;
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

    .row {
        margin: 0;
    }

    .text-center {
        margin-top: 3rem;
        a {
            padding: 3rem 7rem;
        }
    }

    .btn-condition a {
        padding: 10px;
    }
    </style>
</head>

<body>
    <div class="promotion-section">
        <div class="promotion-content">
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

    <div id="menuCarousel" class="carousel slide" data-ride="carousel" data-interval="2000">
        <div class="carousel-inner">
            <?php 
        if ($result->num_rows > 0) {
            $active = true;
            $count = 0;
            $items_per_slide = 2; // จำนวนไอเท็มต่อสไลด์
            echo '<div class="carousel-item '. ($active ? 'active' : '') .'"><div class="row">';
            while ($row = $result->fetch_assoc()) {
                $image_url = htmlspecialchars($row['image_url']); 
                $product_name = htmlspecialchars($row['product_name']);
                ?>
            <div class="col-6">
                <img src="<?php echo $image_url; ?>" class="d-block w-100" alt="<?php echo $product_name; ?>">
            </div>
            <?php
                $count++;
                if ($count % $items_per_slide == 0 && $count < $result->num_rows) {
                    echo '</div></div><div class="carousel-item"><div class="row">';
                }
            }
            echo '</div></div>'; // ปิด div ของสไลด์สุดท้าย
        } else {
            echo '<div class="carousel-item active">
                <img src="https://via.placeholder.com/600x300?text=No+Recommended+Menu" class="d-block w-100" alt="No Menu">
            </div>';
        }
        ?>
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


    <div class="wrapper">
        <div class="btn-upload">
            <?php if (!$is_admin): ?>
            <img src="img/cafe.png" class="bottom-image" alt="Bottom Image">
            <?php endif; ?>
        </div>

        <?php if ($is_admin): ?>
        <div class="btn-condition text-center">
            <a href="add_products.php" class="btn btn-primary">Upload</a>
            <a href="recommended_menu.php" class="btn btn-warning">เพิ่มเมนูแนะนำ</a>
            <a href="history_receipt.php" class="btn btn-info">ใบเสร็จ</a>
        </div>
        <footer class="footer p-4">
                <a href="main.php">หน้าหลัก</a>
                <a href="edit_products.php">รายการอาหาร</a>
                <a href="user_info.php">ข้อมูล User</a>
        </footer>
        <?php endif; ?>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const carouselInner = document.querySelector('.carousel-inner');

        // เมื่อกด carousel-inner ให้ไปที่หน้า menu_page.php
        carouselInner.addEventListener('click', function() {
            window.location.href = 'menu_page.php';
        });
    });
    </script>

</body>

</html>