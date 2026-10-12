<?php
include ('config/connect.php');
include ('includes/config.php');

$page='home';

// ==========================================
// 1. DYNAMIC META & SCHEMA FETCHING
// ==========================================
$meta_q = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='index.php'");
$meta_data = mysqli_fetch_assoc($meta_q);
$title = $meta_data['meta_title'] ?? 'Kyra Pet Shop | Healthy, Vaccinated Puppies in Patel Nagar, New Delhi';
$desc = $meta_data['meta_desc'] ?? 'Premium pure breed puppies in New Delhi. Vet checked, 100% vaccinated, rated 4.9 on Google. Visit Kyra Pet Shop in Patel Nagar.';

$schema_q = mysqli_query($conn, "SELECT * FROM page_schemas WHERE page_url='index.php'");
$schema_data = mysqli_fetch_assoc($schema_q);
// (Note: header.php me aap $schema_data['schema_markup'] print karwa sakte ho)

// ==========================================
// 2. FETCHING ALL SECTIONS DATA
// ==========================================

// Hero Section
$hero_q = mysqli_query($conn, "SELECT * FROM hero_section LIMIT 1");
$hero = mysqli_fetch_assoc($hero_q);

// About Section
$about_q = mysqli_query($conn, "SELECT * FROM about_us LIMIT 1");
$about = mysqli_fetch_assoc($about_q);

// Dog Products
$home_dogs = [];
$prod_q = mysqli_query($conn, "SELECT * FROM products WHERE status=1 AND pro_cate=34158 ORDER BY id DESC LIMIT 4");
while($row = mysqli_fetch_assoc($prod_q)) { 
    $home_dogs[] = [
        'slug'   => $row['slug_url'],               
        'name'   => $row['pro_name'],               
        'breed'  => $row['brand_name'] ?? 'Dog',    
        'gender' => 'Male/Female',                  
        'age'    => '8 Weeks',                      
        'img'    => !empty($row['pro_img']) ? 'admin/assets/img/uploads/' . $row['pro_img'] : null, 
        'n'      => $row['id']
    ]; 
}
// Videos (Distributed into 3 sections)
$v1 = []; $v2 = []; $v3 = [];
$vid_q = mysqli_query($conn, "SELECT * FROM videos WHERE status=1");
while($row = mysqli_fetch_assoc($vid_q)) {
    if(isset($row['section_type'])) {
        if($row['section_type'] == 'customer_stories') $v1[] = $row;
        elseif($row['section_type'] == 'forever_homes') $v2[] = $row;
        elseif($row['section_type'] == 'ask_them') $v3[] = $row;
    }
}

// Reviews / Testimonials (Mapping to match your HTML logic)
$reviews = [];
$colors = ['#4a90e2', '#e91e63', '#4caf50', '#ff9800', '#9c27b0'];
$color_i = 0;
$rev_q = mysqli_query($conn, "SELECT * FROM testimonials WHERE status=1");
while($row = mysqli_fetch_assoc($rev_q)) {
    // Array matching your design: [Name, Designation, Date, Rating, _, Message, Avatar_Color]
    $reviews[] = [$row['name'], $row['designation'], date('M Y', strtotime($row['created_at'])), 5, '', $row['message'], $colors[$color_i % count($colors)]];
    $color_i++;
}

// Gallery Section
$gallery = [];
$gal_q = mysqli_query($conn, "SELECT * FROM gallery ORDER BY ID DESC LIMIT 8");
while($row = mysqli_fetch_assoc($gal_q)) { 
    $gallery[] = 'admin/' . $row['image_path']; 
}

