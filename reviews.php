<?php
require __DIR__.'/includes/config.php';
$title='Customer Reviews | Kyra Pet Shop, Patel Nagar, New Delhi';
$desc='Read verified Google reviews and watch video feedback from families who took a healthy puppy home from Kyra Pet Shop.';
$page='reviews';
require __DIR__.'/includes/header.php';
banner('Reviews from our pet parents','Reviews','Real feedback from real families. Read their words or watch their stories.');
?>
<main>
<section class="greview" style="padding-top:40px"><div class="wrap">
  <div class="rhead">
    <div class="sum rv">
      <div class="g"><?=gicon(26)?> Google reviews</div>
      <div class="big"><?=h($rating['score'])?> <?=stars(5)?></div>
      <p>Based on <?=h($rating['count'])?> reviews</p>
      <?php foreach($rating['bars'] as $n=>$pc): ?><div class="bar"><?=$n?>★<div><i data-w="<?=$pc?>"></i></div><?=$pc?>%</div><?php endforeach; ?>
    </div>
    <div class="badges rv">
      <span class="tag" style="align-self:flex-start">Verified customer reviews</span>
      <h3>Loved by families across Delhi NCR</h3>
      <p>These reviews are posted by real customers on Google. Read them on our profile, or leave your own after your visit.</p>
      <div class="bd-row">
        <a class="pill" href="<?=h($site['g_profile'])?>" target="_blank" rel="noopener"><?=gicon(18)?> See all on Google</a>
        <a class="pill" href="<?=h($site['g_write'])?>" target="_blank" rel="noopener">✎ Write a review</a>
        <span class="pill"><span class="tick">✔</span> Verified purchases</span>
      </div>
    </div>
  </div>

  <div class="fchips" role="group" aria-label="Filter reviews">
    <button class="active" data-v="all">All</button><button data-v="google">Google reviews</button><button data-v="video">Video stories</button>
  </div>
  <div class="rmason" id="grid">
    <?php foreach($reviews as $r): ?>
    <article class="rc rv" data-cat="google">
      <div class="top"><span class="av" style="background:<?=h($r[6])?>"><?=h(mb_substr($r[0],0,1))?></span>
        <div><div class="nm"><?=h($r[0])?><svg width="16" height="16" viewBox="0 0 24 24" aria-label="Verified"><circle cx="12" cy="12" r="12" fill="#1a73e8"/><path d="M7 12.5l3.2 3.2L17 9" stroke="#fff" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></div><small><?=h($r[1])?></small></div>
        <span class="src"><?=gicon(24)?></span></div>
      <div class="st"><?=stars($r[3])?> <span><?=h($r[2])?></span></div>
      <p>"<?=h($r[5])?>"</p><div class="ver">✔ Posted on Google</div>
    </article>
    <?php endforeach; ?>
    <?php foreach($vids as $i=>$v): ?>
    <div class="ppc rv" data-cat="video"><?=vcard($v,$i%3)?><div class="q"><?=stars(5)?><b><?=h($v[1])?></b><span>Video feedback · Kyra Pet Shop</span></div></div>
    <?php endforeach; ?>
  </div>

  <div class="leave rv" style="margin-top:60px">
    <div><h3>Took a puppy home from us?</h3><p>Your review helps other families trust us. It takes less than a minute.</p></div>
    <div><ol><li><b>Open</b> our Google profile</li><li><b>Tap</b> "Write a review"</li><li><b>Share</b> your experience with your puppy</li></ol>
    <div class="cta" style="margin:22px 0 0"><a class="btn btn-teal" href="<?=h($site['g_write'])?>" target="_blank" rel="noopener">Write a Google review</a></div></div>
  </div>
</div></section>
<?php cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
