<?php
include 'includes/header.php';

// Fetch all products
$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching products: " . $e->getMessage());
}

// Handle order placement
$order_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['buy_now'])) {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    
    $user_id = $_SESSION['user']['id'];
    $product_id = (int)$_POST['product_id'];
    $qty = max(1, (int)$_POST['quantity']);
    
    try {
        $pdo->beginTransaction();
        
        // Get product with stock check
        $stmt = $pdo->prepare("SELECT price, stock_quantity FROM products WHERE product_id = ? FOR UPDATE");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            // Validate stock availability
            if ($product['stock_quantity'] < $qty) {
                $order_error = "Sorry, only {$product['stock_quantity']} items available in stock.";
                $pdo->rollBack();
            } else {
                $total = $product['price'] * $qty;
                
                // Insert order
                $stmt = $pdo->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price) VALUES (?, ?, ?, ?)");
                $stmt->execute([$user_id, $product_id, $qty, $total]);
                
                // Decrement stock
                $stmt = $pdo->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
                $stmt->execute([$qty, $product_id]);
                
                $pdo->commit();
                header('Location: shop.php?ordered=1');
                exit;
            }
        } else {
            $order_error = "Product not found.";
            $pdo->rollBack();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error placing order: " . $e->getMessage());
        $order_error = "An error occurred. Please try again.";
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md);">
    <div class="section-title">
        <h2>Shop Pet Essentials</h2>
        <p>Premium quality products for your beloved pets</p>
    </div>
    
    <?php if (isset($_GET['ordered']) && $_GET['ordered'] == 1): ?>
        <div style="background: #E6F7EB; color: var(--accent-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-xl); max-width: 600px; margin-left: auto; margin-right: auto;">
            <i class="fas fa-check-circle"></i> Your order has been placed successfully! <a href="dashboard.php" style="color: var(--primary-green); font-weight: 600;">View your orders</a>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($order_error)): ?>
        <div style="background: #FEECEB; color: var(--accent-red); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-xl); max-width: 600px; margin-left: auto; margin-right: auto;">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($order_error); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($products)): ?>
        <div class="content-grid">
            <?php foreach ($products as $product): ?>
                <div class="card" id="product-<?php echo $product['product_id']; ?>">
                    <img src="https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=400&h=300&fit=crop" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-image">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p style="color: var(--medium-text); margin-bottom: var(--spacing-sm);"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p style="color: var(--primary-green); font-weight: 700; font-size: 1.5rem; margin-bottom: var(--spacing-md);">$<?php echo number_format($product['price'], 2); ?></p>
                        <p style="color: var(--light-text); font-size: 0.9rem; margin-bottom: var(--spacing-md);">In Stock: <?php echo $product['stock_quantity']; ?></p>
                        <form method="post" style="display: flex; align-items: center; gap: var(--spacing-sm); justify-content: center;">
                            <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" style="width: 70px; padding: 8px; border: 1px solid var(--grey-300); border-radius: 6px; text-align: center;">
                            <button type="submit" name="buy_now" class="btn">Buy Now</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center" style="padding: var(--spacing-xl);">No products available at the moment. Please check back soon!</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
