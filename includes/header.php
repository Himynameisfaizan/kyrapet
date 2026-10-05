<?php
session_start();

include('config/connect.php');

$current_page = basename($_SERVER['PHP_SELF']);

$meta_title = "Kyra Pet Shop - Premium Dogs & Pets";
$meta_desc = "Find the best and healthiest dog breeds at Kyra Pet Shop in New Delhi.";
$meta_key = "pet shop, buy dogs, premium dog breeds, Kyra Pet Shop, New Delhi";

$meta_stmt = $conn->prepare("SELECT meta_title, meta_desc, meta_key FROM meta WHERE page_url = ?");
$meta_stmt->bind_param("s", $current_page);
$meta_stmt->execute();
$meta_result = $meta_stmt->get_result();

if ($meta_result->num_rows > 0) {
    $meta_data = $meta_result->fetch_assoc();
    if (!empty($meta_data['meta_title'])) $meta_title = $meta_data['meta_title'];
    if (!empty($meta_data['meta_desc'])) $meta_desc = $meta_data['meta_desc'];
    if (!empty($meta_data['meta_key'])) $meta_key = $meta_data['meta_key'];
}

$logo_path = "assets/images/default-logo.png";
$logo_query = "SELECT logo_path FROM logos WHERE location = 'header' AND is_active = 1 ORDER BY id DESC LIMIT 1";
$logo_result = $conn->query($logo_query);
if ($logo_result->num_rows > 0) {
    $logo_data = $logo_result->fetch_assoc();
    $logo_path = $logo_data['logo_path'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($meta_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($meta_desc); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($meta_key); ?>">
    <meta name="author" content="Kyra Pet Shop">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="assets/style/style.css">
    <link rel="stylesheet" href="assets/style/include.css">

</head>

<body class="fade-in">

    <!-- Top Contact Bar -->
    <div class="top-bar d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <i class="fas fa-map-marker-alt me-2 text-teal"></i> WZ-23 khampur Patel nagar, New Delhi - 110008
                </div>
                <div class="col-md-6 text-end">
                    <a href="mailto:kyrapetshop12@gmail.com" class="me-4"><i class="fas fa-envelope me-2"></i> kyrapetshop12@gmail.com</a>
                    <a href="tel:+919582799502"><i class="fas fa-phone-alt me-2"></i> +91 9582799502</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="navbar navbar-expand-lg custom-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="admin/uploads/<?php echo htmlspecialchars($logo_path); ?>" alt="Kyra Pet Shop Logo">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="about.php">About Us</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'dogs.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="dogs.php">Our Breeds</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'gallery.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">
                        <a class="nav-link" href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        <a class="btn btn-premium" href="contact.php"><i class="fas fa-paw me-2"></i> Get Your Pet</a>
                    </li>
                </ul>
            </div>
    </nav>