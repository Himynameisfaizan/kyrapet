<?php
require __DIR__.'/config/connect.php'; 
require __DIR__.'/includes/config.php';

$page = 'how';

// SEO Variables
$title = 'How It Works | Buying a Puppy from Kyra Pet Shop';
$desc  = 'Four simple steps from choosing your breed to taking your healthy, vaccinated puppy home, with support after.';

$detail = [
    ['Choose your breed', $P['lab'] ?? '', 'Browse our puppies online or visit the shop and meet them in person.', ['Look at breeds, photos and videos', 'Ask about size, grooming and energy', 'Shortlist the puppies you like']],
    ['Talk to our team', $P['golden'] ?? '', 'Tell us about your home, family and daily routine. We suggest the breed that truly fits.', ['Share space, time and budget', 'Get honest breed advice', 'Ask us anything, no pressure']],
    ['Health check and papers', $P['pug'] ?? '', 'Before you decide, we show you the puppy\'s health details and paperwork.', ['Vet health check and vaccination records', 'KCI registration details where applicable', 'Health certificate included']],
    ['Take your puppy home', $P['sweater'] ?? '', 'Pick up your new family member with everything you need for a calm start.', ['Feeding chart and care guide', 'Starter guidance on training', 'Our support number for any question']],
];

$ask = ['Is the puppy vaccinated, and which vaccines were given?', 'Can I see the health records and papers?', 'What food has the puppy been eating?', 'How big will this breed grow, and how much exercise does it need?', 'What grooming does this breed need?', 'Who do I call if I have a question after pickup?'];
$qa  = !empty($faq) ? array_slice($faq, 0, 5) : [];

require __DIR__.'/includes/header.php';
if (function_exists('banner')) banner('How It Works', 'How It Works', 'Four simple steps, no pressure, and support long after you take your puppy home.');
?>
<main>
    <section style="padding: 70px 0;">
        <div class="wrap">
            <div class="sec-head c">
                <span class="tag">Step by step</span>
                <h2>From first hello to puppy at home</h2>
                <p>We keep the process clear, so you always know what happens next.</p>
            </div>

            <?php foreach($detail as $i => $s): ?>
            <div class="trow"
                style="margin-bottom: 60px; display: flex; align-items: center; gap: 40px; flex-direction: <?= $i % 2 !== 0 ? 'row-reverse' : 'row' ?>;">

                <div class="tpic"
                    style="flex: 1; opacity: 1 !important; visibility: visible !important; transform: none !important;">
                    <img src="<?= ph($s[1], 900) ?>" alt="<?= h($s[0]) ?>" loading="lazy"
                        style="width: 100%; border-radius: 20px; box-shadow: 0 10px 30px rgba(15,23,42,0.06);">
                </div>

                <div
                    style="flex: 1; opacity: 1 !important; visibility: visible !important; transform: none !important;">
                    <span class="tnum"
                        style="display:inline-block; background:var(--teal); color:#fff; width:36px; height:36px; line-height:36px; text-align:center; border-radius:50%; font-weight:700; margin-bottom:12px;"><?= $i + 1 ?></span>
                    <h3 style="font-size: 26px; color: var(--ink); margin: 0 0 10px; font-weight: 800;"><?= h($s[0]) ?>
                    </h3>
                    <p style="color: #475569; font-size: 16px; margin-bottom: 20px; line-height: 1.6;"><?= h($s[2]) ?>
                    </p>

                    <ul class="kit" style="list-style: none; padding: 0;">
                        <?php foreach($s[3] as $li): ?>
                        <li
                            style="position:relative; padding-left: 28px; margin-bottom: 12px; color: #1e293b; font-size: 15px; font-weight: 500;">
                            <span style="position:absolute; left:0; top:2px; color:var(--teal);">✓</span>
                            <?= h($li) ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="feat" style="background: #f8fafc; padding: 70px 0;">
        <div class="wrap">
            <div class="sec-head c">
                <span class="tag">Before you buy</span>
                <h2>Smart questions to ask on your visit</h2>
                <p>A good seller is happy to answer all of these.</p>
            </div>

            <div class="duo">
                <div class="panel"
                    style="opacity: 1 !important; visibility: visible !important; transform: none !important; background: #fff; border: 1px solid #e2e8f0;">
                    <h3>Questions to ask</h3>
                    <p>Print or save this list before you visit.</p>
                    <ul class="kit" style="margin-top: 20px;">
                        <?php foreach($ask as $q): ?>
                        <li><?= h($q) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="panel hl"
                    style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                    <h3>Every puppy comes with</h3>
                    <p>What you receive at pickup.</p>
                    <ul class="kit" style="margin-top: 20px;">
                        <?php if(!empty($kit)) { foreach($kit as $k): ?>
                        <li><?= h($k) ?></li>
                        <?php endforeach; } ?>
                    </ul>
                    <div class="cta" style="margin: 26px 0 0;">
                        <a class="btn btn-teal" href="breeds.php">Browse puppies</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section style="padding: 70px 0;">
        <div class="wrap">
            <div class="sec-head c">
                <span class="tag">FAQ</span>
                <h2>Common questions</h2>
            </div>
            <div class="faq-list"
                style="opacity: 1 !important; visibility: visible !important; transform: none !important;">
                <?php foreach($qa as $f): ?>
                <details>
                    <summary><?= h($f[0]) ?></summary>
                    <p><?= h($f[1]) ?></p>
                </details>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if (function_exists('cta_band')) cta_band('Ready to start?', 'Visit us or message us on WhatsApp and we will guide you through every step.'); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>