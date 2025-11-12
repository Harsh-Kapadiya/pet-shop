<?php
include 'includes/admin_header.php';

// Fetch counts
try {
    $petsCount = $pdo->query("SELECT COUNT(*) FROM Pets")->fetchColumn();
    $productsCount = $pdo->query("SELECT COUNT(*) FROM Products")->fetchColumn();
    $usersCount = $pdo->query("SELECT COUNT(*) FROM Customers")->fetchColumn();
} catch (PDOException $e) {
    $petsCount = $productsCount = $usersCount = 0;
}
?>

<section style="padding:40px;">
    <h1 style="text-align:center;margin-bottom:40px;">Admin Dashboard</h1>

    <!-- Top Stats Section -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:25px;margin-bottom:40px;">

        <div style="background:white;padding:25px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;">
            <h2>🐾 Pets</h2>
            <p style="font-size:32px;font-weight:bold;"><?php echo $petsCount; ?></p>
            <a href="view_pets.php" class="btn" style="background:var(--primary-green);color:white;padding:8px 15px;border-radius:8px;text-decoration:none;">Manage Pets</a>
        </div>

        <div style="background:white;padding:25px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;">
            <h2>🛍 Products</h2>
            <p style="font-size:32px;font-weight:bold;"><?php echo $productsCount; ?></p>
            <a href="view_products.php" class="btn" style="background:var(--primary-green);color:white;padding:8px 15px;border-radius:8px;text-decoration:none;">Manage Products</a>
        </div>

        <div style="background:white;padding:25px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);text-align:center;">
            <h2>👤 Customers</h2>
            <p style="font-size:32px;font-weight:bold;"><?php echo $usersCount; ?></p>
            <a href="view_customers.php" class="btn" style="background:var(--primary-green);color:white;padding:8px 15px;border-radius:8px;text-decoration:none;">View Customers</a>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div style="background:white;padding:30px;border-radius:15px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
        <h2 style="margin-bottom:20px;">Quick Actions</h2>
        <div style="display:flex;flex-wrap:wrap;gap:15px;">
            <a href="add_pet.php" class="btn" style="background:#4CAF50;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;">➕ Add New Pet</a>
            <a href="add_product.php" class="btn" style="background:#4CAF50;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;">➕ Add New Product</a>
            <a href="reports.php" class="btn" style="background:#333;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;">📊 View Reports</a>
            <a href="logout.php" class="btn" style="background:red;color:white;padding:12px 25px;border-radius:8px;text-decoration:none;">🚪 Logout</a>
        </div>
    </div>
</section>

<?php include 'includes/admin_footer.php'; ?>