// Blog Section
$blog = [];
$blog_q = mysqli_query($conn, "SELECT * FROM blogs WHERE status=1 ORDER BY created_at DESC LIMIT 3");
while($row = mysqli_fetch_assoc($blog_q)) { 
    // Description column se HTML tags hata kar short text (excerpt) bana rahe hain
    $desc = strip_tags($row['description']);
    $excerpt = mb_substr($desc, 0, 100) . '...';
    
    $blog[] = [
        'slug'    => $row['slug'],
        'title'   => $row['title'],
        'cat'     => 'Pet Care', 
        'date'    => date('M d, Y', strtotime($row['created_at'])),
        'read'    => '5 min read',
        'img'     => 'admin/assets/img/uploads/blogs/' . $row['image'],
        'excerpt' => $excerpt
    ]; 
}
// Contact & Map Data
$contact_q = mysqli_query($conn, "SELECT * FROM contacts LIMIT 1");
$contact = mysqli_fetch_assoc($contact_q);

// ==========================================
// 3. INQUIRY FORM HANDLING (Insert to DB)
// ==========================================
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST' && empty($_POST['website'])){
  $n=trim(strip_tags($_POST['name']??''));
  $p=trim(strip_tags($_POST['phone']??''));
  $b=trim(strip_tags($_POST['breed']??''));
  $m=trim(strip_tags($_POST['message']??''));
  $e=filter_var($_POST['email']??'',FILTER_VALIDATE_EMAIL)?:'';
  
  if($n && preg_match('/^[0-9+\s-]{8,15}$/',$p)){
    // Save to Database
    $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    $subj = "New Inquiry for Breed: " . $b;
    $stmt->bind_param("sssss", $n, $p, $e, $subj, $m);
    $stmt->execute();

    // Send Mail
    @mail($site['email'],'New inquiry from website',"Name: $n\nPhone: $p\nEmail: $e\nBreed: $b\nMessage: $m","From: no-reply@".($_SERVER['HTTP_HOST']??'localhost'));
    $msg='ok';
  } else { 
    $msg='err'; 
  }
}

require __DIR__.'/includes/header.php';
?>
<main id="home">
<!-- HERO (Now Dynamic) -->
<section class="hero" style="padding-bottom:0"><div class="blob b1"></div><div class="blob b2"></div><?php for($k=0;$k<9;$k++): ?><span class="paw" style="left:<?=($k*11+5)%96?>%;animation-delay:<?=$k*1.4?>s;font-size:<?=16+($k%3)*8?>px">🐾</span><?php endfor; ?><div class="wrap">
  <div>
    <div class="chip seq"><?=gicon(18)?> <span><b><?=h($rating['score'] ?? '4.9')?></b> rated on Google by <?=h($rating['count'] ?? '15+')?>+ pet parents</span></div>
    
    <h1 class="split"><?= htmlspecialchars($hero['title'] ?? 'Bring home a healthy puppy you can trust.') ?></h1>
    <p class="lead seq"><?= htmlspecialchars($hero['description'] ?? 'Vet checked, fully vaccinated, pure breed puppies in Patel Nagar, New Delhi. We show you every record before you decide.') ?></p>
    
    <div class="cta seq"><a class="btn btn-teal" href="<?= htmlspecialchars($hero['button_link'] ?? '#breeds') ?>"><?= htmlspecialchars($hero['button_text'] ?? 'See available puppies') ?></a><a class="btn btn-line" href="#videos">Watch customer stories</a></div>
    <div class="mini-proof seq">
      <div class="faces"><?php foreach([$P['grass']??'',$P['tan']??'',$P['tongue']??'',$P['sweater']??''] as $f): if($f): ?><img src="<?=ph($f,100)?>" alt=""><?php endif; endforeach; ?></div>
      <div><span class="stars">★★★★★</span><small>Trusted by 420+ happy clients</small></div>
    </div>
  </div>
  <div class="hero-img">
    <div class="arch"><img src="<?= ph($hero['image_url'] ?? $P['golden'] ?? '', 1000) ?>" alt="Kyra Pet Shop Hero" fetchpriority="high"></div>
    <div class="fb b"><i>🩺</i><span>Vet checked<small>Health records shared</small></span></div>
    <div class="fb a"><i>💉</i><span>100% vaccinated<small>Card given at pickup</small></span></div>
  </div>
</div></section>

