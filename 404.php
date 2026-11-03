<?php
http_response_code(404);
require_once __DIR__ . '/config/connect.php';
require_once __DIR__ . '/includes/config.php';

$page  = '404';
$title = '404 - Page Not Found | Kyra Pet Shop';
$desc  = 'Oops! The page you are looking for might have wandered off. Explore healthy, vaccinated puppies at Kyra Pet Shop.';

require_once __DIR__ . '/includes/header.php';
?>

<style>
.err404-sec {
    padding: 80px 0 100px;
    background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.err404-card {
    max-width: 680px;
    margin: 0 auto;
    background: #ffffff;
    border-radius: 28px;
    padding: 56px 40px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.07);
    position: relative;
}

@media (max-width: 640px) {
    .err404-card {
        padding: 40px 22px;
    }
}

.err404-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #f0fdfa;
    color: var(--teal, #0d9488);
    border: 1px solid #99f6e4;
    padding: 6px 16px;
    border-radius: 999px;
    font-size: 13.5px;
    font-weight: 700;
    margin-bottom: 18px;
}

.err404-code {
    font-size: clamp(72px, 12vw, 120px);
    font-weight: 900;
    line-height: 1;
    color: var(--navy, #0f172a);
    letter-spacing: -3px;
    margin: 0 0 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.err404-code span {
    color: var(--teal, #0d9488);
}

.err404-card h1 {
    font-size: clamp(24px, 3.2vw, 34px);
    font-weight: 800;
    color: var(--ink, #0f172a);
    margin: 0 0 12px;
}

.err404-card p {
    color: var(--mute, #475569);
    font-size: 16.5px;
    line-height: 1.65;
    max-width: 520px;
    margin: 0 auto 30px;
}

.err404-search {
    display: flex;
    gap: 10px;
    max-width: 460px;
    margin: 0 auto 30px;
}

@media (max-width: 480px) {
    .err404-search {
        flex-direction: column;
    }
}

.err404-search input {
    flex: 1;
    padding: 13px 18px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    font-size: 15px;
    outline: none;
}

.err404-search input:focus {
    border-color: var(--teal, #0d9488);
    box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.12);
}

.err404-actions {
    display: flex;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
}

.err404-links {
    margin-top: 34px;
    padding-top: 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: center;
    gap: 22px;
    flex-wrap: wrap;
    font-size: 14px;
}

.err404-links a {
    color: #475569;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}

.err404-links a:hover {
    color: var(--teal, #0d9488);
}
</style>

<main>
    <section class="err404-sec">
        <div class="wrap">
            <div class="err404-card">
                <div class="err404-badge">🐾 Oops! Page Not Found</div>
                <div class="err404-code">4<span>🐾</span>4</div>
                <h1>Looks like this puppy wandered off!</h1>
                <p>The page or link you are looking for doesn't exist, may have been moved, or had its URL changed.
                    Let's get you back on track.</p>

                <!-- Quick Search Bar -->
                <form action="breeds.php" method="GET" class="err404-search">
                    <input type="text" name="search" placeholder="Search for a puppy breed (e.g. Shih Tzu, Pug)..."
                        required>
                    <button type="submit" class="btn btn-teal">Search</button>
                </form>

                <!-- Primary CTA Buttons -->
                <div class="err404-actions">
                    <a href="./" class="btn btn-navy">← Back to Home</a>
                    <a href="breeds" class="btn btn-teal">Browse All Puppies</a>

                    <a href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I need help finding a puppy.') ?>"
                        target="_blank" rel="noopener" class="btn btn-navy">Chat on WhatsApp</a>
                </div>

                <!-- Helpful Quick Links -->
                <div class="err404-links">
                    <a href="about">About Us</a>
                    <a href="location">Locations &amp; Guides</a>
                    <a href="gallery">Puppy Gallery</a>
                    <a href="reviews">Customer Reviews</a>
                    <a href="contact">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>