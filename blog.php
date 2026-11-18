<?php
require __DIR__.'/config/connect.php';
require __DIR__.'/includes/config.php';

$page = 'blog';

// ==========================================
// 1. DYNAMIC META (TITLE, DESC, KEYWORD) & SCHEMA
// ==========================================
$meta_q = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='blog.php' LIMIT 1");
$meta_data = ($meta_q && mysqli_num_rows($meta_q) > 0) ? mysqli_fetch_assoc($meta_q) : null;

$title    = !empty($meta_data['meta_title']) ? $meta_data['meta_title'] : 'Pet Care Blog | Puppy Tips and Advice | Kyra Pet Shop';
$desc     = !empty($meta_data['meta_desc']) ? $meta_data['meta_desc'] : 'Simple, honest puppy care advice: feeding, training, vaccination, choosing the right breed and more.';
$keywords = !empty($meta_data['meta_key']) ? $meta_data['meta_key'] : 'Kyra Pet Shop, puppy care, dog blog';
$meta_key = $keywords;

$schema_q = mysqli_query($conn, "SELECT * FROM page_schemas WHERE page_url='blog.php' LIMIT 1");
$schema_data = ($schema_q && mysqli_num_rows($schema_q) > 0) ? mysqli_fetch_assoc($schema_q) : null;
$schema_markup = $schema_data['schema_markup'] ?? '';

// ==========================================
// 2. FETCH BLOGS FROM DATABASE
// ==========================================
$db_blog = [];
$blog_q = mysqli_query($conn, "SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC");
if ($blog_q) {
    while ($row = mysqli_fetch_assoc($blog_q)) {
        $clean_desc = trim(preg_replace('/\s+/', ' ', strip_tags($row['description'])));
        $excerpt = mb_strlen($clean_desc) > 150 ? mb_substr($clean_desc, 0, 150) . '...' : $clean_desc;
        
        // Reading time calculate karna
        $word_count = str_word_count($clean_desc);
        $read_min = max(3, ceil($word_count / 180)) . ' min read';

        $db_blog[] = [
            'slug'    => $row['slug'],
            'title'   => $row['title'],
            'cat'     => 'Pet Care',
            'date'    => date('M d, Y', strtotime($row['created_at'])),
            'read'    => $read_min,
            'img'     => !empty($row['image']) ? 'admin/assets/img/uploads/blogs/' . $row['image'] : ($P['golden'] ?? ''),
            'excerpt' => $excerpt
        ];
    }
}

require __DIR__.'/includes/header.php';

// Header include hone ke baad fallback set karna taaki $blog overwrite na ho
$display_blog = !empty($db_blog) ? $db_blog : ($blog ?? []);
$cats  = array_values(array_unique(array_column($display_blog, 'cat')));
$first = $display_blog[0] ?? null;
$fu    = $first ? 'blog/' . rawurlencode($first['slug']) : '#';

if (function_exists('banner')) {
    banner('Pet Care Blog','Blog','Practical tips for new and experienced puppy parents.');
}
?>

<style>
/* ================= BLOG PAGE IMAGE & CARD STYLING FIX ================= */
.fpost {
    display: grid !important;
    grid-template-columns: 1.15fr 1fr !important;
    gap: 36px !important;
    align-items: center !important;
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 26px !important;
    padding: 24px !important;
    margin-bottom: 44px !important;
    box-shadow: 0 16px 40px rgba(15, 23, 42, 0.06) !important;
    overflow: hidden !important;
}

.fpost .bim {
    position: relative !important;
    display: block !important;
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    min-height: 0 !important;
    border-radius: 18px !important;
    overflow: hidden !important;
    background: #f8fafc !important;
    border: 1px solid #eef2f6 !important;
}

.fpost .bim img {
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    object-fit: contain !important;
    display: block !important;
    transition: transform 0.45s ease !important;
}

.fpost:hover .bim img {
    transform: scale(1.02) !important;
}

.fpost .bb {
    min-width: 0 !important;
    padding: 10px 14px 10px 0 !important;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
}

.fpost-meta-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
}

