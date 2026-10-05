<?php
include 'config/connect.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$meta_title = "Buy Premium Dogs & Pets | Kyra Pet Shop New Delhi";
$meta_desc = "Kyra Pet Shop in Patel Nagar offers the best, healthy, and premium dog breeds. Find your perfect furry companion today!";
$meta_keywords = "pet shop delhi, buy dogs, premium dog breeds, kyra pet shop";

include 'includes/header.php';
?>

<!-- Hero Slider Section -->
<section class="hero-slider">
    <div id="premiumHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        
        <?php
        $banner_query = "SELECT banner_path, title, description, link_url FROM banners ORDER BY display_order ASC, id DESC";
        $banner_result = $conn->query($banner_query);
        $indicator_count = 0;
        $slide_count = 0;
        ?>

        <!-- Carousel Indicators -->
        <div class="carousel-indicators">
            <?php
            if($banner_result->num_rows > 0) {
                while($indicator_count < $banner_result->num_rows) {
                    $active_class = ($indicator_count == 0) ? 'class="active" aria-current="true"' : '';
                    echo '<button type="button" data-bs-target="#premiumHeroCarousel" data-bs-slide-to="'.$indicator_count.'" '.$active_class.' aria-label="Slide '.($indicator_count+1).'"></button>';
                    $indicator_count++;
                }
            }
            ?>
        </div>

        <!-- Carousel Inner (Slides) -->
        <div class="carousel-inner h-100">
            <?php
            if($banner_result->num_rows > 0) {
                // Reset pointer to start
                $banner_result->data_seek(0);
                
                while($banner = $banner_result->fetch_assoc()) {
                    $active_slide = ($slide_count == 0) ? 'active' : '';
                    // Database me banner_path kaise save hai uspe depend karta hai (e.g., 'uploads/banners/name.jpg')
                    $image_url = !empty($banner['banner_path']) ? htmlspecialchars($banner['banner_path']) : 'assets/images/default-dog-banner.jpg';
                    $title = !empty($banner['title']) ? htmlspecialchars($banner['title']) : 'Premium Dogs Available';
                    $description = !empty($banner['description']) ? htmlspecialchars($banner['description']) : 'Find your perfect companion at Kyra Pet Shop.';
                    $link = !empty($banner['link_url']) ? htmlspecialchars($banner['link_url']) : 'breeds.php';
                    ?>
                    
                    <div class="carousel-item <?php echo $active_slide; ?>">
                        <img src="admin/<?php echo $image_url; ?>" alt="<?php echo $title; ?>">
                        <div class="slider-overlay"></div>
                        <div class="carousel-caption">
                            <h1 class="caption-title"><?php echo $title; ?></h1>
                            <p class="caption-desc"><?php echo $description; ?></p>
                            <div class="caption-btn">
                                <a href="<?php echo $link; ?>" class="btn btn-premium btn-lg">
                                    Explore Breeds <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    $slide_count++;
                }
            } else {
                // Fallback Content agar database me koi banner na ho
                ?>
                <div class="carousel-item active">
                    <img src="assets/images/placeholder-husky.jpg" alt="Kyra Pet Shop">
                    <div class="slider-overlay"></div>
                    <div class="carousel-caption">
                        <h1 class="caption-title">Welcome to Kyra Pet Shop</h1>
                        <p class="caption-desc">Experience the joy of bringing a healthy, premium breed dog to your family.</p>
                        <div class="caption-btn">
                            <a href="contact.php" class="btn btn-premium btn-lg">Get in Touch <i class="fas fa-paw ms-2"></i></a>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#premiumHeroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true" style="width: 3rem; height: 3rem;"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#premiumHeroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true" style="width: 3rem; height: 3rem;"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Dynamic About Us Section -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            
            <!-- Left Side: Image Grid -->
            <div class="col-lg-6 mb-5 mb-lg-0 fade-in">
                <div class="image-grid-container">
                    <div class="grid-img-wrap grid-img-1">
                        <img src="assets/images/section/2.avif" alt="Premium Husky Dog">
                    </div>
                    <div class="grid-img-wrap grid-img-2">
                        <img src="assets/images/section/4.avif" alt="Golden Retriever">
                    </div>
                    <div class="grid-img-wrap grid-img-3">
                        <img src="assets/images/section/5.avif" alt="Cute Pug">
                    </div>
                    
                    <div class="experience-badge">
                        <h4 class="mb-0">100%</h4>
                        <small>Healthy & Pure Breeds</small>
                    </div>
                </div>
            </div>

            <!-- Right Side: Dynamic Content -->
            <div class="col-lg-6 fade-in" style="animation-delay: 0.3s;">
                <div class="about-content-wrapper">
                    
                    <?php
                    // Fetch About Us content from the 'about_us' table
                    $about_query = "SELECT title, content FROM about_us ORDER BY id DESC LIMIT 1";
                    $about_result = $conn->query($about_query);
                    
                    if($about_result->num_rows > 0) {
                        $about_data = $about_result->fetch_assoc();
                        // Assuming title is something like "Welcome to Kyra Pet Shop"
                        $about_title = htmlspecialchars($about_data['title']);
                        // Note: content is usually HTML from CKEditor, so we don't use htmlspecialchars on it
                        // But ensure it's safe if it contains user input.
                        $about_content = $about_data['content']; 
                    } else {
                        // Fallback Content
                        $about_title = "Welcome to Kyra Pet Shop";
                        $about_content = "<p>At Kyra Pet Shop, we believe every home deserves a loyal companion. Located in Patel Nagar, New Delhi, we specialize in providing premium, healthy, and ethically bred dogs. Our team ensures that every pup gets the best care before they meet their new family.</p>";
                    }
                    ?>

                    <span class="section-subtitle">Why Choose Us</span>
                    <h2 class="section-title"><?php echo $about_title; ?></h2>
                    
                    <div class="about-text">
                        <?php echo $about_content; ?>
                    </div>

                    <ul class="feature-list">
                        <li><i class="fas fa-heartbeat"></i> 100% Vaccinated & Health Checked</li>
                        <li><i class="fas fa-medal"></i> Premium & Pure Breed Guarantee</li>
                        <li><i class="fas fa-user-md"></i> Expert Pet Care Guidance</li>
                    </ul>

                    <a href="about.php" class="btn btn-premium mt-2">
                        Read More About Us <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Premium Custom CSS for Breeds/Products Section -->
<style>
    .breeds-section {
        padding: 90px 0;
        background-color: #f8fafc; /* Very light cool grey for contrast */
    }

    .section-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .section-header .subtitle {
        color: var(--brand-teal);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 10px;
        background: rgba(0, 168, 181, 0.1);
        padding: 5px 15px;
        border-radius: 20px;
    }

    .section-header h2 {
        color: var(--brand-navy);
        font-size: 2.8rem;
        font-weight: 700;
    }

    /* Premium Pet Card Styling */
    .pet-card {
        background: var(--bg-pure-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(0,0,0,0.02);
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .pet-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .pet-img-wrap {
        position: relative;
        overflow: hidden;
        padding-top: 75%; /* Aspect ratio 4:3 */
    }

    .pet-img-wrap img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .pet-card:hover .pet-img-wrap img {
        transform: scale(1.08);
    }

    /* Status Badge (e.g., Available, New Arrival) */
    .status-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--brand-teal);
        color: white;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .pet-info {
        padding: 25px 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .pet-title {
        color: var(--brand-navy);
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        transition: color 0.3s;
    }

    .pet-card:hover .pet-title {
        color: var(--brand-teal);
    }

    .pet-price-box {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }

    .selling-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--brand-navy);
    }

    .mrp-price {
        font-size: 0.95rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 10px;
    }

    .pet-features {
        display: flex;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .feature-item {
        font-size: 0.85rem;
        color: var(--text-gray);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .feature-item i {
        color: var(--brand-teal);
    }

    /* Action Buttons */
    .card-actions {
        margin-top: auto;
        display: flex;
        gap: 10px;
    }

    .btn-view {
        flex: 1;
        background: transparent;
        border: 1px solid var(--brand-navy);
        color: var(--brand-navy);
        border-radius: 10px;
        padding: 8px 0;
        font-weight: 600;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
    }

    .btn-view:hover {
        background: var(--brand-navy);
        color: white;
    }

    .btn-whatsapp {
        flex: 1;
        background: #25D366; /* WhatsApp Green */
        border: 1px solid #25D366;
        color: white;
        border-radius: 10px;
        padding: 8px 0;
        font-weight: 600;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
    }

    .btn-whatsapp:hover {
        background: #128C7E;
        border-color: #128C7E;
        color: white;
    }
</style>

<!-- Dynamic Breeds Section -->
<section class="breeds-section fade-in">
    <div class="container">
        
        <!-- Section Header -->
        <div class="section-header">
            <span class="subtitle"><i class="fas fa-paw me-2"></i> Find Your Match</span>
            <h2>Meet Our Premium Breeds</h2>
            <p class="text-muted mt-2 mx-auto" style="max-width: 600px;">
                Explore our collection of healthy, playful, and ethically raised puppies waiting for a loving family.
            </p>
        </div>

        <div class="row g-4">
            <?php
            $dog_query = "SELECT pro_id, pro_name, short_desc, mrp, selling_price, pro_img, slug_url, new_arrival 
                          FROM products 
                          WHERE status = 1 
                          ORDER BY id DESC LIMIT 8";
            $dog_result = $conn->query($dog_query);

            if($dog_result->num_rows > 0) {
                while($dog = $dog_result->fetch_assoc()) {
                    
                    $name = htmlspecialchars($dog['pro_name']);
                    $slug = htmlspecialchars($dog['slug_url']);
                    $selling_price = number_format((float)$dog['selling_price'], 2);
                    $mrp = number_format((float)$dog['mrp'], 2);
                    
                    $image = !empty($dog['pro_img']) ? "admin/assets/img/uploads/" . htmlspecialchars($dog['pro_img']) : "assets/images/default-dog.jpg";
                    
                    $age = "8 Weeks";
                    $gender = rand(0,1) ? "Male" : "Female";
                    
                    ?>
                    
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="pet-card">
                            
                            <?php if($dog['new_arrival'] == 1): ?>
                                <div class="status-badge">New Arrival</div>
                            <?php endif; ?>
                            
                            <div class="pet-img-wrap">
                                <!-- Link to detail page -->
                                <a href="breed-details.php?slug=<?php echo $slug; ?>">
                                    <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
                                </a>
                            </div>
                            
                            <div class="pet-info">
                                <a href="breed-details.php?slug=<?php echo $slug; ?>" style="text-decoration: none;">
                                    <h3 class="pet-title"><?php echo $name; ?></h3>
                                </a>
                                
                                <!-- <div class="pet-price-box">
                                    <span class="selling-price">₹<?php echo $selling_price; ?></span>
                                    <?php if($dog['mrp'] > $dog['selling_price']): ?>
                                        <span class="mrp-price">₹<?php echo $mrp; ?></span>
                                    <?php endif; ?>
                                </div> -->
                                
                                <!-- Pet Specific Info (UI enhancement) -->
                                <div class="pet-features">
                                    <span class="feature-item">
                                        <i class="fas fa-clock"></i> <?php echo $age; ?>
                                    </span>
                                    <span class="feature-item">
                                        <i class="fas fa-venus-mars"></i> <?php echo $gender; ?>
                                    </span>
                                </div>
                                
                                <div class="card-actions">
                                    <a href="breed-details.php?slug=<?php echo $slug; ?>" class="btn-view">
                                        View Details
                                    </a>
                                    <!-- WhatsApp API link with pre-filled message -->
                                    <a href="https://wa.me/919582799502?text=Hello%20Kyra%20Pet%20Shop!%20I'm%20interested%20in%20the%20<?php echo urlencode($name); ?>.%20Please%20share%20more%20details." target="_blank" class="btn-whatsapp">
                                        <i class="fab fa-whatsapp"></i> Inquire
                                    </a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                    <?php
                }
            } else {
                echo '<div class="col-12 text-center"><p class="lead">We are currently updating our adorable collection. Check back soon!</p></div>';
            }
            ?>
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-5">
            <a href="all-breeds.php" class="btn btn-premium btn-lg">
                View All Breeds <i class="fas fa-paw ms-2"></i>
            </a>
        </div>

    </div>
</section>

<!-- Premium Custom CSS for Rest of the Sections -->
<style>
    /* Global Section Spacing */
    .premium-section {
        padding: 90px 0;
    }
    
    .bg-light-grey {
        background-color: #f8fafc;
    }

    .section-title-wrap {
        text-align: center;
        margin-bottom: 50px;
    }
    .section-title-wrap .sub-title {
        color: var(--brand-teal);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-size: 0.9rem;
    }
    .section-title-wrap h2 {
        color: var(--brand-navy);
        font-size: 2.5rem;
        font-weight: 700;
        margin-top: 10px;
    }

    /* 1. Testimonial Styling */
    .testimonial-card {
        background: var(--bg-pure-white);
        padding: 40px 30px;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        text-align: center;
        transition: transform 0.3s ease;
        position: relative;
        height: 100%;
    }
    .testimonial-card:hover {
        transform: translateY(-10px);
    }
    .quote-icon {
        color: rgba(0, 168, 181, 0.15);
        font-size: 3rem;
        position: absolute;
        top: 20px;
        left: 30px;
    }
    .testi-msg {
        font-style: italic;
        color: var(--text-gray);
        margin-bottom: 25px;
        position: relative;
        z-index: 2;
    }
    .client-info h5 {
        color: var(--brand-navy);
        font-weight: 700;
        margin-bottom: 0;
    }
    .client-info span {
        color: var(--brand-teal);
        font-size: 0.85rem;
    }

    /* 2. Gallery Styling */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    .gallery-item {
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        aspect-ratio: 4/3;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .gallery-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .gallery-overlay {
        position: absolute;
        inset: 0;
        background: rgba(29, 53, 87, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .gallery-item:hover img {
        transform: scale(1.1);
    }
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    .gallery-overlay i {
        color: white;
        font-size: 2rem;
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    .gallery-item:hover .gallery-overlay i {
        transform: translateY(0);
    }

    /* 3. Blog Styling */
    .blog-card {
        background: var(--bg-pure-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .blog-card:hover {
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        transform: translateY(-5px);
    }
    .blog-img {
        height: 220px;
        overflow: hidden;
    }
    .blog-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    .blog-card:hover .blog-img img {
        transform: scale(1.05);
    }
    .blog-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    .blog-meta {
        font-size: 0.85rem;
        color: var(--brand-teal);
        margin-bottom: 10px;
    }
    .blog-title {
        color: var(--brand-navy);
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: 15px;
        text-decoration: none;
    }
    .blog-title:hover {
        color: var(--brand-teal);
    }
    .read-more {
        margin-top: auto;
        color: var(--brand-navy);
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: color 0.3s;
    }
    .read-more:hover {
        color: var(--brand-teal);
    }
    .read-more i {
        margin-left: 5px;
        transition: transform 0.3s;
    }
    .read-more:hover i {
        transform: translateX(5px);
    }

    /* 4. Inquiry / Contact Styling */
    .contact-wrapper {
        background: var(--bg-pure-white);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
    }
    .map-container {
        height: 100%;
        min-height: 400px;
    }
    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
    }
    .form-container {
        padding: 50px 40px;
        background: var(--bg-pure-white);
    }
    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        background: #f8fafc;
    }
    .form-control:focus {
        border-color: var(--brand-teal);
        box-shadow: 0 0 0 0.2rem rgba(0, 168, 181, 0.15);
        background: white;
    }
    .submit-btn {
        background: var(--brand-navy);
        color: white;
        border-radius: 30px;
        padding: 12px 30px;
        border: none;
        font-weight: 600;
        width: 100%;
        transition: all 0.3s;
    }
    .submit-btn:hover {
        background: var(--brand-teal);
        transform: translateY(-2px);
    }
</style>

<!-- ================= 1. Happy Pet Owners (Testimonials) ================= -->
<section class="premium-section fade-in">
    <div class="container">
        <div class="section-title-wrap">
            <span class="sub-title"><i class="fas fa-heart me-2"></i> Happy Families</span>
            <h2>What Our Pet Parents Say</h2>
        </div>
        
        <div class="row g-4">
            <?php
            // Fetch Testimonials from database[cite: 2]
            $testi_query = "SELECT name, designation, message FROM testimonials WHERE status = 1 ORDER BY test_id DESC LIMIT 3";
            $testi_result = $conn->query($testi_query);
            
            if($testi_result->num_rows > 0) {
                while($testi = $testi_result->fetch_assoc()) {
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="testimonial-card">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <!-- htmlspecialchars is used for security, but text can be long so we truncate if needed -->
                            <p class="testi-msg">"<?php echo strip_tags($testi['message']); ?>"</p>
                            <div class="client-info mt-4">
                                <h5><?php echo htmlspecialchars($testi['name']); ?></h5>
                                <span><?php echo htmlspecialchars($testi['designation']); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p class="text-center">No reviews yet. Be the first to review us!</p>';
            }
            ?>
        </div>
    </div>
</section>

<!-- ================= 2. Pet Gallery Section ================= -->
<section class="premium-section bg-light-grey fade-in">
    <div class="container">
        <div class="section-title-wrap">
            <span class="sub-title"><i class="fas fa-camera me-2"></i> Adorable Moments</span>
            <h2>Our Pet Gallery</h2>
        </div>
        
        <div class="gallery-grid">
            <?php
            // Fetch Gallery Images[cite: 2]
            $gallery_query = "SELECT image_name, image_path FROM gallery ORDER BY ID DESC LIMIT 6";
            $gallery_result = $conn->query($gallery_query);
            
            if($gallery_result->num_rows > 0) {
                while($img = $gallery_result->fetch_assoc()) {
                    $img_src = !empty($img['image_path']) ? htmlspecialchars($img['image_path']) : 'assets/images/default-gallery.jpg';
                    ?>
                    <div class="gallery-item">
                        <img src="admin/<?php echo $img_src; ?>" alt="Kyra Pet Shop Gallery">
                        <div class="gallery-overlay">
                            <i class="fas fa-search-plus"></i>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Fallback UI agar DB me images nahi hain
                for($i=1; $i<=6; $i++) {
                    echo '<div class="gallery-item"><img src="assets/images/placeholder-gallery-'.$i.'.jpg" alt="Pet Image"><div class="gallery-overlay"><i class="fas fa-search-plus"></i></div></div>';
                }
            }
            ?>
        </div>
        <div class="text-center mt-5">
            <a href="gallery.php" class="btn btn-premium">View Full Gallery</a>
        </div>
    </div>
</section>

<!-- ================= 3. Latest Pet Blogs Section ================= -->
<section class="premium-section fade-in">
    <div class="container">
        <div class="section-title-wrap">
            <span class="sub-title"><i class="fas fa-book-open me-2"></i> Pet Care Tips</span>
            <h2>Latest From Our Blog</h2>
        </div>
        
        <div class="row g-4">
            <?php
            // Fetch Blogs[cite: 2]
            $blog_query = "SELECT title, slug, image, created_at, description FROM blogs WHERE status = 1 ORDER BY blog_id DESC LIMIT 3";
            $blog_result = $conn->query($blog_query);
            
            if($blog_result->num_rows > 0) {
                while($blog = $blog_result->fetch_assoc()) {
                    $blog_img = !empty($blog['image']) ? "admin/assets/img/uploads/blogs/".$blog['image'] : "assets/images/default-blog.jpg";
                    // Format Date
                    $blog_date = date("M d, Y", strtotime($blog['created_at']));
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-card">
                            <div class="blog-img">
                                <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>">
                                    <img src="<?php echo $blog_img; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <i class="far fa-calendar-alt me-1"></i> <?php echo $blog_date; ?> 
                                    <i class="fas fa-paw ms-3 me-1"></i> Kyra Pets
                                </div>
                                <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="blog-title">
                                    <?php echo htmlspecialchars($blog['title']); ?>
                                </a>
                                <!-- Truncate description for preview -->
                                <p class="text-muted" style="font-size: 0.9rem;">
                                    <?php echo substr(strip_tags($blog['description']), 0, 90) . '...'; ?>
                                </p>
                                <a href="blog-details.php?slug=<?php echo $blog['slug']; ?>" class="read-more">
                                    Read Article <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</section>

<!-- ================= 4. Inquiry & Contact Section ================= -->
<section class="premium-section bg-light-grey fade-in" id="inquiry-section">
    <div class="container">
        <div class="section-title-wrap">
            <span class="sub-title"><i class="fas fa-envelope me-2"></i> Get In Touch</span>
            <h2>Send Us an Inquiry</h2>
        </div>
        
        <div class="contact-wrapper">
            <div class="row g-0">
                <?php
                // Fetch Map and Contact info[cite: 2]
                $contact_query = "SELECT map FROM contacts ORDER BY id DESC LIMIT 1";
                $contact_result = $conn->query($contact_query);
                $map_url = "";
                if($contact_result->num_rows > 0) {
                    $contact_data = $contact_result->fetch_assoc();
                    $map_url = $contact_data['map'];
                }
                ?>
                <!-- Left Side: Map -->
                <div class="col-lg-6">
                    <div class="map-container">
                        <?php if(!empty($map_url)): ?>
                            <iframe src="<?php echo $map_url; ?>" allowfullscreen="" loading="lazy"></iframe>
                        <?php else: ?>
                            <!-- Fallback Map for WZ-23 khampur Patel nagar, New Delhi -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3501.5977936168536!2d77.15926711508282!3d28.64182188241402!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd3d2cf5707b%3A0x6b637b38c227eb0!2sPatel%20Nagar%2C%20New%20Delhi!5e0!3m2!1sen!2sin!4v1689874561234!5m2!1sen!2sin" allowfullscreen="" loading="lazy"></iframe>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Right Side: Dynamic Inquiry Form -->
                <div class="col-lg-6">
                    <div class="form-container">
                        <h4 class="mb-4" style="color: var(--brand-navy); font-weight: 700;">Looking for a specific breed?</h4>
                        <p class="text-muted mb-4">Fill out the form below and Mr. Nitin will get back to you shortly.</p>
                        
                        <!-- Form submits data to inquiries table (create process_inquiry.php for backend)[cite: 2] -->
                        <form action="process_inquiry.php" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="phone" class="form-control" placeholder="Phone Number" required>
                                </div>
                                <div class="col-12">
                                    <input type="email" name="email" class="form-control" placeholder="Email Address (Optional)">
                                </div>
                                <div class="col-12">
                                    <input type="text" name="subject" class="form-control" placeholder="Which breed are you looking for?" required>
                                </div>
                                <div class="col-12">
                                    <textarea name="message" rows="4" class="form-control" placeholder="Any specific requirements? (Age, Gender, etc.)"></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="submit-btn">
                                        Send Inquiry <i class="fas fa-paper-plane ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content Section ke liye space -->
<section class="py-5 bg-light text-center">
    <div class="container fade-in">
        <h2 style="color: var(--secondary-navy); font-weight: 700;">Find Your Perfect Companion</h2>
        <p class="text-muted mt-3">We have the finest breeds waiting for a loving home.</p>
        <!-- Yahan dogs ki grid aayegi -->
    </div>
</section>

<!-- Bootstrap JS (Slider run karne ke liye required) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php 
// 4. Footer Include
include 'includes/footer.php'; 
?>