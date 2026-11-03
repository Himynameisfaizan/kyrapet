<?php
require __DIR__.'/config/connect.php'; 
require __DIR__.'/includes/config.php';

$page = 'about';

// SEO Variables
$title = 'About Kyra Pet Shop | Healthy Puppies in Delhi NCR';
$desc = 'Discover Kyra Pet Shop. We provide healthy, vet-checked, and vaccinated purebreed puppies with lifetime guidance in Patel Nagar, New Delhi.';

$values = [
    ['🩺', 'Health first', 'Every puppy is vet-checked and vaccinated before it goes home. A healthy puppy is the only kind we are happy to sell.'],
    ['🤝', 'Honesty', 'Clear breed information, clear records, and clear prices. You always know exactly what you are getting.'],
    ['💚', 'Genuine care', 'We love dogs. Our puppies live in a clean, caring space and receive attention every single day.'],
    ['💰', 'Fair pricing', 'Quality puppies at prices families can trust, with absolutely no hidden charges.'],
    ['🎓', 'Guidance', 'Feeding, grooming, training, and vaccination advice for both first-time and experienced owners.'],
    ['🏠', 'Long-term support', 'We do not disappear after the sale. Call or WhatsApp us whenever you need help.']
];

require __DIR__.'/includes/header.php';
if (function_exists('banner')) banner('About Kyra Pet Shop', 'About Us', 'A trusted home for healthy, happy puppies in Patel Nagar, New Delhi.');
?>
<main>
    <!-- STORY -->
    <section style="padding: 60px 0;">
        <div class="wrap twocol">
            <!-- Left Side Images -->
            <div class="collage"
                style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                <img class="c1" src="<?= ph($P['golden'] ?? '', 900) ?>" alt="Golden Retriever puppy" loading="lazy">
                <img class="c2" src="<?= ph($P['pug'] ?? '', 600) ?>" alt="Pug puppy" loading="lazy">
                <img class="c3" src="<?= ph($P['lab'] ?? '', 600) ?>" alt="Labrador" loading="lazy">
                <div class="exp"><b>25+</b><span>Years of experience</span></div>
            </div>
            <!-- Right Side Content -->
            <div style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                <span class="tag">Our story</span>
                <h2
                    style="font-size:clamp(28px,4vw,44px); margin-bottom:16px; color: var(--ink, #0f172a); font-weight:800; line-height: 1.2;">
                    Your trusted source for healthy and happy puppies in Delhi NCR.
                </h2>
                <p style="color:var(--mute, #475569); font-size:17px; margin-bottom:16px; line-height: 1.6;">
                    Welcome to Kyra Pet Shop, where every wagging tail and happy bark fills our hearts with joy. We are
                    a passionate, family-run pet shop based in Patel Nagar, dedicated to helping you find your perfect
                    four-legged companion.
                </p>
                <p style="color:var(--mute, #475569); font-size:17px; margin-bottom:26px; line-height: 1.6;">
                    With years of experience in the pet world, we have built a reputation for offering healthy,
                    ethically bred, and well-socialized puppies to loving homes across the region. We do not just sell
                    puppies. We help families find the right match and support them for life.
                </p>
                <div class="cta" style="margin:0;">
                    <a class="btn btn-teal" href="breeds.php">See our puppies</a>
                    <a class="btn btn-navy" href="contact.php">Visit the shop</a>
                </div>
            </div>
        </div>
    </section>

    <!-- NUMBERS -->
    <section style="padding: 0 0 90px;">
        <div class="wrap">
            <div class="numbers"
                style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                <?php if(!empty($stats)): foreach($stats as $s): ?>
                <div>
                    <b>
                        <span data-count="<?= h($s[0]) ?>"><?= h($s[0]) ?></span><small><?= h($s[1]) ?></small>
                    </b>
                    <span><?= h($s[2]) ?></span>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <!-- VALUES -->
    <section class="feat" style="background: #f8fafc; padding: 70px 0;">
        <div class="wrap">
            <div class="sec-head c">
                <span class="tag">What we stand for</span>
                <h2>The values behind every puppy we place</h2>
                <p>These are the promises our team keeps, every single day.</p>
            </div>
            <div class="fgrid">
                <?php foreach($values as $f): ?>
                <div class="fcard"
                    style="opacity: 1 !important; visibility: visible !important; transform: none !important; background: #fff;">
                    <i style="font-style: normal; font-size: 24px;"><?= $f[0] ?></i>
                    <h3><?= h($f[1]) ?></h3>
                    <p><?= h($f[2]) ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- COMMITMENTS -->
    <section style="padding: 70px 0;">
        <div class="wrap">
            <div class="sec-head c">
                <span class="tag">Our commitment</span>
                <h2>What you can count on</h2>
            </div>
            <div class="duo">
                <div class="panel hl"
                    style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                    <h3>We always</h3>
                    <p>Simple standards we follow with every family.</p>
                    <ul class="kit">
                        <li>Share vaccination and health records</li>
                        <li>Let you meet the puppy before you decide</li>
                        <li>Explain breed needs honestly</li>
                        <li>Answer your questions after the sale</li>
                        <li>Suggest the breed that fits your home</li>
                    </ul>
                </div>
                <div class="panel"
                    style="opacity: 1 !important; visibility: visible !important; transform: none !important; background:#f8fafc; border: 1px solid #e2e8f0;">
                    <h3>We never</h3>
                    <p>Things you will never experience at Kyra Pet Shop.</p>
                    <ul class="kit nope">
                        <li>Pressure you to buy quickly</li>
                        <li>Hide health or breed details</li>
                        <li>Sell a puppy that is not healthy</li>
                        <li>Add surprise charges</li>
                        <li>Ignore you after you take your puppy home</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- OWNER -->
    <section class="owner" style="padding: 20px 0 60px;">
        <div class="wrap">
            <div class="box" style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                <img src="<?= ph($P['sweater'] ?? '', 700) ?>" alt="Puppy cared for by the Kyra team" loading="lazy">
                <div>
                    <h2>"We want every puppy to go home healthy, and every family to go home happy."</h2>
                    <p>Hello, I am <b><?= h($site['owner'] ?? 'Mr. Nitin') ?></b>. Come visit us, meet the puppies, and
                        ask me anything. I will always tell you the truth about the breed, the health, and the care it
                        needs.</p>
                    <div class="cta" style="margin:0;">
                        <a class="btn btn-teal" href="tel:<?= h($tel ?? '') ?>">Call <?= h($site['phone'] ?? '') ?></a>
                        <a class="btn btn-line" href="how-it-works.php">How it works</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- VIDEOS -->
    <section class="vs2" style="background: #f8fafc; padding: 70px 0;">
        <div class="wrap">
            <div class="sec-head c">
                <span class="tag">Meet our families</span>
                <h2>See the smiles for yourself</h2>
                <p>A few moments from the families who trusted us with their new best friend.</p>
            </div>
            <div class="fan" style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                <?php if(!empty($v1)) { foreach(array_slice($v1, 0, 3) as $i=>$v) { if (function_exists('vcard')) echo vcard($v, $i); } } ?>
            </div>
            <div class="center" style="margin-top: 30px;">
                <a class="btn btn-teal" href="reviews.php">Read and watch more reviews</a>
            </div>
        </div>
    </section>

    <?php if (function_exists('cta_band')) cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>