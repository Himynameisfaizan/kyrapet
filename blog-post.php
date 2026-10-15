<?php
require __DIR__.'/config/connect.php';
require __DIR__.'/includes/config.php';

$slug = trim($_GET['slug'] ?? '');
$post = null;

// ==========================================
// 1. FETCH SINGLE BLOG POST FROM DATABASE
// ==========================================
if ($slug !== '') {
    $slug_esc = mysqli_real_escape_string($conn, $slug);
    $post_q = mysqli_query($conn, "SELECT * FROM blogs WHERE slug='$slug_esc' AND status=1 LIMIT 1");
    
    if ($post_q && mysqli_num_rows($post_q) > 0) {
        $row = mysqli_fetch_assoc($post_q);
        $clean_desc = trim(strip_tags($row['description']));
        $excerpt = mb_strlen($clean_desc) > 150 ? mb_substr($clean_desc, 0, 150) . '...' : $clean_desc;
        
        // Reading time calculate karna
        $word_count = str_word_count($clean_desc);
        $read_min = max(3, ceil($word_count / 180)) . ' min read';

        $post = [
            'slug'          => $row['slug'],
            'title'         => $row['title'],
            'author'        => !empty($row['author']) ? $row['author'] : 'Kyra Pet Shop',
            'cat'           => 'Pet Care',
            'date'          => date('M d, Y', strtotime($row['created_at'])),
            'read'          => $read_min,
            'img'           => !empty($row['image']) ? 'admin/assets/img/uploads/blogs/' . $row['image'] : ($P['golden'] ?? ''),
            'excerpt'       => $excerpt,
            'html_body'     => $row['description'], // Database ka full HTML description
            'meta_title'    => $row['meta_title'] ?? '',
            'meta_desc'     => $row['meta_desc'] ?? '',
            'meta_key'      => $row['meta_key'] ?? '',
            'schema_markup' => $row['schema_markup'] ?? ''
        ];
    }
}

// Fallback: Agar DB mein post na mile toh config.php ke static $blog array mein check karega
if (!$post && !empty($blog)) {
    foreach ($blog as $b) {
        if ($b['slug'] === $slug) {
            $post = $b;
            break;
        }
    }
}

// Agar kahin bhi post na mile toh blog.php par redirect kar dega
if (!$post) {
    header('Location: blog.php');
    exit;
}

// ==========================================
// 2. FETCH RECENT BLOGS FOR SIDEBAR
// ==========================================
$db_recent = [];
$slug_esc = mysqli_real_escape_string($conn, $post['slug']);
$rec_q = mysqli_query($conn, "SELECT * FROM blogs WHERE status=1 AND slug != '$slug_esc' ORDER BY created_at DESC LIMIT 3");
if ($rec_q) {
    while ($r_row = mysqli_fetch_assoc($rec_q)) {
        $db_recent[] = [
            'slug'  => $r_row['slug'],
            'title' => $r_row['title'],
            'date'  => date('M d, Y', strtotime($r_row['created_at'])),
            'img'   => !empty($r_row['image']) ? 'admin/assets/img/uploads/blogs/' . $r_row['image'] : ($P['golden'] ?? '')
        ];
    }
}

// ==========================================
// 3. DYNAMIC META & SCHEMA SETUP
// ==========================================
$title    = !empty($post['meta_title']) ? $post['meta_title'] : ($post['title'] . ' | Kyra Pet Shop Blog');
$desc     = !empty($post['meta_desc']) ? $post['meta_desc'] : $post['excerpt'];
$keywords = !empty($post['meta_key']) ? $post['meta_key'] : 'Kyra Pet Shop, ' . $post['title'];
$meta_key = $keywords;
$schema_markup = $post['schema_markup'] ?? '';

$page = 'blog';
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . strtok($_SERVER['REQUEST_URI'] ?? '', '?') . '?slug=' . urlencode($post['slug']);

require __DIR__.'/includes/header.php';

// Header include ke baad sidebar recent posts ka fallback set karna
$recent = !empty($db_recent) ? $db_recent : array_slice(array_values(array_filter($blog, fn($x) => $x['slug'] !== $post['slug'])), 0, 3);

if (function_exists('banner')) {
    banner($post['title'], 'Blog');
}
?>

<?php if (!empty($schema_markup)): ?>
  <!-- Dynamic Blog Post Schema -->
  <?php if (strpos($schema_markup, '<script') !== false): ?>
    <?= $schema_markup ?>
  <?php else: ?>
    <script type="application/ld+json"><?= $schema_markup ?></script>
  <?php endif; ?>
<?php endif; ?>

<main>
<section style="padding-top:30px"><div class="wrap artwrap">
  <article class="art rv">
    <div class="bmeta" style="margin-bottom:14px"><?=h($post['cat'])?> · <?=h($post['date'])?> · <?=h($post['read'])?> · By Kyra Pet Shop</div>
    
    <?php 
    // Local image path vs Unsplash ph() check
    $hero_img = (strpos($post['img'], '/') !== false) ? $post['img'] : ph($post['img'], 1200);
    ?>
    <div class="hero-i"><img src="<?=h($hero_img)?>" alt="<?=h($post['title'])?>"></div>
    
    <?php 
    // Agar DB se HTML body aayi hai toh direct print karega, warna static array loop chalayega
    if (isset($post['html_body'])): 
        echo $post['html_body'];
    elseif (!empty($post['body']) && is_array($post['body'])):
        foreach($post['body'] as $blk):
            if(is_array($blk)){ echo '<ul>'; foreach($blk as $li) echo '<li>'.h($li).'</li>'; echo '</ul>'; }
            elseif(strpos($blk,'# ')===0){ echo '<h3>'.h(substr($blk,2)).'</h3>'; }
            else { echo '<p>'.h($blk).'</p>'; }
        endforeach;
    endif; 
    ?>
    
    <div class="share"><span>Share:</span>
      <a class="pill" target="_blank" rel="noopener" href="https://wa.me/?text=<?=rawurlencode($post['title'].' '.$url)?>">WhatsApp</a>
      <a class="pill" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?=rawurlencode($url)?>">Facebook</a>
      <a class="pill" href="blog.php">← All articles</a></div>
  </article>
  
  <aside class="side">
    <div class="sbox2 cta"><h4>Talk to our team</h4><p>Questions about feeding, training or choosing a breed? We are happy to help.</p>
      <a class="btn" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'] ?? '')?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I read your article: '.$post['title'])?>">Chat on WhatsApp</a></div>
    
    <div class="sbox2"><h4>More to read</h4>
      <?php foreach($recent as $r): 
        $r_img = (strpos($r['img'], '/') !== false) ? $r['img'] : ph($r['img'], 200);
      ?>
        <a class="rp" href="blog-post.php?slug=<?=urlencode($r['slug'])?>">
          <img src="<?=h($r_img)?>" alt="<?=h($r['title'])?>">
          <span><b><?=h($r['title'])?></b><small><?=h($r['date'])?></small></span>
        </a>
      <?php endforeach; ?>
    </div>
    
    <div class="sbox2"><h4>Meet our puppies</h4><p style="color:var(--mute);font-size:15px;margin-bottom:14px">Healthy, vaccinated and ready for a loving home.</p><a class="btn btn-navy" style="width:100%" href="breeds.php">View breeds</a></div>
  </aside>
</div></section>
<?php if (function_exists('cta_band')) cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>