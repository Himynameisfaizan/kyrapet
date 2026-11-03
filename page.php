<?php
require __DIR__ . '/config/connect.php';
require __DIR__ . '/includes/config.php';

$page = 'custom-page';

$slug = isset($_GET['slug']) ? mysqli_real_escape_string($conn, trim($_GET['slug'])) : '';

$pageData = null;
if ($slug !== '') {
    $pageQuery = mysqli_query($conn, "SELECT * FROM custom_pages WHERE slug_url = '$slug' AND status = 1 LIMIT 1");
    if ($pageQuery && mysqli_num_rows($pageQuery) > 0) {
        $pageData = mysqli_fetch_assoc($pageQuery);
    }
}

// Agar galat slug hai toh bina redirect kiye seedha 404 page dikhao
if (!$pageData) {
    http_response_code(404);
    if (file_exists(__DIR__ . '/404.php')) {
        include __DIR__ . '/404.php';
        exit;
    } else {
        die('404 - Page Not Found');
    }
}

// ==========================================
// 1. SEO VARIABLES MAPPED FOR includes/header.php
// ==========================================
$rawContentPlain = trim(preg_replace('/\s+/', ' ', strip_tags((string)($pageData['content'] ?? ''))));

$title    = !empty($pageData['meta_title']) ? $pageData['meta_title'] : $pageData['page_title'];
$desc     = !empty($pageData['meta_desc']) ? $pageData['meta_desc'] : mb_substr($rawContentPlain, 0, 160);
$meta_key = $pageData['meta_key'] ?? '';

$raw_schema = trim($pageData['schema_markup'] ?? '');
if (!empty($raw_schema) && ($raw_schema[0] === '{' || stripos($raw_schema, '<script') !== false)) {
    $schema_markup = $raw_schema;
}

$breadcrumb_name = !empty($pageData['breadcrumb_title']) ? $pageData['breadcrumb_title'] : $pageData['page_title'];
$wordCount       = str_word_count($rawContentPlain);
$readMins        = max(2, (int)ceil($wordCount / 200));
$pubDate         = !empty($pageData['created_at']) ? date('M d, Y', strtotime($pageData['created_at'])) : date('M d, Y');

// Phone Number fetch for Call-to-action
$contactQuery = mysqli_query($conn, "SELECT phone FROM contacts LIMIT 1");
$contactInfo  = ($contactQuery && mysqli_num_rows($contactQuery) > 0) ? mysqli_fetch_assoc($contactQuery) : [];
$sitePhone    = !empty($contactInfo['phone']) ? $contactInfo['phone'] : ($site['phone'] ?? '+91-9582799502');

require __DIR__ . '/includes/header.php';
?>

