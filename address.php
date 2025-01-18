<?php
include 'include/header.php';
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// กำหนดค่าพิกัดเริ่มต้น
$defaultLat = 13.7563; // กรุงเทพฯ
$defaultLon = 100.5018;
$defaultAddress = "กรุงเทพฯ";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'search' && !empty($_POST['address'])) {
        $address = htmlspecialchars($_POST['address']);

        // ฟังก์ชันสำหรับเรียก Nominatim API
        function getCoordinates($address) {
            $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: MyPHPApp/1.0 (your-email@example.com)'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            if (!empty($data)) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon']
                ];
            }

            return null;
        }

        // แปลงที่อยู่เป็นพิกัด
        $coordinates = getCoordinates($address);

        if ($coordinates) {
            $defaultLat = $coordinates['lat'];
            $defaultLon = $coordinates['lon'];
            $defaultAddress = $address;
        } else {
            echo "<p>ไม่พบพิกัดสำหรับที่อยู่นี้</p>";
        }
    }
    if ($action === 'save' && !empty($_POST['address']) && !empty($_POST['latitude']) && !empty($_POST['longitude'])) {
        $address = htmlspecialchars($_POST['address']);
        $latitude = floatval($_POST['latitude']);
        $longitude = floatval($_POST['longitude']);
    
        // บันทึกข้อมูลลงฐานข้อมูล พร้อม user_id
        $stmt = $conn->prepare("INSERT INTO locations (user_id, address, latitude, longitude) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isdd", $user_id, $address, $latitude, $longitude);
    
        if ($stmt->execute()) {
            // Redirect ไปยัง menu_checlout.php พร้อมส่งค่าที่อยู่
            header("Location: menu_checkout.php?address=" . urlencode($address));
            exit;
        } else {
            echo "<p>เกิดข้อผิดพลาดในการบันทึกข้อมูล</p>";
        }
    
        $stmt->close();
    }    
}

$conn->close();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Leaflet Map with Separate Search and Save</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
    label {
        margin-top: 2rem;
        font-size: 18px;
        font-weight: bold;
        margin: 1.5rem 0.5rem 0.5rem 0.5rem;
    }

    input {
        border: 1px solid #000;
        border-radius: 1.5rem;
        padding: 10px 20px;
        margin-right: 0.2rem;
    }

    button {
        padding: 10px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 1.5rem;
        border: none;
        cursor: pointer;
        background-color: #ffe066;
        color: #333;
    }

    .form-btn {
        display: flex;
        margin-top: 2rem;
    }

    .btn-save {
        margin-top: 1rem;
        margin-left: 0.5rem;
    }
    </style>
</head>

<body>
   <div class="form-btn">
    <form method="POST" style="margin-bottom: 20px;">
        <label for="address">ที่อยู่ </label>
        <input type="text" id="address" name="address" placeholder="กรอกที่อยู่ เช่น Bangkok, Thailand" required />
        <button type="submit" name="action" value="search">ค้นหา</button>
    </form>

    <form method="POST">
        <input type="hidden" id="save-address" name="address" value="" />
        <input type="hidden" id="latitude" name="latitude" value="" />
        <input type="hidden" id="longitude" name="longitude" value="" />
        <input type="hidden" name="user_id" value="<?= $user_id ?>" /> <!-- ส่ง user_id -->
        <button type="submit" class="btn-save" name="action" value="save">บันทึก</button>
    </form>
    </div>

    <div id="map" style="height: 400px; width: 100%;"></div>

    <script>
function initMap() {
    let lat = <?= $defaultLat ?>;
    let lon = <?= $defaultLon ?>;
    let address = "<?= addslashes($defaultAddress) ?>";

    // สร้างแผนที่
    const map = L.map('map').setView([lat, lon], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // สร้างหมุดบนแผนที่
    let marker = L.marker([lat, lon], { draggable: true }).addTo(map)
        .bindPopup(`ที่อยู่: ${address}`)
        .openPopup();

    // อัปเดตค่าพิกัดในฟอร์มเมื่อหมุดถูกลาก
    marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;

        // Log พิกัดใน console
        console.log(`Dragged to: Latitude ${position.lat}, Longitude ${position.lng}`);
    });

    // อัปเดตค่าพิกัดและเพิ่มหมุดใหม่เมื่อคลิกบนแผนที่
    map.on('click', function (e) {
        const position = e.latlng;

        // อัปเดตหมุดไปยังตำแหน่งที่คลิก
        marker.setLatLng(position);

        // อัปเดตค่าพิกัดในฟอร์ม
        document.getElementById('latitude').value = position.lat;
        document.getElementById('longitude').value = position.lng;

        // อัปเดตข้อความใน popup
        marker.bindPopup(`พิกัด: ${position.lat.toFixed(5)}, ${position.lng.toFixed(5)}`).openPopup();

        // Log พิกัดใน console
        console.log(`Clicked on map: Latitude ${position.lat}, Longitude ${position.lng}`);
    });

    // ตั้งค่าเริ่มต้นในฟอร์ม
    document.getElementById('save-address').value = address;
    document.getElementById('latitude').value = lat;
    document.getElementById('longitude').value = lon;

    // Log พิกัดเริ่มต้น
    console.log(`Initial position: Latitude ${lat}, Longitude ${lon}`);
}

// เรียกใช้แผนที่เมื่อโหลดหน้าเสร็จ
window.onload = initMap;
</script>

</body>

</html>