<?php
// admin/pets.php

session_start();
require_once '../includes/db_connect.php';

// 🧩 Fetch all pets from database
try {
    $stmt = $pdo->query("SELECT * FROM Pets ORDER BY pet_id");
    $pets = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching pets: " . $e->getMessage());
}

// 🧩 Handle Add Pet
if (isset($_POST['add_pet'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $breed = $_POST['breed'];
    $age = (int)$_POST['age'];

    try {
        $stmt = $pdo->prepare("INSERT INTO Pets (name, type, breed, age) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $type, $breed, $age]);
        header("Location: pets.php?msg=added");
        exit;
    } catch (PDOException $e) {
        die("Error adding pet: " . $e->getMessage());
    }
}

// 🧩 Handle Delete Pet
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM Pets WHERE pet_id = ?");
        $stmt->execute([$id]);
        header("Location: pets.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error deleting pet: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Pets | Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 1000px; margin: 40px auto; background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { margin-bottom: 20px; text-align: center; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 10px 12px; text-align: center; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        form { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 30px; }
        input, select, button { padding: 10px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; }
        button { background-color: #4CAF50; color: white; cursor: pointer; }
        button:hover { background-color: #43a047; }
        .delete-btn { background-color: #e74c3c; color: white; text-decoration: none; padding: 6px 10px; border-radius: 5px; }
        .delete-btn:hover { background-color: #c0392b; }
        .msg { text-align: center; background: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 6px; }
    </style>
</head>
<body>

<div class="container">
    <h1>Manage Pets 🐾</h1>

    <?php if (isset($_GET['msg'])): ?>
        <div class="msg">
            <?php 
                if ($_GET['msg'] == 'added') echo "✅ Pet added successfully!";
                elseif ($_GET['msg'] == 'deleted') echo "🗑️ Pet deleted successfully!";
            ?>
        </div>
    <?php endif; ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Breed</th>
            <th>Age</th>
            <th>Action</th>
        </tr>
        <?php foreach ($pets as $pet): ?>
            <tr>
                <td><?php echo $pet['pet_id']; ?></td>
                <td><?php echo htmlspecialchars($pet['name']); ?></td>
                <td><?php echo htmlspecialchars($pet['type']); ?></td>
                <td><?php echo htmlspecialchars($pet['breed']); ?></td>
                <td><?php echo $pet['age']; ?></td>
                <td><a href="pets.php?delete=<?php echo $pet['pet_id']; ?>" class="delete-btn" onclick="return confirm('Delete this pet?')">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2 style="margin-top: 40px; color: #333;">Add New Pet</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Pet Name" required>
        <select name="type" required>
            <option value="">Select Type</option>
            <option value="dog">Dog</option>
            <option value="cat">Cat</option>
            <option value="bird">Bird</option>
            <option value="other">Other</option>
        </select>
        <input type="text" name="breed" placeholder="Breed" required>
        <input type="number" name="age" placeholder="Age" min="0" required>
        <button type="submit" name="add_pet">Add Pet</button>
    </form>
</div>

</body>
</html>