<!-- MARQUEE -->
<div class="marq"><div class="marq-track">
  <?php for($k=0;$k<2;$k++): foreach(['Vet checked puppies','100% vaccinated','Pure breed guarantee','Visit before you buy','Lifetime care support','KCI registered puppies','Rated '.($rating['score'] ?? '4.9').' on Google','Visit us in Patel Nagar'] as $t): ?><span><b>✦</b><?=h($t)?></span><?php endforeach; endfor; ?>
</div></div>

<!-- STATS -->
<div class="stats"><div class="wrap"><?php if(!empty($stats)) foreach($stats as $s): ?>
  <div class="stat"><b><span data-count="<?=h($s[0])?>"><?=h($s[0])?></span><small><?=h($s[1])?></small></b><span><?=h($s[2])?></span></div><?php endforeach; ?></div></div>

<!-- VISIT BAR -->
<section class="visit"><div class="wrap">
  <form class="vbar rv" method="post" action="#contact">
    <h3>Schedule your pet visit</h3>
    <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
    <input type="hidden" name="breed" value="Shop visit booking">
    <input name="name" placeholder="Your name" required aria-label="Your name">
    <input name="phone" type="tel" placeholder="Phone number" required aria-label="Phone number">
    <button class="btn btn-teal" type="submit">Book now</button>
  </form>
</div></section>

<!-- ABOUT (Now Dynamic with 3 Images) -->
<section class="about" id="about"><div class="wrap">
  <div class="collage rv" data-anim="left">
    <img class="c1" src="<?= !empty($about['image1']) ? $about['image1'] : ph($P['lab']??'',800) ?>" alt="Labrador retriever" loading="lazy">
    <img class="c2" src="<?= !empty($about['image2']) ? $about['image2'] : ph($P['pug']??'',600) ?>" alt="Pug puppy" loading="lazy">
    <img class="c3" src="<?= !empty($about['image3']) ? $about['image3'] : ph($P['tan']??'',600) ?>" alt="Happy dog" loading="lazy">
    <div class="exp"><b><?= htmlspecialchars($about['years_of_experience'] ?? '25') ?>+</b><span>Years of experience</span></div>
  </div>
  <div class="rv" data-anim="right">
    <span class="tag">About Kyra Pet Shop</span>
    <h2 style="font-size:clamp(28px,4vw,44px);margin-bottom:16px"><?= htmlspecialchars($about['title'] ?? 'A pet shop that treats every puppy like family.') ?></h2>
    
    <div style="color:var(--mute);font-size:17px; margin-bottom:15px;">
        <?= $about['content'] ?? '<p>Choosing a puppy is a big decision. At Kyra we keep it simple and honest.</p>' ?>
    </div>
    
    <ul>
      <li><i>✓</i><div><b>100% vaccinated and health checked</b><span>Records handed over with every puppy.</span></div></li>
      <li><i>★</i><div><b>Premium pure breeds</b><span>Honest breed details so there are no surprises.</span></div></li>
      <li><i>♥</i><div><b>Expert care guidance</b><span>Food, grooming and training advice from <?=h($site['owner'] ?? 'Mr. Nitin')?> and team.</span></div></li>
    </ul>
    <div class="cta" style="margin:0"><a class="btn btn-teal" href="about.php">Read our story</a><a class="btn btn-navy" href="#contact">Talk to our team</a></div>
  </div>
</div></section>

<!-- FEATURES -->
<section class="feat"><div class="wrap">
  <div class="sec-head c"><span class="tag">Why families choose us</span><h2>Six promises we keep with every puppy</h2><p>Trust is earned with transparency. Here is what you can expect from us.</p></div>
  <div class="fgrid"><?php if(!empty($features)) foreach($features as $f): ?>
    <div class="fcard rv"><i><?=$f[0]?></i><h3><?=h($f[1])?></h3><p><?=h($f[2])?></p></div><?php endforeach; ?></div>
</div></section>

