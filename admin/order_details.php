<?php
include 'includes/admin_header.php';
require_once '../includes/db_connect.php';

$orders = [];
$error = "";

// Fetch orders with customer + product info
try {
    $query = "
        SELECT 
            Orders.order_id,
            Customers.cname AS customer_name,
            Products.product_name,
            Orders.quantity,
            Orders.total_price,
            Orders.order_date
        FROM Orders
        INNER JOIN Customers ON Orders.cid = Customers.cid
        INNER JOIN Products ON Orders.product_id = Products.product_id
        ORDER BY Orders.order_date DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Error fetching orders.";
}
?>

<style>
    th {
        background: var(--primary-green);
        color: white;
    }

    td,
    th {
        padding: 14px;
        border-bottom: 1px solid var(--grey-300);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 12px;
        overflow: hidden;
    }

    h1 {
        color: var(--primary-green);
        font-family: var(--font-heading);
    }
</style>

<section style="padding: 40px;">
    <h1 style="text-align:center; margin-bottom: 30px;">Order Details</h1>

    <?php if (!empty($error)): ?>
        <p style="color: var(--accent-red); text-align:center;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <p style="text-align:center; color: gray;">No orders found.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>Order Date</th>
                        <th style="text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                            <td><?php echo $order['quantity']; ?></td>
                            <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo $order['order_date']; ?></td>

                            <td style="text-align:center;">
                                <a href="view_order.php?id=<?php echo $order['order_id']; ?>"
                                    style="background: var(--secondary-green); color: white; padding: 6px 12px; border-radius: 6px; text-decoration:none;">
                                    View
                                </a>

                                <a href="delete_order.php?id=<?php echo $order['order_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this order?');"
                                    style="background: var(--accent-red); color: white; padding: 6px 12px; border-radius: 6px; text-decoration:none;">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php include 'includes/admin_footer.php'; ?>