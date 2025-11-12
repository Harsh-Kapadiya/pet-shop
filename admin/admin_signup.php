<?php
require_once '../includes/db_connect.php';


$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_name = trim($_POST['admin_name']);
    $shop_address = trim($_POST['shop_address']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $ph_no = trim($_POST['ph_no']);
    $doctor_id = trim($_POST['doctor_id']);
    $product_id = trim($_POST['product_id']);

    // Basic validation
    if (empty($admin_name) || empty($shop_address) || empty($email) || empty($password) || empty($confirm_password) || empty($ph_no)) {
        $error = "Sabhi fields fill karna zaroori hai.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords match nahi kar rahe.";
    } else {
        try {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO Admin (admin_id, admin_name, shop_address, email, PASSWORD, ph_no, doctor_id, product_id) VALUES (NULL, :admin_name, :shop_address, :email, :password, :ph_no, :doctor_id, :product_id)");
            $stmt->execute([
                ':admin_name' => $admin_name,
                ':shop_address' => $shop_address,
                ':email' => $email,
                ':password' => $hashed_password,
                ':ph_no' => $ph_no,
                ':doctor_id' => $doctor_id ?: NULL,
                ':product_id' => $product_id ?: NULL
            ]);
            $success = "Admin account successfully created!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Fetch doctors and products for dropdown
$doctors = $pdo->query("SELECT doctor_id, doctor_name FROM Doctors")->fetchAll();
$products = $pdo->query("SELECT product_id, product_name FROM Products")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Signup</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <section class="container" style="padding: 40px 20px; max-width: 500px; margin: auto; font-family: Arial, sans-serif;">
        <div class="section-title" style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 2rem; color: #333;">Create an Account</h2>
            <p style="font-size: 1rem; color: #555;">Join Pet Haven and take care of your furry friends!</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div style="background: #FEECEB; color: #CC0000; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-weight: 600;">
                <i class="fas fa-exclamation-circle" style="margin-right: 8px;"></i> <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div style="background: #E8F9E9; color: #009933; padding: 15px; border-radius: 10px; text-align: center; margin-bottom: 20px; font-weight: 600;">
                <i class="fas fa-check-circle" style="margin-right: 8px;"></i> <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <form method="post" style="background: #fff; padding: 30px 25px; border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <?php
            $fields = [
                ['label' => 'Full Name', 'type' => 'text', 'name' => 'name'],
                ['label' => 'Email Address', 'type' => 'email', 'name' => 'email'],
                ['label' => 'Password', 'type' => 'password', 'name' => 'password'],
                ['label' => 'Address', 'type' => 'text', 'name' => 'address'],
                ['label' => 'Phone Number', 'type' => 'text', 'name' => 'ph_no'],
            ];

            foreach ($fields as $field):
            ?>
                <div style="margin-bottom: 20px;">
                    <label for="<?= $field['name'] ?>" style="display: block; font-weight: 600; margin-bottom: 6px; color: #333;"><?= $field['label'] ?>:</label>
                    <input
                        type="<?= $field['type'] ?>"
                        name="<?= $field['name'] ?>"
                        id="<?= $field['name'] ?>"
                        required
                        style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 1rem; transition: 0.2s; outline: none;"
                        onfocus="this.style.borderColor='#5b9bd5'; this.style.boxShadow='0 0 5px rgba(91, 155, 213, 0.3)';"
                        onblur="this.style.borderColor='#ccc'; this.style.boxShadow='none';">
                </div>
            <?php endforeach; ?>

            <button type="submit" name="signup" style="width: 100%; padding: 14px; background: #5b9bd5; color: #fff; font-size: 1rem; font-weight: bold; border: none; border-radius: 8px; cursor: pointer; transition: background 0.2s;"
                onmouseover="this.style.background='#4073b3';"
                onmouseout="this.style.background='#5b9bd5';">
                Sign Up
            </button>

            <p style="text-align: center; color: #777; margin-top: 15px; font-size: 0.9rem;">
                Already have an account?
                <a href="login.php" style="color: #009933; font-weight: 600; text-decoration: none;">Login here</a>
            </p>
        </form>
    </section>

</body>

</html>