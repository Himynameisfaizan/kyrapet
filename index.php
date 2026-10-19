<?php
include('config/connect.php');
include('includes/config.php');

$page = 'home';

// ==========================================
// 1. DYNAMIC META & SCHEMA FETCHING
// ==========================================
$meta_q = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='index.php'");
$meta_data = mysqli_fetch_assoc($meta_q);
$title = $meta_data['meta_title'] ?? 'Kyra Pet Shop | Healthy, Vaccinated Puppies in Patel Nagar, New Delhi';
$desc = $meta_data['meta_desc'] ?? 'Premium pure breed puppies in New Delhi. Vet checked, 100% vaccinated, rated 4.9 on Google. Visit Kyra Pet Shop in Patel Nagar.';

$schema_q = mysqli_query($conn, "SELECT * FROM page_schemas WHERE page_url='index.php'");
$schema_data = mysqli_fetch_assoc($schema_q);

// ==========================================
// 2. VIDEO SECTION LIMITS (Yahan se aap limit manage kar sakte hain)
// ==========================================
$limit_forever_homes    = 6; // "Where our puppies go next" mein kitni videos dikhani hain
$limit_customer_stories = 3; // "Customer stories (Real families...)" mein kitni videos dikhani hain
$limit_ask_them         = 3; // "Ask them, not us" mein kitni videos dikhani hain

function getCleanYoutubeCode($code, $url = '') {
    $code = trim($code ?? '');
    if ($code !== '' && strlen($code) === 11) return $code;
    $check = trim($url ?: $code);
    if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $check, $m)) {
        return $m[1];
    }
    return $code;
}
?>
<?php
// ==========================================
// 3. FETCHING ALL SECTIONS DATA FROM DATABASE
// ==========================================

// Happy Customers Section Fetch
$db_happy_customers = [];
$hc_q = mysqli_query($conn, "SELECT * FROM happy_customers WHERE status=1 ORDER BY id DESC LIMIT 15");
if ($hc_q) {
    while ($row = mysqli_fetch_assoc($hc_q)) {
        $hc_path = ltrim($row['image_path'], '/');
        $db_happy_customers[] = (strpos($hc_path, 'admin/') === 0 || strpos($hc_path, 'http') === 0) ? $hc_path : 'admin/' . $hc_path;
    }
}

// About Section
$about_q = mysqli_query($conn, "SELECT * FROM about_us LIMIT 1");
$about = mysqli_fetch_assoc($about_q);

// Dog Products
$db_dogs = [];
$prod_q = mysqli_query($conn, "SELECT * FROM products WHERE status=1 ORDER BY id DESC LIMIT 8");
if ($prod_q) {
  while ($row = mysqli_fetch_assoc($prod_q)) {
    $db_dogs[] = [
      'slug'   => $row['slug_url'],
      'name'   => $row['pro_name'],
      'breed'  => $row['brand_name'] ?? 'Dog',
      'gender' => !empty($row['gender']) ? $row['gender'] : 'Male/Female',
      'age'    => !empty($row['age']) ? $row['age'] : '8 Weeks',
      'img'    => !empty($row['pro_img']) ? 'admin/assets/img/uploads/' . $row['pro_img'] : null,
      'n'      => $row['id']
    ];
  }
}

// Dynamic Videos Fetching with Smart Auto-Fill & Custom Limits
$db_v1 = []; // customer_stories
$db_v2 = []; // forever_homes
$db_v3 = []; // ask_them
$all_db_videos = [];

$vid_q = mysqli_query($conn, "SELECT * FROM videos WHERE status = 1 ORDER BY video_id DESC");
if ($vid_q) {
  while ($row = mysqli_fetch_assoc($vid_q)) {
    $v_code = trim($row['video_code'] ?? '');
    if ($v_code === '' && !empty($row['youtube_url'])) {
      if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $row['youtube_url'], $m)) {
        $v_code = $m[1];
      }
    }
    if ($v_code === '') continue;

    $vid_item = [$v_code, !empty($row['title']) ? $row['title'] : 'Happy Pet Parent'];
    $all_db_videos[] = $vid_item;

    $sec_type = trim($row['section_type'] ?? '');
    if ($sec_type === 'customer_stories') {
      $db_v1[] = $vid_item;
    } elseif ($sec_type === 'forever_homes') {
      $db_v2[] = $vid_item;
    } elseif ($sec_type === 'ask_them') {
      $db_v3[] = $vid_item;
    }
  }
}

