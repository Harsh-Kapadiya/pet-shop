<?php
include 'includes/header.php';

// Fetch all available pets
$pets = [];
try {
    $stmt = $pdo->query("SELECT * FROM pets WHERE status = 'Available' ORDER BY added_date DESC");
    $pets = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching pets: " . $e->getMessage());
}

// Handle adoption request
$adoption_error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adopt_pet'])) {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
    
    $pet_id = (int)$_POST['pet_id'];
    $user_id = $_SESSION['user']['id'];
    
    try {
        $pdo->beginTransaction();
        
        // Check if pet is still available with row lock
        $stmt = $pdo->prepare("SELECT status FROM pets WHERE pet_id = ? FOR UPDATE");
        $stmt->execute([$pet_id]);
        $pet = $stmt->fetch();
        
        if ($pet && $pet['status'] === 'Available') {
            // Insert adoption record
            $stmt = $pdo->prepare("INSERT INTO adoptions (user_id, pet_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $pet_id]);
            
            // Update pet status
            $stmt = $pdo->prepare("UPDATE pets SET status = 'Adopted' WHERE pet_id = ?");
            $stmt->execute([$pet_id]);
            
            $pdo->commit();
            header('Location: adopt.php?adopted=1');
            exit;
        } else {
            $adoption_error = "Sorry, this pet is no longer available for adoption.";
            $pdo->rollBack();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Error adopting pet: " . $e->getMessage());
        $adoption_error = "An error occurred. Please try again.";
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md);">
    <div class="section-title">
        <h2>Adopt a Loving Pet</h2>
        <p>Find your perfect companion from our available pets</p>
    </div>
    
    <?php if (isset($_GET['adopted']) && $_GET['adopted'] == 1): ?>
        <div style="background: #E6F7EB; color: var(--accent-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-xl); max-width: 600px; margin-left: auto; margin-right: auto;">
            <i class="fas fa-check-circle"></i> Congratulations! Your adoption request has been submitted. <a href="dashboard.php" style="color: var(--primary-green); font-weight: 600;">View your adoptions</a>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($adoption_error)): ?>
        <div style="background: #FEECEB; color: var(--accent-red); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-xl); max-width: 600px; margin-left: auto; margin-right: auto;">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($adoption_error); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($pets)): ?>
        <div class="content-grid">
            <?php foreach ($pets as $pet): ?>
                <div class="card" id="pet-<?php echo $pet['pet_id']; ?>">
                    <img src="https://images.unsplash.com/photo-<?php echo $pet['type'] == 'dog' ? '1543466835-00a7907e9de1' : '1514888286974-6c03e2ca1dba'; ?>?w=400&h=300&fit=crop" alt="<?php echo htmlspecialchars($pet['name']); ?>" class="card-image">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                        <p style="text-transform: capitalize;"><strong><?php echo htmlspecialchars($pet['type']); ?></strong> - <?php echo htmlspecialchars($pet['breed']); ?></p>
                        <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>
                        <p style="color: var(--medium-text); margin-bottom: var(--spacing-md);"><?php echo htmlspecialchars($pet['description']); ?></p>
                        <form method="post">
                            <input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">
                            <button type="submit" name="adopt_pet" class="btn">Adopt <?php echo htmlspecialchars($pet['name']); ?></button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center" style="padding: var(--spacing-xl);">No pets available for adoption at the moment. Please check back soon!</p>
    <?php endif; ?>
</section>

<?php include 'includes/footer.php'; ?>
