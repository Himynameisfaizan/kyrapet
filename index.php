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
            // Fetch active products (dogs) from the 'products' table
            // Only fetching where status = 1 (Active) and limiting to 8 for the home page
            $dog_query = "SELECT pro_id, pro_name, short_desc, mrp, selling_price, pro_img, slug_url, new_arrival 
                          FROM products 
                          WHERE status = 1 
                          ORDER BY id DESC LIMIT 8";
            $dog_result = $conn->query($dog_query);

            if($dog_result->num_rows > 0) {
                while($dog = $dog_result->fetch_assoc()) {
                    
                    // Assigning variables safely
                    $name = htmlspecialchars($dog['pro_name']);
                    $slug = htmlspecialchars($dog['slug_url']);
                    $selling_price = number_format((float)$dog['selling_price'], 2);
                    $mrp = number_format((float)$dog['mrp'], 2);
                    
                    // Image logic (Assuming images are stored in a folder like 'uploads/products/')
                    $image = !empty($dog['pro_img']) ? "uploads/products/" . htmlspecialchars($dog['pro_img']) : "assets/images/default-dog.jpg";
                    
                    // Dummy features for UI (You can add these columns in your DB later if needed)
                    $age = "8 Weeks";
                    $gender = rand(0,1) ? "Male" : "Female";
                    
                    ?>
                    
                    <!-- Single Pet Card -->
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
                                
                                <div class="pet-price-box">
                                    <span class="selling-price">₹<?php echo $selling_price; ?></span>
                                    <?php if($dog['mrp'] > $dog['selling_price']): ?>
                                        <span class="mrp-price">₹<?php echo $mrp; ?></span>
                                    <?php endif; ?>
                                </div>
                                
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
// include 'includes/footer.php'; 
?>