<style>
/* ================= KYRA PET SHOP - CUSTOM PAGE DETAIL THEME ================= */
.cpage-hero {
    background: linear-gradient(135deg, var(--navy, #0f172a) 0%, #1e293b 100%);
    padding: 56px 0 50px;
    color: #ffffff;
    position: relative;
    overflow: hidden;
}

.cpage-hero::after {
    content: '🐾';
    position: absolute;
    right: 5%;
    bottom: -20px;
    font-size: 130px;
    opacity: 0.06;
    transform: rotate(-15deg);
    pointer-events: none;
}

.cpage-breadcrumb {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13.5px;
    color: #94a3b8;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

.cpage-breadcrumb a {
    color: #5eead4;
    text-decoration: none;
    font-weight: 600;
}

.cpage-breadcrumb a:hover {
    text-decoration: underline;
}

.cpage-hero h1 {
    color: #ffffff;
    font-size: clamp(26px, 3.6vw, 42px);
    font-weight: 800;
    margin: 0 0 10px;
    line-height: 1.25;
}

.cpage-hero p {
    color: #cbd5e1;
    font-size: 16px;
    max-width: 720px;
    margin: 0;
}

.cpage-sec {
    padding: 50px 0 80px;
    background: #f8fafc;
}

.cpage-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 360px;
    gap: 36px;
    align-items: start;
}

@media (max-width: 1024px) {
    .cpage-layout {
        grid-template-columns: 1fr;
    }
}

/* Main Content Card - Forced Visible (No .rv opacity hide bug) */
.cpage-main {
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 12px 35px rgba(15, 23, 42, 0.05);
    padding: 40px 44px;
    opacity: 1 !important;
    visibility: visible !important;
    transform: none !important;
}

@media (max-width: 640px) {
    .cpage-main {
        padding: 24px 20px;
        border-radius: 18px;
    }
}

.cpage-meta-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
    padding-bottom: 22px;
    margin-bottom: 28px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 13.5px;
    color: #64748b;
}

.cpage-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f0fdfa;
    color: var(--teal, #0d9488);
    border: 1px solid #99f6e4;
    padding: 5px 13px;
    border-radius: 999px;
    font-weight: 600;
}

.cpage-top-title {
    font-size: clamp(24px, 3vw, 32px);
    font-weight: 800;
    color: var(--ink, #0f172a);
    margin: 0 0 22px;
    line-height: 1.3;
}

/* Rich Content Typography */
.kyra-article-body {
    color: #334155 !important;
    font-size: 16.5px;
    line-height: 1.8;
    overflow-wrap: break-word;
    opacity: 1 !important;
    visibility: visible !important;
}

.kyra-article-body p {
    margin: 0 0 18px;
    font-weight: 400;
    color: #334155;
}

.kyra-article-body p>strong:only-child {
    font-weight: 400;
    color: #334155;
}

.kyra-article-body h1,
.kyra-article-body h2,
.kyra-article-body h3,
.kyra-article-body h4 {
    color: var(--ink, #0f172a) !important;
    font-weight: 800;
    line-height: 1.3;
    margin: 32px 0 14px;
}

.kyra-article-body h1:first-child,
.kyra-article-body h2:first-child {
    margin-top: 0;
}

.kyra-article-body h1 {
    font-size: clamp(22px, 2.8vw, 30px);
}

.kyra-article-body h2 {
    font-size: clamp(19px, 2.3vw, 24px);
    padding-bottom: 10px;
    border-bottom: 2px solid #f1f5f9;
}

.kyra-article-body h3 {
    font-size: 18px;
    color: #1e293b !important;
}

.kyra-article-body a {
    color: var(--teal, #0d9488);
    font-weight: 600;
    text-decoration: underline;
    text-underline-offset: 3px;
}

.kyra-article-body a:hover {
    color: var(--navy, #0f172a);
}

/* Styled Lists */
.kyra-article-body ul,
.kyra-article-body ol {
    margin: 0 0 22px;
    padding-left: 0;
    list-style: none;
}

.kyra-article-body ul li,
.kyra-article-body ol li {
    position: relative;
    padding: 9px 14px 9px 38px;
    margin-bottom: 8px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    font-size: 15.5px;
    color: #1e293b;
}

.kyra-article-body ul li>strong:only-child {
    font-weight: 600;
}

.kyra-article-body ul li::before {
    content: '✓';
    position: absolute;
    left: 12px;
    top: 10px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: var(--teal, #0d9488);
    color: #ffffff;
    font-size: 11px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive & Styled Tables */
.kyra-article-body table {
    width: 100%;
    border-collapse: collapse;
    margin: 26px 0;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
    border: 1px solid #e2e8f0;
}

.kyra-article-body table tr:first-child td,
.kyra-article-body table th {
    background: var(--navy, #0f172a);
    color: #ffffff !important;
    font-weight: 700;
    padding: 14px 18px;
    text-align: left;
    border: none;
}

.kyra-article-body table tr:first-child td p,
.kyra-article-body table tr:first-child td strong {
    color: #ffffff !important;
    margin: 0;
    font-weight: 700;
}

.kyra-article-body table td {
    padding: 13px 18px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 15px;
    vertical-align: top;
}

.kyra-article-body table td p {
    margin: 0;
}

.kyra-article-body table tr:nth-child(even) td {
    background: #f8fafc;
}

.kyra-article-body img {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    margin: 20px 0;
}

/* Share Bar */
.cpage-share {
    margin-top: 40px;
    padding-top: 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
}

.cpage-share-label {
    font-weight: 700;
    color: var(--ink, #0f172a);
    font-size: 15px;
}

.cpage-share-btns {
    display: flex;
    gap: 10px;
}

.s-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
    color: #334155;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.25s ease;
}

.s-btn:hover {
    background: var(--teal, #0d9488);
    color: #ffffff;
    border-color: var(--teal, #0d9488);
    transform: translateY(-3px);
}

/* Sidebar Widgets */
.cpage-sidebar {
    position: sticky;
    top: 96px;
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.kyra-widget {
    background: #ffffff;
    border-radius: 20px;
    border: 1px solid #e2e8f0;
    padding: 24px;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
}

.kyra-widget-title {
    font-size: 17px;
    font-weight: 800;
    color: var(--ink, #0f172a);
    margin: 0 0 16px;
    padding-bottom: 12px;
    border-bottom: 2px solid #f1f5f9;
    display: flex;
    align-items: center;
    gap: 8px;
}

.kyra-side-search {
    display: flex;
    gap: 8px;
}

.kyra-side-search input {
    flex: 1;
    padding: 11px 14px;
    border: 1px solid #cbd5e1;
    border-radius: 10px;
    font-size: 14px;
    outline: none;
}

.kyra-side-search input:focus {
    border-color: var(--teal, #0d9488);
}

.kyra-side-search button {
    background: var(--teal, #0d9488);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 0 16px;
    font-weight: 600;
    cursor: pointer;
}

.kyra-side-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.kyra-side-list a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border-radius: 10px;
    background: #f8fafc;
    color: #334155;
    text-decoration: none;
    font-size: 14.5px;
    font-weight: 600;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.kyra-side-list a:hover,
.kyra-side-list a.active {
    background: #f0fdfa;
    color: var(--teal, #0d9488);
    border-color: #99f6e4;
    transform: translateX(4px);
}

.kyra-mini-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 10px 0;
    border-bottom: 1px solid #f1f5f9;
    text-decoration: none;
    color: inherit;
}

.kyra-mini-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.kyra-mini-item img {
    width: 62px;
    height: 62px;
    border-radius: 12px;
    object-fit: cover;
    flex-shrink: 0;
    background: #f1f5f9;
}

.kyra-mini-item h5 {
    margin: 0 0 4px;
    font-size: 14.5px;
    font-weight: 700;
    color: var(--ink, #0f172a);
    line-height: 1.35;
}

.kyra-mini-item:hover h5 {
    color: var(--teal, #0d9488);
}

.kyra-mini-item span {
    font-size: 12.5px;
    color: #64748b;
}

.kyra-cta-widget {
    background: linear-gradient(145deg, var(--navy, #0f172a) 0%, #1e293b 100%);
    border-radius: 22px;
    padding: 28px 24px;
    color: #ffffff;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.kyra-cta-widget h4 {
    color: #ffffff;
    font-size: 20px;
    font-weight: 800;
    margin: 10px 0 8px;
}

.kyra-cta-widget p {
    color: #cbd5e1;
    font-size: 14px;
    line-height: 1.6;
    margin: 0 0 20px;
}

.kyra-cta-widget .btn {
    width: 100%;
    justify-content: center;
    margin-bottom: 10px;
}
</style>

<main>
    <!-- Built-in Kyra Hero Header -->
    <section class="cpage-hero">
        <div class="wrap">
            <div class="cpage-breadcrumb">
                <a href="./">Home</a>
                <span>/</span>
                <a href="location">Locations &amp; Guides</a>
                <span>/</span>
                <span><?= h($breadcrumb_name) ?></span>
            </div>
            <h1><?= h($pageData['page_title']) ?></h1>
            <p>Verified information, expert guidance, and complete details from Kyra Pet Shop.</p>
        </div>
    </section>

    <section class="cpage-sec">
        <div class="wrap">
            <div class="cpage-layout">

                <!-- LEFT COLUMN: MAIN CONTENT (No .rv class so it never stays hidden) -->
                <article class="cpage-main">
                    <div class="cpage-meta-bar">
                        <span class="cpage-meta-pill">🐾 <?= h($breadcrumb_name) ?></span>
                        <span>📅 Published <?= h($pubDate) ?></span>
                        <span>⏱ <?= $readMins ?> min read</span>
                        <span>✔ Verified by Kyra Pet Shop</span>
                    </div>

                    <div class="kyra-article-body">
                        <?php if (!empty(trim($pageData['content']))): ?>
                        <?= $pageData['content'] ?>
                        <?php else: ?>
                        <p>Content for this page is being updated. Please contact us on WhatsApp or call us directly for
                            immediate assistance.</p>
                        <?php endif; ?>
                    </div>

                    <!-- Social Share Bar -->
                    <?php
                    $protocol    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                    $current_url = $protocol . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '');
                    ?>
                    <div class="cpage-share">
                        <span class="cpage-share-label">Share this guide with fellow pet parents:</span>
                        <div class="cpage-share-btns">
                            <a href="https://wa.me/?text=<?= rawurlencode($pageData['page_title'] . ' - ' . $current_url) ?>"
                                target="_blank" rel="noopener" class="s-btn" aria-label="Share on WhatsApp">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                    </path>
                                </svg>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= rawurlencode($current_url) ?>"
                                target="_blank" rel="noopener" class="s-btn" aria-label="Share on Facebook">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?= rawurlencode($current_url) ?>&text=<?= rawurlencode($pageData['page_title']) ?>"
                                target="_blank" rel="noopener" class="s-btn" aria-label="Share on X">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                    </path>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= rawurlencode($current_url) ?>"
                                target="_blank" rel="noopener" class="s-btn" aria-label="Share on LinkedIn">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z">
                                    </path>
                                    <rect x="2" y="9" width="4" height="12"></rect>
                                    <circle cx="4" cy="4" r="2"></circle>
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>

                <!-- RIGHT COLUMN: SIDEBAR -->
                <aside class="cpage-sidebar">

                    <!-- 1. Search Breeds Widget -->
                    <div class="kyra-widget">
                        <h4 class="kyra-widget-title">🔍 Search Puppies</h4>
                        <form action="breeds.php" method="GET" class="kyra-side-search">
                            <input type="text" name="search" placeholder="e.g. Golden Retriever, Pug..." required>
                            <button type="submit">Go</button>
                        </form>
                    </div>

                    <!-- 2. Direct Consultation CTA Box -->
                    <div class="kyra-cta-widget">
                        <span class="tag" style="background:rgba(13,148,136,0.25); color:#5eead4;">100% Vaccinated
                            Puppies</span>
                        <h4>Bring Home a Healthy Puppy Today!</h4>
                        <p>Chat with <?= h($site['owner'] ?? 'Mr. Nitin') ?> for live puppy videos, health records, and
                            doorstep guidance.</p>
                        <a href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I was reading "' . $pageData['page_title'] . '" and want to inquire about puppies.') ?>"
                            target="_blank" rel="noopener" class="btn btn-teal">
                            💬 Chat on WhatsApp
                        </a>
                        <a href="tel:<?= h($tel ?? $sitePhone) ?>" class="btn btn-line"
                            style="color:#fff; border-color:rgba(255,255,255,0.25);">
                            📞 Call <?= h($sitePhone) ?>
                        </a>
                    </div>

                    <!-- 3. Featured Puppies / Breeds Widget -->
                    <?php
                    $sideBreeds = mysqli_query($conn, "SELECT pro_name, brand_name, slug_url, pro_img, age FROM products WHERE status = 1 ORDER BY id DESC LIMIT 4");
                    if ($sideBreeds && mysqli_num_rows($sideBreeds) > 0):
                    ?>
                    <div class="kyra-widget">
                        <h4 class="kyra-widget-title">🐶 Available Puppies</h4>
                        <div>
                            <?php while ($sb = mysqli_fetch_assoc($sideBreeds)):
                                $sb_img  = !empty($sb['pro_img']) ? 'admin/assets/img/uploads/' . $sb['pro_img'] : ph($P['golden'] ?? '', 200);
                                $sb_link = !empty($sb['slug_url']) ? 'breed.php?slug=' . rawurlencode($sb['slug_url']) : 'breeds.php';
                            ?>
                            <a href="<?= h($sb_link) ?>" class="kyra-mini-item">
                                <img src="<?= h($sb_img) ?>" alt="<?= h($sb['pro_name']) ?>" loading="lazy">
                                <div>
                                    <h5><?= h($sb['pro_name']) ?></h5>
                                    <span><?= h($sb['brand_name'] ?: 'Pure Breed') ?> ·
                                        <?= h($sb['age'] ?: '8 Weeks') ?></span>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        </div>
                        <div style="margin-top:14px; text-align:center;">
                            <a href="breeds.php"
                                style="font-size:13.5px; font-weight:700; color:var(--teal, #0d9488); text-decoration:none;">View
                                All Breeds →</a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- 4. Other Custom Pages / Locations Widget -->
                    <?php
                    $otherPages = mysqli_query($conn, "SELECT page_title, breadcrumb_title, slug_url FROM custom_pages WHERE status = 1 AND slug_url <> '' ORDER BY id DESC LIMIT 6");
                    if ($otherPages && mysqli_num_rows($otherPages) > 0):
                    ?>
                    <div class="kyra-widget">
                        <h4 class="kyra-widget-title">📍 Explore More Guides</h4>
                        <ul class="kyra-side-list">
                            <?php while ($op = mysqli_fetch_assoc($otherPages)):
                                $opTitle  = !empty($op['breadcrumb_title']) ? $op['breadcrumb_title'] : $op['page_title'];
                                $isActive = ($op['slug_url'] === $slug) ? 'active' : '';
                            ?>
                            <li>
                                <a href="page/<?= rawurlencode($op['slug_url']) ?>" class="<?= $isActive ?>">
                                    <span><?= h(ucwords($opTitle)) ?></span>
                                    <span>→</span>
                                </a>
                            </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <?php endif; ?>

                    <!-- 5. Recent Blogs Widget -->
                    <?php
                    $recentQuery = mysqli_query($conn, "SELECT title, slug, image, created_at FROM blogs WHERE status = 1 ORDER BY created_at DESC LIMIT 3");
                    if ($recentQuery && mysqli_num_rows($recentQuery) > 0):
                    ?>
                    <div class="kyra-widget">
                        <h4 class="kyra-widget-title">📖 Recent Articles</h4>
                        <div>
                            <?php while ($rb = mysqli_fetch_assoc($recentQuery)):
                                $r_date = !empty($rb['created_at']) ? date('M d, Y', strtotime($rb['created_at'])) : '';
                                $r_img  = !empty($rb['image']) ? 'admin/assets/img/uploads/blogs/' . $rb['image'] : ph($P['lab'] ?? '', 200);
                            ?>
                            <a href="blog-post.php?slug=<?= rawurlencode($rb['slug']) ?>" class="kyra-mini-item">
                                <img src="<?= h($r_img) ?>" alt="<?= h($rb['title']) ?>" loading="lazy">
                                <div>
                                    <h5><?= h($rb['title']) ?></h5>
                                    <span><?= h($r_date) ?></span>
                                </div>
                            </a>
                            <?php endwhile; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </aside>

            </div>
        </div>
    </section>

    <?php if (function_exists('cta_band')) cta_band(); ?>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>