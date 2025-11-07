<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// Fetch user info
try {
    $stmt = $pdo->prepare("SELECT * FROM Customers WHERE cid = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching user info: " . $e->getMessage());
}

// Fetch adoption history
try {
    $stmt = $pdo->prepare("
        SELECT a.adoption_date, p.name AS pet_name, p.type, p.breed 
        FROM Adoptions a
        JOIN Pets p ON a.pet_id = p.pet_id
        WHERE a.cid = ?
        ORDER BY a.adoption_date DESC
    ");
    $stmt->execute([$user_id]);
    $adoptions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $adoptions = [];
}

// Fetch appointments
try {
    $stmt = $pdo->prepare("
        SELECT b.appointment_id, b.time, b.pet_category, b.breed, d.doctor_name
        FROM Book_appointment b
        JOIN Doctors d ON b.doctor_id = d.doctor_id
        WHERE b.cid = ?
        ORDER BY b.time DESC
    ");
    $stmt->execute([$user_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $appointments = [];
}
?>

<section class="container" style="padding: 60px 20px;">
    <div style="max-width: 900px; margin: 0 auto;">
        <h2 style="font-size: 2rem; margin-bottom: 20px;">Welcome, <?php echo htmlspecialchars($user['cname']); ?> 👋</h2>
        <p style="color: #666;">Here’s your pet adoption and appointment activity summary.</p>

        <hr style="margin: 30px 0;">

        <!-- USER INFO -->
        <div style="background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 15px;">Your Profile</h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['ph_no']); ?></p>
        </div>

        <!-- ADOPTIONS -->
        <div style="background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 15px;">Your Adoptions 🐾</h3>
            <?php if (empty($adoptions)): ?>
                <p style="color: #777;">You haven’t adopted any pets yet.</p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 10px;">Pet Name</th>
                            <th>Type</th>
                            <th>Breed</th>
                            <th>Adopted On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($adoptions as $adopt): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($adopt['pet_name']); ?></td>
                                <td><?php echo htmlspecialchars($adopt['type']); ?></td>
                                <td><?php echo htmlspecialchars($adopt['breed']); ?></td>
                                <td><?php echo htmlspecialchars(date('d M Y', strtotime($adopt['adoption_date']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- APPOINTMENTS -->
        <div style="background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 3px 8px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 15px;">Your Appointments 🩺</h3>
            <?php if (empty($appointments)): ?>
                <p style="color: #777;">No appointments booked yet.</p>
            <?php else: ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f5f5f5;">
                            <th style="padding: 10px;">Doctor</th>
                            <th>Pet Type</th>
                            <th>Breed</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appt): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 10px;"><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($appt['pet_category']); ?></td>
                                <td><?php echo htmlspecialchars($appt['breed']); ?></td>
                                <td><?php echo htmlspecialchars(date('d M Y h:i A', strtotime($appt['time']))); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
