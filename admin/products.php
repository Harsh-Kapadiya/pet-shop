<?php
// admin/products.php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db_connect.php';

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
    $stmt->execute([$id]);
    header('Location: products.php');
    exit;
}

$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Products</title></head>
<body>
  <p><a href="dashboard.php">Back</a> | <a href="product_add.php">Add Product</a></p>
  <h2>Products</h2>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>Name</th><th>Price</th><th>Actions</th></tr>
    <?php foreach($products as $pr): ?>
    <tr>
      <td><?php echo $pr['product_id']; ?></td>
      <td><?php echo htmlspecialchars($pr['name']); ?></td>
      <td><?php echo number_format($pr['price'],2); ?></td>
      <td><a href="product_edit.php?id=<?php echo $pr['product_id']; ?>">Edit</a> | <a href="products.php?delete=<?php echo $pr['product_id']; ?>" onclick="return confirm('Delete?')">Delete</a></td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
