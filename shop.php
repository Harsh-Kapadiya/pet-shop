<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

// 🧩 Fetch all products (fixing column name issue)
try {
    $stmt = $pdo->query("SELECT * FROM Products ORDER BY product_id ASC");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching products: " . $e->getMessage());
}
?>

<section class="shop-section" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div class="section-title" style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 2rem; margin-bottom: 10px;">Pet Shop</h2>
            <p style="color: #666;">Find food, toys, and accessories for your lovely pets</p>
        </div>

        <?php if (empty($products)): ?>
            <p style="text-align: center; color: #777;">No products available right now.</p>
        <?php else: ?>
            <div class="product-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
                <?php foreach ($products as $product): ?>
                    <div class="product-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.2s;">
                        <div style="height: 220px; background: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                            <?php if (!empty($product['image'])): ?>
                                <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" style="max-height: 100%; max-width: 100%; object-fit: cover;">
                            <?php else: ?>
                                <img src="assets/images/no-image.png" alt="No Image" style="max-height: 100%; max-width: 100%; object-fit: cover;">
                            <?php endif; ?>
                        </div>
                        <div style="padding: 20px;">
                            <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #333;"><?php echo htmlspecialchars($product['name'] ?? 'Unnamed Product'); ?></h3>
                            <p style="color: #888; margin-bottom: 5px;"><strong>Category:</strong> <?php echo htmlspecialchars($product['category'] ?? 'N/A'); ?></p>
                            <p style="color: #666; font-size: 0.9rem; margin-bottom: 10px;"><?php echo htmlspecialchars($product['description'] ?? ''); ?></p>
                            <p style="font-weight: bold; color: #4CAF50; font-size: 1.1rem; margin-bottom: 15px;">₹<?php echo number_format($product['price'] ?? 0, 2); ?></p>

                            <form method="post" action="cart.php" style="display: flex; justify-content: center;">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <button type="submit" name="add_to_cart" style="background: #4CAF50; color: #fff; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer;">Add to Cart</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
