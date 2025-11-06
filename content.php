<?php
include 'includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $subject, $message]);
        header('Location: contact.php?sent=1');
        exit;
    } catch (PDOException $e) {
        error_log("Error sending message: " . $e->getMessage());
    }
}
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md); max-width: 800px;">
    <div class="section-title">
        <h2>Contact Us</h2>
        <p>We'd love to hear from you! Send us a message and we'll respond soon.</p>
    </div>
    
    <?php if (isset($_GET['sent']) && $_GET['sent'] == 1): ?>
        <div style="background: #E6F7EB; color: var(--accent-green); padding: var(--spacing-md); border-radius: 8px; text-align: center; margin-bottom: var(--spacing-xl);">
            <i class="fas fa-check-circle"></i> Your message has been sent successfully! We'll get back to you shortly.
        </div>
    <?php endif; ?>
    
    <form method="post" style="background: white; padding: var(--spacing-xl); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <div style="margin-bottom: var(--spacing-md);">
            <label for="name" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Your Name:</label>
            <input type="text" name="name" id="name" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="email" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Your Email:</label>
            <input type="email" name="email" id="email" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-md);">
            <label for="subject" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Subject:</label>
            <input type="text" name="subject" id="subject" style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem;">
        </div>
        <div style="margin-bottom: var(--spacing-lg);">
            <label for="message" style="display: block; font-weight: 600; margin-bottom: var(--spacing-xs);">Your Message:</label>
            <textarea name="message" id="message" rows="6" required style="width: 100%; padding: 12px; border: 1px solid var(--grey-300); border-radius: 8px; font-size: 1rem; resize: vertical;"></textarea>
        </div>
        <button type="submit" name="send_message" class="btn btn-secondary" style="width: 100%;">Send Message</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
