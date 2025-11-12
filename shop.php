<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

// Handle search and filter
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

try {
    $query = "SELECT * FROM Products WHERE 1";
    $params = [];

    if (!empty($search)) {
        $query .= " AND product_name LIKE :search";
        $params[':search'] = "%$search%";
    }

    if (!empty($filter)) {
        $query .= " AND product_for = :filter";
        $params[':filter'] = $filter;
    }

    $query .= " ORDER BY product_id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
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

        <!-- Search and Filter -->
        <form method="GET" action="shop.php" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 30px;">
            <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>"
                style="padding: 10px 15px; border-radius: 6px; border: 1px solid #ccc; width: 250px;">

            <select name="filter" style="padding: 10px 15px; border-radius: 6px; border: 1px solid #ccc;">
                <option value="">All Categories</option>
                <option value="dog" <?php if ($filter === 'dog') echo 'selected'; ?>>Dog</option>
                <option value="cat" <?php if ($filter === 'cat') echo 'selected'; ?>>Cat</option>
                <option value="fish" <?php if ($filter === 'fish') echo 'selected'; ?>>Fish</option>
                <option value="bird" <?php if ($filter === 'bird') echo 'selected'; ?>>Bird</option>
                <option value="turtle" <?php if ($filter === 'turtle') echo 'selected'; ?>>Turtle</option>
            </select>

            <button type="submit" style="background: #4CAF50; color: #fff; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer;">
                Search
            </button>
        </form>

        <?php if (empty($products)): ?>
            <p style="text-align: center; color: #777;">No products available right now.</p>
        <?php else: ?>
            <div class="product-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
                <?php foreach ($products as $product): ?>
                    <div class="product-card"
                        style="background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.2s; position: relative;">

                        <!-- Product Image -->
                        <div style="height: 220px; background: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                            <?php if (empty($product['images'])): ?>
                                <img src="assets/images/Products/no-image.png"
                                    alt="No Image"
                                    style="max-height: 100%; max-width: 100%; object-fit: cover;">
                                
                            <?php else: ?>
                                <img src="assets/images/Products/<?php echo htmlspecialchars($product['images']); ?>"
                                    alt="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>"
                                    style="max-height: 100%; max-width: 100%; object-fit: cover;">

                                
                            <?php endif; ?>
                        </div>

                        <!-- Product Info -->
                        <div style="padding: 20px;">
                            <h3 style="font-size: 1.2rem; margin-bottom: 10px; color: #333;">
                                <?php echo htmlspecialchars($product['product_name'] ?? 'Unnamed Product'); ?>
                            </h3>
                            <p style="color: #888; margin-bottom: 5px;">
                                <strong>Category:</strong> <?php echo htmlspecialchars($product['product_for'] ?? 'N/A'); ?>
                            </p>
                            <p style="font-weight: bold; color: #4CAF50; font-size: 1.1rem; margin-bottom: 15px;">
                                ₹<?php echo number_format($product['price'] ?? 0, 2); ?>
                            </p>

                            <!-- Add to Cart -->
                            <form method="post" action="cart.php"
                                style="display: flex; justify-content: space-between; align-items: center;">
                                <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                <input type="number" name="quantity" value="1" min="1"
                                    style="width: 60px; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                                <button type="submit" name="add_to_cart"
                                    style="background: #4CAF50; color: #fff; border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer;">
                                    Add to Cart
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>