<?php
require __DIR__.'/includes/config.php';
$slug=$_GET['slug']??'';
$dog=null;
foreach($dogs as $d){ if($d['slug']===$slug){ $dog=$d; break; } }
if(!$dog){ header('Location: breeds.php'); exit; }
$title=$dog['name'].' for Sale in New Delhi | Kyra Pet Shop';
$desc=$dog['desc'];
$page='breeds';
$wa='https://wa.me/'.$site['wa'].'?text='.rawurlencode('Hi Kyra Pet Shop, I am interested in: '.$dog['name']);
$related=array_slice(array_values(array_filter($dogs,fn($x)=>$x['slug']!==$dog['slug'])),0,4);
$src=$dog['img']?(strpos($dog['img'],'/')!==false?$dog['img']:ph($dog['img'],1100)):'';
require __DIR__.'/includes/header.php';
banner($dog['name'],'Breeds');
?>
<main>
<section style="padding-top:30px"><div class="wrap bdet">
  <div class="pic rv" data-anim="left">
    <?php if($src): ?><img src="<?=h($src)?>" alt="<?=h($dog['name'])?>" fetchpriority="high"><?php else: ?><div class="noimg" style="height:100%"><span style="font-size:80px">🐾</span><?=h($dog['breed'])?><small style="font-weight:600;color:var(--mute)">Photo available on request</small></div><?php endif; ?>
  </div>
  <div class="rv" data-anim="right">
    <span class="tag"><?=h($dog['breed'])?></span>
    <h2><?=h($dog['name'])?></h2>
    <p class="lead"><?=h($dog['desc'])?></p>
    <div class="badge-row"><span class="pill"><span class="tick">✔</span> Vet checked</span><span class="pill"><span class="tick">✔</span> Vaccinated</span><span class="pill"><span class="tick">✔</span> Health certificate</span></div>
    <table class="specs"><tbody>
      <tr><td>Breed</td><td><?=h($dog['breed'])?></td></tr>
      <tr><td>Age</td><td><?=h($dog['age'])?></td></tr>
      <tr><td>Gender</td><td><?=h($dog['gender'])?></td></tr>
      <tr><td>Adult size</td><td><?=h($dog['size'])?></td></tr>
      <tr><td>Temperament</td><td><?=h($dog['temper'])?></td></tr>
      <tr><td>Price</td><td>Contact us for today's price</td></tr>
    </tbody></table>
    <div class="cta" style="margin:0 0 26px"><a class="btn btn-wa" target="_blank" rel="noopener" href="<?=h($wa)?>">Inquire on WhatsApp</a><a class="btn btn-navy" href="tel:<?=h($tel)?>">Call <?=h($site['phone'])?></a></div>
    <ul class="kit"><li>Vaccination card and health certificate included</li><li>Feeding chart and care guide</li><li>Visit and meet the puppy before you decide</li></ul>
  </div>
</div></section>

<section class="feat"><div class="wrap">
  <div class="sec-head c"><span class="tag">More puppies</span><h2>You may also like</h2></div>
  <div class="grid4"><?php foreach($related as $d) dog_card($d,$site); ?></div>
  <div class="center"><a class="btn btn-navy" href="breeds.php">View all breeds</a></div>
</div></section>
<?php cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
