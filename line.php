<?php
// URL ที่ต้องการให้เปิดใน LINE
$lineUrl = "https://line.me/ti/p/";

// สร้างหน้า HTML
echo '<!DOCTYPE html>
<html>
<head>
    <title>Open LINE</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9; /* สีพื้นหลัง */
            font-family: Arial, sans-serif;
        }
        .container {
            text-align: center;
        }
        p {
            margin-bottom: 20px;
            font-size: 3rem;
            font-weight: bold;
        }
        img {
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #0066cc;
            font-size: 3rem;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <p>Scan QR Code to Connect on LINE</p>
        <a href="'.$lineUrl.'" target="_blank">
            <img src="img/Line.png" alt="LINE QR Code" style="width:100%; height:auto;">
        </a>
        <p>หรือ <a href="'.$lineUrl.'" target="_blank">คลิกที่นี่</a> เพื่อเปิด LINE โดยตรง</p>
    </div>
</body>
</html>';
?>
