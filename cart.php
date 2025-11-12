<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

// Start session if not started already
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];

    // Check if product exists in database
    $stmt = $pdo->prepare("SELECT * FROM Products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // If product is already in cart, increase quantity
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]['quantity']++;
        } else {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['product_id'],
                'name' => $product['product_name'],
                'price' => $product['price'],
                // 'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }
    header("Location: cart.php");
    exit;
}

// Update quantity
if (isset($_POST['update_quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'];

    if (isset($_SESSION['cart'][$product_id])) {
        if ($action === 'increase') {
            $_SESSION['cart'][$product_id]['quantity']++;
        } elseif ($action === 'decrease') {
            $_SESSION['cart'][$product_id]['quantity']--;
            if ($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// Remove item
if (isset($_POST['remove_item'])) {
    $product_id = (int)$_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = 0;
foreach ($cart as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<section class="cart-section" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container" style="max-width: 900px; margin: 0 auto;">
        <h2 style="text-align:center; margin-bottom:30px;">🛒 Your Cart</h2>

        <?php if (empty($cart)): ?>
            <p style="text-align:center; color:#666;">Your cart is empty. <a href="shop.php" style="color:#4CAF50;">Shop now</a></p>
        <?php else: ?>
            <table style="width:100%; border-collapse:collapse; background:#fff; box-shadow:0 4px 8px rgba(0,0,0,0.1); border-radius:10px; overflow:hidden;">
                <thead style="background:#4CAF50; color:#fff;">
                    <tr>
                        <th style="padding:15px;">Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr style="text-align:center; border-bottom:1px solid #eee;">
                            <td style="padding:15px; display:flex; align-items:center; gap:15px;">
                                <img src="assets/images/Products/<?php echo htmlspecialchars($product['images']); ?>"
                                    alt="<?php echo htmlspecialchars($product['product_name'] ?? ''); ?>"
                                    style="max-height: 100%; max-width: 100%; object-fit: cover;">  
                                <?php echo htmlspecialchars($item['name']); ?>
                            </td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="update_quantity" value="decrease" style="background:none; border:none; color:#4CAF50; font-size:18px; cursor:pointer;">−</button>
                                    <?php echo $item['quantity']; ?>
                                    <button type="submit" name="update_quantity" value="increase" style="background:none; border:none; color:#4CAF50; font-size:18px; cursor:pointer;">+</button>
                                </form>
                            </td>
                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="remove_item" style="background:#f44336; color:#fff; border:none; padding:5px 10px; border-radius:5px; cursor:pointer;">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align:right; margin-top:20px;">
                <h3>Total: ₹<?php echo number_format($total, 2); ?></h3>
                <button style="background:#4CAF50; color:#fff; border:none; padding:10px 20px; border-radius:8px; cursor:pointer;">Proceed to Checkout</button>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>