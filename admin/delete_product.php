<?php
// delete_product.php
session_start();
require_once '../includes/db_connect.php';

// simple admin check (adjust according to your auth)
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: manage_products.php?error=' . urlencode('Invalid product id.'));
    exit;
}

$productId = (int) $_GET['id'];

try {
    // 1) fetch product row so we can remove image file after successful delete
    $stmt = $pdo->prepare("SELECT images FROM Products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: manage_products.php?error=' . urlencode('Product not found.'));
        exit;
    }

    // 2) delete DB row
    $del = $pdo->prepare("DELETE FROM Products WHERE product_id = ?");
    $del->execute([$productId]);

    if ($del->rowCount() === 0) {
        // No rows deleted (unexpected)
        header('Location: manage_products.php?error=' . urlencode('Failed to delete product from database.'));
        exit;
    }

    // 3) remove image file if exists
    if (!empty($product['images'])) {
        // build absolute path (admin folder -> go up one level to project root)
        $imagePath = realpath(__DIR__ . 'assets/images/Products/' . $product['images']);
        // extra safety: ensure file is inside the products directory
        $allowedDir = realpath(__DIR__ . 'assets/images/Products/');

        if ($imagePath && strpos($imagePath, $allowedDir) === 0 && file_exists($imagePath)) {
            @unlink($imagePath); // ignore unlink errors
        }
    }

    header('Location: manage_products.php?deleted=1');
    exit;
} catch (PDOException $e) {
    // common cause: foreign key constraint (orders referencing product)
    $sqlState = $e->getCode();
    if ($sqlState === '23000' || stripos($e->getMessage(), 'foreign key') !== false) {
        $msg = "Cannot delete product: it is referenced by other records (orders/adoptions). Remove related records first.";
    } else {
        $msg = "Database error: " . $e->getMessage();
    }
    header('Location: manage_products.php?error=' . urlencode($msg));
    exit;
}
