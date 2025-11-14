<?php
include 'includes/admin_header.php';
require_once '../includes/db_connect.php';

// Fetch all products
try {
    $stmt = $pdo->prepare("SELECT * FROM Products ORDER BY product_id DESC");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $products = [];
}
?>

<div style="padding:40px;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h2 style="color:var(--primary-green);">Manage Products 🛒</h2>
        <a href="add_product.php"
            style="
                background:var(--primary-green);
                color:white;
                padding:10px 18px;
                border-radius:6px;
                text-decoration:none;
                font-weight:600;
            ">
            + Add New Product
        </a>
    </div>

    <?php if (empty($products)): ?>
        <p style="text-align:center; color:gray;">No products found.</p>

    <?php else: ?>
        <div style="overflow-x:auto;">
            <table
                style="
                    width:100%;
                    border-collapse:collapse;
                    background:white;
                    border-radius:12px;
                    overflow:hidden;
                    box-shadow:0 4px 12px rgba(0,0,0,0.1);
                ">
                <thead style="background:var(--primary-green); color:white;">
                    <tr>
                        <th style="padding:14px;">Image</th>
                        <th style="padding:14px; text-align:left;">Product Name</th>
                        <th style="padding:14px; text-align:left;">Category</th>
                        <th style="padding:14px; text-align:left;">Price</th>
                        <th style="padding:14px; text-align:left;">Stock</th>
                        <th style="padding:14px; text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr style="border-bottom:1px solid #eee;">
                            
                            <!-- Image -->
                            <td style="padding:12px; text-align:center;">
                                <img src="../assets/images/Products/<?php echo htmlspecialchars($product['images']); ?>"
                                     alt=""
                                     style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
                            </td>

                            <!-- Name -->
                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </td>

                            <!-- Category -->
                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($product['product_for']); ?>
                            </td>

                            <!-- Price -->
                            <td style="padding:12px;">
                                ₹<?php echo number_format($product['price'], 2); ?>
                            </td>

                            <!-- Stock -->
                            <td style="padding:12px;">
                                <?php echo htmlspecialchars($product['stock_quantity']); ?>
                            </td>

                            <!-- Action Buttons -->
                            <td style="padding:12px; text-align:center;">

                                <a href="edit_product.php?id=<?php echo $product['product_id']; ?>"
                                    style="
                                        background:var(--secondary-green);
                                        color:white;
                                        padding:8px 14px;
                                        border-radius:6px;
                                        text-decoration:none;
                                        margin-right:5px;
                                        display:inline-block;
                                    ">
                                    Edit
                                </a>

                                <a href="delete_product.php?id=<?php echo $product['product_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this product?');"
                                    style="
                                        background:#d9534f;
                                        color:white;
                                        padding:8px 14px;
                                        border-radius:6px;
                                        text-decoration:none;
                                        display:inline-block;
                                    ">
                                    Delete
                                </a>

                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/admin_footer.php'; ?>
