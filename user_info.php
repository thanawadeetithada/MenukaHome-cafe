<?php
session_start();
include('include/header.php');
include('config.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// ดึงข้อมูลผู้ใช้จากตาราง users
$user_sql = "SELECT user_id, username, surname, email, phone, user_role, created_at FROM users";
$user_result = $conn->query($user_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลผู้ใช้</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

    header {
        background-color: #ffa500;
        text-align: center;
        padding: 10px;
    }

    header h1 {
        font-size: 1.75rem;
        margin: 0;
        font-family: 'Pacifico', cursive;
        color: white;
    }

    .content {
        padding: 20px;
    }

    .user-item {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
        background: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .user-item h3 {
        font-size: 1.2rem;
        color: #333;
    }

    .user-item p {
        margin: 0;
        font-size: 0.9rem;
        color: #555;
    }

    .edit-link {
        text-align: right;
    }
    </style>
</head>

<body>
    <header>
        <h1>ข้อมูลผู้ใช้</h1>
    </header>
    <div class="content">
        <?php if ($user_result->num_rows > 0): ?>
        <?php while ($user = $user_result->fetch_assoc()): ?>
        <div class="user-item">
            <h3><?php echo htmlspecialchars($user['username']) . ' ' . htmlspecialchars($user['surname']); ?></h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><strong>Role:</strong> <?php echo $user['user_role'] == 1 ? 'Admin' : 'User'; ?></p>
            <div class="edit-link">
                <a style="color: red;" href="delete_user.php?user_id=<?php echo $user['user_id']; ?>"
                    onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?')">ลบ</a>
                <a href="edit_user_info.php?user_id=<?php echo $user['user_id']; ?>">แก้ไข</a>
            </div>
        </div>
        <?php endwhile; ?>
        <?php else: ?>
        <p>No user data found.</p>
        <?php endif; ?>
    </div>
    <footer class="footer p-4">
        <a href="main.php">หน้าหลัก</a>
        <a href="edit_products.php">รายการอาหาร</a>
        <a href="user_info.php">ข้อมูล User</a>
    </footer>
</body>

</html>

<?php
$conn->close();
?>