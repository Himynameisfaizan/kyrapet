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
        $clean_desc = trim(strip_tags($row['description']));
        $excerpt = mb_strlen($clean_desc) > 120 ? mb_substr($clean_desc, 0, 120) . '...' : $clean_desc;
        
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
$display_blog = !empty($db_blog) ? $db_blog : $blog;
$cats  = array_values(array_unique(array_column($display_blog, 'cat')));
$first = $display_blog[0] ?? null;
$rest  = array_slice($display_blog, 1);
$fu    = $first ? 'blog-post.php?slug=' . urlencode($first['slug']) : '#';

if (function_exists('banner')) {
    banner('Pet Care Blog','Blog','Practical tips for new and experienced puppy parents.');
}
?>

<?php if (!empty($schema_markup)): ?>
  <!-- Dynamic Page Schema -->
  <?php if (strpos($schema_markup, '<script') !== false): ?>
    <?= $schema_markup ?>
  <?php else: ?>
    <script type="application/ld+json"><?= $schema_markup ?></script>
  <?php endif; ?>
<?php endif; ?>

<main>
<section style="padding-top:30px"><div class="wrap">
  <?php if ($first): 
    $first_img = (strpos($first['img'], '/') !== false) ? $first['img'] : ph($first['img'], 1100);
  ?>
  <article class="fpost rv">
    <a class="bim" href="<?=$fu?>"><img src="<?=h($first_img)?>" alt="<?=h($first['title'])?>" fetchpriority="high"><span class="bc">Featured · <?=h($first['cat'])?></span></a>
    <div class="bb"><div class="bmeta"><?=h($first['date'])?> · <?=h($first['read'])?></div>
      <h2><a href="<?=$fu?>"><?=h($first['title'])?></a></h2><p><?=h($first['excerpt'])?></p>
      <div><a class="btn btn-teal" href="<?=$fu?>">Read article</a></div></div>
  </article>
  <?php endif; ?>

  <div class="fchips" role="group" aria-label="Filter articles">
    <button class="active" data-v="all">All articles</button>
    <?php foreach($cats as $c): ?><button data-v="<?=h($c)?>"><?=h($c)?></button><?php endforeach; ?>
  </div>
  
  <div class="bgrid" id="grid">
    <?php foreach($display_blog as $b) {
        if (function_exists('blog_card')) blog_card($b);
    } ?>
  </div>
  
  <div class="empty" id="empty" <?= empty($display_blog) ? 'style="display:block;"' : '' ?>><b>No articles yet</b>Check back soon.</div>

  <div class="leave rv" style="margin-top:64px">
    <div><h3>Have a question about your puppy?</h3><p>Our team is happy to help with feeding, training, health and grooming. Message us any time.</p></div>
    <div class="cta" style="margin:0"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'] ?? '')?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I have a question about puppy care.')?>">Ask on WhatsApp</a></div>
  </div>
</div></section>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>