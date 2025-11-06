<?php
include 'includes/header.php';

// Fetch featured pets
$pets = [];
try {
    $stmt = $pdo->query("SELECT * FROM pets WHERE status = 'Available' ORDER BY added_date DESC LIMIT 4");
    $pets = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching pets: " . $e->getMessage());
}

// Fetch featured products
$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 4");
    $products = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching products: " . $e->getMessage());
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Find Your Pet's Perfect Match & More!</h1>
        <p>From adoption to accessories, and expert vet care – everything your beloved pet needs is right here. Join our community of pet lovers today!</p>
        <button class="hero_btn" onclick="window.location.href='adopt.php'">Adopt Now</button>
        <div class="hero-images-wrapper">
            <button class="scroll-arrow left" aria-label="Scroll Left">&#10094;</button>
            <div class="hero-images">
                <img src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=200&h=200&fit=crop" alt="Happy Dog">
                <img src="https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=200&h=200&fit=crop" alt="Happy Cat">
                <img src="https://images.unsplash.com/photo-1425082661705-1834bfd09dca?w=200&h=200&fit=crop" alt="Happy Bird">
                <img src="https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?w=200&h=200&fit=crop" alt="Happy Rabbit">
                <img src="https://images.unsplash.com/photo-1535591273668-578e31182c4f?w=200&h=200&fit=crop" alt="Happy Fish">
                <img src="https://images.unsplash.com/photo-1425082661705-1834bfd09dca?w=200&h=200&fit=crop" alt="Happy Pet">
            </div>
            <button class="scroll-arrow right" aria-label="Scroll Right">&#10095;</button>
        </div>
    </div>
    <div class="a">
        <img src="https://images.unsplash.com/photo-1450778869180-41d0601e046e?w=550&h=550&fit=crop" alt="Happy pet with owner">
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us container">
    <div class="section-title">
        <h2>Why Choose Pet Haven?</h2>
        <p>Dedicated to the well-being and happiness of every pet, we offer a unique blend of services and products.</p>
    </div>
    <div class="features-grid">
        <div class="feature-item">
            <i class="icon fas fa-heart"></i>
            <h3>Compassionate Adoption</h3>
            <p>Connect with loving homes for pets in need, ensuring a safe and caring transition.</p>
        </div>
        <div class="feature-item">
            <i class="icon fas fa-paw"></i>
            <h3>Premium Pet Products</h3>
            <p>A curated selection of the highest quality food, toys, and accessories for all animals.</p>
        </div>
        <div class="feature-item">
            <i class="icon fas fa-stethoscope"></i>
            <h3>Expert Veterinary Care</h3>
            <p>Access trusted and experienced veterinarians for health check-ups and specialized treatments.</p>
        </div>
        <div class="feature-item">
            <i class="icon fas fa-users"></i>
            <h3>Supportive Community</h3>
            <p>Join a network of pet owners and experts for advice, events, and shared experiences.</p>
        </div>
    </div>
</section>

<!-- Featured Pets Section -->
<section class="featured-pets container">
    <div class="section-title">
        <h2>Our Lovable Pets Waiting for Adoption</h2>
        <p>Meet some of our adorable residents. Each one is looking for a loving home like yours.</p>
    </div>
    <?php if (!empty($pets)): ?>
        <div class="content-grid">
            <?php foreach ($pets as $pet): ?>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-<?php echo $pet['type'] == 'dog' ? '1543466835-00a7907e9de1' : '1514888286974-6c03e2ca1dba'; ?>?w=400&h=300&fit=crop" alt="<?php echo htmlspecialchars($pet['name']); ?>" class="card-image">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($pet['name']); ?></h3>
                        <p><?php echo htmlspecialchars($pet['type']); ?> (<?php echo htmlspecialchars($pet['breed']); ?>) - <?php echo htmlspecialchars($pet['age']); ?> years</p>
                        <a href="adopt.php#pet-<?php echo $pet['pet_id']; ?>" class="btn">Meet <?php echo htmlspecialchars($pet['name']); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="adopt.php" class="btn btn-outline">View All Adoptable Pets</a>
        </div>
    <?php else: ?>
        <p class="text-center">No adoptable pets found at the moment. Please check back soon!</p>
    <?php endif; ?>
</section>

<!-- Featured Products Section -->
<section class="featured-products container">
    <div class="section-title">
        <h2>Our Bestselling Pet Essentials</h2>
        <p>Discover top-rated products that keep tails wagging and purrs rumbling. Quality and comfort guaranteed!</p>
    </div>
    <?php if (!empty($products)): ?>
        <div class="content-grid">
            <?php foreach ($products as $product): ?>
                <div class="card">
                    <img src="https://images.unsplash.com/photo-1589924691995-400dc9ecc119?w=400&h=300&fit=crop" alt="<?php echo htmlspecialchars($product['name']); ?>" class="card-image">
                    <div class="card-content">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p><?php echo htmlspecialchars(substr($product['description'], 0, 80)); ?>...</p>
                        <p class="price" style="color: var(--primary-green); font-weight: 700; font-size: 1.3rem;">$<?php echo number_format($product['price'], 2); ?></p>
                        <a href="shop.php#product-<?php echo $product['product_id']; ?>" class="btn">View Product</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="shop.php" class="btn btn-outline">Browse All Products</a>
        </div>
    <?php else: ?>
        <p class="text-center">No products found at the moment. Please check back soon!</p>
    <?php endif; ?>
</section>

<!-- Call to Action Section -->
<section class="cta-section container">
    <div class="cta-content">
        <h2>Keep Your Pet Healthy and Happy</h2>
        <p>Schedule a vet check-up, grooming session, or training consultation with our expert team today!</p>
        <a href="appointment.php" class="btn btn-secondary">Book an Appointment</a>
    </div>
    <div class="cta-image">
        <img src="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=500&h=400&fit=crop" alt="Veterinarian checking a dog">
    </div>
</section>

<?php include 'includes/footer.php'; ?>
