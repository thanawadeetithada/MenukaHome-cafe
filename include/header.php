<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // ตรวจสอบว่ามีการ login หรือไม่
    $user_logged_in = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $user_role      = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
ob_start();
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
        height: 7vh;
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

    .right-section i {
        color: initial;
    }


    .notification-menu {
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
        z-index: 9999;
        width: 150px;
    }

    .notification-menu.show {
        display: block;
        padding: 0px;
    }

    .notification-menu li {
        padding: 10px 20px;
        cursor: pointer;
        border-bottom: 1px solid #ddd;
    }

    .notification-menu li:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .notification-menu li a {
        color: black;
    }

    .bar-menu {
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
        z-index: 9999;
        width: 150px;
    }

    .bar-menu.show {
        display: block;
    }

    .bar-menu li {
        padding: 10px 20px;
        cursor: pointer;
    }

    .bar-menu li a {
        color: black;
    }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const barIcon = document.getElementById('bar-icon');
        const barMenu = document.querySelector('.bar-menu');

        if (barIcon && barMenu) {
            console.log("Bar icon found!");
            barIcon.addEventListener('click', function() {
                barMenu.classList.toggle('show');
            });

            document.addEventListener('click', function(event) {
                if (!barIcon.contains(event.target) && !barMenu.contains(event.target)) {
                    barMenu.classList.remove('show');
                }
            });
        } else {
            console.log("Bar icon or menu not found");
        }

        const bellIcon = document.getElementById('notification-bell');
        const notificationMenu = document.querySelector('.notification-menu');

        function checkNotifications() {
            fetch('fetch_notifications.php')
                .then(response => response.json())
                .then(data => {
                    notificationMenu.innerHTML = '';

                    if (data.new_orders) {
                        bellIcon.style.color = 'red';
                        data.orders.forEach(order => {
                            notificationMenu.innerHTML +=
                                `<li><a href="${order.link}">Order ID: ${order.order_id}</a></li>`;
                        });
                    } else {
                        bellIcon.style.color = 'black';
                        if (data.orders.length > 0) {
                            data.orders.forEach(order => {
                                notificationMenu.innerHTML +=
                                    `<li><a href="${order.link}">Order ID: ${order.order_id}</a></li>`;
                            });
                        } else {
                            notificationMenu.innerHTML = '<li>No orders</li>';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                });
        }

        setInterval(checkNotifications, 1000);

        bellIcon.addEventListener('click', function() {
            notificationMenu.classList.toggle('show');
            if (bellIcon.style.color === 'red') {
                bellIcon.style.color = 'black';
                markNotificationsAsRead();
            }
        });

        function markNotificationsAsRead() {
            fetch('mark_notifications_as_read.php')
                .then(response => response.text())
                .then(data => {
                    console.log('Notifications marked as read.');
                });
        }
        checkNotifications();

        const backButton = document.querySelector('.fa-angles-left');

        if (backButton) {
            backButton.addEventListener('click', function() {
                window.history.back();
            });
        }
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
            <?php if ($user_role === 0): ?>
            <i class="fa-solid fa-bars" id="bar-icon"></i>
            <ul class="bar-menu">
                <li><a href="main.php">หน้าหลัก</a></li>
                <li><a href="history_receipt.php">ประวัติการซื้อ</a></li>
                <li><a href="line.php">ติดต่อเรา</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
            <?php elseif ($user_role === 1): ?>
            <i class="fa-regular fa-bell" id="notification-bell" style="color: black;"></i>&nbsp;
            <ul class="notification-menu"></ul>
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
            <?php endif; ?>

        </div>
    </header>
</body>

</html>