.fpost-badge {
    display: inline-flex;
    align-items: center;
    background: var(--teal, #0d9488);
    color: #ffffff;
    font-size: 12.5px;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px;
    letter-spacing: 0.3px;
}

.fpost .bmeta {
    margin: 0 !important;
    font-size: 14px !important;
    color: #64748b !important;
    font-weight: 600 !important;
}

.fpost .bb h2 {
    font-size: clamp(22px, 2.5vw, 32px) !important;
    line-height: 1.3 !important;
    margin: 0 0 14px !important;
    color: var(--ink, #0f172a) !important;
    font-weight: 800 !important;
}

.fpost .bb h2 a {
    color: inherit !important;
    text-decoration: none !important;
    transition: color 0.2s ease;
}

.fpost .bb h2 a:hover {
    color: var(--teal, #0d9488) !important;
}

.fpost .bb p {
    color: #475569 !important;
    font-size: 16px !important;
    line-height: 1.7 !important;
    margin: 0 0 24px !important;
}

/* Blog Grid Cards Below */
.bgrid {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 28px !important;
}

.bgrid article,
.bgrid .bcard {
    background: #ffffff !important;
    border-radius: 20px !important;
    border: 1px solid #e2e8f0 !important;
    overflow: hidden !important;
    display: flex !important;
    flex-direction: column !important;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05) !important;
    transition: transform 0.35s ease, box-shadow 0.35s ease !important;
}

.bgrid article:hover,
.bgrid .bcard:hover {
    transform: translateY(-6px) !important;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.1) !important;
}

.bgrid article > a:first-child,
.bgrid .bim {
    display: block !important;
    width: 100% !important;
    overflow: hidden !important;
    background: #f8fafc !important;
    position: relative !important;
}

.bgrid article img {
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important;
    aspect-ratio: 16 / 10 !important;
    object-fit: contain !important;
    background: #f8fafc !important;
    display: block !important;
    transition: transform 0.45s ease !important;
}

.bgrid article:hover img {
    transform: scale(1.03) !important;
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .fpost {
        grid-template-columns: 1fr !important;
        gap: 22px !important;
        padding: 18px !important;
    }
    .fpost .bb {
        padding: 4px 6px 8px !important;
    }
    .bgrid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 640px) {
    .bgrid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<main>
    <section style="padding-top:30px">
        <div class="wrap">
            <?php if ($first): 
                $first_img = (strpos($first['img'], '/') !== false) ? $first['img'] : ph($first['img'], 1100);
            ?>
            <article class="fpost rv">
                <a class="bim" href="<?= h($fu) ?>">
                    <img src="<?= h($first_img) ?>" alt="<?= h($first['title']) ?>" fetchpriority="high">
                </a>
                <div class="bb">
                    <div class="fpost-meta-row">
                        <span class="fpost-badge">Featured · <?= h($first['cat']) ?></span>
                        <div class="bmeta"><?= h($first['date']) ?> · <?= h($first['read']) ?></div>
                    </div>
                    <h2><a href="<?= h($fu) ?>"><?= h($first['title']) ?></a></h2>
                    <p><?= h($first['excerpt']) ?></p>
                    <div><a class="btn btn-teal" href="<?= h($fu) ?>">Read article</a></div>
                </div>
            </article>
            <?php endif; ?>

            <div class="fchips" role="group" aria-label="Filter articles">
                <button class="active" data-v="all">All articles</button>
                <?php foreach($cats as $c): ?><button data-v="<?= h($c) ?>"><?= h($c) ?></button><?php endforeach; ?>
            </div>

            <div class="bgrid" id="grid">
                <?php foreach($display_blog as $b) {
                    if (function_exists('blog_card')) blog_card($b);
                } ?>
            </div>

            <div class="empty" id="empty" <?= empty($display_blog) ? 'style="display:block;"' : '' ?>>
                <b>No articles yet</b>Check back soon.
            </div>

            <div class="leave rv" style="margin-top:64px">
                <div>
                    <h3>Have a question about your puppy?</h3>
                    <p>Our team is happy to help with feeding, training, health and grooming. Message us any time.</p>
                </div>
                <div class="cta" style="margin:0">
                    <a class="btn btn-teal" target="_blank" rel="noopener"
                        href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I have a question about puppy care.') ?>">Ask on WhatsApp</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>