// Smart Auto-Fill: Agar kisi section mein limit se kam videos tagged hain, toh DB ki baaki videos se pura karein
if (!empty($all_db_videos)) {
  // 1. Fill Forever Homes ($db_v2) up to $limit_forever_homes (6)
  foreach ($all_db_videos as $av) {
    if (count($db_v2) >= $limit_forever_homes) break;
    if (!in_array($av[0], array_column($db_v2, 0))) {
      $db_v2[] = $av;
    }
  }
  // 2. Fill Customer Stories ($db_v1) up to $limit_customer_stories (3)
  foreach ($all_db_videos as $av) {
    if (count($db_v1) >= $limit_customer_stories) break;
    if (!in_array($av[0], array_column($db_v1, 0))) {
      $db_v1[] = $av;
    }
  }
  // 3. Fill Ask Them ($db_v3) up to $limit_ask_them (3)
  foreach (array_reverse($all_db_videos) as $av) {
    if (count($db_v3) >= $limit_ask_them) break;
    if (!in_array($av[0], array_column($db_v3, 0))) {
      $db_v3[] = $av;
    }
  }
}

// Apply exact limits set at top
$db_v2 = array_slice($db_v2, 0, $limit_forever_homes);
$db_v1 = array_slice($db_v1, 0, $limit_customer_stories);
$db_v3 = array_slice($db_v3, 0, $limit_ask_them);

// Reviews / Testimonials 
$db_reviews = [];
$colors = ['#4a90e2', '#e91e63', '#4caf50', '#ff9800', '#9c27b0'];
$color_i = 0;
$rev_q = mysqli_query($conn, "SELECT * FROM testimonials WHERE status=1 ORDER BY test_id DESC");
if ($rev_q) {
  while ($row = mysqli_fetch_assoc($rev_q)) {
    $db_reviews[] = [$row['name'], $row['designation'], date('M Y', strtotime($row['created_at'])), 5, '', $row['message'], $colors[$color_i % count($colors)]];
    $color_i++;
  }
}

// Gallery Section
$db_gallery = [];
$gal_q = mysqli_query($conn, "SELECT * FROM gallery ORDER BY ID DESC LIMIT 8");
if ($gal_q) {
  while ($row = mysqli_fetch_assoc($gal_q)) {
    $db_gallery[] = 'admin/' . ltrim($row['image_path'], '/');
  }
}

// Blog Section
$db_blog = [];
$blog_q = mysqli_query($conn, "SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC LIMIT 3");
if ($blog_q) {
  while ($row = mysqli_fetch_assoc($blog_q)) {
    $desc_text = strip_tags($row['description']);
    $excerpt = mb_substr($desc_text, 0, 100) . '...';
    $db_blog[] = [
      'slug'    => $row['slug'],
      'title'   => $row['title'],
      'cat'     => 'Pet Care',
      'date'    => date('M d, Y', strtotime($row['created_at'])),
      'read'    => '5 min read',
      'img'     => 'admin/assets/img/uploads/blogs/' . $row['image'],
      'excerpt' => $excerpt
    ];
  }
}

// Contact & Map Data
$contact_q = mysqli_query($conn, "SELECT * FROM contacts LIMIT 1");
$contact = mysqli_fetch_assoc($contact_q);

