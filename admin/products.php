<?php
// admin/products.php

session_start();
require_once '../includes/db_connect.php';

// 🧩 Fetch all products
try {
    $stmt = $pdo->query("SELECT * FROM Products ORDER BY product_id");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}

// 🧩 Handle Add Product
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $description = $_POST['description'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Products (name, category, price, stock, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $stock, $description]);
        header("Location: products.php?msg=added");
        exit;
    } catch (PDOException $e) {
        die("Error adding product: " . $e->getMessage());
    }
}

// 🧩 Handle Delete Product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM Products WHERE product_id = ?");
        $stmt->execute([$id]);
        header("Location: products.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error deleting product: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products | Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 1100px; margin: 40px auto; background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px 12px; text-align: center; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        form { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 30px; justify-content: center; }
        input, select, textarea, button { padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; }
        textarea { resize: none; width: 100%; max-width: 400px; }
        button { background-color: #4CAF50; color: white; cursor: pointer; }
        button:hover { background-color: #43a047; }
        .delete-btn { background-color: #e74c3c; color: white; text-decoration: none; padding: 6px 10px; border-radius: 5px; }
        .delete-btn:hover { background-color: #c0392b; }
        .msg { text-align: center; background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 6px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Products 🛒</h1>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg">
            <?php 
                if ($_GET['msg'] == 'added') echo "✅ Product added successfully!";
                elseif ($_GET['msg'] == 'deleted') echo "🗑️ Product deleted successfully!";
            ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price (₹)</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?php echo $p['product_id']; ?></td>
                <td><?php echo htmlspecialchars($p['name']); ?></td>
                <td><?php echo htmlspecialchars($p['category']); ?></td>
                <td><?php echo number_format($p['price'], 2); ?></td>
                <td><?php echo $p['stock']; ?></td>
                <td><?php echo htmlspecialchars($p['description']); ?></td>
                <td>
                    <a href="products.php?delete=<?php echo $p['product_id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2 style="margin-top: 40px; color: #333;">Add New Product</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required>
        <select name="category" required>
            <option value="">Select Category</option>
            <option value="Food">Food</option>
            <option value="Accessories">Accessories</option>
            <option value="Toys">Toys</option>
            <option value="Medicine">Medicine</option>
            <option value="Other">Other</option>
        </select>
        <input type="number" name="price" placeholder="Price (₹)" step="0.01" required>
        <input type="number" name="stock" placeholder="Stock" min="0" required>
        <textarea name="description" placeholder="Description" rows="2"></textarea>
        <button type="submit" name="add_product">Add Product</button>
    </form>
</div>

</body>
</html>
