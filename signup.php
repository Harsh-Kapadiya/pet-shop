<?php
include 'admin/includes/header.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $address = trim($_POST['address']);
    $ph_no = trim($_POST['ph_no']);

    try {
        // Check if user already exists
        $checkStmt = $pdo->prepare("SELECT * FROM Customers WHERE email = ?");
        $checkStmt->execute([$email]);

        if ($checkStmt->rowCount() > 0) {
            $error_message = 'Email is already registered.';
        } else {
            // Insert new customer (no password hashing)
            $stmt = $pdo->prepare("INSERT INTO Customers (cname, email, address, ph_no, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $address, $ph_no, $password]);
            $success_message = 'Account created successfully! You can now log in.';
        }
    } catch (PDOException $e) {
        // echo "Database Error: " . $e->getMessage(); // (for testing only)
        error_log("Signup error: " . $e->getMessage());
        $error_message = 'An error occurred. Please try again.';
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md); max-width: 500px;">
    <div class="section-title">
        <h2>Create an Account</h2>
        <p>Join Pet Haven and take care of your furry friends!</p>
    </div>

    <?php if (!empty($error_message)): ?>
        <div style="background: #FEECEB; color: var(--accent-red); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <div style="background: #E8F9E9; color: var(--primary-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-lg);">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success_message); ?>
        </div>
    <?php endif; ?>

    <form method="post" style="background: white; padding: var(--spacing-xl); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="margin-bottom: var(--spacing-md);">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Full Name:</label>
            <input type="text" name="name" id="name" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Email Address:</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="password" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Password:</label>
            <input type="password" name="password" id="password" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="address" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Address:</label>
            <input type="text" name="address" id="address" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-lg);">
            <label for="ph_no" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Phone Number:</label>
            <input type="text" name="ph_no" id="ph_no" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <button type="submit" name="signup" class="btn btn-secondary" style="width: 100%; margin-bottom: var(--spacing-md);">Sign Up</button>
        <p style="text-align: center; color: var(--medium-text);">Already have an account? <a href="login.php" style="color: var(--primary-green); font-weight: 600; text-decoration: none;">Login here</a></p>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
