<?php
include 'includes/header.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    try {
        // ✅ Table name fixed: Customers
        $stmt = $pdo->prepare("SELECT cid, cname, email, password FROM Customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) { // password_verify not needed if passwords are plain text
            $_SESSION['user'] = [
                'id' => $user['cid'],
                'name' => $user['cname'],
                'email' => $user['email']
            ];
            header('Location: index.php');
            exit;
        } else {
            $error_message = 'Invalid email or password.';
        }
    } catch (PDOException $e) {
        // ✅ To see the real error temporarily (remove after test)
        // echo "Database Error: " . $e->getMessage();
        error_log("Login error: " . $e->getMessage());
        $error_message = 'An error occurred. Please try again.';
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md); max-width: 500px;">
    <div class="section-title">
        <h2>Login to Your Account</h2>
        <p>Welcome back to Pet Haven!</p>
    </div>

    <?php if (!empty($error_message)): ?>
        <div style="background: #FEECEB; color: var(--accent-red); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form method="post" style="background: white; padding: var(--spacing-xl); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="margin-bottom: var(--spacing-md);">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Email Address:</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-lg);">
            <label for="password" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Password:</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <button type="submit" name="login" class="btn btn-secondary" style="width: 100%; margin-bottom: var(--spacing-md);">Login</button>
        <p style="text-align: center; color: var(--medium-text);">Don't have an account? <a href="signup.php" style="color: var(--primary-green); font-weight: 600; text-decoration: none;">Sign up here</a></p>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
