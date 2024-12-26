<?php
// Include database configuration file
include('config.php');

// Handle form submission
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_name = $_POST['category_name'];
    $updated_by = $_POST['updated_by'];
    $image_url = null;

    // Handle file upload
    if (!empty($_FILES['image_url']['name'])) {
        $target_dir = "uploads/";
        $file_name = basename($_FILES['image_url']['name']);
        $target_file = $target_dir . time() . "_" . $file_name; // Add timestamp to avoid filename conflict
        $upload_ok = true;

        // Check file type
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $valid_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_type, $valid_extensions)) {
            echo "Only JPG, JPEG, PNG & GIF files are allowed.";
            $upload_ok = false;
        }

        // Move file to the target directory
        if ($upload_ok) {
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_file)) {
                $image_url = $target_file;
            } else {
                echo "Error uploading the file.";
            }
        }
    }

    // Insert category into the categories table
   // Check if category_name exists
$stmt = $conn->prepare("SELECT category_id FROM categories WHERE category_name = ?");
$stmt->bind_param("s", $category_name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Category exists, update it
    $stmt->bind_result($category_id);
    $stmt->fetch();
    $stmt->close();

    // Update existing category (add any fields you want to update here)
    $stmt = $conn->prepare("UPDATE categories SET category_name = ? WHERE category_id = ?");
    $stmt->bind_param("si", $category_name, $category_id);
    if ($stmt->execute()) {
        echo "Category updated successfully.";
    } else {
        echo "Error updating category: " . $stmt->error;
    }
} else {
    // Category does not exist, insert a new one
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO categories (category_name) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    if ($stmt->execute()) {
        $category_id = $stmt->insert_id; // Get the newly inserted category_id
        echo "Category added successfully.";
    } else {
        echo "Error adding category: " . $stmt->error;
    }
}
$stmt->close();

// Insert product into the products table
    $stmt = $conn->prepare("INSERT INTO products (product_name, description, price, image_url, category_id, updated_by, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssdssi", $product_name, $description, $price, $image_url, $category_id, $updated_by);
    if ($stmt->execute()) {
        echo "Product and category added successfully.";
    } else {
        echo "Error adding product: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch all products from the database
$result = $conn->query("SELECT * FROM products");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
</head>

<body>
    <h1>Add Products</h1>

    <!-- Display products -->
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Image</th>
            <th>Category</th>
            <th>Updated By</th>
        </tr>
        <?php foreach ($products as $product): ?>
        <tr>
        <td><?= htmlspecialchars($product['product_id'] ?? '') ?></td>
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
<td><?= htmlspecialchars($product['updated_by'] ?? '') ?></td>

        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Add New Product and Category</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" required><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>

        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br>

        <label for="image_url">Upload Image:</label><br>
        <input type="file" id="image_url" name="image_url" accept="image/*"><br>

        <label for="category_name">Category Name:</label><br>
        <input type="text" id="category_name" name="category_name" required><br>

        <label for="updated_by">Updated By (User ID):</label><br>
        <input type="number" id="updated_by" name="updated_by"><br><br>

        <button type="submit" name="add_product">Add Product and Category</button>
    </form>

</body>

</html>