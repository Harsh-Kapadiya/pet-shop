<?php
include 'includes/admin_header.php';

// Fetch order details
try {
    $query = "
        SELECT 
            Orders.oid AS order_id,
            Customers.cname AS customer_name,
            Orders.total_amount,
            Orders.order_date,
            Orders.status
        FROM Orders
        JOIN Customers ON Orders.cid = Customers.cid
        ORDER BY Orders.order_date DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
}
?>

<section style="padding:40px;">
    <h1 style="text-align:center;margin-bottom:40px;">Order Details</h1>

    <?php if (empty($orders)): ?>
        <p style="text-align:center;color:gray;">No orders found.</p>
    <?php else: ?>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;background:white;border-radius:10px;box-shadow:0 4px 10px rgba(0,0,0,0.1);">
                <thead style="background:var(--primary-green);color:white;">
                    <tr>
                        <th style="padding:15px;text-align:left;">Order ID</th>
                        <th style="padding:15px;text-align:left;">Customer Name</th>
                        <th style="padding:15px;text-align:left;">Total Amount</th>
                        <th style="padding:15px;text-align:left;">Order Date</th>
                        <th style="padding:15px;text-align:left;">Status</th>
                        <th style="padding:15px;text-align:center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:15px;"><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td style="padding:15px;"><?php echo htmlspecialchars($order['customer_name']); ?></td>
                            <td style="padding:15px;">₹<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td style="padding:15px;"><?php echo htmlspecialchars($order['order_date']); ?></td>
                            <td style="padding:15px;">
                                <span style="padding:6px 12px;border-radius:8px;
                                    background:
                                    <?php 
                                        echo $order['status'] === 'Completed' ? '#C8E6C9' :
                                            ($order['status'] === 'Pending' ? '#FFF9C4' : '#FFCDD2');
                                    ?>;
                                    color:
                                    <?php 
                                        echo $order['status'] === 'Completed' ? '#256029' :
                                            ($order['status'] === 'Pending' ? '#7A6B00' : '#C62828');
                                    ?>;
                                    font-weight:600;">
                                    <?php echo htmlspecialchars($order['status']); ?>
                                </span>
                            </td>
                            <td style="padding:15px;text-align:center;">
                                <a href="view_order.php?id=<?php echo $order['order_id']; ?>" 
                                   style="background:var(--primary-green);color:white;padding:8px 15px;border-radius:6px;text-decoration:none;">
                                    View
                                </a>
                                <a href="delete_order.php?id=<?php echo $order['order_id']; ?>" 
                                   onclick="return confirm('Are you sure you want to delete this order?');"
                                   style="background:red;color:white;padding:8px 15px;border-radius:6px;text-decoration:none;">
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