// ==========================================
// 4. INQUIRY FORM HANDLING (Insert to DB)
// ==========================================
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['website'])) {
  $n = trim(strip_tags($_POST['name'] ?? ''));
  $p = trim(strip_tags($_POST['phone'] ?? ''));
  $b = trim(strip_tags($_POST['breed'] ?? ''));
  $m = trim(strip_tags($_POST['message'] ?? ''));
  $e = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';

  if ($n && preg_match('/^[0-9+\s-]{8,15}$/', $p)) {
    $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $subj = "New Inquiry for Breed: " . $b;
    $stmt->bind_param("sssss", $n, $p, $e, $subj, $m);
    $stmt->execute();
    @mail($site['email'], 'New inquiry from website', "Name: $n\nPhone: $p\nEmail: $e\nBreed: $b\nMessage: $m", "From: no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    $msg = 'ok';
  } else {
    $msg = 'err';
  }
}

require __DIR__ . '/includes/header.php';
?>
<main id="home">
  <!-- HERO (Dynamic Slider from banners table) -->
  <?php
  $hero_slides = [];
  $hero_q = mysqli_query($conn, "SELECT * FROM banners WHERE status=1 ORDER BY display_order ASC, id DESC");
  if ($hero_q && mysqli_num_rows($hero_q) > 0) {
      while ($h_row = mysqli_fetch_assoc($hero_q)) {
          $b_path = $h_row['banner_path'];
          if (strpos($b_path, 'http') !== 0 && strpos($b_path, 'admin/') !== 0) {
              $b_path = 'admin/' . ltrim($b_path, '/');
          }
          
          $hero_slides[] = [
              'title'       => $h_row['title'],
              'description' => $h_row['description'],
              'image_url'   => $b_path,
              'link_url'    => !empty($h_row['link_url']) ? $h_row['link_url'] : '#breeds'
          ];
      }
  }

  // Fallback agar banners table khali ho
  if (empty($hero_slides)) {
      $hero_slides[] = [
          'title'       => 'Bring home a healthy puppy you can trust.',
          'description' => 'Vet checked, fully vaccinated, pure breed puppies in Patel Nagar, New Delhi. We show you every record before you decide.',
          'image_url'   => 'admin/assets/img/uploads/golden.png',
          'link_url'    => '#breeds'
      ];
  }
  ?>

  <section class="hero" style="padding-bottom:0; position:relative; overflow:hidden;">
    <div class="blob b1"></div>
    <div class="blob b2"></div>
    <?php for ($k = 0; $k < 9; $k++): ?>
        <span class="paw" style="left:<?= ($k * 11 + 5) % 96 ?>%;animation-delay:<?= $k * 1.4 ?>s;font-size:<?= 16 + ($k % 3) * 8 ?>px">🐾</span>
    <?php endfor; ?>

    <div class="wrap">
        <?php foreach ($hero_slides as $index => $hero): ?>
            <div class="hero-slide-pane" style="display: <?= $index === 0 ? 'contents' : 'none' ?>;" data-slide-index="<?= $index ?>">
                <div>
                    <div class="chip seq"><?= gicon(18) ?> <span><b><?= h($rating['score'] ?? '4.9') ?></b> rated on Google by <?= h($rating['count'] ?? '15+') ?>+ pet parents</span></div>

                    <h1 class="split"><?= htmlspecialchars($hero['title']) ?></h1>
                    <p class="lead seq"><?= htmlspecialchars($hero['description']) ?></p>

                    <div class="cta seq">
                        <a class="btn btn-teal" href="<?= htmlspecialchars($hero['link_url']) ?>">See available puppies</a>
                        <a class="btn btn-line" href="#videos">Watch customer stories</a>
                    </div>

                    <div class="mini-proof seq">
                      <div class="faces">
                        <?php foreach ([$P['grass'] ?? '', $P['tan'] ?? '', $P['tongue'] ?? '', $P['sweater'] ?? ''] as $f): if ($f): ?>
                            <img src="<?= ph($f, 100) ?>" alt="">
                        <?php endif; endforeach; ?>
                      </div>
                      <div><span class="stars">★★★★★</span><small>Trusted by 420+ happy clients</small></div>
                    </div>
                </div>

                <div class="hero-img">
                    <div class="arch">
                        <img src="<?= htmlspecialchars($hero['image_url']) ?>" alt="Kyra Pet Shop Hero" <?= $index === 0 ? 'fetchpriority="high"' : '' ?>>
                    </div>
                    <div class="fb b"><i>🩺</i><span>Vet checked<small>Health records shared</small></span></div>
                    <div class="fb a"><i>💉</i><span>100% vaccinated<small>Card given at pickup</small></span></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($hero_slides) > 1): ?>
    <div class="hero-dots" style="text-align: center; padding: 12px 0 0; position: relative; z-index: 10;">
        <?php foreach ($hero_slides as $index => $slide): ?>
            <span class="h-dot <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>" style="display: inline-block; width: 12px; height: 12px; margin: 0 5px; background: <?= $index === 0 ? 'var(--teal, #0d9488)' : '#cbd5e1' ?>; border-radius: 50%; cursor: pointer; transition: background 0.3s;"></span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const panes = document.querySelectorAll('.hero-slide-pane');
      const dots = document.querySelectorAll('.h-dot');
      if (panes.length <= 1) return;

      let currentSlide = 0;
      function showSlide(index) {
          panes.forEach((pane, idx) => {
              pane.style.display = (idx === index) ? 'contents' : 'none';
          });
          dots.forEach((dot, idx) => {
              dot.style.background = (idx === index) ? 'var(--teal, #0d9488)' : '#cbd5e1';
          });
          currentSlide = index;
      }

      let slideInterval = setInterval(() => {
          let next = (currentSlide + 1) % panes.length;
          showSlide(next);
      }, 5000);

      dots.forEach((dot, idx) => {
          dot.addEventListener('click', () => {
              clearInterval(slideInterval);
              showSlide(idx);
          });
      });
  });
  </script>

  <!-- MARQUEE -->
  <div class="marq">
    <div class="marq-track">
      <?php for ($k = 0; $k < 2; $k++): foreach (['Vet checked puppies', '100% vaccinated', 'Pure breed guarantee', 'Visit before you buy', 'Lifetime care support', 'KCI registered puppies', 'Rated ' . ($rating['score'] ?? '4.9') . ' on Google', 'Visit us in Patel Nagar'] as $t): ?><span><b>✦</b><?= h($t) ?></span><?php endforeach; endfor; ?>
    </div>
  </div>

  <!-- OUR HAPPY CUSTOMERS (Larger & More Attractive Cards + Video Grid Fixes) -->
  <style>
    .hc-wrapper { padding: 85px 0; background: var(--bg-alt, #f8f9fa); overflow: hidden; }
    .hc-container { position: relative; max-width: 1440px; margin: 0 auto; }
    .hc-slider { 
      display: flex; gap: 26px; overflow-x: auto; padding: 24px 4%; 
      scroll-snap-type: x mandatory; scrollbar-width: none; -ms-overflow-style: none; 
      scroll-behavior: smooth;
    }
    .hc-slider::-webkit-scrollbar { display: none; }
    
    /* Bigger, Bolder & More Attractive Customer Cards */
    .hc-item { 
      flex: 0 0 calc(28% - 18px); 
      min-width: 310px;
      scroll-snap-align: center; 
      border-radius: 26px; 
      overflow: hidden; 
      background: #fff;
      box-shadow: 0 18px 42px rgba(15, 23, 42, 0.12); 
      transition: transform 0.45s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.45s ease; 
      aspect-ratio: 3 / 3.9;
      position: relative;
      cursor: grab;
      border: 4px solid #ffffff;
    }
    @media(max-width: 1100px) { .hc-item { flex: 0 0 calc(40% - 16px); min-width: 280px; } }
    @media(max-width: 768px)  { .hc-item { flex: 0 0 calc(65% - 14px); min-width: 260px; } }
    @media(max-width: 480px)  { .hc-item { flex: 0 0 calc(84% - 10px); min-width: 240px; } }
    
    .hc-item img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.65s ease; }
    .hc-item:hover { transform: translateY(-12px) scale(1.01); box-shadow: 0 26px 55px rgba(15, 23, 42, 0.18); }
    .hc-item:hover img { transform: scale(1.08); }
    .hc-item::after {
      content: ''; position: absolute; inset: 0; border-radius: 22px;
      background: linear-gradient(to top, rgba(15,23,42,0.28) 0%, transparent 35%);
      pointer-events: none;
    }

    /* Premium Navigation Arrows */
    .hc-nav { 
      position: absolute; top: 50%; transform: translateY(-50%); 
      width: 54px; height: 54px; background: #fff; 
      border: 1px solid #e2e8f0; border-radius: 50%; 
      box-shadow: 0 10px 28px rgba(0,0,0,0.14); 
      cursor: pointer; display: flex; align-items: center; justify-content: center; 
      z-index: 10; font-size: 22px; color: #0f172a; 
      transition: all 0.3s ease; 
    }
    .hc-nav:hover { 
      background: var(--teal, #0d9488); color: #fff; 
      border-color: var(--teal, #0d9488); 
      transform: translateY(-50%) scale(1.08);
    }
    .hc-prev { left: 1.5%; }
    .hc-next { right: 1.5%; }
    .hc-nav svg { width: 26px; height: 26px; fill: currentColor; }
    @media(max-width: 768px) { .hc-nav { display: none; } }

    /* Video Sections Grid Layout Support for 6, 3, and 3 Videos */
    .vs2 .fan.multi-vids {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
      align-items: stretch;
    }
    .vs2 .fan.multi-vids > * {
      transform: none !important;
      margin: 0 !important;
      width: 100% !important;
    }
    .vs1 .trio {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr);
      gap: 18px;
    }
    .vs3 .pp {
      display: grid !important;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }
    @media(max-width: 900px) {
      .vs2 .fan.multi-vids, .vs1 .trio, .vs3 .pp {
        grid-template-columns: repeat(2, 1fr);
      }
    }
    @media(max-width: 560px) {
      .vs2 .fan.multi-vids, .vs1 .trio, .vs3 .pp {
        grid-template-columns: 1fr;
      }
    }
  </style>

  <section class="hc-wrapper" id="happy-customers">
    <div class="wrap">
      <div class="sec-head c rv">
        <span class="tag">Our Happy Customers</span>
        <h2>Families Completed by Kyra</h2>
        <p>See the joy on our customers' faces when they meet their new best friend. Real families, real smiles!</p>
      </div>
    </div>
    
    <div class="hc-container rv" data-anim="bottom">
      <button class="hc-nav hc-prev" id="hc-prev" aria-label="Previous">
        <svg viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
      </button>

      <div class="hc-slider" id="hc-slider">
        <?php 
        $fallback_gal = !empty($db_gallery) ? $db_gallery : ($gallery ?? []);
        $display_hc = !empty($db_happy_customers) ? $db_happy_customers : $fallback_gal;
        foreach ($display_hc as $hc_img): 
          $hc_src = (strpos($hc_img, '/') !== false) ? $hc_img : ph($hc_img, 800);
        ?>
          <div class="hc-item">
            <img src="<?= h($hc_src) ?>" alt="Happy customer with puppy at Kyra Pet Shop" loading="lazy">
          </div>
        <?php endforeach; ?>
      </div>

      <button class="hc-nav hc-next" id="hc-next" aria-label="Next">
        <svg viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
      </button>
    </div>
  </section>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const slider = document.getElementById('hc-slider');
      const btnPrev = document.getElementById('hc-prev');
      const btnNext = document.getElementById('hc-next');
      if (!slider) return;
      let autoScrollTimer;

      function getScrollAmount() {
        const item = slider.querySelector('.hc-item');
        return item ? item.offsetWidth + 26 : 340;
      }

      function slideNext() {
        if (slider.scrollLeft + slider.clientWidth >= slider.scrollWidth - 10) {
          slider.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
          slider.scrollBy({ left: getScrollAmount(), behavior: 'smooth' });
        }
      }

      function slidePrev() {
        if (slider.scrollLeft <= 0) {
          slider.scrollTo({ left: slider.scrollWidth, behavior: 'smooth' });
        } else {
          slider.scrollBy({ left: -getScrollAmount(), behavior: 'smooth' });
        }
      }

      if (btnNext) btnNext.addEventListener('click', slideNext);
      if (btnPrev) btnPrev.addEventListener('click', slidePrev);

      function startAutoScroll() { autoScrollTimer = setInterval(slideNext, 3200); }
      function stopAutoScroll() { clearInterval(autoScrollTimer); }

      slider.addEventListener('mouseenter', stopAutoScroll);
      slider.addEventListener('mouseleave', startAutoScroll);
      slider.addEventListener('touchstart', stopAutoScroll, {passive: true});
      slider.addEventListener('touchend', startAutoScroll, {passive: true});
      if (btnNext) btnNext.addEventListener('mouseenter', stopAutoScroll);
      if (btnPrev) btnPrev.addEventListener('mouseenter', stopAutoScroll);

      startAutoScroll();
    });
  </script>

  <!-- BREEDS -->
  <section id="breeds">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Find your match</span>
        <h2>Puppies waiting for a loving home</h2>
        <p>Healthy, playful and raised with care. Tap Inquire to chat with us on WhatsApp.</p>
      </div>

      <div class="grid4">
        <?php
        $display_dogs = !empty($db_dogs) ? $db_dogs : array_slice($dogs, 0, 8);
        foreach ($display_dogs as $d) {
          if (function_exists('dog_card')) dog_card($d, $site);
        }
        ?>
      </div>

      <div class="chips rv"><span>Also available:</span><?php if (!empty($more)) foreach ($more as $m): ?><a href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, do you have ' . $m . ' puppies?') ?>" target="_blank" rel="noopener"><?= h($m) ?></a><?php endforeach; ?></div>
      <div class="center"><a class="btn btn-navy" href="breeds.php">View all breeds</a></div>
    </div>
  </section>

  <!-- VIDEOS 2: FOREVER HOMES (6 Videos Dynamic) -->
  <section class="vs2">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Forever homes</span>
        <h2>Where our puppies go next</h2>
        <p>The best part of our work is the moment a puppy meets its new family. Here are a few of those moments.</p>
      </div>
      <?php
      $display_v2 = !empty($db_v2) ? $db_v2 : array_slice($v2 ?? [], 0, $limit_forever_homes);
      ?>
      <div class="fan multi-vids rv" data-anim="zoom">
        <?php
        foreach ($display_v2 as $i => $v) {
          if (function_exists('vcard')) echo vcard($v, $i % 3);
        }
        ?>
      </div>
      <div class="center"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I saw your videos and want a puppy.') ?>">Get a puppy like these</a></div>
    </div>
  </section>

  <!-- GOOGLE REVIEWS -->
  <section class="greview" id="reviews">
    <div class="wrap">
      <div class="rhead">
        <div class="sum rv">
          <div class="g"><?= gicon(26) ?> Google reviews</div>
          <div class="big"><?= h($rating['score'] ?? '4.9') ?> <?= stars(5) ?></div>
          <p>Based on <?= h($rating['count'] ?? '15') ?> reviews</p>
          <?php if (!empty($rating['bars'])) foreach ($rating['bars'] as $n => $pc): ?><div class="bar"><?= $n ?>★<div><i data-w="<?= $pc ?>"></i></div><?= $pc ?>%</div><?php endforeach; ?>
        </div>
        <div class="badges rv">
          <span class="tag" style="align-self:flex-start">Verified customer reviews</span>
          <h3>Rated 4.9 by families who actually took a puppy home</h3>
          <p>These reviews are posted by real customers on Google. Read them on our profile or leave your own after your visit.</p>
          <div class="bd-row">
            <a class="pill" href="<?= h($site['g_profile'] ?? '') ?>" target="_blank" rel="noopener"><?= gicon(18) ?> See all on Google</a>
            <a class="pill" href="<?= h($site['g_write'] ?? '') ?>" target="_blank" rel="noopener">✎ Write a review</a>
            <span class="pill"><span class="tick">✔</span> Verified purchases</span>
          </div>
        </div>
      </div>
      <div class="rwrap">
        <div class="rtrack" id="rtrack">
          <?php
          $display_reviews = !empty($db_reviews) ? $db_reviews : $reviews;
          foreach ($display_reviews as $r):
          ?>
            <article class="rc">
              <div class="top"><span class="av" style="background:<?= h($r[6]) ?>"><?= h(mb_substr($r[0], 0, 1)) ?></span>
                <div>
                  <div class="nm"><?= h($r[0]) ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" aria-label="Verified">
                      <circle cx="12" cy="12" r="12" fill="#1a73e8" />
                      <path d="M7 12.5l3.2 3.2L17 9" stroke="#fff" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                  </div>
                  <small><?= h($r[1]) ?></small>
                </div>
                <span class="src"><?= gicon(24) ?></span>
              </div>
              <div class="st"><?= stars($r[3]) ?> <span><?= h($r[2]) ?></span></div>
              <p>"<?= h($r[5]) ?>"</p>
              <div class="ver">✔ Posted on Google</div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="arrows"><button class="arr" id="rprev" aria-label="Previous reviews">←</button><button class="arr" id="rnext" aria-label="Next reviews">→</button></div>
      <div class="center"><a class="btn btn-navy" href="reviews.php">Read all reviews</a></div>
    </div>
  </section>

  <!-- ABOUT (Dynamic with 3 Images) -->
  <section class="about" id="about">
    <div class="wrap">
      <div class="collage rv" data-anim="left">
        <img class="c1" src="<?= !empty($about['image1']) ? $about['image1'] : ph($P['lab'] ?? '', 800) ?>" alt="Labrador retriever" loading="lazy">
        <img class="c2" src="<?= !empty($about['image2']) ? $about['image2'] : ph($P['pug'] ?? '', 600) ?>" alt="Pug puppy" loading="lazy">
        <img class="c3" src="<?= !empty($about['image3']) ? $about['image3'] : ph($P['tan'] ?? '', 600) ?>" alt="Happy dog" loading="lazy">
        <div class="exp"><b><?= htmlspecialchars($about['years_of_experience'] ?? '25') ?>+</b><span>Years of experience</span></div>
      </div>
      <div class="rv" data-anim="right">
        <span class="tag">About Kyra Pet Shop</span>
        <h2 style="font-size:clamp(28px,4vw,44px);margin-bottom:16px"><?= htmlspecialchars($about['title'] ?? 'A pet shop that treats every puppy like family.') ?></h2>

        <div style="color:var(--mute);font-size:17px; margin-bottom:15px;">
          <?= $about['content'] ?? '<p>Choosing a puppy is a big decision. At Kyra we keep it simple and honest.</p>' ?>
        </div>

        <ul>
          <li><i>✓</i>
            <div><b>100% vaccinated and health checked</b><span>Records handed over with every puppy.</span></div>
          </li>
          <li><i>★</i>
            <div><b>Premium pure breeds</b><span>Honest breed details so there are no surprises.</span></div>
          </li>
          <li><i>♥</i>
            <div><b>Expert care guidance</b><span>Food, grooming and training advice from <?= h($site['owner'] ?? 'Mr. Nitin') ?> and team.</span></div>
          </li>
        </ul>
        <div class="cta" style="margin:0"><a class="btn btn-teal" href="about.php">Read our story</a><a class="btn btn-navy" href="#contact">Talk to our team</a></div>
      </div>
    </div>
  </section>

  <!-- GALLERY -->
  <section id="gallery">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Gallery</span>
        <h2>Adorable moments at Kyra</h2>
      </div>
      <div class="gal-grid">
        <?php
        $display_gallery = !empty($db_gallery) ? $db_gallery : $gallery;
        foreach ($display_gallery as $g):
          $img_src = (strpos($g, '/') !== false) ? $g : ph($g, 800);
          $full_src = (strpos($g, '/') !== false) ? $g : ph($g, 1600);
        ?>
          <a class="rv" href="<?= h($full_src) ?>" target="_blank" rel="noopener">
            <img src="<?= h($img_src) ?>" alt="Puppy at Kyra Pet Shop" loading="lazy">
          </a>
        <?php endforeach; ?>
      </div>
      <div class="center"><a class="btn btn-teal" href="gallery.php">View full gallery</a> &nbsp; <a class="btn btn-navy" href="<?= h($site['insta'] ?? '') ?>" target="_blank" rel="noopener">Follow us on Instagram</a></div>
    </div>
  </section>

  <!-- STATS -->
  <div class="stats">
    <div class="wrap"><?php if (!empty($stats)) foreach ($stats as $s): ?>
        <div class="stat"><b><span data-count="<?= h($s[0]) ?>"><?= h($s[0]) ?></span><small><?= h($s[1]) ?></small></b><span><?= h($s[2]) ?></span></div><?php endforeach; ?>
    </div>
  </div>

  <!-- FEATURES -->
  <section class="feat">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Why families choose us</span>
        <h2>Six promises we keep with every puppy</h2>
        <p>Trust is earned with transparency. Here is what you can expect from us.</p>
      </div>
      <div class="fgrid"><?php if (!empty($features)) foreach ($features as $f): ?>
          <div class="fcard rv"><i><?= $f[0] ?></i>
            <h3><?= h($f[1]) ?></h3>
            <p><?= h($f[2]) ?></p>
          </div><?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- PROCESS -->
  <section class="proc" id="process">
    <div class="wrap">
      <div class="sec-head"><span class="tag">How it works</span>
        <h2>From first hello to puppy at home in four easy steps</h2>
        <p>No rush and no hidden steps. You stay informed at every stage.</p>
      </div>
      <div class="steps rv"><?php if (!empty($steps)) foreach ($steps as $s): ?><div class="step rv">
            <h3><?= h($s[0]) ?></h3>
            <p><?= h($s[1]) ?></p>
          </div><?php endforeach; ?></div>
      <div class="center"><a class="btn btn-teal" href="how-it-works.php">See the full process</a></div>
    </div>
  </section>

  <!-- KIT + COMPARE -->
  <section>
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Our guarantee</span>
        <h2>What you get, and why it matters</h2>
      </div>
      <div class="duo">
        <div class="panel hl rv">
          <h3>Every puppy comes with</h3>
          <p>Everything you need for a confident start.</p>
          <ul class="kit"><?php if (!empty($kit)) foreach ($kit as $k): ?><li><?= h($k) ?></li><?php endforeach; ?></ul>
        </div>
        <div class="panel rv">
          <h3>Kyra vs a typical seller</h3>
          <p>Questions every buyer should ask.</p>
          <table>
            <thead>
              <tr>
                <th></th>
                <th>Kyra</th>
                <th>Others</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($compare)) foreach ($compare as $c): ?><tr>
                  <td style="text-align:left;color:var(--ink);font-weight:600"><?= h($c[0]) ?></td>
                  <td><?= h($c[1]) ?></td>
                  <td><?= h($c[2]) ?></td>
                </tr><?php endforeach; ?></tbody>
          </table>
        </div>
      </div>
    </div>
  </section>

  <!-- VIDEOS 1: CUSTOMER STORIES (3 Videos Dynamic) -->
  <section class="videos vs1" id="videos">
    <div class="wrap">
      <div class="rv" data-anim="left">
        <span class="tag">Customer stories</span>
        <h2>Real families. Real puppies. Real smiles.</h2>
        <p style="color:#B4C4DC;font-size:17px;margin:14px 0 24px">Do not just take our word for it. Watch customers talk about their visit, their puppy and their experience with Kyra Pet Shop.</p>
        <ul class="kit">
          <li>Honest feedback, recorded at our shop</li>
          <li>Meet the people who took a puppy home</li>
          <li>See how happy and healthy the puppies are</li>
        </ul>
        <div class="cta" style="margin:30px 0 0"><a class="btn btn-teal" href="#contact">Plan your visit</a><a class="btn btn-line" href="<?= h($site['yt'] ?? '') ?>" target="_blank" rel="noopener">Our YouTube channel</a></div>
      </div>
      <div class="trio rv" data-anim="right">
        <?php
        $display_v1 = !empty($db_v1) ? $db_v1 : array_slice($v1 ?? [], 0, $limit_customer_stories);
        foreach ($display_v1 as $i => $v) {
          if (function_exists('vcard')) echo vcard($v, $i % 3);
        }
        ?>
      </div>
    </div>
  </section>

  <!-- VIDEOS 3: PUPPY PARENTS / ASK THEM, NOT US (3 Videos Dynamic) -->
  <section class="vs3">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Puppy parents speak</span>
        <h2>Ask them, not us</h2>
        <p>Honest words from people who visited our shop and took a puppy home. Tap a video to hear them.</p>
      </div>
      <div class="pp">
        <?php
        $display_v3 = !empty($db_v3) ? $db_v3 : array_slice($v3 ?? [], 0, $limit_ask_them);
        foreach ($display_v3 as $i => $v):
        ?>
          <div class="ppc rv">
            <?php if (function_exists('vcard')) echo vcard($v, $i % 3); ?>
            <div class="q"><?= stars(5) ?><b><?= h($v[1] ?? 'Customer') ?></b><span>Customer feedback at Kyra Pet Shop</span></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- OWNER NOTE -->
  <section class="owner">
    <div class="wrap">
      <div class="box rv">
        <img src="<?= ph($P['sweater'] ?? '', 700) ?>" alt="Puppy cared for by the Kyra team" loading="lazy">
        <div>
          <h2>"We want every puppy to go home healthy, and every family to go home happy."</h2>
          <p>Hello, I am <b><?= h($site['owner'] ?? 'Mr. Nitin') ?></b>. Come visit us, meet the puppies, and ask me anything. I will always tell you the truth about the breed, the health and the care it needs.</p>
          <div class="cta" style="margin:0"><a class="btn btn-teal" href="tel:<?= h($tel ?? '') ?>">Call <?= h($site['phone'] ?? '') ?></a><a class="btn btn-line" href="#contact">Plan a visit</a></div>
        </div>
      </div>
    </div>
  </section>

  <!-- BLOG PREVIEW -->
  <section class="feat" style="padding-bottom:80px">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Pet care tips</span>
        <h2>Latest from our blog</h2>
        <p>Simple, honest advice for new puppy parents.</p>
      </div>
      <div class="bgrid">
        <?php
        $display_blog = !empty($db_blog) ? $db_blog : $blog;
        foreach (array_slice($display_blog, 0, 3) as $b) {
          if (function_exists('blog_card')) blog_card($b);
        }
        ?>
      </div>
      <div class="center">
        <a class="btn btn-navy" href="blog.php">Read all articles</a>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section style="padding-top:20px">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">FAQ</span>
        <h2>Questions before you visit</h2>
      </div>
      <div class="faq-list"><?php if (!empty($faq)) foreach ($faq as $f): ?><details>
            <summary><?= h($f[0]) ?></summary>
            <p><?= h($f[1]) ?></p>
          </details><?php endforeach; ?></div>
    </div>
  </section>

  <!-- CTA BAND -->
  <div class="band">
    <div class="wrap">
      <div class="in rv">
        <div>
          <h2>Ready to meet your new best friend?</h2>
          <p>Visit us <?= h($site['hours'] ?? '') ?> or message us on WhatsApp.</p>
        </div>
        <a class="btn" target="_blank" rel="noopener" href="https://wa.me/<?= h($site['wa'] ?? '') ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I would like to visit and see the puppies.') ?>">Chat on WhatsApp</a>
      </div>
    </div>
  </div>

  <!-- CONTACT (Dynamic Map iframe) -->
  <section class="contact" id="contact">
    <div class="wrap">
      <div class="sec-head c"><span class="tag">Get in touch</span>
        <h2>Send us an inquiry</h2>
        <p><?= h($site['owner'] ?? 'Mr. Nitin') ?> will call you back, usually within a few hours.</p>
      </div>
      <div class="cbox">

        <!-- MAP DYNAMIC CODE -->
        <div><iframe title="Kyra Pet Shop location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= htmlspecialchars($contact['map'] ?? 'https://www.google.com/maps?q=WZ-23+Khampur+Patel+Nagar+New+Delhi+110008&output=embed') ?>"></iframe></div>

        <form class="form" method="post" action="#contact">
          <h3>Looking for a specific breed?</h3>
          <p class="sub">Share your details and we will send photos, videos and health records.</p>
          <?php if ($msg === 'ok'): ?><div class="alert ok" role="status">Thank you. We received your inquiry and will call you soon.</div>
          <?php elseif ($msg === 'err'): ?><div class="alert err" role="alert">Please enter your name and a valid phone number.</div><?php endif; ?>
          <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
          <div class="f2"><input name="name" placeholder="Your name" required><input name="phone" type="tel" placeholder="Phone number" required></div>
          <input name="email" type="email" placeholder="Email (optional)"><input name="breed" placeholder="Which breed are you looking for?">
          <textarea name="message" rows="3" placeholder="Anything specific? (age, gender, etc.)"></textarea>
          <button class="btn btn-navy" type="submit">Send inquiry</button>
          <div class="info"><span>📞 <?= h($site['phone'] ?? '') ?></span><span>🕒 <?= h($site['hours'] ?? '') ?></span></div>
        </form>
      </div>
    </div>
  </section>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>