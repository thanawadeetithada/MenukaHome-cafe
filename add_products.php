<?php
session_start();
include('include/header.php');
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$is_admin = false;

$query = "SELECT user_role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($is_admin);
$stmt->fetch();
$stmt->close();

if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_name = $_POST['category_name'];
    $updated_by = $_POST['updated_by'];
    $image_url = null;

    if (!empty($_FILES['image_url']['name'])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['image_url']['name']);
        $target_file = $target_dir . time() . "_" . $file_name;
        $upload_ok = true;

        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_type, $valid_extensions)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            $upload_ok = false;
        }

        if ($upload_ok) {
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            } else {
                echo "Error uploading the file.";
            }
        }
    }

    $stmt = $conn->prepare("SELECT category_id FROM categories WHERE category_name = ?");
    $stmt->bind_param("s", $category_name);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($category_id);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
        $stmt->bind_param("si", $category_name, $category_id);
        if (!$stmt->execute()) {
            echo "Error updating category: " . $stmt->error;
        }
    } else {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            $category_id = $stmt->insert_id;
        } else {
            echo "Error adding category: " . $stmt->error;
        }
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, image_url, category_id, updated_by, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdssi", $product_name, $description, $price, $image_url, $category_id, $updated_by);
    if (!$stmt->execute()) {
        echo "Error adding product: " . $stmt->error;
    }
    $stmt->close();
}

$query = "SELECT * FROM products";
$result = $conn->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
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

    table {
        width: 100%;
        margin: 20px auto;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .btn-upload {
        text-align: center;
        margin-top: 20px;
    }

    footer {
        background-color: #ffc107;
        padding: 10px;
        text-align: center;
    }

    footer a {
        margin: 0 10px;
        color: #333;
        text-decoration: none;
    }

    .form-group {
        margin-bottom: 0.5rem;
    }

    button {
        margin-bottom: 2rem;
    }
    </style>
</head>

<body>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>ชื่อ</th>
                    <th>Description</th>
                    <th>ราคา</th>
                    <th>Image</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['product_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($product['description'] ?? '') ?></td>
                    <td><?= htmlspecialchars($product['price'] ?? '') ?></td>
                    <td>
                        <?php if (!empty($product['image_url'])): ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="Image" width="50">
                        <?php else: ?>
                        No Image
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['category_id'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <form action="" method="POST" enctype="multipart/form-data" class="w-75 mx-auto">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="product_name">ชื่อสินค้า</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="price">ราคา</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" required>
                </div>
                <div class="form-group col-md-6">
                    <label for="image_url">Upload Image</label>
                    <input type="file" id="image_url" name="image_url" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="category_name">Category</label>
                    <select id="category_name" name="category_name" class="form-control" required>
                        <option value="เบเกอรี่">เบเกอรี่</option>
                        <option value="เครื่องดื่ม">เครื่องดื่ม</option>
                    </select>
                </div>

                <!-- Hidden input for updated_by -->
                <input type="hidden" id="updated_by" name="updated_by" class="form-control"
                    value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
            </div>
            <button type="submit" name="add_product" class="btn btn-primary btn-block">เพิ่มข้อมูลสินค้า</button>
        </form>

    </div>

    <footer class="footer p-4">
        <a href="main.php">หน้าหลัก</a>
        <a href="edit_products.php">รายการอาหาร</a>
        <a href="user_info.php">ข้อมูล User</a>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>