<!-- BREEDS (Now Dynamic from Products Table) -->
<section id="breeds"><div class="wrap">
  <div class="sec-head c"><span class="tag">Find your match</span><h2>Puppies waiting for a loving home</h2><p>Healthy, playful and raised with care. Tap Inquire to chat with us on WhatsApp.</p></div>
  
  <div class="grid4"><?php foreach($home_dogs as $d) { if(function_exists('dog_card')) dog_card($d,$site); } ?></div>
  
  <div class="chips rv"><span>Also available:</span><?php if(!empty($more)) foreach($more as $m): ?><a href="https://wa.me/<?=h($site['wa']??'')?>?text=<?=rawurlencode('Hi Kyra Pet Shop, do you have '.$m.' puppies?')?>" target="_blank" rel="noopener"><?=h($m)?></a><?php endforeach; ?></div>
  <div class="center"><a class="btn btn-navy" href="breeds.php">View all breeds</a></div>
</div></section>

<!-- VIDEOS 2: FOREVER HOMES (Dynamic DB) -->
<section class="vs2"><div class="wrap">
  <div class="sec-head c"><span class="tag">Forever homes</span><h2>Where our puppies go next</h2><p>The best part of our work is the moment a puppy meets its new family. Here are a few of those moments.</p></div>
  <div class="fan rv" data-anim="zoom"><?php foreach($v2 as $i=>$v) { if(function_exists('vcard')) echo vcard($v,$i); } ?></div>
  <div class="center"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa']??'')?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I saw your videos and want a puppy.')?>">Get a puppy like these</a></div>
</div></section>

<!-- PROCESS -->
<section class="proc" id="process"><div class="wrap">
  <div class="sec-head"><span class="tag">How it works</span><h2>From first hello to puppy at home in four easy steps</h2><p>No rush and no hidden steps. You stay informed at every stage.</p></div>
  <div class="steps rv"><?php if(!empty($steps)) foreach($steps as $s): ?><div class="step rv"><h3><?=h($s[0])?></h3><p><?=h($s[1])?></p></div><?php endforeach; ?></div>
<div class="center"><a class="btn btn-teal" href="how-it-works.php">See the full process</a></div>
</div></section>

<!-- KIT + COMPARE -->
<section><div class="wrap">
  <div class="sec-head c"><span class="tag">Our guarantee</span><h2>What you get, and why it matters</h2></div>
  <div class="duo">
    <div class="panel hl rv"><h3>Every puppy comes with</h3><p>Everything you need for a confident start.</p>
      <ul class="kit"><?php if(!empty($kit)) foreach($kit as $k): ?><li><?=h($k)?></li><?php endforeach; ?></ul></div>
    <div class="panel rv"><h3>Kyra vs a typical seller</h3><p>Questions every buyer should ask.</p>
      <table><thead><tr><th></th><th>Kyra</th><th>Others</th></tr></thead><tbody>
      <?php if(!empty($compare)) foreach($compare as $c): ?><tr><td style="text-align:left;color:var(--ink);font-weight:600"><?=h($c[0])?></td><td><?=h($c[1])?></td><td><?=h($c[2])?></td></tr><?php endforeach; ?></tbody></table></div>
  </div>
</div></section>

<!-- VIDEOS 1: CUSTOMER STORIES (Dynamic DB) -->
<section class="videos vs1" id="videos"><div class="wrap">
  <div class="rv" data-anim="left">
    <span class="tag">Customer stories</span>
    <h2>Real families. Real puppies. Real smiles.</h2>
    <p style="color:#B4C4DC;font-size:17px;margin:14px 0 24px">Do not just take our word for it. Watch customers talk about their visit, their puppy and their experience with Kyra Pet Shop.</p>
    <ul class="kit"><li>Honest feedback, recorded at our shop</li><li>Meet the people who took a puppy home</li><li>See how happy and healthy the puppies are</li></ul>
    <div class="cta" style="margin:30px 0 0"><a class="btn btn-teal" href="#contact">Plan your visit</a><a class="btn btn-line" href="<?=h($site['yt']??'')?>" target="_blank" rel="noopener">Our YouTube channel</a></div>
  </div>
  <div class="trio rv" data-anim="right"><?php foreach($v1 as $i=>$v) { if(function_exists('vcard')) echo vcard($v,$i); } ?></div>
</div></section>

