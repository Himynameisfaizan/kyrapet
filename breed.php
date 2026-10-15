<?php
require __DIR__.'/config/connect.php';
require __DIR__.'/includes/config.php';

$slug = trim($_GET['slug'] ?? '');
$dog = null;

// ==========================================
// 1. FETCH SINGLE BREED / PUPPY FROM DATABASE
// ==========================================
if ($slug !== '') {
    $slug_esc = mysqli_real_escape_string($conn, $slug);
    $prod_q = mysqli_query($conn, "SELECT * FROM products WHERE slug_url='$slug_esc' AND status=1 LIMIT 1");
    
    if ($prod_q && mysqli_num_rows($prod_q) > 0) {
        $row = mysqli_fetch_assoc($prod_q);
        
        // Short description (Upar lead text ke liye)
        $raw_short = !empty($row['short_desc']) ? $row['short_desc'] : ($row['description'] ?? '');
        $clean_short = trim(strip_tags($raw_short));
        if ($clean_short === '') {
            $clean_short = 'Healthy, purebred and vaccinated puppy raised with care at Kyra Pet Shop.';
        }

        // Long description (Niche Left Column ke liye)
        $long_desc = !empty($row['description']) ? $row['description'] : $raw_short;

        $dog = [
            'n'             => (int)$row['id'],
            'slug'          => $row['slug_url'],
            'name'          => $row['pro_name'],
            'breed'         => !empty($row['brand_name']) ? $row['brand_name'] : $row['pro_name'],
            'gender'        => !empty($row['gender']) ? $row['gender'] : 'Male / Female',
            'age'           => !empty($row['age']) ? $row['age'] : '8 Weeks',
            'size'          => !empty($row['size']) ? $row['size'] : 'Medium to Large',
            'temper'        => !empty($row['temper']) ? $row['temper'] : 'Friendly, playful, loyal',
            'img'           => !empty($row['pro_img']) ? 'admin/assets/img/uploads/' . $row['pro_img'] : null,
            'desc'          => $clean_short,
            'long_desc'     => $long_desc,
            'meta_title'    => (!empty($row['meta_title']) && $row['meta_title'] !== '0') ? $row['meta_title'] : '',
            'meta_desc'     => (!empty($row['meta_desc']) && $row['meta_desc'] !== '0') ? $row['meta_desc'] : '',
            'meta_key'      => (!empty($row['meta_key']) && $row['meta_key'] !== '0') ? $row['meta_key'] : '',
            'schema_markup' => (!empty($row['schema_markup']) && $row['schema_markup'] !== 'schema') ? $row['schema_markup'] : ''
        ];
    }
}

// Fallback: Agar DB mein na mile toh config.php ke static $dogs array mein check karega
if (!$dog && !empty($dogs)) {
    foreach ($dogs as $d) {
        if ($d['slug'] === $slug) {
            $dog = $d;
            $dog['long_desc'] = '<p>' . h($d['desc']) . '</p>';
            break;
        }
    }
}

if (!$dog) {
    header('Location: breeds.php');
    exit;
}

$product_id = (int)($dog['n'] ?? 0);

// ==========================================
// 2. HANDLE REVIEW SUBMISSION (POST)
// ==========================================
$rev_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review']) && empty($_POST['website'])) {
    $r_name  = trim(strip_tags($_POST['reviewer_name'] ?? ''));
    $r_email = filter_var(trim($_POST['reviewer_email'] ?? ''), FILTER_VALIDATE_EMAIL) ?: '';
    $r_rate  = (int)($_POST['rating'] ?? 5);
    if ($r_rate < 1 || $r_rate > 5) $r_rate = 5;
    $r_text  = trim(strip_tags($_POST['review_text'] ?? ''));

    if ($r_name !== '' && $r_text !== '' && $product_id > 0) {
        // status = 1 rakha hai taaki review turant page par dikhe (Agar admin approval chahiye toh 1 ki jagah 0 kar dena)
        $status_val = 1;
        $stmt = $conn->prepare("INSERT INTO product_reviews (product_id, reviewer_name, reviewer_email, rating, review_text, status) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("issisi", $product_id, $r_name, $r_email, $r_rate, $r_text, $status_val);
            $stmt->execute();
            $rev_msg = 'ok';
        }
    } else {
        $rev_msg = 'err';
    }
}

