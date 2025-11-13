<?php
session_start();
require_once '../includes/db_connect.php';
// include 'includes/admin_header.php';

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
        $stmt = $pdo->prepare("SELECT * FROM Admin WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && $admin['password'] == $password) {

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

<!-- PAGE CONTENT ONLY — NO HTML/BODY TAGS -->
<div style="max-width:400px; margin:60px auto; background:#fff; padding:35px;
            border-radius:14px; box-shadow:0 0 18px rgba(0,0,0,0.15); text-align:center;">

    <h2 style="margin-bottom:20px; color:#556b2f;">Admin Login</h2>

    <?php if (!empty($error_message)): ?>
        <div style="background:#fde3e3; color:#cc0000; padding:12px; border-radius:6px;
                    margin-bottom:15px; font-size:14px;">
            <?= $error_message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <input type="email" name="email" placeholder="Enter your email" required
            style="width:100%; padding:12px; margin:12px 0; border-radius:8px; border:1px solid #ccc; font-size:15px;">

        <input type="password" name="password" placeholder="Enter your password" required
            style="width:100%; padding:12px; margin:12px 0; border-radius:8px; border:1px solid #ccc; font-size:15px;">

        <button type="submit" name="login"
            style="width:100%; padding:12px; background:#556b2f; border:none; color:white;
                       font-size:16px; border-radius:8px; cursor:pointer;">
            Login
        </button>
    </form>

    <div style="margin-top:10px; font-size:14px;">
        Don’t have an account?
        <a href="admin_signup.php" style="color:#28a745; font-weight:700; text-decoration:none;">
            Sign Up
        </a>
    </div>

</div>

<?php include 'includes/admin_footer.php'; ?>