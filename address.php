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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQh_6TQaoLrEGM1ADIFvn8yodtioAmY_U&libraries=places&v=weekly"></script>
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


        #map {
            height: 500px;
            width: 100%;
        }
        #search-box {
            margin: 10px;
            display: flex;
            justify-content: center;
        }
        #search-input {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div id="search-box">
        <input id="search-input" type="text" placeholder="Search for a location...">
    </div>
    <div id="map"></div>

    <script>
        function initMap() {
            // ตำแหน่งเริ่มต้น (Bangkok, Thailand)
            const defaultCenter = { lat: 13.736717, lng: 100.523186 };

            // สร้างแผนที่
            const map = new google.maps.Map(document.getElementById("map"), {
                center: defaultCenter,
                zoom: 13,
            });

            // สร้าง Marker
            const marker = new google.maps.Marker({
                position: defaultCenter,
                map: map,
                draggable: true, // ให้ลาก Marker ได้
            });

            // สร้าง Search Box
            const input = document.getElementById("search-input");
            const searchBox = new google.maps.places.SearchBox(input);

            // ปรับขอบเขตการค้นหาให้เป็นไปตามมุมมองปัจจุบันของแผนที่
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            // เมื่อมีการเลือกสถานที่จาก Search Box
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();
                if (places.length === 0) return;

                const place = places[0];
                if (!place.geometry || !place.geometry.location) return;

                // ย้ายแผนที่และ Marker ไปยังสถานที่ที่เลือก
                map.setCenter(place.geometry.location);
                map.setZoom(15);
                marker.setPosition(place.geometry.location);
            });

            // อัปเดตตำแหน่ง Marker ใน Console เมื่อมีการลาก
            marker.addListener("dragend", () => {
                const position = marker.getPosition();
                console.log(`Latitude: ${position.lat()}, Longitude: ${position.lng()}`);
            });
        }

        // เรียกใช้แผนที่เมื่อหน้าโหลด
        window.onload = initMap;
    </script>
</body>
</html>
