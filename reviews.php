<?php
require __DIR__.'/config/connect.php';
require __DIR__.'/includes/config.php';

$title='Customer Reviews | Kyra Pet Shop, Patel Nagar, New Delhi';
$desc='Read verified Google reviews and watch video feedback from families who took a healthy puppy home from Kyra Pet Shop.';
$page='reviews';

// ==========================================
// 1. PAGINATION SETUP
// ==========================================
$limit = 12; // Ek page par kitne items dikhane hain
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $limit;

// ==========================================
// 2. FETCH REVIEWS & VIDEOS COUNT & DATA
// ==========================================
$db_reviews = [];
$colors = ['#4a90e2', '#e91e63', '#4caf50', '#ff9800', '#9c27b0'];
$color_i = 0;
$rev_q = mysqli_query($conn, "SELECT * FROM testimonials WHERE status=1 ORDER BY test_id DESC");
if ($rev_q && mysqli_num_rows($rev_q) > 0) {
    while ($row = mysqli_fetch_assoc($rev_q)) {
        $db_reviews[] = [
            $row['name'], 
            !empty($row['designation']) ? $row['designation'] : 'Verified Pet Parent', 
            date('M Y', strtotime($row['created_at'])), 
            5, 
            'google', 
            $row['message'], 
            $colors[$color_i % count($colors)]
        ];
        $color_i++;
    }
}

$db_vids = [];
$vid_q = mysqli_query($conn, "SELECT * FROM videos WHERE status=1 ORDER BY video_id DESC");
if ($vid_q && mysqli_num_rows($vid_q) > 0) {
    while ($v_row = mysqli_fetch_assoc($vid_q)) {
        $db_vids[] = [$v_row['video_code'], $v_row['title']];
    }
}

// Fallback data agar database khali ho
$display_reviews = !empty($db_reviews) ? $db_reviews : ($reviews ?? []);
$display_vids    = !empty($db_vids) ? $db_vids : ($vids ?? []);

// Dono arrays ko combine karke ek single items array banate hain pagination ke liye
$all_items = [];
foreach ($display_reviews as $r) {
    $all_items[] = ['type' => 'google', 'data' => $r];
}
foreach ($display_vids as $i => $v) {
    $all_items[] = ['type' => 'video', 'data' => $v, 'index' => $i % 3];
}

$total_items = count($all_items);
$total_pages = ceil($total_items / $limit);

// Current page ke hisaab se items slice karna
$paginated_items = array_slice($all_items, $offset, $limit);

require __DIR__.'/includes/header.php';

if (function_exists('banner')) {
    banner('Reviews from our pet parents','Reviews','Real feedback from real families. Read their words or watch their stories.');
}
?>
<main>
<section class="greview" style="padding-top:40px"><div class="wrap">
  <div class="rhead">
    <div class="sum rv">
      <div class="g"><?=gicon(26)?> Google reviews</div>
      <div class="big"><?=h($rating['score'] ?? '4.9')?> <?=stars(5)?></div>
      <p>Based on <?=h($rating['count'] ?? count($display_reviews))?> reviews</p>
      <?php if (!empty($rating['bars'])): foreach($rating['bars'] as $n=>$pc): ?><div class="bar"><?=$n?>★<div><i data-w="<?=$pc?>"></i></div><?=$pc?>%</div><?php endforeach; endif; ?>
    </div>
    <div class="badges rv">
      <span class="tag" style="align-self:flex-start">Verified customer reviews</span>
      <h3>Loved by families across Delhi NCR</h3>
      <p>These reviews are posted by real customers on Google. Read them on our profile, or leave your own after your visit.</p>
      <div class="bd-row">
        <a class="pill" href="<?=h($site['g_profile'] ?? '#')?>" target="_blank" rel="noopener"><?=gicon(18)?> See all on Google</a>
        <a class="pill" href="<?=h($site['g_write'] ?? '#')?>" target="_blank" rel="noopener">✎ Write a review</a>
        <span class="pill"><span class="tick">✔</span> Verified purchases</span>
      </div>
    </div>
  </div>

  <div class="fchips" role="group" aria-label="Filter reviews">
    <button class="active" data-v="all">All</button><button data-v="google">Google reviews</button><button data-v="video">Video stories</button>
  </div>
  
  <div class="rmason" id="grid">
    <?php foreach($paginated_items as $item): ?>
      <?php if ($item['type'] === 'google'): $r = $item['data']; ?>
        <article class="rc rv" data-cat="google">
          <div class="top"><span class="av" style="background:<?=h($r[6])?>"><?=h(mb_substr($r[0],0,1))?></span>
            <div><div class="nm"><?=h($r[0])?><svg width="16" height="16" viewBox="0 0 24 24" aria-label="Verified"><circle cx="12" cy="12" r="12" fill="#1a73e8"/><path d="M7 12.5l3.2 3.2L17 9" stroke="#fff" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></div><small><?=h($r[1])?></small></div>
            <span class="src"><?=gicon(24)?></span></div>
          <div class="st"><?=stars($r[3])?> <span><?=h($r[2])?></span></div>
          <p>"<?=h($r[5])?>"</p><div class="ver">✔ Posted on Google</div>
        </article>
      <?php else: $v = $item['data']; $i = $item['index']; ?>
        <div class="ppc rv" data-cat="video">
          <?php if(function_exists('vcard')) echo vcard($v, $i); ?>
          <div class="q"><?=stars(5)?><b><?=h($v[1] ?? 'Customer Story')?></b><span>Video feedback · Kyra Pet Shop</span></div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <!-- DYNAMIC PAGINATION -->
  <?php if ($total_pages > 1): ?>
  <div class="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 40px; flex-wrap: wrap;">
      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
          <a href="?page=<?= $p ?>" class="btn <?= ($current_page == $p) ? 'btn-teal' : 'btn-line' ?>" style="padding: 10px 20px; border-radius: 8px; text-decoration: none;">
              <?= $p ?>
          </a>
      <?php endfor; ?>
  </div>
  <?php endif; ?>

  <div class="leave rv" style="margin-top:60px">
    <div><h3>Took a puppy home from us?</h3><p>Your review helps other families trust us. It takes less than a minute.</p></div>
    <div><ol><li><b>Open</b> our Google profile</li><li><b>Tap</b> "Write a review"</li><li><b>Share</b> your experience with your puppy</li></ol>
    <div class="cta" style="margin:22px 0 0"><a class="btn btn-teal" href="<?=h($site['g_write'] ?? '#')?>" target="_blank" rel="noopener">Write a Google review</a></div></div>
  </div>
</div></section>
<?php 
if (function_exists('cta_band')) {
    cta_band(); 
}
?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>