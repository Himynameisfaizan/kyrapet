<?php
require __DIR__ . '/config/connect.php';
require __DIR__ . '/includes/config.php';

$page = 'locations';

// ==========================================
// 1. DYNAMIC META & SCHEMA FETCHING
// ==========================================
$meta_q = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='location.php' LIMIT 1");
if ($meta_q && mysqli_num_rows($meta_q) > 0) {
    $meta_data = mysqli_fetch_assoc($meta_q);
    $title    = $meta_data['meta_title'];
    $desc     = $meta_data['meta_desc'];
    $meta_key = $meta_data['meta_key'] ?? '';
} else {
    $title    = 'Locations & Pet Guides | Kyra Pet Shop New Delhi';
    $desc     = 'Explore our location guides, puppy availability across Delhi NCR, and helpful resources from Kyra Pet Shop.';
    $meta_key = 'Kyra Pet Shop locations, puppies in Delhi NCR, pet shop guides';
}

$schema_q = mysqli_query($conn, "SELECT * FROM page_schemas WHERE page_url='location.php' LIMIT 1");
if ($schema_q && mysqli_num_rows($schema_q) > 0) {
    $schema_data   = mysqli_fetch_assoc($schema_q);
    $schema_markup = $schema_data['schema_markup'];
}

// ==========================================
// 2. FETCH ACTIVE CUSTOM PAGES
// ==========================================
$customPages = [];
$sql = "SELECT id, page_title, breadcrumb_title, slug_url, content, meta_title, meta_desc, created_at
        FROM custom_pages
        WHERE status = 1 AND slug_url <> ''
        ORDER BY id DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $customPages[] = $row;
    }
}

require __DIR__ . '/includes/header.php';
if (function_exists('banner')) {
    banner(
        'Our Locations & Guides',
        'Locations',
        'Explore our location-wise puppy availability, expert pet care guides, and trusted services.'
    );
}
?>

<style>
/* ================= KYRA PET SHOP - LOCATIONS & CUSTOM PAGES THEME ================= */
.loc-sec {
    padding: 60px 0 80px;
}

.loc-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    background: #ffffff;
    padding: 18px 24px;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
    border: 1px solid #e2e8f0;
    margin-bottom: 40px;
}

.loc-search-wrap {
    position: relative;
    flex: 1 1 320px;
    max-width: 480px;
}

