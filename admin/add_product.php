<?php
include 'includes/admin_header.php';
require_once '../includes/db_connect.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $pname = trim($_POST['pname']);
    $product_for = trim($_POST['product_for']);
    $price = floatval($_POST['price']);
    $stock_quantity = intval($_POST['stock_quantity']);
    $image = $_FILES['image']['name'];

    if (!$pname || !$product_for || !$price || !$stock_quantity || !$image) {
        $error = "Please fill out all fields.";
    } else {

        // Correct upload location
        $target_dir = "../assets/images/Products/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        // Unique filename
        $image_name = time() . "_" . basename($image);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO Products (product_name, product_for, price, stock_quantity, images)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([$pname, $product_for, $price, $stock_quantity, $image_name]);

                $success = "Product added successfully!";
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
            }

        } else {
            $error = "Failed to upload image.";
        }
    }
}
?>

<div class="card" style="max-width:700px;margin:auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align:center;margin-bottom:25px;color:var(--primary-green);">Add New Product 🛒</h2>

    <?php if ($success): ?>
        <p style="color:green;text-align:center;"><?php echo $success; ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:15px;">

        <label>Product Name:</label>
        <input type="text" name="pname" required>

        <label>Product For (Category):</label>
        <input type="text" name="product_for" placeholder="dog / cat / bird / fish" required>

        <label>Price (₹):</label>
        <input type="number" step="0.01" name="price" required>

        <label>Stock Quantity:</label>
        <input type="number" name="stock_quantity" required>

        <label>Upload Product Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit"
            style="background:#556b2f;color:white;padding:10px;border:none;border-radius:6px;cursor:pointer;">
            Add Product
        </button>

    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
s