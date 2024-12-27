<?php
session_start();
include('include/header.php');
include('config.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// ตรวจสอบว่ามีการส่ง user_id มาหรือไม่
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    echo "Invalid User ID.";
    exit;
}

// ดึงข้อมูลผู้ใช้เพื่อแสดงในฟอร์ม
$sql = "SELECT user_id, username, surname, email, phone, user_role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();

// ตรวจสอบว่าฟอร์มถูกส่ง
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $surname = isset($_POST['surname']) ? trim($_POST['surname']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $user_role = isset($_POST['user_role']) ? intval($_POST['user_role']) : 0;

    // ตรวจสอบข้อมูล
    if ($username && $surname && $email && $phone) {
        $update_sql = "UPDATE users SET username = ?, surname = ?, email = ?, phone = ?, user_role = ? WHERE user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssssii", $username, $surname, $email, $phone, $user_role, $user_id);

        if ($update_stmt->execute()) {
            echo "User updated successfully.";
            header("Location: user_info.php");
            exit;
        } else {
            echo "Error updating user: " . $update_stmt->error;
        }
    } else {
        echo "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้</title>
    <style>
    body {
        font-family: 'Prompt', sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f9f9f9;
    }

    .container {
        max-width: 500px;
        margin: auto;
        padding: 20px;
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #333;
    }

    label {
        display: block;
        margin-bottom: 10px;
        font-size: 1rem;
        color: #555;
    }

    input,
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    .cart-button {
        background-color: #ffa500;
        color: white;
        text-align: center;
        padding: 10px;
        font-size: 1.25rem;
        font-weight: bold;
        border: none;
        border-radius: 1.5rem;
        margin-top: 20px;
        width: 100%;
        cursor: pointer;
        outline: none;
    }

    .cart-button:hover {
        background-color: rgba(255, 166, 0, 0.8);
    }

    .cart-button:focus {
        outline: none;
        border: none;
    }

    </style>
</head>

<body>
    <div class="container">
        <h1>แก้ไขข้อมูลผู้ใช้</h1>
        <form action="" method="POST">
            <label for="username">ชื่อ</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                required>

            <label for="surname">นามสกุล</label>
            <input type="text" id="surname" name="surname" value="<?php echo htmlspecialchars($user['surname']); ?>"
                required>

            <label for="email">อีเมล</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="phone">เบอร์โทร</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

            <label for="user_role">บทบาท</label>
            <select id="user_role" name="user_role" required>
                <option value="0" <?php echo $user['user_role'] == 0 ? 'selected' : ''; ?>>User</option>
                <option value="1" <?php echo $user['user_role'] == 1 ? 'selected' : ''; ?>>Admin</option>
            </select>

            <button class="cart-button" type="submit">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
</body>

</html>

<?php
$conn->close();
?>
