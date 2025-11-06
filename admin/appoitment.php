<?php
// admin/appointments.php
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/../includes/db_connect.php';

// mark as completed (optional)
if (isset($_GET['complete'])) {
    $id = (int)$_GET['complete'];
    $stmt = $pdo->prepare("UPDATE appointments SET status = 'Completed' WHERE appointment_id = ?");
    $stmt->execute([$id]);
    header('Location: appointments.php');
    exit;
}

$appointments = $pdo->query("SELECT a.*, u.cname as user_name, p.name as pet_name
    FROM appointments a
    LEFT JOIN customers u ON a.customer_id = u.cid
    LEFT JOIN pets p ON a.pet_id = p.pet_id
    ORDER BY a.date_time DESC")->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Appointments</title></head>
<body>
  <p><a href="dashboard.php">Back</a></p>
  <h2>Appointments</h2>
  <table border="1" cellpadding="6">
    <tr><th>ID</th><th>User</th><th>Pet</th><th>Date/Time</th><th>Status</th><th>Actions</th></tr>
    <?php foreach($appointments as $a): ?>
    <tr>
      <td><?php echo $a['appointment_id']; ?></td>
      <td><?php echo htmlspecialchars($a['user_name']); ?></td>
      <td><?php echo htmlspecialchars($a['pet_name']); ?></td>
      <td><?php echo htmlspecialchars($a['date_time']); ?></td>
      <td><?php echo htmlspecialchars($a['status']); ?></td>
      <td>
        <?php if($a['status'] !== 'Completed'): ?>
          <a href="appointments.php?complete=<?php echo $a['appointment_id']; ?>">Mark Completed</a>
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</body>
</html>
