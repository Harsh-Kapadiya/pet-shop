<?php
// admin/check_appointment.php
session_start();
require_once '../includes/db_connect.php';
include 'includes/admin_header.php';

// simple admin auth check
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit;
}

$success = $error = '';

// Handle deletion (cancel) via POST to avoid unsafe GET deletes
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_appointment'])) {
    $apptId = intval($_POST['appointment_id'] ?? 0);
    if ($apptId <= 0) {
        $error = "Invalid appointment id.";
    } else {
        try {
            $del = $pdo->prepare("DELETE FROM Book_appointment WHERE appointment_id = ?");
            $del->execute([$apptId]);

            if ($del->rowCount() > 0) {
                $success = "Appointment #{$apptId} cancelled successfully.";
            } else {
                $error = "Appointment not found or already removed.";
            }
        } catch (PDOException $e) {
            $error = "Database error while deleting appointment: " . $e->getMessage();
        }
    }
}

// Fetch today's appointments and future appointments
try {
    // Today's appointments (date = today)
    $todayStmt = $pdo->prepare("
        SELECT b.appointment_id, b.time, b.pet_category, b.breed, b.pet_id, b.cid,
               d.doctor_id, d.doctor_name, d.specialization,
               p.name AS pet_name,
               c.cname AS customer_name, c.email AS customer_email, c.ph_no AS customer_phone
        FROM Book_appointment b
        LEFT JOIN Doctors d ON b.doctor_id = d.doctor_id
        LEFT JOIN Pets p ON b.pet_id = p.pet_id
        LEFT JOIN Customers c ON b.cid = c.cid
        WHERE DATE(b.time) = CURDATE()
        ORDER BY b.time ASC
    ");
    $todayStmt->execute();
    $todayAppointments = $todayStmt->fetchAll(PDO::FETCH_ASSOC);

    // Future appointments (date > today)
    $futureStmt = $pdo->prepare("
        SELECT b.appointment_id, b.time, b.pet_category, b.breed, b.pet_id, b.cid,
               d.doctor_id, d.doctor_name, d.specialization,
               p.name AS pet_name,
               c.cname AS customer_name, c.email AS customer_email, c.ph_no AS customer_phone
        FROM Book_appointment b
        LEFT JOIN Doctors d ON b.doctor_id = d.doctor_id
        LEFT JOIN Pets p ON b.pet_id = p.pet_id
        LEFT JOIN Customers c ON b.cid = c.cid
        WHERE DATE(b.time) > CURDATE()
        ORDER BY b.time ASC
    ");
    $futureStmt->execute();
    $futureAppointments = $futureStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Failed to load appointments: " . $e->getMessage();
    $todayAppointments = $futureAppointments = [];
}
?>

<section style="padding:28px; background:var(--grey-100); min-height:60vh;">
    <div style="max-width:1200px; margin:0 auto;">
        <h1 style="font-family:var(--font-heading); color:var(--primary-green); margin-bottom:6px;">Appointments</h1>
        <p style="color:var(--medium-text); margin-bottom:20px;">View and manage today's and upcoming appointments.</p>

        <?php if ($success): ?>
            <div style="background:#E8F9E9;color:var(--primary-green);padding:12px;border-radius:8px;margin-bottom:16px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div style="background:#FFECEC;color:var(--accent-red);padding:12px;border-radius:8px;margin-bottom:16px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Today's Appointments -->
        <h2 style="color:var(--dark-text); margin-top:12px; margin-bottom:12px;">Today's Appointments</h2>

        <?php if (empty($todayAppointments)): ?>
            <p style="color:var(--medium-text); margin-bottom:20px;">No appointments scheduled for today.</p>
        <?php else: ?>
            <div style="overflow-x:auto; margin-bottom:24px;">
                <table style="width:100%; border-collapse:collapse; background:var(--white); border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.04);">
                    <thead style="background:var(--primary-green); color:#fff;">
                        <tr>
                            <th style="padding:12px; text-align:left;">ID</th>
                            <th style="padding:12px; text-align:left;">Time</th>
                            <th style="padding:12px; text-align:left;">Doctor</th>
                            <th style="padding:12px; text-align:left;">Specialization</th>
                            <th style="padding:12px; text-align:left;">Pet</th>
                            <th style="padding:12px; text-align:left;">Type / Breed</th>
                            <th style="padding:12px; text-align:left;">Customer</th>
                            <th style="padding:12px; text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todayAppointments as $a): ?>
                            <tr style="border-bottom:1px solid var(--grey-200);">
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['appointment_id']); ?></td>
                                <td style="padding:12px;"><?php echo date('d M Y, h:i A', strtotime($a['time'])); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['doctor_name'] ?? '—'); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['specialization'] ?? '—'); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['pet_name'] ?? '—'); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars(($a['pet_category'] ?? $a['type'] ?? '—') . ' / ' . ($a['breed'] ?? '—')); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['customer_name'] ?? ($a['customer_email'] ?? '—')); ?></td>
                                <td style="padding:12px; text-align:center;">
                                    <!-- View details link (optional) -->
                                    <a href="view_appointment.php?id=<?php echo $a['appointment_id']; ?>" style="display:inline-block;padding:8px 12px;border-radius:6px;background:var(--primary-green);color:#fff;text-decoration:none;margin-right:6px;">View</a>

                                    <!-- Delete (Cancel) form -->
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Cancel this appointment?');">
                                        <input type="hidden" name="appointment_id" value="<?php echo $a['appointment_id']; ?>">
                                        <button type="submit" name="delete_appointment" style="padding:8px 12px;border-radius:6px;background:#dc3545;color:#fff;border:none;cursor:pointer;">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>


        <!-- Upcoming Appointments -->
        <h2 style="color:var(--dark-text); margin-top:10px; margin-bottom:12px;">Upcoming Appointments</h2>

        <?php if (empty($futureAppointments)): ?>
            <p style="color:var(--medium-text);">No upcoming appointments.</p>
        <?php else: ?>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; background:var(--white); border-radius:10px; box-shadow:0 6px 20px rgba(0,0,0,0.04);">
                    <thead style="background:var(--primary-green); color:#fff;">
                        <tr>
                            <th style="padding:12px; text-align:left;">ID</th>
                            <th style="padding:12px; text-align:left;">Date & Time</th>
                            <th style="padding:12px; text-align:left;">Doctor</th>
                            <th style="padding:12px; text-align:left;">Specialization</th>
                            <th style="padding:12px; text-align:left;">Pet</th>
                            <th style="padding:12px; text-align:left;">Type / Breed</th>
                            <th style="padding:12px; text-align:left;">Customer</th>
                            <th style="padding:12px; text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($futureAppointments as $a): ?>
                            <tr style="border-bottom:1px solid var(--grey-200);">
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['appointment_id']); ?></td>
                                <td style="padding:12px;"><?php echo date('d M Y, h:i A', strtotime($a['time'])); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['doctor_name'] ?? '—'); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['specialization'] ?? '—'); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['pet_name'] ?? '—'); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars(($a['pet_category'] ?? $a['type'] ?? '—') . ' / ' . ($a['breed'] ?? '—')); ?></td>
                                <td style="padding:12px;"><?php echo htmlspecialchars($a['customer_name'] ?? ($a['customer_email'] ?? '—')); ?></td>
                                <td style="padding:12px; text-align:center;">
                                    <a href="view_appointment.php?id=<?php echo $a['appointment_id']; ?>" style="display:inline-block;padding:8px 12px;border-radius:6px;background:var(--primary-green);color:#fff;text-decoration:none;margin-right:6px;">View</a>
                                    <form method="POST" style="display:inline-block;" onsubmit="return confirm('Cancel this appointment?');">
                                        <input type="hidden" name="appointment_id" value="<?php echo $a['appointment_id']; ?>">
                                        <button type="submit" name="delete_appointment" style="padding:8px 12px;border-radius:6px;background:#dc3545;color:#fff;border:none;cursor:pointer;">
                                            Cancel
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php include 'includes/admin_footer.php'; ?>