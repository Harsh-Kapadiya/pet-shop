<?php
session_start();
require_once '../includes/db_connect.php';
// include 'includes/admin_header.php';

// If admin logged in
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

                // SUCCESS → Redirect
                $_SESSION['admin_signup_success'] = "Admin account created successfully!";
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

<!-- PAGE CONTENT (NO HTML, BODY, HEADER HERE) -->
<div style="max-width:450px; margin:60px auto; background:#fff; padding:30px; 
            border-radius:16px; box-shadow:0 6px 20px rgba(0,0,0,0.1);">

    <h2 style="text-align:center; color:#556b2f; margin-bottom:20px;">Admin Signup</h2>

    <?php if (!empty($error_message)): ?>
        <div style="background:#ffe5e5; color:#b02a37; padding:12px; border-radius:6px; margin-bottom:15px; text-align:center;">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div style="background:#e8ffe8; color:#28a745; padding:12px; border-radius:6px; margin-bottom:15px; text-align:center;">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <label style="font-weight:600; margin-top:12px;">Admin Name</label>
        <input type="text" name="admin_name" required style="width:100%; padding:12px; border-radius:8px; border:1px solid #d4d8ce;">

        <label style="font-weight:600; margin-top:12px;">Shop Address</label>
        <input type="text" name="shop_address" required style="width:100%; padding:12px; border-radius:8px; border:1px solid #d4d8ce;">

        <label style="font-weight:600; margin-top:12px;">Email</label>
        <input type="email" name="email" required style="width:100%; padding:12px; border-radius:8px; border:1px solid #d4d8ce;">

        <label style="font-weight:600; margin-top:12px;">Phone Number</label>
        <input type="text" name="ph_no" required style="width:100%; padding:12px; border-radius:8px; border:1px solid #d4d8ce;">

        <label style="font-weight:600; margin-top:12px;">Password</label>
        <input type="password" name="password" required style="width:100%; padding:12px; border-radius:8px; border:1px solid #d4d8ce;">

        <label style="font-weight:600; margin-top:12px;">Confirm Password</label>
        <input type="password" name="confirm_password" required style="width:100%; padding:12px; border-radius:8px; border:1px solid #d4d8ce;">

        <button type="submit" name="signup"
            style="width:100%; padding:12px; margin-top:15px; background:#556b2f; color:#fff; border:none; 
                       border-radius:8px; font-weight:600; cursor:pointer;">
            Create Account
        </button>

    </form>

    <p style="margin-top:15px; text-align:center;">
        Already have an account?
        <a href="admin_login.php" style="color:#556b2f; font-weight:600;">Login</a>
    </p>

</div>

<?php include 'includes/admin_footer.php'; ?>