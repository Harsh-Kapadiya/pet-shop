<?php
include 'includes/header.php';
?>

<section class="container" style="padding: var(--spacing-xxl) var(--spacing-md);">
    <div class="section-title">
        <h2>About Pet Haven</h2>
        <p>Your trusted partner in pet care and adoption</p>
    </div>
    
    <div style="max-width: 900px; margin: 0 auto; background: white; padding: var(--spacing-xl); border-radius: 15px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <h3 style="font-family: var(--font-heading); color: var(--primary-green); margin-bottom: var(--spacing-md);">Our Story</h3>
        <p style="margin-bottom: var(--spacing-md); line-height: 1.8;">
            Pet Haven was founded with a simple mission: to connect loving pets with caring families and provide 
            everything needed for their wellbeing. Since our establishment, we've helped thousands of pets find 
            their forever homes and supported countless pet parents with quality products and expert veterinary care.
        </p>
        
        <h3 style="font-family: var(--font-heading); color: var(--primary-green); margin-bottom: var(--spacing-md);">What We Do</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: var(--spacing-md); margin-bottom: var(--spacing-lg);">
            <div style="padding: var(--spacing-md); background: var(--grey-100); border-radius: 10px;">
                <h4 style="color: var(--dark-text); margin-bottom: var(--spacing-sm);"><i class="fas fa-heart" style="color: var(--primary-green);"></i> Pet Adoption</h4>
                <p>We rescue and rehome pets, ensuring each animal finds a loving, suitable family.</p>
            </div>
            <div style="padding: var(--spacing-md); background: var(--grey-100); border-radius: 10px;">
                <h4 style="color: var(--dark-text); margin-bottom: var(--spacing-sm);"><i class="fas fa-shopping-bag" style="color: var(--primary-green);"></i> Quality Products</h4>
                <p>We offer premium food, toys, and accessories for all your pet's needs.</p>
            </div>
            <div style="padding: var(--spacing-md); background: var(--grey-100); border-radius: 10px;">
                <h4 style="color: var(--dark-text); margin-bottom: var(--spacing-sm);"><i class="fas fa-stethoscope" style="color: var(--primary-green);"></i> Vet Services</h4>
                <p>Our expert veterinarians provide comprehensive health care and consultations.</p>
            </div>
        </div>
        
        <h3 style="font-family: var(--font-heading); color: var(--primary-green); margin-bottom: var(--spacing-md);">Our Values</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="margin-bottom: var(--spacing-sm); padding-left: 30px; position: relative;">
                <i class="fas fa-check-circle" style="position: absolute; left: 0; top: 3px; color: var(--secondary-green);"></i>
                <strong>Compassion:</strong> Every animal deserves love, care, and respect
            </li>
            <li style="margin-bottom: var(--spacing-sm); padding-left: 30px; position: relative;">
                <i class="fas fa-check-circle" style="position: absolute; left: 0; top: 3px; color: var(--secondary-green);"></i>
                <strong>Quality:</strong> We provide only the best products and services
            </li>
            <li style="margin-bottom: var(--spacing-sm); padding-left: 30px; position: relative;">
                <i class="fas fa-check-circle" style="position: absolute; left: 0; top: 3px; color: var(--secondary-green);"></i>
                <strong>Community:</strong> Building lasting relationships with pet parents
            </li>
            <li style="margin-bottom: var(--spacing-sm); padding-left: 30px; position: relative;">
                <i class="fas fa-check-circle" style="position: absolute; left: 0; top: 3px; color: var(--secondary-green);"></i>
                <strong>Expertise:</strong> Professional guidance for all your pet care needs
            </li>
        </ul>
        
        <div style="text-align: center; margin-top: var(--spacing-xl);">
            <a href="adopt.php" class="btn btn-secondary" style="margin-right: var(--spacing-sm);">Adopt a Pet</a>
            <a href="contact.php" class="btn btn-outline">Contact Us</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
