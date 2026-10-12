<?php
require __DIR__.'/includes/config.php';
$title='How It Works | Buying a Puppy from Kyra Pet Shop';
$desc='Four simple steps from choosing your breed to taking your healthy, vaccinated puppy home, with support after.';
$page='how';
$detail=[
 ['Choose your breed',$P['lab'],'Browse our puppies online or visit the shop and meet them in person.',['Look at breeds, photos and videos','Ask about size, grooming and energy','Shortlist the puppies you like']],
 ['Talk to our team',$P['golden'],'Tell us about your home, family and daily routine. We suggest the breed that truly fits.',['Share space, time and budget','Get honest breed advice','Ask us anything, no pressure']],
 ['Health check and papers',$P['pug'],'Before you decide, we show you the puppy\'s health details and paperwork.',['Vet health check and vaccination records','KCI registration details where applicable','Health certificate included']],
 ['Take your puppy home',$P['sweater'],'Pick up your new family member with everything you need for a calm start.',['Feeding chart and care guide','Starter guidance on training','Our support number for any question']],
];
$ask=['Is the puppy vaccinated, and which vaccines were given?','Can I see the health records and papers?','What food has the puppy been eating?','How big will this breed grow, and how much exercise does it need?','What grooming does this breed need?','Who do I call if I have a question after pickup?'];
$qa=array_slice($faq,0,5);
require __DIR__.'/includes/header.php';
banner('How It Works','How It Works','Four simple steps, no pressure, and support long after you take your puppy home.');
?>
<main>
<section><div class="wrap">
  <div class="sec-head c"><span class="tag">Step by step</span><h2>From first hello to puppy at home</h2><p>We keep the process clear, so you always know what happens next.</p></div>
  <?php foreach($detail as $i=>$s): ?>
  <div class="trow">
    <div class="tpic rv" data-anim="<?=$i%2?'right':'left'?>"><img src="<?=ph($s[1],900)?>" alt="<?=h($s[0])?>" loading="lazy"></div>
    <div class="rv" data-anim="<?=$i%2?'left':'right'?>">
      <span class="tnum"><?=$i+1?></span>
      <h3><?=h($s[0])?></h3><p><?=h($s[2])?></p>
      <ul class="kit"><?php foreach($s[3] as $li): ?><li><?=h($li)?></li><?php endforeach; ?></ul>
    </div>
  </div>
  <?php endforeach; ?>
</div></section>

<section class="feat"><div class="wrap">
  <div class="sec-head c"><span class="tag">Before you buy</span><h2>Smart questions to ask on your visit</h2><p>A good seller is happy to answer all of these.</p></div>
  <div class="duo">
    <div class="panel rv"><h3>Questions to ask</h3><p>Print or save this list before you visit.</p>
      <ul class="kit"><?php foreach($ask as $q): ?><li><?=h($q)?></li><?php endforeach; ?></ul></div>
    <div class="panel hl rv"><h3>Every puppy comes with</h3><p>What you receive at pickup.</p>
      <ul class="kit"><?php foreach($kit as $k): ?><li><?=h($k)?></li><?php endforeach; ?></ul>
      <div class="cta" style="margin:26px 0 0"><a class="btn btn-teal" href="breeds.php">Browse puppies</a></div></div>
  </div>
</div></section>

<section><div class="wrap">
  <div class="sec-head c"><span class="tag">FAQ</span><h2>Common questions</h2></div>
  <div class="faq-list"><?php foreach($qa as $f): ?><details><summary><?=h($f[0])?></summary><p><?=h($f[1])?></p></details><?php endforeach; ?></div>
</div></section>
<?php cta_band('Ready to start?','Visit us or message us on WhatsApp and we will guide you through every step.'); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
