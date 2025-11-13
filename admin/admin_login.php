<?php
session_start();
require_once '../includes/db_connect.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    try {
        // Fetch admin by email
        $stmt = $pdo->prepare("SELECT * FROM Admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Login Validation
        if ($admin && $admin['password'] == $password) {

            // Set login session
            $_SESSION['admin_id']   = $admin['admin_id'];
            $_SESSION['admin_name'] = $admin['admin_name'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        error_log("Admin Login Error: " . $e->getMessage());
        $error_message = "Something went wrong. Try again.";
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f6f8f1;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .login-box {
            width: 400px;
            background: #fff;
            padding: 35px;
            border-radius: 14px;
            box-shadow: 0 0 18px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #556b2f;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #556b2f;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #6b8e23;
        }

        .error {
            background: #fde3e3;
            color: #cc0000;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .signup-link {
            margin-top: 10px;
            font-size: 14px;
        }

        .signup-link a {
            color: #28a745;
            font-weight: 700;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <div class="login-box">

        <h2>Admin Login</h2>

        <?php if (!empty($error_message)): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>

            <input type="password" name="password" placeholder="Enter your password" required>

            <button type="submit" name="login">Login</button>
        </form>

        <div class="signup-link">
            Don’t have an account? <a href="admin_signup.php">Sign Up</a>
        </div>

    </div>

</body>

</html>