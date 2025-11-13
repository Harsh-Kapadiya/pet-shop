<?php
session_start();
require_once '../includes/db_connect.php';

// If admin already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {

    $admin_name = $_POST['admin_name'] ?? '';
    $shop_address = $_POST['shop_address'] ?? '';
    $email = $_POST['email'] ?? '';
    $ph_no = $_POST['ph_no'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match!";
    } else {
        try {
            $check = $pdo->prepare("SELECT * FROM Admin WHERE email = ?");
            $check->execute([$email]);

            if ($check->rowCount() > 0) {
                $error_message = "Email is already registered.";
            } else {
                $admin_id = rand(1000, 9999);

                $stmt = $pdo->prepare("
                    INSERT INTO Admin 
                    (admin_name, admin_id, shop_address, email, password, ph_no)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                $stmt->execute([
                    $admin_name,
                    $admin_id,
                    $shop_address,
                    $email,
                    $password,
                    $ph_no
                ]);

                // ---------------------------
                // SUCCESSFUL SIGNUP → REDIRECT
                // ---------------------------
                $success_message = "Admin account created successfully!";
                header("Location: admin_login.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Admin Signup Error: " . $e->getMessage());
            $error_message = "Something went wrong. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Signup | Pet Haven</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- INTERNAL CSS -->
    <style>
        :root {
            --primary-green: #556b2f;
            --secondary-green: #6b8e23;
            --accent-green: #28a745;
            --accent-red: #dc3545;
            --white: #ffffff;
            --grey-100: #f6f8f1;
            --grey-300: #d4d8ce;
        }

        body {
            background-color: var(--grey-100);
            font-family: Arial, sans-serif;
            padding: 40px;
            display: flex;
            justify-content: center;
        }

        .signup-box {
            background: var(--white);
            width: 450px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: var(--primary-green);
            margin-bottom: 20px;
        }

        label {
            font-weight: 600;
            display: block;
            margin-top: 12px;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid var(--grey-300);
            margin-bottom: 10px;
            font-size: 1rem;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background-color: var(--secondary-green);
        }

        .error-box {
            background: #ffe5e5;
            color: var(--accent-red);
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success-box {
            background: #e8ffe8;
            color: var(--accent-green);
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        a {
            text-decoration: none;
            color: var(--primary-green);
            font-weight: 600;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="signup-box">

        <h2>Admin Signup</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error-box"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="success-box"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <form method="POST">

            <label>Admin Name</label>
            <input type="text" name="admin_name" required>

            <label>Shop Address</label>
            <input type="text" name="shop_address" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Phone Number</label>
            <input type="text" name="ph_no" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit" name="signup">Create Account</button>
        </form>

        <p style="margin-top:15px; text-align:center;">
            Already have an account? <a href="admin_login.php">Login</a>
        </p>

    </div>

</body>

</html>