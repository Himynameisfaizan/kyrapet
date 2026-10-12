<?php
require __DIR__.'/includes/config.php';
$title='Pet Care Blog | Puppy Tips and Advice | Kyra Pet Shop';
$desc='Simple, honest puppy care advice: feeding, training, vaccination, choosing the right breed and more.';
$page='blog';
$cats=array_values(array_unique(array_column($blog,'cat')));
$first=$blog[0]; $rest=array_slice($blog,1);
$fu='blog-post.php?slug='.urlencode($first['slug']);
require __DIR__.'/includes/header.php';
banner('Pet Care Blog','Blog','Practical tips for new and experienced puppy parents.');
?>
<main>
<section style="padding-top:30px"><div class="wrap">
  <article class="fpost rv">
    <a class="bim" href="<?=$fu?>"><img src="<?=ph($first['img'],1100)?>" alt="<?=h($first['title'])?>" fetchpriority="high"><span class="bc">Featured · <?=h($first['cat'])?></span></a>
    <div class="bb"><div class="bmeta"><?=h($first['date'])?> · <?=h($first['read'])?></div>
      <h2><a href="<?=$fu?>"><?=h($first['title'])?></a></h2><p><?=h($first['excerpt'])?></p>
      <div><a class="btn btn-teal" href="<?=$fu?>">Read article</a></div></div>
  </article>

  <div class="fchips" role="group" aria-label="Filter articles">
    <button class="active" data-v="all">All articles</button>
    <?php foreach($cats as $c): ?><button data-v="<?=h($c)?>"><?=h($c)?></button><?php endforeach; ?>
  </div>
  <div class="bgrid" id="grid"><?php foreach($blog as $b) blog_card($b); ?></div>
  <div class="empty" id="empty"><b>No articles yet</b>Check back soon.</div>

  <div class="leave rv" style="margin-top:64px">
    <div><h3>Have a question about your puppy?</h3><p>Our team is happy to help with feeding, training, health and grooming. Message us any time.</p></div>
    <div class="cta" style="margin:0"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'])?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I have a question about puppy care.')?>">Ask on WhatsApp</a></div>
  </div>
</div></section>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
