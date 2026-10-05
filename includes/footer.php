<?php
// Footer ke liye database connection (agar is file me directly include kar raha hai to)
// global $conn;

// 1. Fetch Active Footer Logo
$footer_logo_path = "assets/images/default-footer-logo.png";
$f_logo_query = "SELECT logo_path FROM logos WHERE location = 'header' AND is_active = 1 ORDER BY id DESC LIMIT 1";
$f_logo_result = $conn->query($f_logo_query);
if ($f_logo_result && $f_logo_result->num_rows > 0) {
    $f_logo_data = $f_logo_result->fetch_assoc();
    $footer_logo_path = $f_logo_data['logo_path'];
}

// 2. Fetch Contact Details
$contact_query = "SELECT address, phone, email, working_hours, facebook, instagram, twitter, linkdin FROM contacts ORDER BY id DESC LIMIT 1";
$contact_result = $conn->query($contact_query);

// Default contact array agar database se data na mile
$contact = [
    'address' => 'WZ-23 khampur Patel nagar, New Delhi - 110008',
    'phone' => '9582799502',
    'email' => 'kyrapetshop12@gmail.com',
    'working_hours' => 'Mon-Sun: 10:00 AM - 9:00 PM',
    'facebook' => '#',
    'instagram' => '#',
    'twitter' => '#',
    'linkdin' => '#'
];

if ($contact_result && $contact_result->num_rows > 0) {
    $db_contact = $contact_result->fetch_assoc();
    // Overwrite defaults with DB data if not empty
    foreach ($db_contact as $key => $value) {
        if (!empty($value)) {
            $contact[$key] = $value;
        }
    }
}
?>

<style>
    .footer-section {
        background-color: #1a1a1a;
        color: #d1d1d1;
        padding: 60px 0 20px;
        font-family: 'Poppins', sans-serif;
    }
    .footer-section h5 {
        color: #ffffff;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 1.2rem;
    }
    .footer-section p {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .footer-logo {
        max-height: 80px;
        margin-bottom: 20px;
    }
    .footer-links {
        list-style: none;
        padding: 0;
    }
    .footer-links li {
        margin-bottom: 10px;
    }
    .footer-links a {
        color: #d1d1d1;
        text-decoration: none;
        transition: color 0.3s;
    }
    .footer-links a:hover {
        color: #00A8B5; /* Brand Teal */
    }
    .contact-list {
        list-style: none;
        padding: 0;
    }
    .contact-list li {
        display: flex;
        margin-bottom: 15px;
    }
    .contact-list li i {
        color: #00A8B5;
        margin-right: 15px;
        margin-top: 5px;
    }
    .social-icons a {
        display: inline-block;
        background-color: #333;
        color: #fff;
        width: 35px;
        height: 35px;
        line-height: 35px;
        text-align: center;
        border-radius: 50%;
        margin-right: 10px;
        transition: background-color 0.3s;
    }
    .social-icons a:hover {
        background-color: #00A8B5;
    }
    .footer-bottom {
        border-top: 1px solid #333;
        padding-top: 20px;
        margin-top: 40px;
        text-align: center;
        font-size: 0.9rem;
    }
</style>

<footer class="footer-section">
    <div class="container">
        <div class="row">
            
            <!-- Column 1: Logo & About -->
            <div class="col-lg-4 col-md-6 mb-4">
                <img src="admin/uploads/<?php echo htmlspecialchars($footer_logo_path); ?>" alt="Kyra Pet Shop Footer Logo" class="footer-logo">
                <p>Welcome to Kyra Pet Shop. We provide the healthiest and most premium dog breeds in New Delhi. Find your perfect companion with us today.</p>
                
                <!-- Dynamic Social Media Links[cite: 2] -->
                <div class="social-icons mt-3">
                    <?php if($contact['facebook'] != '#') { echo '<a href="'.htmlspecialchars($contact['facebook']).'" target="_blank"><i class="fab fa-facebook-f"></i></a>'; } ?>
                    <?php if($contact['instagram'] != '#') { echo '<a href="'.htmlspecialchars($contact['instagram']).'" target="_blank"><i class="fab fa-instagram"></i></a>'; } ?>
                    <?php if($contact['twitter'] != '#') { echo '<a href="'.htmlspecialchars($contact['twitter']).'" target="_blank"><i class="fab fa-twitter"></i></a>'; } ?>
                    <?php if($contact['linkdin'] != '#') { echo '<a href="'.htmlspecialchars($contact['linkdin']).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>'; } ?>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Quick Links</h5>
                <ul class="footer-links">
                    <li><a href="index.php"><i class="fas fa-angle-right me-2"></i>Home</a></li>
                    <li><a href="about.php"><i class="fas fa-angle-right me-2"></i>About Us</a></li>
                    <li><a href="breeds.php"><i class="fas fa-angle-right me-2"></i>Our Breeds</a></li>
                    <li><a href="gallery.php"><i class="fas fa-angle-right me-2"></i>Gallery</a></li>
                    <li><a href="contact.php"><i class="fas fa-angle-right me-2"></i>Contact Us</a></li>
                </ul>
            </div>

            <!-- Column 3: Contact Info -->
            <div class="col-lg-5 col-md-12 mb-4">
                <h5>Contact Information</h5>
                <ul class="contact-list">
                    <li>
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo htmlspecialchars($contact['address']); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt"></i>
                        <span>
                            <a href="tel:<?php echo htmlspecialchars($contact['phone']); ?>" style="color: inherit; text-decoration: none;">
                                +91 <?php echo htmlspecialchars($contact['phone']); ?>
                            </a>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <span>
                            <a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($contact['email']); ?>
                            </a>
                        </span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Working Hours:<br><?php echo htmlspecialchars($contact['working_hours']); ?></span>
                    </li>
                </ul>
            </div>
            
        </div>
        
        <!-- Footer Bottom Bar -->
        <div class="footer-bottom">
            <p class="mb-0">&copy; <?php echo date("Y"); ?> Kyra Pet Shop. All Rights Reserved.</p>
        </div>
    </div>
</footer>