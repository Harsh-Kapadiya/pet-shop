<?php
include 'includes/header.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format.';
    } elseif (strlen($password) < 8) {
        $error_message = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $error_message = 'An account with this email already exists.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    $success_message = 'Registration successful! You can now <a href="login.php" style="color: var(--primary-green); font-weight: 600;">login</a>.';
                } else {
                    $error_message = 'Something went wrong. Please try again.';
                }
            }
        } catch (PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            $error_message = 'An error occurred. Please try again.';
        }
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md); max-width: 500px;">
    <div class="section-title">
        <h2>Create Your Account</h2>
        <p>Join the Pet Haven community today!</p>
    </div>
    
    <?php if (!empty($error_message)): ?>
        <div style="background: #FEECEB; color: var(--accent-red); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($success_message)): ?>
        <div style="background: #E6F7EB; color: var(--accent-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
            <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
        </div>
    <?php endif; ?>
    
    <form method="post" style="background: white; padding: var(--spacing-xl); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="margin-bottom: var(--spacing-md);">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Full Name:</label>
            <input type="text" name="name" id="name" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Email Address:</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="password" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Password:</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-lg);">
            <label for="confirm_password" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <button type="submit" name="register" class="btn btn-secondary" style="width: 100%; margin-bottom: var(--spacing-md);">Create Account</button>
        <p style="text-align: center; color: var(--medium-text);">Already have an account? <a href="login.php" style="color: var(--primary-green); font-weight: 600; text-decoration: none;">Login here</a></p>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
