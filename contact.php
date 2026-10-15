<?php
require __DIR__.'/config/connect.php';
require __DIR__.'/includes/config.php';

$title='Contact Us | Kyra Pet Shop, Patel Nagar, New Delhi';
$desc='Call, WhatsApp or visit Kyra Pet Shop in Patel Nagar, New Delhi. Send an inquiry and our team will get back to you shortly.';
$page='contact';

// ==========================================
// 1. FETCH CONTACT INFO FROM DATABASE
// ==========================================
$contact_q = mysqli_query($conn, "SELECT * FROM contacts LIMIT 1");
$db_contact = ($contact_q && mysqli_num_rows($contact_q) > 0) ? mysqli_fetch_assoc($contact_q) : null;

// Dynamic variables override from DB with static config fallbacks
$shop_phone  = !empty($db_contact['phone']) ? $db_contact['phone'] : ($site['phone'] ?? '+91 9582799502');
$shop_wa     = !empty($db_contact['wp_number']) ? $db_contact['wp_number'] : ($site['wa'] ?? '919582799502');
$shop_email  = !empty($db_contact['email']) ? $db_contact['email'] : ($site['email'] ?? 'kyrapetshop12@gmail.com');
$shop_addr   = !empty($db_contact['address']) ? $db_contact['address'] : ($site['addr'] ?? 'WZ-23 Khampur Patel Nagar, New Delhi - 110008');
$shop_hours  = !empty($db_contact['working_hours']) ? $db_contact['working_hours'] : ($site['hours'] ?? 'Tuesday - Sunday, 11 AM to 8 PM');
$shop_owner  = $site['owner'] ?? 'Mr. Nitin';
$shop_tel    = str_replace(' ', '', $shop_phone);

// Map iframe src from DB
$shop_map    = !empty($db_contact['map']) ? $db_contact['map'] : 'https://www.google.com/maps?q=' . rawurlencode($shop_addr) . '&output=embed';

// ==========================================
// 2. HANDLE FORM SUBMISSION & SAVE TO INQUIRIES
// ==========================================
$msg=''; 
$old=['name'=>'','phone'=>'','email'=>'','topic'=>'','breed'=>'','date'=>'','message'=>''];

