<?php
include 'includes/header.php';
require_once 'includes/db_connect.php';
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md); text-align:center; max-width:600px;">
    <div class="section-title" style="margin-bottom: var(--spacing-xl);">
        <h2>Choose Your Signup Type</h2>
        <p>Select whether you want to register as an Admin or a Customer.</p>
    </div>

    <div style="display:flex; justify-content:center; gap:40px; flex-wrap:wrap;">
        <!-- Admin Signup -->
        <a href="admin/admin_signup.php"
            style="background: var(--primary-green); color:white; padding:15px 35px; border-radius:12px; text-decoration:none; font-size:1.2rem; font-weight:600; transition: all 0.3s;">
            Admin Signup
        </a>

        <!-- Customer Signup -->
        <a href="signup.php"
            style="background: var(--accent-yellow); color:black; padding:15px 35px; border-radius:12px; text-decoration:none; font-size:1.2rem; font-weight:600; transition: all 0.3s;">
            Customer Signup
        </a>
    </div>

    <div style="margin-top:40px;">
        <p style="color:var(--medium-text); font-size:0.95rem;">
            Already have an account?
            <a href="login.php" style="color:var(--primary-green); font-weight:600; text-decoration:none;">Login here</a>
        </p>
    </div>
</section>

<?php
include 'includes/footer.php';
?>