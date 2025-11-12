<?php
include 'includes/admin_header.php';

// Fetching counts
try {
    $petsCount = $pdo->query("SELECT COUNT(*) FROM Pets")->fetchColumn();
    $productsCount = $pdo->query("SELECT COUNT(*) FROM Products")->fetchColumn();
} catch (PDOException $e) {
    $petsCount = $productsCount = 0;
}
?>

<div style="padding:40px;">
    <h1 style="text-align:center; margin-bottom:40px;">Welcome, Admin 👋</h1>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap:25px;">

        <div style="background:white;padding:25px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;">
            <h2>🐶 Pets</h2>
            <p style="font-size:30px;font-weight:bold;margin:10px 0;"><?php echo $petsCount; ?></p>
            <a href="view_pets.php" style="display:inline-block;margin:5px;padding:8px 15px;background:var(--primary-green);color:white;border-radius:5px;text-decoration:none;">View Pets</a>
            <a href="add_pet.php" style="display:inline-block;margin:5px;padding:8px 15px;background:#555;color:white;border-radius:5px;text-decoration:none;">Add Pet</a>
        </div>

        <div style="background:white;padding:25px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;">
            <h2>🛒 Products</h2>
            <p style="font-size:30px;font-weight:bold;margin:10px 0;"><?php echo $productsCount; ?></p>
            <a href="view_products.php" style="display:inline-block;margin:5px;padding:8px 15px;background:var(--primary-green);color:white;border-radius:5px;text-decoration:none;">View Products</a>
            <a href="add_product.php" style="display:inline-block;margin:5px;padding:8px 15px;background:#555;color:white;border-radius:5px;text-decoration:none;">Add Product</a>
        </div>

        <div style="background:white;padding:25px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;">
            <h2>⚙️ Admin Actions</h2>
            <p>Manage data, update content, and maintain your petshop efficiently.</p>
            <a href="logout.php" style="display:inline-block;margin-top:15px;padding:10px 20px;background:red;color:white;border-radius:5px;text-decoration:none;">Logout</a>
        </div>
    </div>
</div>

<?php include 'includes/admin_footer.php'; ?>