if($_SERVER['REQUEST_METHOD']==='POST' && empty($_POST['website'])){ // honeypot check
  foreach($old as $k=>$_){ 
      $old[$k] = trim(strip_tags($_POST[$k]??'')); 
  }
  $email = filter_var($old['email'], FILTER_VALIDATE_EMAIL) ?: '';
  
  if($old['name'] && preg_match('/^[0-9+\s-]{8,15}$/', $old['phone'])){
    // Save to database inquiries table
    $stmt = $conn->prepare("INSERT INTO inquiries (name, phone, email, subject, message) VALUES (?, ?, ?, ?, ?)");
    if ($stmt) {
        $subj = "Contact Inquiry: " . ($old['topic'] !== '' ? $old['topic'] : 'General') . (!empty($old['breed']) ? ' (Breed: '.$old['breed'].')' : '');
        $full_msg = "Topic: " . $old['topic'] . "\nBreed: " . $old['breed'] . "\nPreferred Date: " . $old['date'] . "\nMessage: " . $old['message'];
        $stmt->bind_param("sssss", $old['name'], $old['phone'], $email, $subj, $full_msg);
        $stmt->execute();
    }

    // Send notification email
    $body = "Name: {$old['name']}\nPhone: {$old['phone']}\nEmail: $email\nTopic: {$old['topic']}\nBreed: {$old['breed']}\nPreferred visit: {$old['date']}\nMessage: {$old['message']}";
    @mail($shop_email, 'New inquiry from website', $body, "From: no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
    
    $msg = 'ok'; 
    $old = array_map(fn($v) => '', $old);
  } else { 
    $msg = 'err'; 
  }
}

// ==========================================
// 3. PAGE SECTIONS DATA
// ==========================================
$cards = [
  ['📞', 'Call us', $shop_phone, 'tel:' . $shop_tel, 'Tap to call'],
  ['💬', 'WhatsApp', 'Chat with our team', 'https://wa.me/' . $shop_wa . '?text=' . rawurlencode('Hi Kyra Pet Shop, I would like to know more about your puppies.'), 'Reply within a few hours'],
  ['✉️', 'Email', $shop_email, 'mailto:' . $shop_email, 'Send us a message'],
  ['📍', 'Visit the shop', $shop_addr, 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($shop_addr), 'Get directions'],
];

$visit = [
  ['Call or message first', 'Tell us the breed you like so we can have the puppies ready for you.'],
  ['Come and meet them', 'Spend time with the puppies and ask our team anything you like.'],
  ['Decide in your own time', 'See the health records and papers. There is never any pressure.'],
];

$qa = [
  isset($faq[4]) ? $faq[4] : ['Can I visit before I decide?', 'Yes. Visit the shop, meet the puppies in person and ask anything you like.'],
  isset($faq[1]) ? $faq[1] : ['How much do your puppies cost?', 'Prices vary by breed, age and pedigree. Contact us for details.'],
  isset($faq[7]) ? $faq[7] : ['Can I book or reserve a puppy in advance?', 'Yes. Send us an inquiry or WhatsApp message and we will share photos and details.']
];

require __DIR__.'/includes/header.php';
if (function_exists('banner')) {
    banner('Contact Us', 'Contact', 'We would love to hear from you. Call, message or visit us in Patel Nagar.');
}
?>
<main>
<!-- CONTACT CARDS -->
<section style="padding:40px 0 20px"><div class="wrap">
  <div class="cgrid">
    <?php foreach($cards as $c): ?>
    <a class="fcard rv" href="<?=h($c[3])?>" <?=strpos($c[3],'http')===0?'target="_blank" rel="noopener"':''?>>
      <i><?=$c[0]?></i><h3><?=h($c[1])?></h3><p style="color:var(--ink);font-weight:600;overflow-wrap:anywhere"><?=h($c[2])?></p><span class="more" style="font-size:14px"><?=h($c[4])?> →</span>
    </a>
    <?php endforeach; ?>
  </div>
</div></section>

<!-- FORM + MAP -->
<section class="contact" id="contact" style="padding-top:60px"><div class="wrap">
  <div class="sec-head c"><span class="tag">Send an inquiry</span><h2>Tell us what you are looking for</h2><p><?=h($shop_owner)?> will call you back, usually within a few hours.</p></div>
  <div class="cbox rv">
    <form class="form" method="post" action="contact.php#contact">
      <h3>Looking for a specific breed?</h3><p class="sub">Share your details and we will send photos, videos and health records.</p>
      <?php if($msg==='ok'): ?><div class="alert ok" role="status">Thank you. We received your inquiry and will call you soon.</div>
      <?php elseif($msg==='err'): ?><div class="alert err" role="alert">Please enter your name and a valid phone number.</div><?php endif; ?>
      <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
      <div class="f2"><input name="name" placeholder="Your name" required value="<?=h($old['name'])?>"><input name="phone" type="tel" placeholder="Phone number" required value="<?=h($old['phone'])?>"></div>
      <input name="email" type="email" placeholder="Email (optional)" value="<?=h($old['email'])?>">
      <div class="f2">
        <select name="topic" aria-label="I want to">
          <?php foreach(['Buy a puppy','Visit the shop','Puppy care advice','Something else'] as $t): ?><option <?=$old['topic']===$t?'selected':''?>><?=h($t)?></option><?php endforeach; ?>
        </select>
        <input name="breed" placeholder="Preferred breed" value="<?=h($old['breed'])?>">
      </div>
      <input name="date" type="date" aria-label="Preferred visit date" value="<?=h($old['date'])?>" min="<?=date('Y-m-d')?>">
      <textarea name="message" rows="3" placeholder="Anything specific? (age, gender, budget, etc.)"><?=h($old['message'])?></textarea>
      <button class="btn btn-navy" type="submit">Send inquiry</button>
      <div class="info"><span>📞 <?=h($shop_phone)?></span><span>🕒 <?=h($shop_hours)?></span></div>
    </form>
    <div><iframe title="Kyra Pet Shop location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="<?=h($shop_map)?>"></iframe></div>
  </div>
</div></section>

<!-- PLAN YOUR VISIT -->
<section><div class="wrap">
  <div class="sec-head c"><span class="tag">Plan your visit</span><h2>What to expect when you come in</h2></div>
  <div class="fgrid"><?php foreach($visit as $i=>$v): ?>
    <div class="fcard rv"><i><?=$i+1?></i><h3><?=h($v[0])?></h3><p><?=h($v[1])?></p></div><?php endforeach; ?></div>
  <div class="hours rv">
    <div><b>Shop timings</b><span><?=h($shop_hours)?></span></div>
    <div><b>Address</b><span><?=h($shop_addr)?></span></div>
    <div><b>Phone</b><span><a href="tel:<?=h($shop_tel)?>"><?=h($shop_phone)?></a></span></div>
  </div>
</div></section>

<!-- FAQ -->
<section class="feat"><div class="wrap">
  <div class="sec-head c"><span class="tag">FAQ</span><h2>Quick answers</h2></div>
  <div class="faq-list"><?php foreach($qa as $f): ?><details><summary><?=h($f[0])?></summary><p><?=h($f[1])?></p></details><?php endforeach; ?></div>
</div></section>
<?php 
if (function_exists('cta_band')) {
    cta_band('Prefer to talk right now?', 'Call us or message us on WhatsApp and we will help you straight away.'); 
}
?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>