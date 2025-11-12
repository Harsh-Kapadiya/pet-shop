<?php
include 'includes/admin_header.php';

$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $breed = trim($_POST['breed']);
    $age = intval($_POST['age']);
    $image = $_FILES['image']['name'];

    if (empty($name) || empty($type) || empty($breed) || empty($age) || empty($image)) {
        $error = "Please fill out all fields.";
    } else {
        $target_dir = "../uploads/pets/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);

        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        try {
            $stmt = $pdo->prepare("INSERT INTO Pets (pet_id, name, type, breed, age, images) 
                                   VALUES (NULL, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $type, $breed, $age, $image]);
            $success = "Pet added successfully!";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<div class="card" style="max-width:700px;margin:auto;background:#fff;padding:25px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <h2 style="text-align:center;margin-bottom:25px;">Add New Pet 🐕</h2>

    <?php if ($success): ?><p style="color:green;text-align:center;"><?php echo $success; ?></p><?php endif; ?>
    <?php if ($error): ?><p style="color:red;text-align:center;"><?php echo $error; ?></p><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" style="display:flex;flex-direction:column;gap:15px;">
        <label>Pet Name:</label>
        <input type="text" name="name" placeholder="Enter pet name" required>

        <label>Type:</label>
        <input type="text" name="type" placeholder="dog / cat / bird / fish etc." required>

        <label>Breed:</label>
        <input type="text" name="breed" placeholder="Enter breed" required>

        <label>Age:</label>
        <input type="number" name="age" placeholder="Enter age" required>

        <label>Upload Pet Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <button type="submit" style="background:var(--primary-green);color:white;padding:10px;border:none;border-radius:6px;cursor:pointer;">Add Pet</button>
    </form>
</div>

<?php include 'includes/admin_footer.php'; ?>
