<?php
// Double load se bachne ke liye include_once ka use
include_once 'config/connect.php';
include_once 'includes/config.php';

// Har page ka naam automatically nikalna (jaise 'contact.php', 'about.php')
$current_page_url = basename($_SERVER['PHP_SELF']);

// 1. DYNAMIC META FETCHING 
if (empty($title) || empty($desc)) {
    $meta_q = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='$current_page_url' LIMIT 1");
    if ($meta_q && mysqli_num_rows($meta_q) > 0) {
        $meta_data = mysqli_fetch_assoc($meta_q);
        if (empty($title)) { $title = $meta_data['meta_title']; }
        if (empty($desc)) { $desc = $meta_data['meta_desc']; }
        if (empty($meta_key)) { $meta_key = $meta_data['meta_key'] ?? ''; }
    }
}

// 2. DYNAMIC SCHEMA FETCHING
if (empty($schema_markup)) {
    $schema_q = mysqli_query($conn, "SELECT * FROM page_schemas WHERE page_url='$current_page_url' LIMIT 1");
    if ($schema_q && mysqli_num_rows($schema_q) > 0) {
        $schema_data = mysqli_fetch_assoc($schema_q);
        $schema_markup = $schema_data['schema_markup'];
    }
}

// Default Fallback agar database mein bhi kuch na ho
if (empty($title)) { $title = 'Kyra Pet Shop | Healthy Puppies in New Delhi'; }
if (empty($desc)) { $desc = 'Premium pure breed puppies in New Delhi. Vet checked, 100% vaccinated. Visit Kyra Pet Shop.'; }

// 3. DYNAMIC CANONICAL URL GENERATION
// Protocol check (http or https)
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// Host and clean path (strtok '?' query parameters hata deta hai clean SEO URL ke liye)
$clean_path = strtok($_SERVER['REQUEST_URI'], '?');
$canonical_url = $protocol . $_SERVER['HTTP_HOST'] . $clean_path;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS aur Images ke path theek karne ke liye -->
    <base href="/projects/kyrapet/">

    <!-- DYNAMIC CANONICAL TAG -->
    <link rel="canonical" href="<?= htmlspecialchars($canonical_url) ?>" />

    <!-- DYNAMIC SEO TAGS -->
    <title><?= htmlspecialchars($title) ?></title>
    <meta name="description" content="<?= htmlspecialchars($desc) ?>">
    <?php if (!empty($meta_key)): ?>
    <meta name="keywords" content="<?= htmlspecialchars($meta_key) ?>">
    <?php endif; ?>

    <!-- DYNAMIC SCHEMA MARKUP -->
    <?php if (!empty($schema_markup)): ?>
    <?php if (strpos($schema_markup, '<script') !== false): ?>
    <?= $schema_markup ?>
    <?php else: ?>
    <script type="application/ld+json">
    <?= $schema_markup ?>
    </script>
    <?php endif; ?>
    <?php endif; ?>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="prog"></div>
    <div class="topbar">
        <div class="wrap"><span><?= h($site['addr'] ?? '') ?></span>
            <span><a href="mailto:<?= h($site['email'] ?? '') ?>"><?= h($site['email'] ?? '') ?></a> &nbsp;|&nbsp; <a
                    href="tel:<?= h($tel ?? '') ?>"><?= h($site['phone'] ?? '') ?></a></span>
        </div>
    </div>

    <header class="nav" id="nav">
        <div class="wrap">
            <a class="logo" href="./" aria-label="Kyra Pet Shop home"><img src="assets/img/logo.png"
                    alt="Kyra Pet Shop"></a>
            <button class="burger" id="burger" aria-label="Open menu"
                aria-expanded="false"><span></span><span></span><span></span></button>
            <nav class="menu" id="menu">
                <a class="<?= isset($a) && is_callable($a) ? $a('home') : '' ?>" href="./">Home</a>
                <div class="dd-wrap">
                    <button class="dd-toggle<?= !empty($aboutGroup) ? ' active' : '' ?>" aria-expanded="false"
                        aria-haspopup="true">About Us</button>
                    <div class="dd">
                        <a class="<?= isset($a) && is_callable($a) ? $a('about') : '' ?>" href="about">About
                            Us<small>Our story and values</small></a>
                        <a class="<?= isset($a) && is_callable($a) ? $a('how') : '' ?>" href="how-it-works">How It
                            Works<small>From first hello to puppy
                                at home</small></a>
                        <a class="<?= isset($a) && is_callable($a) ? $a('reviews') : '' ?>"
                            href="reviews">Reviews<small>Google reviews and video
                                stories</small></a>
                    </div>
                </div>
                <a class="<?= isset($a) && is_callable($a) ? $a('breeds') : '' ?>" href="breeds">Breeds</a>
                <a class="<?= isset($a) && is_callable($a) ? $a('gallery') : '' ?>" href="gallery">Gallery</a>
                <a class="<?= isset($a) && is_callable($a) ? $a('blog') : '' ?>" href="blog">Blog</a>
                <a class="<?= isset($a) && is_callable($a) ? $a('contact') : '' ?>" href="contact">Contact</a>
                <a class="btn btn-teal" href="tel:<?= h($tel ?? '') ?>">Call now</a>
            </nav>
        </div>
    </header>