// ==========================================
// 3. FETCH MULTI-ANGLE IMAGES FOR GALLERY
// ==========================================
$gallery_images = [];
$main_src = !empty($dog['img']) ? (strpos($dog['img'], '/') !== false ? $dog['img'] : ph($dog['img'], 1100)) : '';
if ($main_src !== '') {
    $gallery_images[] = $main_src;
}

if ($product_id > 0) {
    $img_q = mysqli_query($conn, "SELECT image_path FROM product_images WHERE product_id = $product_id ORDER BY id ASC");
    if ($img_q && mysqli_num_rows($img_q) > 0) {
        while ($img_row = mysqli_fetch_assoc($img_q)) {
            $p_img = trim($img_row['image_path']);
            if ($p_img !== '') {
                // Check if full relative path is saved or just filename
                if (strpos($p_img, 'admin/') === 0 || strpos($p_img, 'http') === 0) {
                    $gallery_images[] = $p_img;
                } elseif (strpos($p_img, 'uploads/') === 0) {
                    $gallery_images[] = 'admin/' . $p_img;
                } else {
                    $gallery_images[] = 'admin/assets/img/uploads/' . $p_img;
                }
            }
        }
    }
}
$gallery_images = array_values(array_unique($gallery_images));

// ==========================================
// 4. FETCH PRODUCT REVIEWS FROM DATABASE
// ==========================================
$breed_reviews = [];
$total_rating = 0;
if ($product_id > 0) {
    $rev_q = mysqli_query($conn, "SELECT * FROM product_reviews WHERE product_id = $product_id AND status = 1 ORDER BY created_at DESC");
    if ($rev_q && mysqli_num_rows($rev_q) > 0) {
        while ($r_row = mysqli_fetch_assoc($rev_q)) {
            $breed_reviews[] = $r_row;
            $total_rating += (int)$r_row['rating'];
        }
    }
}
$rev_count = count($breed_reviews);
$avg_rating = $rev_count > 0 ? round($total_rating / $rev_count, 1) : '5.0';

// ==========================================
// 5. FETCH CUSTOMER VIDEOS (FOR VIDEO SECTION)
// ==========================================
$db_v3 = [];
$vid_q = mysqli_query($conn, "SELECT * FROM videos WHERE status=1 ORDER BY video_id DESC");
if ($vid_q) {
    while ($v_row = mysqli_fetch_assoc($vid_q)) {
        if (!isset($v_row['section_type']) || $v_row['section_type'] == 'ask_them' || count($db_v3) < 3) {
            $db_v3[] = [$v_row['video_code'], $v_row['title']];
        }
    }
    $db_v3 = array_slice($db_v3, 0, 3);
}

// ==========================================
// 6. FETCH RELATED PUPPIES ("You may also like")
// ==========================================
$db_related = [];
$slug_esc = mysqli_real_escape_string($conn, $dog['slug']);
$rel_q = mysqli_query($conn, "SELECT * FROM products WHERE status=1 AND pro_cate=34158 AND slug_url != '$slug_esc' ORDER BY id DESC LIMIT 4");
if ($rel_q) {
    while ($r_row = mysqli_fetch_assoc($rel_q)) {
        $db_related[] = [
            'slug'   => $r_row['slug_url'],
            'name'   => $r_row['pro_name'],
            'breed'  => !empty($r_row['brand_name']) ? $r_row['brand_name'] : 'Dog',
            'gender' => !empty($r_row['gender']) ? $r_row['gender'] : 'Male/Female',
            'age'    => !empty($r_row['age']) ? $r_row['age'] : '8 Weeks',
            'img'    => !empty($r_row['pro_img']) ? 'admin/assets/img/uploads/' . $r_row['pro_img'] : null,
            'n'      => $r_row['id']
        ];
    }
}

