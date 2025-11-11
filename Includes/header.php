<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
// At the top of db_connect.php or a config file
define('BASE_URL', '/'); // Adjust if in subfolder, e.g., '/petshop/'
require_once __DIR__ . '/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pet Haven | Adopt, Shop & Care</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">🐾 Pet Haven</a>
            </div>
            <nav class="nav-menu">
                <a href="index.php">Home</a>
                <a href="about.php">About</a>
                <a href="adopt.php">Adopt</a>
                <a href="shop.php">Shop</a>
                <a href="appoitment.php">Appointment</a>
                <!-- <a href="contact.php">Contact</a> -->
                
                <?php if(isset($_SESSION['user'])): ?>
                    <a href="dashboard.php">Dashboard</a>
                    <span class="user-greeting">Hi, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</span>
                    <a href="logout.php" class="btn btn-outline">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline">Login</a>
                    <a href="signup.php" class="btn btn-secondary">Sign Up</a>
                <?php endif; ?>
            </nav>
            <button class="nav-toggle" aria-label="toggle navigation">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>
    <main>
