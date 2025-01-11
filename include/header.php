<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ตรวจสอบว่ามีการ login หรือไม่
$user_logged_in = isset($_SESSION['username']) ? $_SESSION['username'] : null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menuka Home Cafe</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Bootstrap (optional) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    .header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #f4f4f9;
        padding: 10px 20px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .header .left-section {
        display: flex;
        align-items: center;
    }

    .header .left-section img {
        width: 40px;
        height: 40px;
        margin-left: 10px;
        border-radius: 50%;
    }

    .header .right-section {
        position: relative;
    }

    .header .right-section i {
        font-size: 1.5rem;
        cursor: pointer;
    }

    .dropdown-menu {
        display: none;
    position: absolute;
    right: 0;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    list-style: none;
    padding: 10px 0;
    margin: 5px 0 0;
    z-index: 9999; /* เพิ่มระดับสูงสุด */
    width: 150px;
        left: -600%;
    }

    .dropdown-menu.show {
        display: block;
    }

    .dropdown-menu li {
        padding: 10px 20px;
        cursor: pointer;
    }

    .dropdown-menu li:hover {
        background-color: #f4f4f9;
    }

    .dropdown-menu li a {
        text-decoration: none;
        color: #333;
        display: block;
    }
    </style>
    <script>
     document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.querySelector('.fa-bars');
        const dropdownMenu = document.querySelector('.dropdown-menu');

        menuButton.addEventListener('click', function() {
            dropdownMenu.classList.toggle('show');
        });

        // ปิดเมนูเมื่อคลิกนอก Dropdown
        document.addEventListener('click', function(event) {
            if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.remove('show');
            }
        });

        const backButton = document.querySelector('.fa-angles-left');
        backButton.addEventListener('click', function() {
            window.history.back();
        });
    });
    </script>
</head>
<body>
    <header class="header">
        <div class="left-section">
            <i class="fa-solid fa-angles-left"></i>
            <img src="img/logo.png" alt="Logo">
        </div>
        <div class="right-section">
            <i class="fa-solid fa-bars"></i>
            <ul class="dropdown-menu">
                <li><a href="history_receipt.php">History Recipes</a></li>
                <li><a href="menu_page.php">Menu</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>
</body>

</html>