// ==========================================
// 7. DYNAMIC META & SCHEMA SETUP
// ==========================================
$title    = !empty($dog['meta_title']) ? $dog['meta_title'] : ($dog['name'] . ' for Sale in New Delhi | Kyra Pet Shop');
$desc     = !empty($dog['meta_desc']) ? $dog['meta_desc'] : $dog['desc'];
$keywords = !empty($dog['meta_key']) ? $dog['meta_key'] : ($dog['name'] . ', ' . $dog['breed'] . ', Kyra Pet Shop');
$meta_key = $keywords;
$schema_markup = $dog['schema_markup'] ?? '';

$page = 'breeds';
$wa   = 'https://wa.me/' . ($site['wa'] ?? '') . '?text=' . rawurlencode('Hi Kyra Pet Shop, I am interested in: ' . $dog['name']);

require __DIR__.'/includes/header.php';

// Fallbacks after header include
$related = !empty($db_related) ? $db_related : array_slice(array_values(array_filter($dogs, fn($x) => $x['slug'] !== $dog['slug'])), 0, 4);
$display_v3 = !empty($db_v3) ? $db_v3 : ($v3 ?? []);

if (function_exists('banner')) {
    banner($dog['name'], 'Breeds');
}
?>

<?php if (!empty($schema_markup)): ?>
  <?php if (strpos($schema_markup, '<script') !== false): ?>
    <?= $schema_markup ?>
  <?php else: ?>
    <script type="application/ld+json"><?= $schema_markup ?></script>
  <?php endif; ?>
<?php endif; ?>

