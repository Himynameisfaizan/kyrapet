<?php include ('includes/config.php'); ?>
<footer><div class="wrap">
  <div class="ft">
    <div><img src="assets/img/logo.png" alt="Kyra Pet Shop" style="height:52px;margin-bottom:16px;background:#fff;border-radius:12px;padding:5px"><p>Healthy, premium dog breeds in New Delhi. Find your perfect companion with us today.</p>
      <div class="soc"><a href="<?=h($site['fb'])?>" aria-label="Facebook">f</a><a href="<?=h($site['insta'])?>" aria-label="Instagram">ig</a><a href="<?=h($site['yt'])?>" aria-label="YouTube">yt</a></div></div>
    <div><h4>Quick links</h4><ul><li><a href="about.php">About us</a></li><li><a href="how-it-works.php">How it works</a></li><li><a href="reviews.php">Reviews</a></li><li><a href="breeds.php">Our breeds</a></li><li><a href="gallery.php">Gallery</a></li><li><a href="blog.php">Blog</a></li><li><a href="contact.php">Contact</a></li></ul></div>
    <div><h4>Contact</h4><ul><li><?=h($site['addr'])?></li><li><a href="tel:<?=h($tel)?>"><?=h($site['phone'])?></a></li><li><a href="mailto:<?=h($site['email'])?>"><?=h($site['email'])?></a></li><li><?=h($site['hours'])?></li></ul></div>
  </div>
  <div class="copy">&copy; <?=date('Y')?> Kyra Pet Shop. All rights reserved. Design & Developed by <a href="digitalwebtrackers.com"></a>.</div>
</div></footer>

<a class="fab call" href="tel:<?=h($tel)?>" aria-label="Call Kyra Pet Shop"><svg viewBox="0 0 24 24"><path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z"/></svg></a>
<a class="fab" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" href="https://wa.me/<?=h($site['wa'])?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I would like to know more about your puppies.')?>">
<svg viewBox="0 0 32 32"><path d="M16 3C9 3 3.4 8.6 3.4 15.5c0 2.3.6 4.4 1.7 6.3L3 29l7.4-2c1.8 1 3.8 1.5 5.6 1.5 7 0 12.6-5.6 12.6-12.5S23 3 16 3zm6.5 17.7c-.3.8-1.7 1.5-2.3 1.6-.6.1-1.3.1-2.1-.1-.5-.2-1.1-.4-1.9-.7-3.3-1.4-5.4-4.7-5.6-4.9-.2-.2-1.3-1.7-1.3-3.2s.8-2.300 1.100-2.600c.3-.3.6-.4.8-.4h.6c.2 0 .5-.1.7.5l1 2.300c.1.2.1.4 0 .6l-.4.6-.5.6c-.2.2-.4.4-.2.8.2.4 1 1.600 2.100 2.600 1.400 1.200 2.600 1.600 3 1.800.4.2.6.1.8-.1.2-.3 1-1.100 1.200-1.500.3-.4.5-.3.8-.2l2.200 1c.4.2.6.3.7.4.1.2.1.8-.2 1.500z"/></svg></a>
<script src="assets/js/main.js" defer></script>
</body>
</html>