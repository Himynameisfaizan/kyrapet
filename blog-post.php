<?php
require __DIR__.'/includes/config.php';
$slug=$_GET['slug']??'';
$post=null;
foreach($blog as $b){ if($b['slug']===$slug){ $post=$b; break; } }
if(!$post){ header('Location: blog.php'); exit; }
$title=$post['title'].' | Kyra Pet Shop Blog';
$desc=$post['excerpt'];
$page='blog';
$recent=array_slice(array_values(array_filter($blog,fn($x)=>$x['slug']!==$post['slug'])),0,3);
$url=(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off'?'https':'http').'://'.($_SERVER['HTTP_HOST']??'localhost').strtok($_SERVER['REQUEST_URI']??'','?').'?slug='.urlencode($post['slug']);
require __DIR__.'/includes/header.php';
banner($post['title'],'Blog');
?>
<main>
<section style="padding-top:30px"><div class="wrap artwrap">
  <article class="art rv">
    <div class="bmeta" style="margin-bottom:14px"><?=h($post['cat'])?> · <?=h($post['date'])?> · <?=h($post['read'])?> · By Kyra Pet Shop</div>
    <div class="hero-i"><img src="<?=ph($post['img'],1200)?>" alt="<?=h($post['title'])?>"></div>
    <?php foreach($post['body'] as $blk):
      if(is_array($blk)){ echo '<ul>'; foreach($blk as $li) echo '<li>'.h($li).'</li>'; echo '</ul>'; }
      elseif(strpos($blk,'# ')===0){ echo '<h3>'.h(substr($blk,2)).'</h3>'; }
      else { echo '<p>'.h($blk).'</p>'; }
    endforeach; ?>
    <div class="share"><span>Share:</span>
      <a class="pill" target="_blank" rel="noopener" href="https://wa.me/?text=<?=rawurlencode($post['title'].' '.$url)?>">WhatsApp</a>
      <a class="pill" target="_blank" rel="noopener" href="https://www.facebook.com/sharer/sharer.php?u=<?=rawurlencode($url)?>">Facebook</a>
      <a class="pill" href="blog.php">← All articles</a></div>
  </article>
  <aside class="side">
    <div class="sbox2 cta"><h4>Talk to our team</h4><p>Questions about feeding, training or choosing a breed? We are happy to help.</p>
      <a class="btn" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'])?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I read your article: '.$post['title'])?>">Chat on WhatsApp</a></div>
    <div class="sbox2"><h4>More to read</h4>
      <?php foreach($recent as $r): ?><a class="rp" href="blog-post.php?slug=<?=urlencode($r['slug'])?>"><img src="<?=ph($r['img'],200)?>" alt=""><span><b><?=h($r['title'])?></b><small><?=h($r['date'])?></small></span></a><?php endforeach; ?></div>
    <div class="sbox2"><h4>Meet our puppies</h4><p style="color:var(--mute);font-size:15px;margin-bottom:14px">Healthy, vaccinated and ready for a loving home.</p><a class="btn btn-navy" style="width:100%" href="breeds.php">View breeds</a></div>
  </aside>
</div></section>
<?php cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>