<style>
  /* =====================================================
     PREMIUM GALLERY SLIDER, THUMBNAILS & ZOOM STYLING
     ===================================================== */
  .bdet-gal-col { display: flex; flex-direction: column; gap: 16px; min-width: 0; }
  .pic-slider-box { position: relative; border-radius: 26px; overflow: hidden; background: #f1f5f9; aspect-ratio: 4 / 3.6; box-shadow: 0 18px 45px rgba(15,23,42,0.08); user-select: none; }
  .pic-slider-box img.main-dog-img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.35s ease; cursor: zoom-in; transform-origin: center center; }
  
  /* Desktop Hover Zoom Effect */
  @media (hover: hover) and (pointer: fine) {
    .pic-slider-box.zoomed img.main-dog-img { transform: scale(1.85); }
  }

  /* Slider Prev / Next Arrows */
  .gal-nav-btn { position: absolute; top: 50%; transform: translateY(-50%); width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,0.92); border: 1px solid rgba(15,23,42,0.08); color: var(--ink, #0f172a); display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 5; box-shadow: 0 8px 20px rgba(0,0,0,0.12); transition: all 0.25s ease; font-size: 18px; font-weight: bold; }
  .gal-nav-btn:hover { background: var(--teal, #0d9488); color: #fff; transform: translateY(-50%) scale(1.06); }
  .gal-nav-btn.prev { left: 14px; }
  .gal-nav-btn.next { right: 14px; }

  /* Zoom Hint Pill */
  .zoom-pill { position: absolute; bottom: 14px; right: 14px; background: rgba(15,23,42,0.75); backdrop-filter: blur(6px); color: #fff; padding: 6px 13px; border-radius: 30px; font-size: 12.5px; font-weight: 600; display: flex; align-items: center; gap: 6px; z-index: 5; pointer-events: none; }
  .slide-counter { position: absolute; bottom: 14px; left: 14px; background: rgba(255,255,255,0.9); color: var(--ink, #0f172a); padding: 5px 12px; border-radius: 30px; font-size: 12.5px; font-weight: 700; z-index: 5; }

  /* Thumbnails Track */
  .thumb-strip { display: flex; gap: 12px; overflow-x: auto; padding: 4px 2px 8px; scrollbar-width: none; scroll-behavior: smooth; }
  .thumb-strip::-webkit-scrollbar { display: none; }
  .thumb-btn { flex: 0 0 84px; height: 72px; border-radius: 14px; overflow: hidden; border: 2.5px solid transparent; padding: 0; cursor: pointer; background: #e2e8f0; transition: all 0.25s ease; opacity: 0.65; }
  .thumb-btn img { width: 100%; height: 100%; object-fit: cover; display: block; }
  .thumb-btn:hover { opacity: 0.9; transform: translateY(-2px); }
  .thumb-btn.active { border-color: var(--teal, #0d9488); opacity: 1; box-shadow: 0 6px 16px rgba(13,148,136,0.25); }

  /* Fullscreen Mobile & Desktop Zoom Modal */
  .zoom-modal { position: fixed; inset: 0; background: rgba(11,18,32,0.94); z-index: 9999; display: none; align-items: center; justify-content: center; flex-direction: column; touch-action: pinch-zoom; }
  .zoom-modal.open { display: flex; }
  .zm-img-wrap { max-width: 92vw; max-height: 80vh; overflow: auto; display: flex; align-items: center; justify-content: center; border-radius: 16px; }
  .zm-img-wrap img { max-width: 100%; max-height: 80vh; object-fit: contain; transition: transform 0.3s ease; cursor: zoom-in; }
  .zm-img-wrap img.deep-zoom { transform: scale(1.9); cursor: zoom-out; }
  .zm-close { position: absolute; top: 20px; right: 20px; width: 44px; height: 44px; border-radius: 50%; border: none; background: rgba(255,255,255,0.15); color: #fff; font-size: 20px; cursor: pointer; }
  .zm-controls { margin-top: 16px; display: flex; gap: 12px; align-items: center; }
  .zm-btn { background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 30px; cursor: pointer; font-weight: 600; font-size: 14px; }
  .zm-btn:hover { background: var(--teal, #0d9488); border-color: transparent; }

  /* =====================================================
     2-COLUMN LONG DESCRIPTION & REVIEWS SECTION
     ===================================================== */
  .desc-rev-sec { padding: 40px 0 70px; }
  .desc-rev-grid { display: grid; grid-template-columns: 1.15fr 0.85fr; gap: 32px; align-items: start; }
  @media (max-width: 960px) { .desc-rev-grid { grid-template-columns: 1fr; } }

  .dr-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 24px; padding: 34px; box-shadow: 0 12px 35px rgba(15,23,42,0.04); }
  @media (max-width: 600px) { .dr-card { padding: 22px; } }

  .dr-content { color: var(--ink, #1e293b); line-height: 1.75; font-size: 16px; }
  .dr-content h1, .dr-content h2, .dr-content h3, .dr-content h4 { color: var(--ink, #0f172a); margin: 18px 0 10px; font-size: 20px; }
  .dr-content p { margin-bottom: 14px; color: var(--mute, #475569); }
  .dr-content ul { padding-left: 20px; margin-bottom: 16px; color: var(--mute, #475569); }

  .trust-highlights { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; margin-top: 24px; padding-top: 24px; border-top: 1px solid #e2e8f0; }
  @media (max-width: 520px) { .trust-highlights { grid-template-columns: 1fr; } }
  .th-box { display: flex; gap: 12px; align-items: flex-start; background: #f8fafc; padding: 14px; border-radius: 16px; }
  .th-box i { font-style: normal; font-size: 22px; }
  .th-box b { display: block; font-size: 14.5px; color: var(--ink, #0f172a); }
  .th-box small { color: var(--mute, #64748b); font-size: 13px; }

  /* Review List & Star Rating Form */
  .rev-summary-bar { display: flex; align-items: center; justify-content: space-between; background: #f8fafc; padding: 16px 20px; border-radius: 18px; margin: 16px 0 22px; border: 1px solid #e2e8f0; }
  .rev-summary-bar .score { font-size: 32px; font-weight: 800; color: var(--ink, #0f172a); line-height: 1; }
  .rev-list-scroll { max-height: 360px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; padding-right: 4px; margin-bottom: 24px; }
  .rev-item-card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; }
  .rev-item-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; }
  .rev-user { display: flex; align-items: center; gap: 10px; }
  .rev-av { width: 36px; height: 36px; border-radius: 50%; background: var(--teal, #0d9488); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 15px; }
  .rev-item-card p { margin: 6px 0 0; font-size: 14.5px; color: var(--mute, #475569); line-height: 1.55; }

  /* Interactive Star Picker */
  .star-picker { display: inline-flex; flex-direction: row-reverse; gap: 4px; }
  .star-picker input { display: none; }
  .star-picker label { font-size: 26px; color: #cbd5e1; cursor: pointer; transition: color 0.2s; }
  .star-picker input:checked ~ label,
  .star-picker label:hover,
  .star-picker label:hover ~ label { color: #f59e0b; }

  .rev-form input, .rev-form textarea { width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 12px; font-family: inherit; font-size: 14.5px; margin-bottom: 12px; background: #f8fafc; }
  .rev-form input:focus, .rev-form textarea:focus { outline: none; border-color: var(--teal, #0d9488); background: #fff; }
</style>

<main>
<!-- TOP PRODUCT DETAILS + MULTI-ANGLE SLIDER & ZOOM -->
<section style="padding-top:30px"><div class="wrap bdet">
  
  <!-- Left: Featured Image Slider + Related Angle Thumbnails -->
  <div class="bdet-gal-col rv" data-anim="left">
    <?php if (!empty($gallery_images)): ?>
      <div class="pic-slider-box" id="picSliderBox">
        <?php if (count($gallery_images) > 1): ?>
          <button type="button" class="gal-nav-btn prev" id="galPrev" aria-label="Previous angle">←</button>
          <button type="button" class="gal-nav-btn next" id="galNext" aria-label="Next angle">→</button>
          <span class="slide-counter" id="slideCounter">1 / <?= count($gallery_images) ?></span>
        <?php endif; ?>
        
        <img class="main-dog-img" id="mainDogImg" src="<?= h($gallery_images[0]) ?>" alt="<?= h($dog['name']) ?>" fetchpriority="high">
        <span class="zoom-pill">🔍 Tap or hover to zoom</span>
      </div>

      <!-- Angle Thumbnails Row -->
      <?php if (count($gallery_images) > 1): ?>
      <div class="thumb-strip" id="thumbStrip" aria-label="More angles of <?= h($dog['name']) ?>">
        <?php foreach ($gallery_images as $idx => $g_img): ?>
          <button type="button" class="thumb-btn <?= $idx === 0 ? 'active' : '' ?>" data-idx="<?= $idx ?>" data-src="<?= h($g_img) ?>" aria-label="View angle <?= $idx + 1 ?>">
            <img src="<?= h($g_img) ?>" alt="<?= h($dog['name']) ?> angle <?= $idx + 1 ?>" loading="lazy">
          </button>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

    <?php else: ?>
      <div class="pic"><div class="noimg" style="height:100%"><span style="font-size:80px">🐾</span><?=h($dog['breed'])?><small style="font-weight:600;color:var(--mute)">Photo available on request</small></div></div>
    <?php endif; ?>
  </div>

  <!-- Right: Puppy Specs & Quick Actions -->
  <div class="rv" data-anim="right">
    <span class="tag"><?=h($dog['breed'])?></span>
    <h2><?=h($dog['name'])?></h2>
    <p class="lead"><?=h($dog['desc'])?></p>
    <div class="badge-row">
      <span class="pill"><span class="tick">✔</span> Vet checked</span>
      <span class="pill"><span class="tick">✔</span> Vaccinated</span>
      <span class="pill"><span class="tick">✔</span> Health certificate</span>
    </div>
    <table class="specs"><tbody>
      <tr><td>Breed</td><td><?=h($dog['breed'])?></td></tr>
      <tr><td>Age</td><td><?=h($dog['age'])?></td></tr>
      <tr><td>Gender</td><td><?=h($dog['gender'])?></td></tr>
      <tr><td>Adult size</td><td><?=h($dog['size'])?></td></tr>
      <tr><td>Temperament</td><td><?=h($dog['temper'])?></td></tr>
      <tr><td>Price</td><td>Contact us for today's price</td></tr>
    </tbody></table>
    <div class="cta" style="margin:0 0 26px">
      <a class="btn btn-wa" target="_blank" rel="noopener" href="<?=h($wa)?>">Inquire on WhatsApp</a>
      <a class="btn btn-navy" href="tel:<?=h($tel ?? '')?>">Call <?=h($site['phone'] ?? '')?></a>
    </div>
    <ul class="kit">
      <li>Vaccination card and health certificate included</li>
      <li>Feeding chart and care guide</li>
      <li>Visit and meet the puppy before you decide</li>
    </ul>
  </div>
</div></section>

<!-- 2-COLUMN SECTION: LEFT LONG DESCRIPTION | RIGHT CUSTOMER REVIEWS -->
<section class="desc-rev-sec" id="reviews-section"><div class="wrap">
  <div class="desc-rev-grid">
    
    <!-- Left Column: Long Description & Trust Highlights -->
    <div class="dr-card rv" data-anim="left">
      <span class="tag">Breed Overview & Care</span>
      <h3 style="font-size:clamp(22px,3vw,30px);margin:8px 0 18px">Everything about <?= h($dog['name']) ?></h3>
      
      <div class="dr-content">
        <?= $dog['long_desc'] ?>
      </div>

      <div class="trust-highlights">
        <div class="th-box">
          <i>🩺</i>
          <div><b>100% Vet Checked</b><small>Complete physical checkup before handover</small></div>
        </div>
        <div class="th-box">
          <i>💉</i>
          <div><b>Up-to-Date Vaccines</b><small>Authentic vaccination card provided</small></div>
        </div>
        <div class="th-box">
          <i>🥣</i>
          <div><b>Custom Diet Chart</b><small>Age-wise feeding & nutrition guide included</small></div>
        </div>
        <div class="th-box">
          <i>📞</i>
          <div><b>Lifetime Support</b><small>Direct guidance from <?= h($site['owner'] ?? 'Mr. Nitin') ?></small></div>
        </div>
      </div>
    </div>

    <!-- Right Column: Customer Reviews + Submit Review Form -->
    <div class="dr-card rv" data-anim="right">
      <span class="tag">Customer Feedback</span>
      <h3 style="font-size:clamp(22px,3vw,28px);margin:8px 0 6px">Reviews for <?= h($dog['name']) ?></h3>
      
      <div class="rev-summary-bar">
        <div>
          <div class="score"><?= h($avg_rating) ?> <span style="font-size:20px;color:#f59e0b">★★★★★</span></div>
          <small style="color:var(--mute);font-weight:600">Based on <?= $rev_count ?> verified review<?= $rev_count === 1 ? '' : 's' ?></small>
        </div>
        <a href="#writeReviewForm" class="btn btn-teal" style="padding:9px 16px;font-size:13.5px">✎ Write a review</a>
      </div>

      <!-- Existing Reviews List -->
      <div class="rev-list-scroll">
        <?php if (!empty($breed_reviews)): ?>
          <?php foreach ($breed_reviews as $rev): ?>
            <div class="rev-item-card">
              <div class="rev-item-head">
                <div class="rev-user">
                  <span class="rev-av"><?= h( strtoupper(mb_substr($rev['reviewer_name'], 0, 1)) ) ?></span>
                  <div>
                    <b style="font-size:15px;color:var(--ink)"><?= h($rev['reviewer_name']) ?></b>
                    <div style="font-size:12px;color:var(--mute)"><?= date('M d, Y', strtotime($rev['created_at'])) ?> · <span style="color:#16a34a;font-weight:600">✔ Verified</span></div>
                  </div>
                </div>
                <div><?= stars((int)$rev['rating']) ?></div>
              </div>
              <p>"<?= h($rev['review_text']) ?>"</p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="rev-item-card" style="text-align:center;padding:24px">
            <b style="color:var(--ink)">No reviews yet for this puppy</b>
            <p style="margin-top:4px">Visited our shop or brought home a <?= h($dog['breed']) ?>? Share your experience below!</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- Write a Review Form -->
      <form class="rev-form" id="writeReviewForm" method="post" action="?slug=<?= urlencode($dog['slug']) ?>#reviews-section" style="border-top:1px solid #e2e8f0;padding-top:20px">
        <h4 style="margin:0 0 10px;font-size:18px">Leave a Review</h4>
        
        <?php if ($rev_msg === 'ok'): ?>
          <div class="alert ok" style="margin-bottom:14px;padding:12px;border-radius:10px;background:#dcfce7;color:#166534;font-weight:600">Thank you! Your review has been posted successfully.</div>
        <?php elseif ($rev_msg === 'err'): ?>
          <div class="alert err" style="margin-bottom:14px;padding:12px;border-radius:10px;background:#fee2e2;color:#991b1b;font-weight:600">Please enter your name and review message.</div>
        <?php endif; ?>

        <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" style="display:none">
        
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
          <span style="font-size:14px;font-weight:600;color:var(--ink)">Your Rating:</span>
          <div class="star-picker">
            <input type="radio" id="st5" name="rating" value="5" checked><label for="st5" title="5 stars">★</label>
            <input type="radio" id="st4" name="rating" value="4"><label for="st4" title="4 stars">★</label>
            <input type="radio" id="st3" name="rating" value="3"><label for="st3" title="3 stars">★</label>
            <input type="radio" id="st2" name="rating" value="2"><label for="st2" title="2 stars">★</label>
            <input type="radio" id="st1" name="rating" value="1"><label for="st1" title="1 star">★</label>
          </div>
        </div>

        <input type="text" name="reviewer_name" placeholder="Your full name *" required>
        <input type="email" name="reviewer_email" placeholder="Your email (optional)">
        <textarea name="review_text" rows="3" placeholder="Share your experience with our puppy and service *" required></textarea>
        <button type="submit" name="submit_review" value="1" class="btn btn-navy" style="width:100%">Submit Review</button>
      </form>
    </div>

  </div>
</div></section>

<!-- YOUTUBE VIDEO REVIEWS SECTION (Same as Home Page "Ask them, not us") -->
<section class="vs3"><div class="wrap">
  <div class="sec-head c">
    <span class="tag">Puppy parents speak</span>
    <h2>Ask them, not us</h2>
    <p>Honest words from people who visited our shop and took a puppy home. Tap a video to hear them.</p>
  </div>
  <div class="pp">
    <?php foreach ($display_v3 as $i =>$v): ?>
      <div class="ppc rv">
        <?php if (function_exists('vcard')) echo vcard($v,$i); ?>
        <div class="q"><?= stars(5) ?><b><?= h($v[1] ?? 'Happy Pet Parent') ?></b><span>Customer feedback at Kyra Pet Shop</span></div>
      </div>
    <?php endforeach; ?>
  </div>
</div></section>

<!-- MORE PUPPIES SECTION -->
<section class="feat"><div class="wrap">
  <div class="sec-head c"><span class="tag">More puppies</span><h2>You may also like</h2></div>
  <div class="grid4"><?php foreach($related as$d) { if(function_exists('dog_card')) dog_card($d,$site); } ?></div>
  <div class="center"><a class="btn btn-navy" href="breeds.php">View all breeds</a></div>
</div></section>
<?php if(function_exists('cta_band')) cta_band(); ?>
</main>

<!-- FULLSCREEN ZOOM MODAL FOR MOBILE & DESKTOP -->
<div class="zoom-modal" id="zoomModal" role="dialog" aria-modal="true" aria-label="Zoomed puppy photo">
  <button type="button" class="zm-close" id="zmClose" aria-label="Close zoom">✕</button>
  <div class="zm-img-wrap">
    <img src="" id="zmImg" alt="<?= h($dog['name']) ?> zoomed view">
  </div>
  <div class="zm-controls">
    <button type="button" class="zm-btn" id="zmPrev">← Prev</button>
    <button type="button" class="zm-btn" id="zmToggle">🔍 Zoom In / Out</button>
    <button type="button" class="zm-btn" id="zmNext">Next →</button>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const images = <?= json_encode($gallery_images) ?>;
  if (!images || images.length === 0) return;

  let currentIndex = 0;
  const sliderBox    = document.getElementById('picSliderBox');
  const mainImg      = document.getElementById('mainDogImg');
  const prevBtn      = document.getElementById('galPrev');
  const nextBtn      = document.getElementById('galNext');
  const counterEl    = document.getElementById('slideCounter');
  const thumbs       = document.querySelectorAll('.thumb-btn');

  // Zoom Modal Elements
  const zoomModal    = document.getElementById('zoomModal');
  const zmImg        = document.getElementById('zmImg');
  const zmClose      = document.getElementById('zmClose');
  const zmToggle     = document.getElementById('zmToggle');
  const zmPrev       = document.getElementById('zmPrev');
  const zmNext       = document.getElementById('zmNext');

  function updateSlide(index) {
    if (index < 0) index = images.length - 1;
    if (index >= images.length) index = 0;
    currentIndex = index;

    mainImg.src = images[currentIndex];
    if (zmImg) zmImg.src = images[currentIndex];
    if (counterEl) counterEl.textContent = (currentIndex + 1) + ' / ' + images.length;

    thumbs.forEach((btn, idx) => {
      if (idx === currentIndex) {
        btn.classList.add('active');
        btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      } else {
        btn.classList.remove('active');
      }
    });
  }

  if (prevBtn) prevBtn.addEventListener('click', (e) => { e.stopPropagation(); updateSlide(currentIndex - 1); });
  if (nextBtn) nextBtn.addEventListener('click', (e) => { e.stopPropagation(); updateSlide(currentIndex + 1); });

  thumbs.forEach(btn => {
    btn.addEventListener('click', function() {
      updateSlide(parseInt(this.getAttribute('data-idx'), 10));
    });
  });

  // Mobile Touch Swipe Support on Main Image
  let touchStartX = 0;
  let touchEndX = 0;
  if (sliderBox) {
    sliderBox.addEventListener('touchstart', e => {
      touchStartX = e.changedTouches[0].screenX;
    }, {passive: true});

    sliderBox.addEventListener('touchend', e => {
      touchEndX = e.changedTouches[0].screenX;
      if (touchStartX - touchEndX > 45) updateSlide(currentIndex + 1);
      if (touchEndX - touchStartX > 45) updateSlide(currentIndex - 1);
    }, {passive: true});

    // Desktop Hover Lens Zoom
    sliderBox.addEventListener('mousemove', function(e) {
      if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
        this.classList.add('zoomed');
        const rect = this.getBoundingClientRect();
        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;
        mainImg.style.transformOrigin = `${x}% ${y}%`;
      }
    });

    sliderBox.addEventListener('mouseleave', function() {
      this.classList.remove('zoomed');
      mainImg.style.transformOrigin = 'center center';
    });

    // Click / Tap to Open Fullscreen Zoom Modal (Great for Mobile & Desktop)
    mainImg.addEventListener('click', function() {
      zmImg.src = images[currentIndex];
      zmImg.classList.remove('deep-zoom');
      zoomModal.classList.add('open');
    });
  }

  // Modal Controls
  if (zmClose) zmClose.addEventListener('click', () => zoomModal.classList.remove('open'));
  if (zmToggle) zmToggle.addEventListener('click', () => zmImg.classList.toggle('deep-zoom'));
  if (zmImg) zmImg.addEventListener('click', () => zmImg.classList.toggle('deep-zoom'));
  if (zmPrev) zmPrev.addEventListener('click', () => updateSlide(currentIndex - 1));
  if (zmNext) zmNext.addEventListener('click', () => updateSlide(currentIndex + 1));

  zoomModal.addEventListener('click', function(e) {
    if (e.target === zoomModal) zoomModal.classList.remove('open');
  });
});
</script>
<?php require __DIR__.'/includes/footer.php'; ?>