<!-- GOOGLE REVIEWS (Dynamic from Testimonials Table) -->
<section class="greview" id="reviews"><div class="wrap">
  <div class="rhead">
    <div class="sum rv">
      <div class="g"><?=gicon(26)?> Google reviews</div>
      <div class="big"><?=h($rating['score'] ?? '4.9')?> <?=stars(5)?></div>
      <p>Based on <?=h($rating['count'] ?? '15')?> reviews</p>
      <?php if(!empty($rating['bars'])) foreach($rating['bars'] as $n=>$pc): ?><div class="bar"><?=$n?>★<div><i data-w="<?=$pc?>"></i></div><?=$pc?>%</div><?php endforeach; ?>
    </div>
    <div class="badges rv">
      <span class="tag" style="align-self:flex-start">Verified customer reviews</span>
      <h3>Rated 4.9 by families who actually took a puppy home</h3>
      <p>These reviews are posted by real customers on Google. Read them on our profile or leave your own after your visit.</p>
      <div class="bd-row">
        <a class="pill" href="<?=h($site['g_profile']??'')?>" target="_blank" rel="noopener"><?=gicon(18)?> See all on Google</a>
        <a class="pill" href="<?=h($site['g_write']??'')?>" target="_blank" rel="noopener">✎ Write a review</a>
        <span class="pill"><span class="tick">✔</span> Verified purchases</span>
      </div>
    </div>
  </div>
  <div class="rwrap"><div class="rtrack" id="rtrack">
    <?php foreach($reviews as $r): ?>
    <article class="rc">
      <div class="top"><span class="av" style="background:<?=h($r[6])?>"><?=h(mb_substr($r[0],0,1))?></span>
        <div><div class="nm"><?=h($r[0])?>
          <svg width="16" height="16" viewBox="0 0 24 24" aria-label="Verified"><circle cx="12" cy="12" r="12" fill="#1a73e8"/><path d="M7 12.5l3.2 3.2L17 9" stroke="#fff" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg></div>
          <small><?=h($r[1])?></small></div>
        <span class="src"><?=gicon(24)?></span></div>
      <div class="st"><?=stars($r[3])?> <span><?=h($r[2])?></span></div>
      <p>"<?=h($r[5])?>"</p>
      <div class="ver">✔ Posted on Google</div>
    </article><?php endforeach; ?>
  </div></div>
  <div class="arrows"><button class="arr" id="rprev" aria-label="Previous reviews">←</button><button class="arr" id="rnext" aria-label="Next reviews">→</button></div>
  <div class="center"><a class="btn btn-navy" href="reviews.php">Read all reviews</a></div>
</div></section>

<!-- GALLERY (Dynamic DB) -->
<section id="gallery"><div class="wrap">
  <div class="sec-head c"><span class="tag">Gallery</span><h2>Adorable moments at Kyra</h2></div>
  <div class="gal-grid">
    <?php foreach($gallery as $g): ?>
      <a class="rv" href="<?= h($g) ?>" target="_blank" rel="noopener">
        <img src="<?= h($g) ?>" alt="Puppy at Kyra Pet Shop" loading="lazy">
      </a>
    <?php endforeach; ?>
  </div>
  <div class="center"><a class="btn btn-teal" href="gallery.php">View full gallery</a> &nbsp; <a class="btn btn-navy" href="<?=h($site['insta']??'')?>" target="_blank" rel="noopener">Follow us on Instagram</a></div>
</div></section>

<!-- VIDEOS 3: PUPPY PARENTS (Dynamic DB) -->
<section class="vs3"><div class="wrap">
  <div class="sec-head c"><span class="tag">Puppy parents speak</span><h2>Ask them, not us</h2><p>Honest words from people who visited our shop and took a puppy home. Tap a video to hear them.</p></div>
  <div class="pp"><?php foreach($v3 as $i=>$v): ?><div class="ppc rv"><?php if(function_exists('vcard')) echo vcard($v,$i); ?><div class="q"><?=stars(5)?><b><?=h(is_array($v) ? ($v['title'] ?? 'Customer') : 'Customer')?></b><span>Customer feedback at Kyra Pet Shop</span></div></div><?php endforeach; ?></div>
