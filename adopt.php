<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';

// 🐾 Fetch available pets from database
try {
    $stmt = $pdo->query("SELECT * FROM Pets ORDER BY pet_id ASC");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching pets: " . $e->getMessage());
}
?>

<section class="adopt-section" style="padding: 60px 0; background-color: #f9f9f9;">
    <div class="container" style="max-width: 1200px; margin: 0 auto;">
        <div class="section-title" style="text-align: center; margin-bottom: 40px;">
            <h2 style="font-size: 2rem; margin-bottom: 10px;">Adopt a Pet</h2>
            <p style="color: #666;">Give a loving home to a furry friend 🐶🐱🐰</p>
        </div>

        <?php if (empty($pets)): ?>
            <p style="text-align: center; color: #777;">No pets available for adoption right now.</p>
        <?php else: ?>
            <div class="pet-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;">
                <?php foreach ($pets as $pet): ?>
                    <div class="pet-card" style="background: #fff; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); overflow: hidden; transition: transform 0.2s;">
                        <div style="height: 250px; background: #f0f0f0; display: flex; justify-content: center; align-items: center;">
                            <?php if (!empty($pet['images'])): ?>
                                <img src="assets/images/pets/<?php echo htmlspecialchars($pet['images']); ?>" alt="<?php echo htmlspecialchars($pet['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <img src="assets/images/pets/no-image.jpg" alt="No Image" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php endif; ?>
                        </div>

                        <div style="padding: 20px;">
                            <h3 style="font-size: 1.3rem; margin-bottom: 8px; color: #333;"><?php echo htmlspecialchars($pet['name']); ?></h3>
                            <p style="margin-bottom: 5px; color: #666;"><strong>Type:</strong> <?php echo htmlspecialchars($pet['type']); ?></p>
                            <p style="margin-bottom: 5px; color: #666;"><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></p>
                            <p style="margin-bottom: 5px; color: #666;"><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years</p>
                            <p style="margin-bottom: 15px; color: #4CAF50;"><strong>Status:</strong> Available</p>

                            <form method="post" action="adopt_request.php" style="text-align: center;">
                                <input type="hidden" name="pet_id" value="<?php echo $pet['pet_id']; ?>">
                                <button type="submit" name="adopt"
                                    style="background: var(--accent-green); color: var(--white); border: none; padding: 10px 18px; border-radius: 6px; cursor: pointer; font-weight: 600;">
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