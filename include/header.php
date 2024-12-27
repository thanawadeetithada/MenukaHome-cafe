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

    .header .right-section i {
        font-size: 1.5rem;
        cursor: pointer;
    }

    .header .user-info {
        font-size: 0.9rem;
        color: #555;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const backButton = document.querySelector('.fa-angles-left');
        backButton.addEventListener('click', function() {
            window.history.back();
        });

        const logoutButton = document.querySelector('.fa-arrow-right-from-bracket');
        logoutButton.addEventListener('click', function() {
            window.location.href = 'logout.php';
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
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
    </header>
</body>

</html>