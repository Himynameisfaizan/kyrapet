<?php
require __DIR__.'/includes/config.php';
$title='Our Premium Breeds | Puppies for Sale in New Delhi | Kyra Pet Shop';
$desc='Browse healthy, vaccinated puppies: Labrador, Golden Retriever, Pug, Shih Tzu, Beagle, German Shepherd, Husky, Poodle and more.';
$page='breeds';
$breedNames=array_values(array_unique(array_column($dogs,'breed')));
require __DIR__.'/includes/header.php';
banner('Our Premium Breeds','Breeds','Healthy, vaccinated and raised with care. Pick a puppy and message us on WhatsApp.');
?>
<main>
<section style="padding-top:30px"><div class="wrap">
  <div class="tools">
    <div class="sbox"><input id="q" type="search" placeholder="Search breed or name..." aria-label="Search puppies"></div>
    <div class="sel">
      <select id="fGender" aria-label="Filter by gender"><option value="all">All genders</option><option>Male</option><option>Female</option></select>
      <select id="fSort" aria-label="Sort puppies"><option value="order">Featured</option><option value="az">Name A to Z</option></select>
    </div>
  </div>
  <div class="fchips" role="group" aria-label="Filter by breed">
    <button class="active" data-v="all">All breeds</button>
    <?php foreach($breedNames as $b): ?><button data-v="<?=h($b)?>"><?=h($b)?></button><?php endforeach; ?>
  </div>
  <div class="count" id="cnt"></div>
  <div class="grid4" id="grid"><?php foreach($dogs as $d) dog_card($d,$site); ?></div>
  <div class="empty" id="empty"><b>No puppies match your search</b>Try another breed, or message us. New puppies arrive often.</div>

  <div class="leave rv" style="margin-top:64px">
    <div><h3>Cannot see your dream breed?</h3><p>Tell us the breed, gender and age you want. We will share photos, videos and health details as soon as we have a match.</p></div>
    <div class="cta" style="margin:0"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'])?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I am looking for a specific breed.')?>">Ask on WhatsApp</a><a class="btn btn-line" href="tel:<?=h($tel)?>">Call us</a></div>
  </div>
</div></section>
<?php cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
