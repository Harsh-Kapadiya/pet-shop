<?php
include 'includes/admin_header.php';
require_once '../includes/db_connect.php';

try {
    $stmt = $pdo->query("SELECT * FROM Pets ORDER BY pet_id DESC");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $pets = [];
}
?>

<section style="padding:40px;">
    <h1 style="text-align:center;margin-bottom:40px;color:var(--primary-green);">
        Manage Pets
    </h1>

    <?php if (empty($pets)): ?>
        <p style="text-align:center;color:var(--medium-text);">No pets available.</p>
    <?php else: ?>

        <div style="overflow-x:auto;">
            <table style="
            width:100%;border-collapse:collapse;background:white;border-radius:12px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        ">
                <thead style="background:var(--primary-green);color:white;">
                    <tr>
                        <th style="padding:15px;">Image</th>
                        <th style="padding:15px;">Name</th>
                        <th style="padding:15px;">Type</th>
                        <th style="padding:15px;">Breed</th>
                        <th style="padding:15px;">Age</th>
                        <th style="padding:15px;text-align:center;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($pets as $pet): ?>
                        <tr style="border-bottom:1px solid #eee;">

                            <td style="padding:10px;">
                                <img src="../assets/images/Pets/<?php echo htmlspecialchars($pet['images']); ?>"
                                    style="width:90px;height:90px;border-radius:8px;object-fit:cover;">
                            </td>

                            <td style="padding:15px;"><?php echo htmlspecialchars($pet['name']); ?></td>
                            <td style="padding:15px;"><?php echo htmlspecialchars($pet['type']); ?></td>
                            <td style="padding:15px;"><?php echo htmlspecialchars($pet['breed']); ?></td>
                            <td style="padding:15px;"><?php echo htmlspecialchars($pet['age']); ?></td>

                            <td style="padding:15px;text-align:center;">
                                <a href="edit_pet.php?id=<?php echo $pet['pet_id']; ?>"
                                    style="background:#6b8e23;color:white;padding:8px 15px;border-radius:6px;text-decoration:none;margin-right:6px;">
                                    Edit
                                </a>

                                <a href="delete_pet.php?id=<?php echo $pet['pet_id']; ?>"
                                    onclick="return confirm('Delete this pet?');"
                                    style="background:#C62828;color:white;padding:8px 15px;border-radius:6px;text-decoration:none;">
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