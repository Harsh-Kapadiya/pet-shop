<?php
include 'includes/admin_header.php';
require_once '../includes/db_connect.php';

$success = "";
$error = "";

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name  = trim($_POST['name']);
    $type  = trim($_POST['type']);
    $breed = trim($_POST['breed']);
    $age   = intval($_POST['age']);
    $image = $_FILES['image']['name'] ?? '';

    if (empty($name) || empty($type) || empty($breed) || empty($age) || empty($image)) {
        $error = "Please fill out all fields.";
    } else {

        // Upload folder
        $folder = "../assets/images/Pets/";
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $target_file = $folder . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);

        try {
            $stmt = $pdo->prepare("
                INSERT INTO Pets (name, type, breed, age, images)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$name, $type, $breed, $age, $image]);

            $success = "Pet added successfully!";
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<style>
    :root {
        --primary-green: #556b2f;
        --secondary-green: #6b8e23;
        --accent-green: #28A745;
        --accent-red: #DC3545;
        --dark-text: #2c3e1f;
        --medium-text: #5f7040;
        --light-text: #8a9770;
        --white: #ffffff;
        --grey-100: #f6f8f1;
        --grey-200: #e8ebe3;
        --grey-300: #d4d8ce;
        --font-body: 'Inter', sans-serif;
        --font-heading: 'Poppins', sans-serif;
    }

    .admin-container {
        max-width: 750px;
        margin: 40px auto;
        background: var(--white);
        padding: 30px;
        border-radius: 14px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        font-family: var(--font-body);
    }

    .admin-container h2 {
        text-align: center;
        font-family: var(--font-heading);
        color: var(--primary-green);
        margin-bottom: 20px;
        font-size: 2rem;
    }

    .form-group {
        margin-bottom: 18px;
    }

    label {
        font-weight: 600;
        color: var(--dark-text);
        margin-bottom: 6px;
        display: block;
        font-size: 1rem;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--grey-300);
        border-radius: 8px;
        background: var(--white);
        font-size: 1rem;
        transition: 0.2s ease;
    }

    input:focus {
        border-color: var(--primary-green);
        box-shadow: 0 0 0 3px rgba(85, 107, 47, 0.2);
        outline: none;
    }

    .btn-submit {
        width: 100%;
        background: var(--primary-green);
        padding: 12px;
        border: none;
        font-size: 1.1rem;
        color: var(--white);
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.2s ease;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background: var(--secondary-green);
    }

    .alert {
        padding: 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 15px;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .alert.success {
        background: #E8F9E9;
        color: var(--accent-green);
        border: 1px solid #B7E8C2;
    }

    .alert.error {
        background: #FFE8E8;
        color: var(--accent-red);
        border: 1px solid #FFBABA;
    }

    @media (max-width: 480px) {
        .admin-container {
            padding: 20px;
        }
    }
</style>

<div class="admin-container">

    <h2>Add New Pet 🐾</h2>

    <?php if (!empty($success)): ?>
        <div class="alert success"><?php echo $success; ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Pet Name:</label>
            <input type="text" name="name" placeholder="Enter pet name" required>
        </div>

        <div class="form-group">
            <label>Type:</label>
            <input type="text" name="type" placeholder="dog / cat / bird / fish / rabit" required>
        </div>

        <div class="form-group">
            <label>Breed:</label>
            <input type="text" name="breed" placeholder="Enter breed" required>
        </div>

        <div class="form-group">
            <label>Age:</label>
            <input type="number" name="age" placeholder="Enter age" required>
        </div>

        <div class="form-group">
            <label>Upload Image:</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        <button type="submit" class="btn-submit">Add Pet</button>

    </form>

</div>

<?php include 'includes/admin_footer.php'; ?>