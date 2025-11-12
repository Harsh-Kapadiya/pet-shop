<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

$customer_id = $_SESSION['customer_id'] ?? null;
$error_message = '';
$success_message = '';

if (!$customer_id) {
    $error_message = "Please login to view your adopted pets.";
} else {
    try {
        // Fetch user's adopted pets
        $query = "
            SELECT a.adoption_id, a.adoption_date, 
                   p.pet_id, p.name AS pet_name, p.type, p.breed, p.age
            FROM Adoptions a
            INNER JOIN Pets p ON a.pet_id = p.pet_id
            WHERE a.cid = :cid
            ORDER BY a.adoption_date DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':cid' => $customer_id]);
        $adopted_pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Adoption fetch error: " . $e->getMessage());
        $error_message = "Could not load adopted pets.";
        $adopted_pets = [];
    }
}

// Handle payment simulation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now'])) {
    $pet_id = $_POST['pet_id'];
    $success_message = "Payment successful for Pet ID #{$pet_id}. Thank you for supporting adoption!";
}
?>

<section style="padding: var(--spacing-xxl) 0; background-color: var(--grey-100);">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">

        <div class="section-title" style="text-align:center; margin-bottom: var(--spacing-lg);">
            <h2 style="font-size: 2rem; color: var(--primary-green); font-family: var(--font-heading);">
                Your Adopted Pets
            </h2>
            <p style="color: var(--medium-text);">Manage your adopted pets and complete your adoption payments.</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div style="background: #FEECEB; color: var(--accent-red); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
                <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div style="background: #E8F9E9; color: var(--primary-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($adopted_pets)): ?>
            <p style="text-align: center; color: var(--medium-text);">You haven't adopted any pets yet.</p>
        <?php else: ?>
            <div class="adopted-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
                <?php foreach ($adopted_pets as $pet): ?>
                    <div class="adopted-card"
                        style="background: var(--white); border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.2s;">
                        
                        <!-- Pet Image -->
                        <div style="height: 220px; background: var(--grey-200); display: flex; justify-content: center; align-items: center;">
                            <img src="assets/images/pets/<?php echo strtolower($pet['type']); ?>.jpg"
                                 alt="<?php echo htmlspecialchars($pet['pet_name']); ?>"
                                 style="max-height: 100%; max-width: 100%; object-fit: cover;">
                        </div>

                        <!-- Pet Info -->
                        <div style="padding: var(--spacing-md); text-align: center;">
                            <h3 style="font-size: 1.3rem; color: var(--primary-green); margin-bottom: var(--spacing-xs);">
                                <?php echo htmlspecialchars($pet['pet_name']); ?>
                            </h3>
                            <p style="color: var(--medium-text); margin: 5px 0;">
                                <strong>Type:</strong> <?php echo htmlspecialchars($pet['type']); ?>
                            </p>
                            <p style="color: var(--medium-text); margin: 5px 0;">
                                <strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?>
                            </p>
                            <p style="color: var(--medium-text); margin: 5px 0;">
                                <strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years
                            </p>
                            <p style="color: var(--medium-text); margin: 8px 0;">
                                <strong>Adopted On:</strong> <?php echo htmlspecialchars($pet['adoption_date']); ?>
                            </p>

                            <!-- Payment Form -->
                            <form method="POST" style="margin-top: var(--spacing-sm);">
                                <input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">
                                <button type="submit" name="pay_now"
                                    style="background: var(--accent-green); color: var(--white); border: none; padding: var(--spacing-sm) var(--spacing-md); border-radius: 6px; cursor: pointer; font-weight: 600;">
                                    Pay Now
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
