<?php
include 'config/connect.php';
// Output as XML
header("Content-Type: application/xml; charset=utf-8");

$base_url = "http://localhost/projects/kyrapet"; 
// Note: Live hone par isko badal kar "https://yourdomain.com" kar dena

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    // Function to print XML nodes
    function addUrlNode($url, $freq = "weekly", $priority = "0.8") {
    echo " <url>\n";
        echo " <loc>" . htmlspecialchars($url) . "</loc>\n";
        echo " <changefreq>$freq</changefreq>\n";
        echo " <priority>$priority</priority>\n";
        echo " </url>\n";
    }

    // 1. Static Pages
    $static_pages = [
    '', '/about', '/contact', '/gallery', '/breeds', '/blog',
    '/reviews', '/how-it-works', '/location', '/privacy-policy',
    '/refund-policy', '/shipping-policy', '/terms-condition'
    ];
    foreach ($static_pages as $page) {
    addUrlNode($base_url . $page, ($page == '' ? 'daily' : 'weekly'), ($page == '' ? '1.0' : '0.8'));
    }

    // 2. Dynamic Breed / Product Pages
    $prod_q = mysqli_query($conn, "SELECT slug_url FROM products WHERE status = 1");
    if ($prod_q) {
    while ($row = mysqli_fetch_assoc($prod_q)) {
    addUrlNode($base_url . '/breed/' . $row['slug_url'], 'weekly', '0.9');
    }
    }

    // 3. Dynamic Blog Pages
    $blog_q = mysqli_query($conn, "SELECT slug FROM blogs WHERE status = 1");
    if ($blog_q) {
    while ($row = mysqli_fetch_assoc($blog_q)) {
    addUrlNode($base_url . '/blog-post/' . $row['slug'], 'monthly', '0.7');
    }
    }

    // 4. Dynamic Custom Pages (Locations & Guides)
    $page_q = mysqli_query($conn, "SELECT slug_url FROM custom_pages WHERE status = 1 AND slug_url <> ''");
        if ($page_q) {
        while ($row = mysqli_fetch_assoc($page_q)) {
        addUrlNode($base_url . '/page/' . $row['slug_url'], 'monthly', '0.7');
        }
        }

        echo '</urlset>';
?>