</div></section>

<!-- OWNER NOTE -->
<section class="owner"><div class="wrap"><div class="box rv">
  <img src="<?=ph($P['sweater']??'',700)?>" alt="Puppy cared for by the Kyra team" loading="lazy">
  <div><h2>"We want every puppy to go home healthy, and every family to go home happy."</h2>
    <p>Hello, I am <b><?=h($site['owner']??'Mr. Nitin')?></b>. Come visit us, meet the puppies, and ask me anything. I will always tell you the truth about the breed, the health and the care it needs.</p>
    <div class="cta" style="margin:0"><a class="btn btn-teal" href="tel:<?=h($tel??'')?>">Call <?=h($site['phone']??'')?></a><a class="btn btn-line" href="#contact">Plan a visit</a></div></div>
</div></div></section>

<!-- BLOG PREVIEW (Dynamic DB) -->
<section class="feat" style="padding-bottom:80px"><div class="wrap">
  <div class="sec-head c"><span class="tag">Pet care tips</span><h2>Latest from our blog</h2><p>Simple, honest advice for new puppy parents.</p></div>
  <div class="bgrid">
    <?php foreach(array_slice($blog,0,3) as $b) { 
      if(function_exists('blog_card')) blog_card($b);
       } ?>
  </div>
  <div class="center">
    <a class="btn btn-navy" href="blog.php">Read all articles</a>
  </div>
</div>
</section>

<!-- FAQ -->
<section style="padding-top:20px"><div class="wrap">
  <div class="sec-head c"><span class="tag">FAQ</span><h2>Questions before you visit</h2></div>
  <div class="faq-list"><?php if(!empty($faq)) foreach($faq as $f): ?><details><summary><?=h($f[0])?></summary><p><?=h($f[1])?></p></details><?php endforeach; ?></div>
</div></section>

<!-- CTA BAND -->
<div class="band"><div class="wrap"><div class="in rv">
  <div><h2>Ready to meet your new best friend?</h2><p>Visit us <?=h($site['hours']??'')?> or message us on WhatsApp.</p></div>
  <a class="btn" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa']??'')?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I would like to visit and see the puppies.')?>">Chat on WhatsApp</a>
</div></div></div>

<!-- CONTACT (Dynamic Map iframe) -->
<section class="contact" id="contact"><div class="wrap">
  <div class="sec-head c"><span class="tag">Get in touch</span><h2>Send us an inquiry</h2><p><?=h($site['owner']??'Mr. Nitin')?> will call you back, usually within a few hours.</p></div>
  <div class="cbox">
    
    <!-- MAP DYNAMIC CODE -->
    <div><iframe title="Kyra Pet Shop location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?= htmlspecialchars($contact['map'] ?? 'https://www.google.com/maps?q=WZ-23+Khampur+Patel+Nagar+New+Delhi+110008&output=embed') ?>"></iframe></div>
    
    <form class="form" method="post" action="#contact">
      <h3>Looking for a specific breed?</h3><p class="sub">Share your details and we will send photos, videos and health records.</p>
      <?php if($msg==='ok'): ?><div class="alert ok" role="status">Thank you. We received your inquiry and will call you soon.</div>
      <?php elseif($msg==='err'): ?><div class="alert err" role="alert">Please enter your name and a valid phone number.</div><?php endif; ?>
      <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
      <div class="f2"><input name="name" placeholder="Your name" required><input name="phone" type="tel" placeholder="Phone number" required></div>
      <input name="email" type="email" placeholder="Email (optional)"><input name="breed" placeholder="Which breed are you looking for?">
      <textarea name="message" rows="3" placeholder="Anything specific? (age, gender, etc.)"></textarea>
      <button class="btn btn-navy" type="submit">Send inquiry</button>
      <div class="info"><span>📞 <?=h($site['phone']??'')?></span><span>🕒 <?=h($site['hours']??'')?></span></div>
    </form>
  </div>
</div></section>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>