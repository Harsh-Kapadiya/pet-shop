<?php
include 'includes/header.php';

// Fetch doctors
$doctors = [];
try {
    $stmt = $pdo->query("SELECT * FROM doctors ORDER BY doctor_name");
    $doctors = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching doctors: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book'])) {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }

    $user_id = $_SESSION['user']['id'];
    $doctor_id = (int)$_POST['doctor_id'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $pet_category = $_POST['pet_category'];
    $breed = trim($_POST['breed']);

    try {
        $stmt = $pdo->prepare("INSERT INTO appointments (user_id, doctor_id, appointment_date, appointment_time, pet_category, breed) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $doctor_id, $date, $time, $pet_category, $breed]);
        header('Location: appointment.php?booked=1');
        exit;
    } catch (PDOException $e) {
        error_log("Error booking appointment: " . $e->getMessage());
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md); max-width: 700px;">
    <div class="section-title">
        <h2>Book a Vet Appointment</h2>
        <p>Schedule a consultation with our expert veterinarians</p>
    </div>

    <?php if (isset($_GET['booked']) && $_GET['booked'] == 1): ?>
        <div style="background: #E6F7EB; color: var(--accent-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-xl);">
            <i class="fas fa-check-circle"></i> Your appointment has been booked successfully! We look forward to seeing you.
        </div>
    <?php endif; ?>

    <form method="post" style="background: white; padding: var(--spacing-xl); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="margin-bottom: var(--spacing-md);">
            <label for="doctor_id" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Select Doctor:</label>
            <select name="doctor_id" id="doctor_id" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
                <option value="">Choose a veterinarian...</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?php echo $doctor['doctor_id']; ?>">
                        <?php echo htmlspecialchars($doctor['doctor_name']); ?>
                        <?php if (!empty($doctor['specialization'])) echo ' - ' . htmlspecialchars($doctor['specialization']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: var(--spacing-md);">
            <label for="date" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Appointment Date:</label>
            <input type="date" name="date" id="date" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;" min="<?php echo date('Y-m-d'); ?>">
        </div>

        <div style="margin-bottom: var(--spacing-md);">
            <label for="time" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Preferred Time:</label>
            <input type="time" name="time" id="time" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>

        <div style="margin-bottom: var(--spacing-md);">
            <label for="pet_category" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Pet Type:</label>
            <select name="pet_category" id="pet_category" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
                <option value="">Select pet type...</option>
                <option value="dog">Dog</option>
                <option value="cat">Cat</option>
                <option value="bird">Bird</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div style="margin-bottom: var(--spacing-lg);">
            <label for="breed" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Breed (if known):</label>
            <input type="text" name="breed" id="breed" style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>

        <button type="submit" name="book" class="btn btn-secondary" style="width: 100%;">Book Appointment</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
