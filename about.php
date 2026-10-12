<?php
require __DIR__.'/includes/config.php';
$title='About Us | Kyra Pet Shop, Patel Nagar, New Delhi';
$desc='Learn about Kyra Pet Shop: 25+ years of experience, KCI registered, vet checked and vaccinated puppies, and honest care guidance.';
$page='about';
$values=[
 ['🩺','Health first','Every puppy is vet checked and vaccinated before it goes home. A healthy puppy is the only kind we are happy to sell.'],
 ['🤝','Honesty','Clear breed information, clear records and clear prices. You always know what you are getting.'],
 ['💚','Genuine care','We love dogs. Our puppies live in a clean, caring space and get attention every day.'],
 ['💰','Fair pricing','Quality puppies at prices families can trust, with no hidden charges.'],
 ['🎓','Guidance','Feeding, grooming, training and vaccination advice for first-time and experienced owners.'],
 ['🏠','Long-term support','We do not disappear after the sale. Call or WhatsApp us whenever you need help.'],
];
require __DIR__.'/includes/header.php';
banner('About Kyra Pet Shop','About Us','A trusted home for healthy, happy puppies in Patel Nagar, New Delhi.');
?>
<main>
<!-- STORY -->
<section><div class="wrap twocol">
  <div class="collage rv" data-anim="left">
    <img class="c1" src="<?=ph($P['golden'],900)?>" alt="Golden Retriever puppy" loading="lazy">
    <img class="c2" src="<?=ph($P['pug'],600)?>" alt="Pug puppy" loading="lazy">
    <img class="c3" src="<?=ph($P['lab'],600)?>" alt="Labrador" loading="lazy">
    <div class="exp"><b>25+</b><span>Years of experience</span></div>
  </div>
  <div class="rv" data-anim="right">
    <span class="tag">Our story</span>
    <h2 style="font-size:clamp(28px,4vw,44px);margin-bottom:16px">Your trusted source for healthy and happy puppies in Delhi NCR.</h2>
    <p style="color:var(--mute);font-size:17px;margin-bottom:16px">Welcome to Kyra Pet Shop, where every wagging tail and happy bark fills our hearts with joy. We are a passionate, family-run pet shop based in Patel Nagar, dedicated to helping you find your perfect four-legged companion.</p>
    <p style="color:var(--mute);font-size:17px;margin-bottom:26px">With years of experience in the pet world, we have built a reputation for offering healthy, ethically bred and well-socialized puppies to loving homes across the region. We do not just sell puppies. We help families find the right match and support them for life.</p>
    <div class="cta" style="margin:0"><a class="btn btn-teal" href="breeds.php">See our puppies</a><a class="btn btn-navy" href="index.php#contact">Visit the shop</a></div>
  </div>
</div></section>

<!-- NUMBERS -->
<section style="padding:0 0 90px"><div class="wrap"><div class="numbers rv">
  <?php foreach($stats as $s): ?><div><b><span data-count="<?=h($s[0])?>"><?=h($s[0])?></span><small><?=h($s[1])?></small></b><span><?=h($s[2])?></span></div><?php endforeach; ?>
</div></div></section>

<!-- VALUES -->
<section class="feat"><div class="wrap">
  <div class="sec-head c"><span class="tag">What we stand for</span><h2>The values behind every puppy we place</h2><p>These are the promises our team keeps, every single day.</p></div>
  <div class="fgrid"><?php foreach($values as $f): ?><div class="fcard rv"><i><?=$f[0]?></i><h3><?=h($f[1])?></h3><p><?=h($f[2])?></p></div><?php endforeach; ?></div>
</div></section>

<!-- COMMITMENTS -->
<section><div class="wrap">
  <div class="sec-head c"><span class="tag">Our commitment</span><h2>What you can count on</h2></div>
  <div class="duo">
    <div class="panel hl rv"><h3>We always</h3><p>Simple standards we follow with every family.</p>
      <ul class="kit"><li>Share vaccination and health records</li><li>Let you meet the puppy before you decide</li><li>Explain breed needs honestly</li><li>Answer your questions after the sale</li><li>Suggest the breed that fits your home</li></ul></div>
    <div class="panel rv"><h3>We never</h3><p>Things you will never experience at Kyra Pet Shop.</p>
      <ul class="kit nope"><li>Pressure you to buy quickly</li><li>Hide health or breed details</li><li>Sell a puppy that is not healthy</li><li>Add surprise charges</li><li>Ignore you after you take your puppy home</li></ul></div>
  </div>
</div></section>

<!-- OWNER -->
<section class="owner" style="padding-top:20px"><div class="wrap"><div class="box rv">
  <img src="<?=ph($P['sweater'],700)?>" alt="Puppy cared for by the Kyra team" loading="lazy">
  <div><h2>"We want every puppy to go home healthy, and every family to go home happy."</h2>
    <p>Hello, I am <b><?=h($site['owner'])?></b>. Come visit us, meet the puppies and ask me anything. I will always tell you the truth about the breed, the health and the care it needs.</p>
    <div class="cta" style="margin:0"><a class="btn btn-teal" href="tel:<?=h($tel)?>">Call <?=h($site['phone'])?></a><a class="btn btn-line" href="how-it-works.php">How it works</a></div></div>
</div></div></section>

<!-- VIDEOS -->
<section class="vs2"><div class="wrap">
  <div class="sec-head c"><span class="tag">Meet our families</span><h2>See the smiles for yourself</h2><p>A few moments from the families who trusted us with their new best friend.</p></div>
  <div class="fan rv" data-anim="zoom"><?php foreach($v1 as $i=>$v) echo vcard($v,$i); ?></div>
  <div class="center"><a class="btn btn-teal" href="reviews.php">Read and watch more reviews</a></div>
</div></section>

<?php cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
