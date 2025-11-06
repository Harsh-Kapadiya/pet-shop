<?php
require '../includes/db_connect.php';
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user']['role']!=='admin'){
header('Location: /petshop/login.php'); exit;
}
include '../includes/header.php';
$totalPets = $pdo->query('SELECT COUNT(*) FROM pets')->fetchColumn();
$totalProducts = $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn();
$totalOrders = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
$totalUsers = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
?>
<section class="page">
 <h2>Admin Dashboard</h2>
 <div class="stats">
 <div class="stat">Pets: <strong><?php echo $totalPets; ?></strong></div>
 <div class="stat">Products: <strong><?php echo $totalProducts; ?></strong></
div>
 <div class="stat">Orders: <strong><?php echo $totalOrders; ?></strong></div>
 <div class="stat">Users: <strong><?php echo $totalUsers; ?></strong></div>
 </div>
 <p><a href="pets.php" class="btn-small">Manage Pets</a> <a href="products.php"
class="btn-small">Manage Products</a></p>
</section>
<?php include '../includes/footer.php'; ?>