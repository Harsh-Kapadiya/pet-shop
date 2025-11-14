<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

session_start();

// ---------- CHECK LOGIN ----------
if (!isset($_SESSION['customer_id'])) {
    echo "<script>alert('Please login to adopt a pet.'); window.location.href='login.php';</script>";
    exit;
}

$customer_id = $_SESSION['customer_id'];
$success_message = '';
$error_message = '';


// ---------- WHEN USER CLICKS "ADOPT NOW" ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adopt'])) {

    $pet_id = $_POST['pet_id'] ?? null;

    if (!$pet_id) {
        $error_message = "Invalid pet selected.";
    } else {
        try {

            // CHECK IF USER ALREADY ADOPTED SAME PET
            $check = $pdo->prepare("SELECT * FROM Adoptions WHERE cid = ? AND pet_id = ?");
            $check->execute([$customer_id, $pet_id]);

            if ($check->rowCount() > 0) {
                $error_message = "You have already adopted this pet.";
            } else {

                // INSERT ADOPTION
                $stmt = $pdo->prepare("
                    INSERT INTO Adoptions (cid, pet_id)
                    VALUES (?, ?)
                ");
                $stmt->execute([$customer_id, $pet_id]);

                $success_message = "Adoption successful! Visit your adopted pets section.";
            }
        } catch (PDOException $e) {
            error_log("Adoption Insert Error: " . $e->getMessage());
            $error_message = "Something went wrong while processing adoption.";
        }
    }
}
?>

<section style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">

        <div style="text-align:center; margin-bottom: 30px;">
            <h2 style="font-size: 2rem; color: var(--primary-green);">Adoption Status</h2>
            <p style="color: var(--medium-text);">Here is your adoption update.</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div style="background:#FEECEC; padding:15px; color:#C62828; border-radius:8px; margin-bottom:20px; text-align:center;">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div style="background:#E7F8E9; padding:15px; color:#2E7D32; border-radius:8px; margin-bottom:20px; text-align:center;">
                <i class="fas fa-check-circle"></i>
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <div style="text-align:center; margin-top:30px;">
            <a href="adopt.php"
                style="background: var(--primary-green); padding: 12px 20px; color:white; border-radius:6px; text-decoration:none; margin-right:10px;">
                Back to Pets
            </a>

            <a href="adopted_pets.php"
                style="background: var(--accent-green); padding: 12px 20px; color:white; border-radius:6px; text-decoration:none;">
                View Adopted Pets
            </a>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>