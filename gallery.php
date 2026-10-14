<?php
require __DIR__.'/config/connect.php'; // Database connection
require __DIR__.'/includes/config.php';

$title='Gallery | Puppy and Dog Photos | Kyra Pet Shop';
$desc='Adorable moments at Kyra Pet Shop: healthy puppies, happy dogs and the families who love them.';
$page='gallery';

// ==========================================
// FETCH GALLERY FROM DATABASE
// ==========================================
$db_gallery = [];
$gal_q = mysqli_query($conn, "SELECT * FROM gallery ORDER BY ID DESC");
if ($gal_q) {
    while ($row = mysqli_fetch_assoc($gal_q)) {
        $db_gallery[] = [
            'path' => 'admin/' . $row['image_path'],
            'name' => $row['image_name'],
            'cat'  => 'Puppies' 
        ];
    }
}

$cats = ['Puppies'];
require __DIR__.'/includes/header.php';

if(function_exists('banner')) {
    banner('Our Pet Gallery','Gallery','Adorable moments from Kyra Pet Shop. Tap any photo to see it larger.');
}
?>
<main>
<section style="padding-top:30px"><div class="wrap">
  <div class="fchips" role="group" aria-label="Filter photos">
    <button class="active" data-v="all">All photos</button>
    <?php foreach($cats as $c): ?><button data-v="<?=h($c)?>"><?=h($c)?></button><?php endforeach; ?>
  </div>
  
  <div class="masonry" id="grid">
    <?php 
    if (!empty($db_gallery)) {
        foreach($db_gallery as $g): 
    ?>
        <a class="gitem rv" href="<?=h($g['path'])?>" data-cat="<?=h($g['cat'])?>">
            <img src="<?=h($g['path'])?>" alt="<?=h($g['name'])?>" loading="lazy">
        </a>
    <?php 
        endforeach;
    } else {
        // Fallback to static config array if database has no records
        foreach($gal as $g): 
    ?>
        <a class="gitem rv" href="<?=h(ph($g[0],1600))?>" data-cat="<?=h($g[1])?>">
            <img src="<?=h(ph($g[0],700))?>" alt="<?=h($g[2])?>" loading="lazy">
        </a>
    <?php 
        endforeach;
    }
    ?>
  </div>

  <div class="center">
    <a class="btn btn-teal" href="reviews.php">Watch customer videos</a> &nbsp;
    <a class="btn btn-navy" href="<?=h($site['insta'] ?? '#')?>" target="_blank" rel="noopener">Follow us on Instagram</a>
  </div>
</div></section>
<?php 
if(function_exists('cta_band')) {
    cta_band('Love what you see?','Come and meet our puppies in person. We would love to introduce you.'); 
}
?>
</main>

<div class="lb" id="lb" role="dialog" aria-modal="true" aria-label="Photo viewer">
  <button class="x" aria-label="Close">✕</button><button class="pv" aria-label="Previous">←</button>
  <img src="" alt=""><button class="nx" aria-label="Next">→</button>
</div>
<?php require __DIR__.'/includes/footer.php'; ?>