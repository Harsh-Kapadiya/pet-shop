<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

// Get logged-in customer ID
$customer_id = $_SESSION['customer_id'] ?? null;

// Message variables
$error_message = '';
$success_message = '';

// Handle adopt request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adopt'])) {

    if (!$customer_id) {
        $error_message = "Please login to adopt a pet.";
    } else {
        $pet_id = $_POST['pet_id'];

        try {
            // Check if already adopted by this user
            $check = $pdo->prepare("SELECT * FROM Adoptions WHERE cid = ? AND pet_id = ?");
            $check->execute([$customer_id, $pet_id]);

            if ($check->rowCount() > 0) {
                $error_message = "You have already adopted this pet.";
            } else {
                // Insert adoption (NO adoption_date because not in DB)
                $insert = $pdo->prepare("INSERT INTO Adoptions (cid, pet_id) VALUES (?, ?)");
                $insert->execute([$customer_id, $pet_id]);

                $success_message = "Congratulations! You have successfully adopted this pet.";
            }
        } catch (PDOException $e) {
            error_log("Adoption Insert Error: " . $e->getMessage());
            $error_message = "Something went wrong. Try again.";
        }
    }
}

// Fetch all pets
try {
    $stmt = $pdo->query("SELECT * FROM Pets ORDER BY pet_id ASC");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching pets: " . $e->getMessage());
}
?>

<section style="padding: var(--spacing-xxl) 0; background-color: var(--grey-100);">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">

        <div class="section-title" style="text-align: center; margin-bottom: var(--spacing-xl);">
            <h2 style="font-family: var(--font-heading); font-size: 2.2rem; color: var(--primary-green);">
                Adopt a Pet
            </h2>
            <p style="color: var(--medium-text);">Give a forever home to a loving pet.</p>
        </div>

        <!-- Success Message -->
        <?php if (!empty($success_message)): ?>
            <div style="background: #E8F9E9; color: var(--primary-green); padding: 15px; border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (!empty($error_message)): ?>
            <div style="background: #FDECEC; color: var(--accent-red); padding: 15px; border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($pets)): ?>
            <p style="text-align: center; color: var(--medium-text);">No pets available for adoption right now.</p>
        <?php else: ?>
            <div class="pet-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">

                <?php foreach ($pets as $pet): ?>
                    <div class="pet-card"
                        style="background: var(--white); border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); overflow: hidden;">

                        <!-- Pet Image -->
                        <div style="height: 250px; background: var(--grey-200); display: flex; justify-content: center; align-items: center;">
                            <img src="assets/images/pets/<?php echo htmlspecialchars($pet['images']); ?>"
                                alt="<?php echo htmlspecialchars($pet['name']); ?>"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <!-- Pet Info -->
                        <div style="padding: var(--spacing-md); text-align: center;">
                            <h3 style="font-size: 1.4rem; color: var(--primary-green); margin-bottom: 8px; font-family: var(--font-heading);">
                                <?php echo htmlspecialchars($pet['name']); ?>
                            </h3>

                            <p style="color: var(--medium-text);"><strong>Type:</strong> <?php echo htmlspecialchars($pet['type']); ?></p>
                            <p style="color: var(--medium-text);"><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                            <p style="color: var(--medium-text);"><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>

                            <p style="color: var(--accent-green); font-weight: 600; margin-top: 10px;">
                                Status: Available
                            </p>

                            <form method="POST" style="margin-top: 15px;">
                                <input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">

                                <button type="submit" name="adopt"
                                    style="background: var(--primary-green); color: var(--white);
                                           padding: 10px 18px; border: none; border-radius: 6px;
                                           cursor: pointer; font-weight: 600;">
                                    Adopt Now
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