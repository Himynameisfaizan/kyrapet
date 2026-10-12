<?php
require __DIR__.'/includes/config.php';
$title='Gallery | Puppy and Dog Photos | Kyra Pet Shop';
$desc='Adorable moments at Kyra Pet Shop: healthy puppies, happy dogs and the families who love them.';
$page='gallery';
$cats=array_values(array_unique(array_column($gal,1)));
require __DIR__.'/includes/header.php';
banner('Our Pet Gallery','Gallery','Adorable moments from Kyra Pet Shop. Tap any photo to see it larger.');
?>
<main>
<section style="padding-top:30px"><div class="wrap">
  <div class="fchips" role="group" aria-label="Filter photos">
    <button class="active" data-v="all">All photos</button>
    <?php foreach($cats as $c): ?><button data-v="<?=h($c)?>"><?=h($c)?></button><?php endforeach; ?>
  </div>
  <div class="masonry" id="grid">
    <?php foreach($gal as $g): ?>
    <a class="gitem rv" href="<?=h(ph($g[0],1600))?>" data-cat="<?=h($g[1])?>"><img src="<?=h(ph($g[0],700))?>" alt="<?=h($g[2])?>" loading="lazy"></a>
    <?php endforeach; ?>
  </div>
  <div class="center">
    <a class="btn btn-teal" href="reviews.php">Watch customer videos</a> &nbsp;
    <a class="btn btn-navy" href="<?=h($site['insta'])?>" target="_blank" rel="noopener">Follow us on Instagram</a>
  </div>
</div></section>
<?php cta_band('Love what you see?','Come and meet our puppies in person. We would love to introduce you.'); ?>
</main>

<div class="lb" id="lb" role="dialog" aria-modal="true" aria-label="Photo viewer">
  <button class="x" aria-label="Close">✕</button><button class="pv" aria-label="Previous">←</button>
  <img src="" alt=""><button class="nx" aria-label="Next">→</button>
</div>
<?php require __DIR__.'/includes/footer.php'; ?>