.loc-search-wrap input {
    width: 100%;
    padding: 13px 18px 13px 44px;
    border-radius: 12px;
    border: 1px solid #cbd5e1;
    font-size: 15px;
    color: var(--ink, #0f172a);
    background: #f8fafc;
    transition: all 0.25s ease;
    outline: none;
}

.loc-search-wrap input:focus {
    background: #ffffff;
    border-color: var(--teal, #0d9488);
    box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.12);
}

.loc-search-wrap svg {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: #64748b;
    pointer-events: none;
}

.loc-count-pill {
    background: #f0fdfa;
    color: var(--teal, #0d9488);
    border: 1px solid #99f6e4;
    padding: 8px 16px;
    border-radius: 999px;
    font-size: 14px;
    font-weight: 600;
}

/* Cards Grid */
.loc-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

@media (max-width: 1024px) {
    .loc-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 640px) {
    .loc-grid {
        grid-template-columns: 1fr;
    }
}

.loc-card {
    background: #ffffff;
    border-radius: 22px;
    border: 1px solid #e2e8f0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
    transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.35s ease, border-color 0.35s ease;
    position: relative;
}

.loc-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 22px 45px rgba(15, 23, 42, 0.12);
    border-color: var(--teal, #0d9488);
}

/* Decorative Top Banner inside Card */
.loc-card-top {
    background: linear-gradient(135deg, var(--navy, #0f172a) 0%, #1e293b 100%);
    padding: 24px 24px 20px;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.loc-card-top::after {
    content: '🐾';
    position: absolute;
    right: -8px;
    bottom: -14px;
    font-size: 68px;
    opacity: 0.08;
    transform: rotate(-15deg);
    pointer-events: none;
}

.loc-sec .rv,
.loc-card {
    opacity: 1 !important;
    visibility: visible !important;
    transform: none !important;
}

.loc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(13, 148, 136, 0.22);
    border: 1px solid rgba(45, 212, 191, 0.35);
    color: #5eead4;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 999px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.loc-icon-circle {
    width: 42px;
    height: 42px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.1);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.3s ease, transform 0.3s ease;
}

.loc-card:hover .loc-icon-circle {
    background: var(--teal, #0d9488);
    transform: scale(1.08);
}

.loc-card-body {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.loc-card-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--ink, #0f172a);
    margin: 0 0 12px;
    line-height: 1.35;
    transition: color 0.25s ease;
}

.loc-card:hover .loc-card-title {
    color: var(--teal, #0d9488);
}

.loc-card-desc {
    color: var(--mute, #475569);
    font-size: 15px;
    line-height: 1.65;
    margin: 0 0 22px;
    flex-grow: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.loc-card-foot {
    padding-top: 16px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 13px;
    color: #64748b;
}

.loc-read-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: 700;
    font-size: 14px;
    color: var(--teal, #0d9488);
}

.loc-read-btn svg {
    width: 16px;
    height: 16px;
    transition: transform 0.25s ease;
}

.loc-card:hover .loc-read-btn svg {
    transform: translateX(4px);
}

.loc-empty {
    text-align: center;
    background: #ffffff;
    border-radius: 22px;
    padding: 60px 24px;
    border: 1px dashed #cbd5e1;
    color: var(--mute, #64748b);
}
</style>

<main>
    <section class="loc-sec">
        <div class="wrap">
            <div class="sec-head c rv">
                <span class="tag">Explore Directory</span>
                <h2>Locations &amp; Helpful Guides</h2>
                <p>Browse our dedicated location pages, breed guides, and verified information prepared by our team.</p>
            </div>

            <?php if (!empty($customPages)): ?>
            <!-- Search & Filter Bar -->
            <div class="loc-toolbar rv">
                <div class="loc-search-wrap">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="search" id="locSearchInput" placeholder="Search location or guide title..."
                        aria-label="Search pages">
                </div>
                <div class="loc-count-pill" id="locCountText">
                    Showing <?= count($customPages) ?> <?= count($customPages) === 1 ? 'Page' : 'Pages' ?>
                </div>
            </div>

            <!-- Cards Grid -->
            <div class="loc-grid" id="locGrid">
                <?php foreach ($customPages as $cp):
                        $cardTitle = trim((string)($cp['page_title'] ?? ''));
                        if ($cardTitle === '') {
                            $cardTitle = trim((string)($cp['breadcrumb_title'] ?? 'Explore Guide'));
                        }

                        $badgeText = trim((string)($cp['breadcrumb_title'] ?? ''));
                        if ($badgeText === '') {
                            $badgeText = 'Featured Guide';
                        }

                        // Extract clean excerpt from content or meta_desc
                        $rawContentText = trim(preg_replace('/\s+/', ' ', strip_tags((string)($cp['content'] ?? ''))));
                        $metaDescText   = trim(strip_tags((string)($cp['meta_desc'] ?? '')));

                        if (strlen($metaDescText) > 35 && strtolower($metaDescText) !== strtolower((string)$cp['slug_url'])) {
                            $description = $metaDescText;
                        } elseif ($rawContentText !== '') {
                            $description = mb_substr($rawContentText, 0, 160) . '...';
                        } else {
                            $description = 'Click to read full details, expert guidance, and complete information on this topic.';
                        }

                        // Estimate reading time
                        $wordCount = str_word_count($rawContentText);
                        $readMins  = max(2, (int)ceil($wordCount / 200));

                        $slug    = trim((string)($cp['slug_url'] ?? ''));
                        $pageUrl = 'page/' . rawurlencode($slug);
                    ?>
                <a href="<?= h($pageUrl) ?>" class="loc-card rv"
                    data-search="<?= h(strtolower($cardTitle . ' ' . $badgeText . ' ' . $description)) ?>">
                    <div class="loc-card-top">
                        <span class="loc-badge">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <?= h($badgeText) ?>
                        </span>
                        <span class="loc-icon-circle">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                        </span>
                    </div>

                    <div class="loc-card-body">
                        <h3 class="loc-card-title"><?= h($cardTitle) ?></h3>
                        <p class="loc-card-desc"><?= h($description) ?></p>

                        <div class="loc-card-foot">
                            <span>⏱ <?= $readMins ?> min read</span>
                            <span class="loc-read-btn">
                                Read Guide
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                    <polyline points="12 5 19 12 12 19"></polyline>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <div class="loc-empty" id="locNoMatch" style="display:none; margin-top: 24px;">
                <h3 style="margin-bottom:8px; color:var(--ink, #0f172a);">No matching pages found</h3>
                <p style="margin:0;">Try searching with a different keyword or browse all available breeds.</p>
            </div>

            <?php else: ?>
            <div class="loc-empty rv">
                <h3 style="margin-bottom:8px; color:var(--ink, #0f172a);">No custom pages published yet</h3>
                <p style="margin-bottom:20px;">New location guides and articles will appear here soon.</p>
                <a href="breeds.php" class="btn btn-teal">Browse Available Puppies</a>
            </div>
            <?php endif; ?>

            <!-- Bottom Inquiry Banner matching Breeds Page -->
            <div class="leave rv" style="margin-top:64px">
                <div>
                    <h3>Looking for a healthy puppy near your location?</h3>
                    <p>Talk directly with <?= h($site['owner'] ?? 'Mr. Nitin') ?> for photos, videos, vaccination
                        records, and safe visit details.</p>
                </div>
                <div class="cta" style="margin:0">
                    <a class="btn btn-teal" target="_blank" rel="noopener"
                        href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I want to inquire about available puppies.') ?>">Chat
                        on WhatsApp</a>
                    <a class="btn btn-line" href="tel:<?= h($tel ?? '') ?>">Call <?= h($site['phone'] ?? '') ?></a>
                </div>
            </div>
        </div>
    </section>

    <?php if (function_exists('cta_band')) cta_band(); ?>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('locSearchInput');
    const cards = document.querySelectorAll('#locGrid .loc-card');
    const countText = document.getElementById('locCountText');
    const noMatch = document.getElementById('locNoMatch');

    if (!searchInput) return;

    searchInput.addEventListener('input', function() {
        const q = this.value.trim().toLowerCase();
        let visible = 0;

        cards.forEach(card => {
            const text = card.getAttribute('data-search') || '';
            if (text.includes(q)) {
                card.style.display = 'flex';
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        if (countText) {
            countText.textContent = 'Showing ' + visible + (visible === 1 ? ' Page' : ' Pages');
        }
        if (noMatch) {
            noMatch.style.display = (visible === 0) ? 'block' : 'none';
        }
    });
});
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>