<?php
/* =====================================================
   KYRA PET SHOP - HOME PAGE  (edit only this top block)
   ===================================================== */
$site = [
    'name' => 'Kyra Pet Shop',
    'phone' => '+91 9582799502',
    'wa' => '919582799502',
    'email' => 'kyrapetshop12@gmail.com',
    'addr' => 'WZ-23 Khampur Patel Nagar, New Delhi - 110008',
    'hours' => 'Tuesday - Sunday, 11 AM to 8 PM',
    'owner' => 'Mr. Nitin',
    // Paste your real Google Business links here
    'g_profile' => 'https://www.google.com/maps/search/?api=1&query=Kyra+Pet+Shop+Patel+Nagar+New+Delhi',
    'g_write'  => 'https://search.google.com/local/writereview?placeid=YOUR_PLACE_ID',
    'insta' => '#',
    'fb' => '#',
    'yt' => '#',
];
// Unsplash helper (free to use under the Unsplash License)
function ph($id, $w = 900)
{
    return "https://images.unsplash.com/photo-$id?auto=format&fit=crop&q=75&w=$w";
}
$P = [
    'golden' => '1447029080250-270ded608d91',
    'lab' => '1422565096762-bdb997a56a84',
    'pug' => '1423958950820-4f2f1f44e075',
    'bulldog' => '1456534231849-7d5fcd82d77b',
    'chow' => '1446231855385-1d4b0f025248',
    'grass' => '1442605527737-ed62b867591f',
    'tan' => '1426287658398-5a912ce1ed0a',
    'tongue' => '1457144759132-40d119c2f120',
    'sweater' => '1470390356535-d19bbf47bacb',
    'pug2' => '1455380579765-810023662ea2',
    'black' => '1477936432016-8172ed08637e',
    'grey' => '1477973770766-6228305816df',
];
// Rating summary (use your real Google numbers)
$rating = ['score' => '4.9', 'count' => '16', 'bars' => [5 => 94, 4 => 6, 3 => 0, 2 => 0, 1 => 0]];
$stats = [['420', '+', 'Happy clients'], ['600', '+', 'Happy pets'], ['25', '+', 'Years of experience'], ['100', '%', 'Satisfaction']];
// YouTube: only the video ID (after v=)
$vids = [
    ['aKeiqtnY6Go', 'From our shop to your home'],
    ['TM08ew9Zcpw', 'Real clients, real smiles'],
    ['HJSLjs6Et6U', 'Happy pup in a new home'],
    ['X0LDgoDdKMo', 'Puppy goes home'],
    ['CYWG8jh85uU', 'A first puppy for the family'],
    ['yaW3h-0IceE', 'Forever home moment'],
    ['w1mMA05ud_I', 'Meet our puppy parents'],
    ['7NxpmVMsJAo', 'Customer feedback'],
    ['OJHSFNsC7qY', 'Happy puppy buying experience'],
];  // rename the titles to match each video
$v1 = array_slice($vids, 0, 3);
$v2 = array_slice($vids, 3, 3);
$v3 = array_slice($vids, 6, 3);
function vcard($v, $i = 0)
{
    return '<button class="vid sv" data-yt="' . htmlspecialchars($v[0]) . '" aria-label="Play video: ' . htmlspecialchars($v[1]) . '" style="--i:' . $i . '"><img src="https://i.ytimg.com/vi/' . htmlspecialchars($v[0]) . '/hqdefault.jpg" alt="" loading="lazy" onerror="this.style.opacity=.2"><span class="play"></span><span class="cap">' . htmlspecialchars($v[1]) . '</span></button>';
}
// Reviews: use ONLY real reviews copied from your Google profile. src: google | facebook | justdial
$reviews = [
    ['Amit Sharma', 'Local Guide · 14 reviews', '2 weeks ago', 5, 'google', 'Humne kaafi time se ek pure breed puppy dhoondh rahe the. Kyra Pet Shop ki team ne health records ekdum transparent rakhe. Genuine pet lovers!', '#1a73e8'],
    ['Sneha Kapoor', '8 reviews', '1 month ago', 5, 'google', 'Pehli baar dog adopt kar rahi thi aur nervous thi. Team ne har cheez samjhayi, feeding se vaccination tak. Best pet shop in Patel Nagar!', '#e8710a'],
    ['Rahul Verma', 'Local Guide · 31 reviews', '2 months ago', 5, 'google', 'Puppy lena mera best decision tha. Mr. Nitin ne poori process mein help ki. Puppy healthy aur well-vaccinated mila.', '#188038'],
    ['Pooja Mehta', '5 reviews', '3 months ago', 5, 'google', 'Clean setup, healthy puppies aur honest pricing. Purchase ke baad bhi guidance mil rahi hai. Highly recommended.', '#a142f4'],
    ['Karan Malhotra', 'Local Guide · 22 reviews', '4 months ago', 5, 'google', 'Humne Labrador liya, bahut active aur healthy hai. Vaccination card aur feeding chart diya gaya. Great service!', '#d93025'],
];
$breeds = [
    ['Healthy Labrador Retriever', $P['lab'], '8 Weeks', 'Male'],
    ['Premium Golden Retriever', $P['golden'], '8 Weeks', 'Male'],
    ['Chow Chow Puppy', $P['chow'], '8 Weeks', 'Female'],
    ['Adorable Pug Puppy', $P['pug'], '8 Weeks', 'Male'],
];
$more = ['Shih Tzu', 'Beagle', 'German Shepherd', 'Siberian Husky', 'Poodle', 'Pomeranian', 'Pug', 'Chow Chow'];
$gallery = [$P['sweater'], $P['grass'], $P['tongue'], $P['tan'], $P['chow'], $P['pug2'], $P['black'], $P['grey']];
$features = [
    ['🏅', 'KCI registered puppies', 'Purebred and certified, so you have peace of mind about what you are bringing home.'],
    ['🩺', 'Healthy and vaccinated', 'Vet checked and vaccinated for their age. Only healthy, happy puppies leave our shop.'],
    ['📜', 'Health certificate included', 'Certified health checks and records are handed over with every puppy.'],
    ['💰', 'Affordable, clear pricing', 'Top quality puppies at prices you can trust. Transparent from the first call.'],
    ['🏠', 'Visit before you buy', 'Meet the puppies at the shop and ask anything. No pressure, ever.'],
    ['📞', 'Support after you leave', 'Call or WhatsApp us whenever you have a question about your puppy.'],
];
$steps = [
    ['Choose your breed', 'Browse puppies here or visit the shop and meet them in person.'],
    ['Talk to our team', 'Tell us about your home and family. We suggest the breed that fits.'],
    ['Health check and papers', 'We share vaccination records and the vet health check before you pay.'],
    ['Take your puppy home', 'Get the feeding chart, starter guidance and our support number.'],
];
$kit = ['KCI registration details', 'Vaccination card with dates', 'Vet health certificate', 'Age-wise feeding chart', 'Training and care guide', 'Lifetime WhatsApp support'];
$compare = [['Vaccination records shared', '✓', 'Often missing'], ['Vet health check', '✓', 'Not always'], ['Visit puppies before buying', '✓', 'Rarely allowed'], ['Clear breed information', '✓', 'Unclear'], ['Support after purchase', '✓', 'No follow-up'], ['Real customer reviews and videos', '✓', 'Hard to find']];
$faq = [
    ['What breeds of puppies do you have for sale?', 'Popular breeds like Labrador, Golden Retriever, German Shepherd, Pomeranian, Beagle, Shih Tzu, Pug, Toy Poodle and more.'],
    ['How much do your puppies cost?', 'Prices vary by breed, age and pedigree. We keep pricing affordable and transparent. Contact us for details.'],
    ['Are your puppies vaccinated?', 'Yes. Every puppy is vet checked and vaccinated for its age. You receive the vaccination card at pickup.'],
    ['Do you provide a health certificate?', 'Yes. A health certificate and records are included with every puppy.'],
    ['Can I visit before I decide?', 'Yes. Visit the shop, meet the puppies in person and ask anything you like.'],
    ['I am a first-time owner. Will you guide me?', 'Yes. We explain feeding, grooming, training and the vaccination schedule so you feel confident from day one.'],
    ['How do I choose the right breed for my family?', 'Tell us about your home, family and daily routine. We will suggest breeds that fit.'],
    ['Can I book or reserve a puppy in advance?', 'Yes. Send us an inquiry or WhatsApp message and we will share photos, videos and health details first.'],
];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST['website'])) {
    $n = trim(strip_tags($_POST['name'] ?? ''));
    $p = trim(strip_tags($_POST['phone'] ?? ''));
    $b = trim(strip_tags($_POST['breed'] ?? ''));
    $m = trim(strip_tags($_POST['message'] ?? ''));
    $e = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL) ?: '';
    if ($n && preg_match('/^[0-9+\s-]{8,15}$/', $p)) {
        // TODO: save to your database here if needed
        @mail($site['email'], 'New inquiry from website', "Name: $n\nPhone: $p\nEmail: $e\nBreed: $b\nMessage: $m", "From: no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        $msg = 'ok';
    } else $msg = 'err';
}
function h($s)
{
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
function gicon($s = 20)
{
    return '<svg width="' . $s . '" height="' . $s . '" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>';
}
function stars($n)
{
    return '<span class="stars" aria-label="' . $n . ' out of 5 stars">' . str_repeat('★', $n) . '</span>';
}
$tel = str_replace(' ', '', $site['phone']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kyra Pet Shop | Healthy, Vaccinated Puppies in Patel Nagar, New Delhi</title>
    <meta name="description" content="Premium pure breed puppies in New Delhi. Vet checked, 100% vaccinated, rated 4.9 on Google. Visit Kyra Pet Shop in Patel Nagar.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://images.unsplash.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style/style.css">
</head>

<body>
    <div id="prog"></div>

    <div class="topbar">
        <div class="wrap"><span><?= h($site['addr']) ?></span>
            <span><a href="mailto:<?= h($site['email']) ?>"><?= h($site['email']) ?></a> &nbsp;|&nbsp; <a href="tel:<?= h($tel) ?>"><?= h($site['phone']) ?></a></span>
        </div>
    </div>

    <header class="nav" id="nav">
        <div class="wrap">
            <a class="logo" href="#home"><img src="assets/images/logo/logo.png" alt="Kyra Pet Shop"></a>
            <button class="burger" id="burger" aria-label="Open menu" aria-expanded="false"><span></span><span></span><span></span></button>
            <nav class="menu" id="menu">
                <a href="#home">Home</a><a href="#about">About</a><a href="#breeds">Breeds</a><a href="#process">How it works</a><a href="#reviews">Reviews</a><a href="#gallery">Gallery</a><a href="#contact">Contact</a>
                <a class="btn btn-teal" href="#contact">Get your pet</a>
            </nav>
        </div>
    </header>

    <main id="home">
        <!-- HERO -->
        <section class="hero" style="padding-bottom:0">
            <div class="blob b1"></div>
            <div class="blob b2"></div><?php for ($k = 0; $k < 9; $k++): ?><span class="paw" style="left:<?= ($k * 11 + 5) % 96 ?>%;animation-delay:<?= $k * 1.4 ?>s;font-size:<?= 16 + ($k % 3) * 8 ?>px">🐾</span><?php endfor; ?><div class="wrap">
                <div>
                    <div class="chip seq"><?= gicon(18) ?> <span><b><?= h($rating['score']) ?></b> rated on Google by <?= h($rating['count']) ?>+ pet parents</span></div>
                    <h1 class="split">Bring home a healthy puppy you can trust.</h1>
                    <p class="lead seq">Vet checked, fully vaccinated, pure breed puppies in Patel Nagar, New Delhi. We show you every record before you decide.</p>
                    <div class="cta seq"><a class="btn btn-teal" href="#breeds">See available puppies</a><a class="btn btn-line" href="#videos">Watch customer stories</a></div>
                    <div class="mini-proof seq">
                        <div class="faces"><?php foreach ([$P['grass'], $P['tan'], $P['tongue'], $P['sweater']] as $f): ?><img src="<?= ph($f, 100) ?>" alt=""><?php endforeach; ?></div>
                        <div><span class="stars">★★★★★</span><small>Trusted by 420+ happy clients</small></div>
                    </div>
                </div>
                <div class="hero-img">
                    <div class="arch"><img src="<?= ph($P['golden'], 1000) ?>" alt="Healthy Golden Retriever puppy at Kyra Pet Shop" fetchpriority="high"></div>
                    <div class="fb b"><i>🩺</i><span>Vet checked<small>Health records shared</small></span></div>
                    <div class="fb a"><i>💉</i><span>100% vaccinated<small>Card given at pickup</small></span></div>
                </div>
            </div>
        </section>

        <!-- MARQUEE -->
        <div class="marq">
            <div class="marq-track">
                <?php for ($k = 0; $k < 2; $k++): foreach (['Vet checked puppies', '100% vaccinated', 'Pure breed guarantee', 'Visit before you buy', 'Lifetime care support', 'KCI registered puppies', 'Rated ' . $rating['score'] . ' on Google', 'Visit us in Patel Nagar'] as $t): ?><span><b>✦</b><?= h($t) ?></span><?php endforeach;
                                                                                                                                                                                                                                                                                                endfor; ?>
            </div>
        </div>

        <!-- STATS -->
        <div class="stats">
            <div class="wrap"><?php foreach ($stats as $s): ?>
                    <div class="stat"><b><span data-count="<?= h($s[0]) ?>"><?= h($s[0]) ?></span><small><?= h($s[1]) ?></small></b><span><?= h($s[2]) ?></span></div><?php endforeach; ?>
            </div>
        </div>

        <!-- VISIT BAR -->
        <section class="visit">
            <div class="wrap">
                <form class="vbar rv" method="post" action="#contact">
                    <h3>Schedule your pet visit</h3>
                    <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <input type="hidden" name="breed" value="Shop visit booking">
                    <input name="name" placeholder="Your name" required aria-label="Your name">
                    <input name="phone" type="tel" placeholder="Phone number" required aria-label="Phone number">
                    <button class="btn btn-teal" type="submit">Book now</button>
                </form>
            </div>
        </section>

        <!-- ABOUT -->
        <section class="about" id="about">
            <div class="wrap">
                <div class="collage rv" data-anim="left">
                    <img class="c1" src="<?= ph($P['lab'], 800) ?>" alt="Labrador retriever" loading="lazy">
                    <img class="c2" src="<?= ph($P['pug'], 600) ?>" alt="Pug puppy" loading="lazy">
                    <img class="c3" src="<?= ph($P['tan'], 600) ?>" alt="Happy dog" loading="lazy">
                    <div class="exp"><b>25+</b><span>Years of experience</span></div>
                </div>
                <div class="rv" data-anim="right">
                    <span class="tag">About Kyra Pet Shop</span>
                    <h2 style="font-size:clamp(28px,4vw,44px);margin-bottom:16px">A pet shop that treats every puppy like family.</h2>
                    <p style="color:var(--mute);font-size:17px">Choosing a puppy is a big decision. At Kyra we keep it simple and honest: healthy puppies, clear records, and a team that stays with you long after you take your new friend home.</p>
                    <ul>
                        <li><i>✓</i>
                            <div><b>100% vaccinated and health checked</b><span>Records handed over with every puppy.</span></div>
                        </li>
                        <li><i>★</i>
                            <div><b>Premium pure breeds</b><span>Honest breed details so there are no surprises.</span></div>
                        </li>
                        <li><i>♥</i>
                            <div><b>Expert care guidance</b><span>Food, grooming and training advice from <?= h($site['owner']) ?> and team.</span></div>
                        </li>
                    </ul>
                    <a class="btn btn-teal" href="#contact">Talk to our team</a>
                </div>
            </div>
        </section>

        <!-- FEATURES -->
        <section class="feat">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Why families choose us</span>
                    <h2>Six promises we keep with every puppy</h2>
                    <p>Trust is earned with transparency. Here is what you can expect from us.</p>
                </div>
                <div class="fgrid"><?php foreach ($features as $f): ?>
                        <div class="fcard rv"><i><?= $f[0] ?></i>
                            <h3><?= h($f[1]) ?></h3>
                            <p><?= h($f[2]) ?></p>
                        </div><?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- BREEDS -->
        <section id="breeds">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Find your match</span>
                    <h2>Puppies waiting for a loving home</h2>
                    <p>Healthy, playful and raised with care. Tap Inquire to chat with us on WhatsApp.</p>
                </div>
                <div class="grid4"><?php foreach ($breeds as $b): $t = rawurlencode("Hi Kyra Pet Shop, I am interested in: " . $b[0]); ?>
                        <article class="card rv">
                            <div class="im"><img src="<?= ph($b[1], 700) ?>" alt="<?= h($b[0]) ?>" loading="lazy"><span class="ok">✓ Vaccinated</span></div>
                            <div class="bd">
                                <h3><?= h($b[0]) ?></h3>
                                <div class="meta"><?= h($b[2]) ?> &nbsp;|&nbsp; <?= h($b[3]) ?></div>
                                <div class="row"><a class="btn" href="#contact">Details</a><a class="btn btn-wa" target="_blank" rel="noopener" href="https://wa.me/<?= h($site['wa']) ?>?text=<?= $t ?>">Inquire</a></div>
                            </div>
                        </article><?php endforeach; ?>
                </div>
                <div class="chips rv"><span>Also available:</span><?php foreach ($more as $m): ?><a href="https://wa.me/<?= h($site['wa']) ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, do you have ' . $m . ' puppies?') ?>" target="_blank" rel="noopener"><?= h($m) ?></a><?php endforeach; ?></div>
                <div class="center"><a class="btn btn-navy" href="#contact">View all breeds</a></div>
            </div>
        </section>

        <!-- VIDEOS 2: FOREVER HOMES -->
        <section class="vs2">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Forever homes</span>
                    <h2>Where our puppies go next</h2>
                    <p>The best part of our work is the moment a puppy meets its new family. Here are a few of those moments.</p>
                </div>
                <div class="fan rv" data-anim="zoom"><?php foreach ($v2 as $i => $v) echo vcard($v, $i); ?></div>
                <div class="center"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?= h($site['wa']) ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I saw your videos and want a puppy.') ?>">Get a puppy like these</a></div>
            </div>
        </section>

        <!-- PROCESS -->
        <section class="proc" id="process">
            <div class="wrap">
                <div class="sec-head"><span class="tag">How it works</span>
                    <h2>From first hello to puppy at home in four easy steps</h2>
                    <p>No rush and no hidden steps. You stay informed at every stage.</p>
                </div>
                <div class="steps rv"><?php foreach ($steps as $s): ?><div class="step rv">
                            <h3><?= h($s[0]) ?></h3>
                            <p><?= h($s[1]) ?></p>
                        </div><?php endforeach; ?></div>
            </div>
        </section>

        <!-- KIT + COMPARE -->
        <section>
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Our guarantee</span>
                    <h2>What you get, and why it matters</h2>
                </div>
                <div class="duo">
                    <div class="panel hl rv">
                        <h3>Every puppy comes with</h3>
                        <p>Everything you need for a confident start.</p>
                        <ul class="kit"><?php foreach ($kit as $k): ?><li><?= h($k) ?></li><?php endforeach; ?></ul>
                    </div>
                    <div class="panel rv">
                        <h3>Kyra vs a typical seller</h3>
                        <p>Questions every buyer should ask.</p>
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Kyra</th>
                                    <th>Others</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($compare as $c): ?><tr>
                                        <td style="text-align:left;color:var(--ink);font-weight:600"><?= h($c[0]) ?></td>
                                        <td><?= h($c[1]) ?></td>
                                        <td><?= h($c[2]) ?></td>
                                    </tr><?php endforeach; ?></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <!-- VIDEOS 1: CUSTOMER STORIES -->
        <section class="videos vs1" id="videos">
            <div class="wrap">
                <div class="rv" data-anim="left">
                    <span class="tag">Customer stories</span>
                    <h2>Real families. Real puppies. Real smiles.</h2>
                    <p style="color:#B4C4DC;font-size:17px;margin:14px 0 24px">Do not just take our word for it. Watch customers talk about their visit, their puppy and their experience with Kyra Pet Shop.</p>
                    <ul class="kit">
                        <li>Honest feedback, recorded at our shop</li>
                        <li>Meet the people who took a puppy home</li>
                        <li>See how happy and healthy the puppies are</li>
                    </ul>
                    <div class="cta" style="margin:30px 0 0"><a class="btn btn-teal" href="#contact">Plan your visit</a><a class="btn btn-line" href="<?= h($site['yt']) ?>" target="_blank" rel="noopener">Our YouTube channel</a></div>
                </div>
                <div class="trio rv" data-anim="right"><?php foreach ($v1 as $i => $v) echo vcard($v, $i); ?></div>
            </div>
        </section>

        <!-- GOOGLE REVIEWS -->
        <section class="greview" id="reviews">
            <div class="wrap">
                <div class="rhead">
                    <div class="sum rv">
                        <div class="g"><?= gicon(26) ?> Google reviews</div>
                        <div class="big"><?= h($rating['score']) ?> <?= stars(5) ?></div>
                        <p>Based on <?= h($rating['count']) ?> reviews</p>
                        <?php foreach ($rating['bars'] as $n => $pc): ?><div class="bar"><?= $n ?>★<div><i data-w="<?= $pc ?>"></i></div><?= $pc ?>%</div><?php endforeach; ?>
                    </div>
                    <div class="badges rv">
                        <span class="tag" style="align-self:flex-start">Verified customer reviews</span>
                        <h3>Rated 4.9 by families who actually took a puppy home</h3>
                        <p>These reviews are posted by real customers on Google. Read them on our profile or leave your own after your visit.</p>
                        <div class="bd-row">
                            <a class="pill" href="<?= h($site['g_profile']) ?>" target="_blank" rel="noopener"><?= gicon(18) ?> See all on Google</a>
                            <a class="pill" href="<?= h($site['g_write']) ?>" target="_blank" rel="noopener">✎ Write a review</a>
                            <span class="pill"><span class="tick">✔</span> Verified purchases</span>
                        </div>
                    </div>
                </div>
                <div class="rwrap">
                    <div class="rtrack" id="rtrack">
                        <?php foreach ($reviews as $r): ?>
                            <article class="rc">
                                <div class="top"><span class="av" style="background:<?= h($r[6]) ?>"><?= h(mb_substr($r[0], 0, 1)) ?></span>
                                    <div>
                                        <div class="nm"><?= h($r[0]) ?>
                                            <svg width="16" height="16" viewBox="0 0 24 24" aria-label="Verified">
                                                <circle cx="12" cy="12" r="12" fill="#1a73e8" />
                                                <path d="M7 12.5l3.2 3.2L17 9" stroke="#fff" stroke-width="2.4" fill="none" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <small><?= h($r[1]) ?></small>
                                    </div>
                                    <span class="src"><?= gicon(24) ?></span>
                                </div>
                                <div class="st"><?= stars($r[3]) ?> <span><?= h($r[2]) ?></span></div>
                                <p>"<?= h($r[5]) ?>"</p>
                                <div class="ver">✔ Posted on Google</div>
                            </article><?php endforeach; ?>
                    </div>
                </div>
                <div class="arrows"><button class="arr" id="rprev" aria-label="Previous reviews">←</button><button class="arr" id="rnext" aria-label="Next reviews">→</button></div>
            </div>
        </section>

        <!-- GALLERY -->
        <section id="gallery">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Gallery</span>
                    <h2>Adorable moments at Kyra</h2>
                </div>
                <div class="gal-grid"><?php foreach ($gallery as $g): ?><a class="rv" href="<?= ph($g, 1600) ?>" target="_blank" rel="noopener"><img src="<?= ph($g, 800) ?>" alt="Puppy at Kyra Pet Shop" loading="lazy"></a><?php endforeach; ?></div>
                <div class="center"><a class="btn btn-navy" href="<?= h($site['insta']) ?>" target="_blank" rel="noopener">Follow us on Instagram</a></div>
            </div>
        </section>

        <!-- VIDEOS 3: PUPPY PARENTS -->
        <section class="vs3">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Puppy parents speak</span>
                    <h2>Ask them, not us</h2>
                    <p>Honest words from people who visited our shop and took a puppy home. Tap a video to hear them.</p>
                </div>
                <div class="pp"><?php foreach ($v3 as $i => $v): ?><div class="ppc rv"><?= vcard($v, $i) ?><div class="q"><?= stars(5) ?><b><?= h($v[1]) ?></b><span>Customer feedback at Kyra Pet Shop</span></div>
                        </div><?php endforeach; ?></div>
            </div>
        </section>

        <!-- OWNER NOTE -->
        <section class="owner">
            <div class="wrap">
                <div class="box rv">
                    <img src="<?= ph($P['sweater'], 700) ?>" alt="Puppy cared for by the Kyra team" loading="lazy">
                    <div>
                        <h2>"We want every puppy to go home healthy, and every family to go home happy."</h2>
                        <p>Hello, I am <b><?= h($site['owner']) ?></b>. Come visit us, meet the puppies, and ask me anything. I will always tell you the truth about the breed, the health and the care it needs.</p>
                        <div class="cta" style="margin:0"><a class="btn btn-teal" href="tel:<?= h($tel) ?>">Call <?= h($site['phone']) ?></a><a class="btn btn-line" href="#contact">Plan a visit</a></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section style="padding-top:20px">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">FAQ</span>
                    <h2>Questions before you visit</h2>
                </div>
                <div class="faq-list"><?php foreach ($faq as $f): ?><details>
                            <summary><?= h($f[0]) ?></summary>
                            <p><?= h($f[1]) ?></p>
                        </details><?php endforeach; ?></div>
            </div>
        </section>

        <!-- CTA BAND -->
        <div class="band">
            <div class="wrap">
                <div class="in rv">
                    <div>
                        <h2>Ready to meet your new best friend?</h2>
                        <p>Visit us <?= h($site['hours']) ?> or message us on WhatsApp.</p>
                    </div>
                    <a class="btn" target="_blank" rel="noopener" href="https://wa.me/<?= h($site['wa']) ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I would like to visit and see the puppies.') ?>">Chat on WhatsApp</a>
                </div>
            </div>
        </div>

        <!-- CONTACT -->
        <section class="contact" id="contact">
            <div class="wrap">
                <div class="sec-head c"><span class="tag">Get in touch</span>
                    <h2>Send us an inquiry</h2>
                    <p><?= h($site['owner']) ?> will call you back, usually within a few hours.</p>
                </div>
                <div class="cbox">
                    <div><iframe title="Kyra Pet Shop location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.google.com/maps?q=WZ-23+Khampur+Patel+Nagar+New+Delhi+110008&output=embed"></iframe></div>
                    <form class="form" method="post" action="#contact">
                        <h3>Looking for a specific breed?</h3>
                        <p class="sub">Share your details and we will send photos, videos and health records.</p>
                        <?php if ($msg === 'ok'): ?><div class="alert ok" role="status">Thank you. We received your inquiry and will call you soon.</div>
                        <?php elseif ($msg === 'err'): ?><div class="alert err" role="alert">Please enter your name and a valid phone number.</div><?php endif; ?>
                        <input class="hp" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                        <div class="f2"><input name="name" placeholder="Your name" required><input name="phone" type="tel" placeholder="Phone number" required></div>
                        <input name="email" type="email" placeholder="Email (optional)"><input name="breed" placeholder="Which breed are you looking for?">
                        <textarea name="message" rows="3" placeholder="Anything specific? (age, gender, etc.)"></textarea>
                        <button class="btn btn-navy" type="submit">Send inquiry</button>
                        <div class="info"><span>📞 <?= h($site['phone']) ?></span><span>🕒 <?= h($site['hours']) ?></span></div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="wrap">
            <div class="ft">
                <div><img src="assets/images/logo/logo.png" alt="Kyra Pet Shop" style="height:52px;margin-bottom:16px;background:#fff;border-radius:12px;padding:5px">
                    <p>Healthy, premium dog breeds in New Delhi. Find your perfect companion with us today.</p>
                    <div class="soc"><a href="<?= h($site['fb']) ?>" aria-label="Facebook">f</a><a href="<?= h($site['insta']) ?>" aria-label="Instagram">ig</a><a href="<?= h($site['yt']) ?>" aria-label="YouTube">yt</a></div>
                </div>
                <div>
                    <h4>Quick links</h4>
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="#breeds">Our breeds</a></li>
                        <li><a href="#reviews">Reviews</a></li>
                        <li><a href="#gallery">Gallery</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Contact</h4>
                    <ul>
                        <li><?= h($site['addr']) ?></li>
                        <li><a href="tel:<?= h($tel) ?>"><?= h($site['phone']) ?></a></li>
                        <li><a href="mailto:<?= h($site['email']) ?>"><?= h($site['email']) ?></a></li>
                        <li><?= h($site['hours']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="copy">&copy; <?= date('Y') ?> Kyra Pet Shop. All rights reserved. Photos by Unsplash contributors.</div>
        </div>
    </footer>

    <a class="fab call" href="tel:<?= h($tel) ?>" aria-label="Call Kyra Pet Shop"><svg viewBox="0 0 24 24">
            <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1l-2.3 2.2z" />
        </svg></a>
    <a class="fab" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" href="https://wa.me/<?= h($site['wa']) ?>?text=<?= rawurlencode('Hi Kyra Pet Shop, I would like to know more about your puppies.') ?>">
        <svg viewBox="0 0 32 32">
            <path d="M16 3C9 3 3.4 8.6 3.4 15.5c0 2.300.6 4.400 1.700 6.300L3 29l7.400-2c1.800 1 3.800 1.500 5.600 1.500 7 0 12.600-5.600 12.600-12.500S23 3 16 3zm6.500 17.700c-.3.800-1.700 1.500-2.300 1.600-.6.100-1.300.1-2.100-.1-.5-.2-1.100-.4-1.900-.7-3.300-1.400-5.400-4.700-5.600-4.900-.2-.2-1.300-1.700-1.300-3.200s.8-2.300 1.100-2.600c.3-.3.600-.4.800-.4h.6c.2 0 .5-.1.700.5l1 2.300c.1.200.1.400 0 .6l-.4.600-.5.600c-.2.200-.4.400-.2.800.2.400 1 1.600 2.100 2.600 1.400 1.200 2.600 1.600 3 1.800.4.200.6.100.8-.1.200-.3 1-1.100 1.200-1.500.3-.4.500-.3.800-.2l2.200 1c.4.200.6.300.7.400.1.200.1.800-.2 1.500z" />
        </svg></a>

    <script>
        const nav = document.getElementById('nav'),
            menu = document.getElementById('menu'),
            burger = document.getElementById('burger');
        addEventListener('scroll', () => nav.classList.toggle('stuck', scrollY > 10), {
            passive: true
        });
        burger.addEventListener('click', () => {
            const o = menu.classList.toggle('open');
            burger.setAttribute('aria-expanded', o)
        });
        menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
            menu.classList.remove('open');
            burger.setAttribute('aria-expanded', false)
        }));

        // lazy YouTube player
        document.querySelectorAll('.vid').forEach(b => b.addEventListener('click', () => {
            const f = document.createElement('iframe');
            f.src = 'https://www.youtube-nocookie.com/embed/' + b.dataset.yt + '?autoplay=1&rel=0';
            f.allow = 'autoplay; encrypted-media; picture-in-picture';
            f.allowFullscreen = true;
            f.title = 'Customer video';
            b.innerHTML = '';
            b.appendChild(f);
            b.style.cursor = 'default';
        }, {
            once: true
        }));

        // review slider
        const rt = document.getElementById('rtrack'),
            step = () => rt.firstElementChild.getBoundingClientRect().width + 20;
        document.getElementById('rnext').onclick = () => {
            rt.scrollLeft + step() >= rt.scrollWidth - rt.clientWidth - 5 ? rt.scrollTo({
                left: 0,
                behavior: 'smooth'
            }) : rt.scrollBy({
                left: step(),
                behavior: 'smooth'
            })
        };
        document.getElementById('rprev').onclick = () => rt.scrollBy({
            left: -step(),
            behavior: 'smooth'
        });
        let auto = setInterval(() => document.getElementById('rnext').click(), 5000);
        rt.addEventListener('pointerenter', () => clearInterval(auto));

        // reveal, bars, counters
        const io = new IntersectionObserver(es => es.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('in');
                e.target.querySelectorAll('.bar i').forEach(b => b.style.width = b.dataset.w + '%');
                io.unobserve(e.target)
            }
        }), {
            threshold: .12
        });
        document.querySelectorAll('.rv').forEach((el, i) => {
            el.style.transitionDelay = (i % 4) * 80 + 'ms';
            io.observe(el)
        });
        const co = new IntersectionObserver(es => es.forEach(e => {
            if (!e.isIntersecting) return;
            co.unobserve(e.target);
            const t = parseFloat(e.target.dataset.count),
                d = e.target.dataset.count.includes('.') ? 1 : 0,
                s = performance.now();
            (function tick(n) {
                const p = Math.min((n - s) / 1500, 1);
                e.target.textContent = (t * (1 - Math.pow(1 - p, 3))).toFixed(d);
                if (p < 1) requestAnimationFrame(tick)
            })(s)
        }), {
            threshold: .6
        });
        document.querySelectorAll('[data-count]').forEach(el => co.observe(el));

        // scroll progress bar
        const prog = document.getElementById('prog');
        addEventListener('scroll', () => {
            const m = document.documentElement.scrollHeight - innerHeight;
            prog.style.transform = 'scaleX(' + (m > 0 ? scrollY / m : 0) + ')'
        }, {
            passive: true
        });
        // headline word-by-word entrance
        document.querySelectorAll('.split').forEach(h => {
            h.innerHTML = h.textContent.trim().split(/\s+/).map((w, i) => '<span class="w" style="--i:' + i + '">' + w + '</span>').join(' ')
        });
        // mouse effects only on devices with a real pointer
        if (matchMedia('(pointer:fine)').matches && !matchMedia('(prefers-reduced-motion:reduce)').matches) {
            const hero = document.querySelector('.hero'),
                himg = document.querySelector('.hero-img');
            hero.addEventListener('pointermove', e => {
                const x = e.clientX / innerWidth - .5,
                    y = e.clientY / innerHeight - .5;
                himg.style.translate = (x * -20) + 'px ' + (y * -14) + 'px';
                hero.querySelectorAll('.fb').forEach((f, i) => f.style.translate = (x * (i ? 34 : -34)) + 'px ' + (y * 22) + 'px')
            });
            document.querySelectorAll('.card,.fcard,.rc,.panel').forEach(c => {
                c.addEventListener('pointermove', e => {
                    const r = c.getBoundingClientRect(),
                        x = (e.clientX - r.left) / r.width - .5,
                        y = (e.clientY - r.top) / r.height - .5;
                    c.style.transition = 'transform .12s';
                    c.style.transform = 'perspective(800px) rotateX(' + (-y * 7) + 'deg) rotateY(' + (x * 7) + 'deg) translateY(-6px)'
                });
                c.addEventListener('pointerleave', () => {
                    c.style.transition = '';
                    c.style.transform = ''
                })
            });
        }
    </script>
</body>

</html>