<?php
/* =====================================================
   KYRA PET SHOP - HOME PAGE  (edit only this top block)
   ===================================================== */
$site = [
  'name'=>'Kyra Pet Shop','phone'=>'+91 9582799502','wa'=>'919582799502',
  'email'=>'kyrapetshop12@gmail.com','addr'=>'WZ-23 Khampur Patel Nagar, New Delhi - 110008',
  'hours'=>'Tuesday - Sunday, 11 AM to 8 PM','owner'=>'Mr. Nitin',
  // Paste your real Google Business links here
  'g_profile'=>'https://www.google.com/maps/search/?api=1&query=Kyra+Pet+Shop+Patel+Nagar+New+Delhi',
  'g_write'  =>'https://search.google.com/local/writereview?placeid=YOUR_PLACE_ID',
  'insta'=>'#','fb'=>'#','yt'=>'#',
];

// --- YAHA PE CHANGE KARNA HAI --- 
if (!function_exists('ph')) {
    function ph($id,$w=900){ return "https://images.unsplash.com/photo-$id?auto=format&fit=crop&q=75&w=$w"; }
}
// ---------------------------------

$P = [
  'golden'=>'1447029080250-270ded608d91','lab'=>'1422565096762-bdb997a56a84','pug'=>'1423958950820-4f2f1f44e075',
  'bulldog'=>'1456534231849-7d5fcd82d77b','chow'=>'1446231855385-1d4b0f025248','grass'=>'1442605527737-ed62b867591f',
  'tan'=>'1426287658398-5a912ce1ed0a','tongue'=>'1457144759132-40d119c2f120','sweater'=>'1470390356535-d19bbf47bacb',
  'pug2'=>'1455380579765-810023662ea2','black'=>'1477936432016-8172ed08637e','grey'=>'1477973770766-6228305816df',
];

$rating = ['score'=>'4.9','count'=>'16','bars'=>[5=>94,4=>6,3=>0,2=>0,1=>0]];
$stats = [['420','+','Happy clients'],['600','+','Happy pets'],['25','+','Years of experience'],['100','%','Satisfaction']];
$vids = [
  ['aKeiqtnY6Go','From our shop to your home'],
  ['TM08ew9Zcpw','Real clients, real smiles'],
  ['HJSLjs6Et6U','Happy pup in a new home'],
  ['X0LDgoDdKMo','Puppy goes home'],
  ['CYWG8jh85uU','A first puppy for the family'],
  ['yaW3h-0IceE','Forever home moment'],
  ['w1mMA05ud_I','Meet our puppy parents'],
  ['7NxpmVMsJAo','Customer feedback'],
  ['OJHSFNsC7qY','Happy puppy buying experience'],
];  // rename the titles to match each video
$v1=array_slice($vids,0,3);$v2=array_slice($vids,3,3);$v3=array_slice($vids,6,3);

// --- BAKI FUNCTIONS MEIN BHI ADD KAR DENA SAFE RAHEGA ---
if (!function_exists('vcard')) {
    function vcard($v,$i=0){return '<button class="vid sv" data-yt="'.htmlspecialchars($v[0]).'" aria-label="Play video: '.htmlspecialchars($v[1]).'" style="--i:'.$i.'"><img src="https://i.ytimg.com/vi/'.htmlspecialchars($v[0]).'/hqdefault.jpg" alt="" loading="lazy" onerror="this.style.opacity=.2"><span class="play"></span><span class="cap">'.htmlspecialchars($v[1]).'</span></button>';}
}
// --------------------------------------------------------

$reviews = [
  ['Amit Sharma','Local Guide · 14 reviews','2 weeks ago',5,'google','Humne kaafi time se ek pure breed puppy dhoondh rahe the. Kyra Pet Shop ki team ne health records ekdum transparent rakhe. Genuine pet lovers!','#1a73e8'],
  ['Sneha Kapoor','8 reviews','1 month ago',5,'google','Pehli baar dog adopt kar rahi thi aur nervous thi. Team ne har cheez samjhayi, feeding se vaccination tak. Best pet shop in Patel Nagar!','#e8710a'],
  ['Rahul Verma','Local Guide · 31 reviews','2 months ago',5,'google','Puppy lena mera best decision tha. Mr. Nitin ne poori process mein help ki. Puppy healthy aur well-vaccinated mila.','#188038'],
  ['Pooja Mehta','5 reviews','3 months ago',5,'google','Clean setup, healthy puppies aur honest pricing. Purchase ke baad bhi guidance mil rahi hai. Highly recommended.','#a142f4'],
  ['Karan Malhotra','Local Guide · 22 reviews','4 months ago',5,'google','Humne Labrador liya, bahut active aur healthy hai. Vaccination card aur feeding chart diya gaya. Great service!','#d93025'],
];
$breeds = [
  ['Healthy Labrador Retriever',$P['lab'],'8 Weeks','Male'],
  ['Premium Golden Retriever',$P['golden'],'8 Weeks','Male'],
  ['Chow Chow Puppy',$P['chow'],'8 Weeks','Female'],
  ['Adorable Pug Puppy',$P['pug'],'8 Weeks','Male'],
];
$more=['Shih Tzu','Beagle','German Shepherd','Siberian Husky','Poodle','Pomeranian','Pug','Chow Chow'];
$gallery = [$P['sweater'],$P['grass'],$P['tongue'],$P['tan'],$P['chow'],$P['pug2'],$P['black'],$P['grey']];
$features = [
  ['🏅','KCI registered puppies','Purebred and certified, so you have peace of mind about what you are bringing home.'],
  ['🩺','Healthy and vaccinated','Vet checked and vaccinated for their age. Only healthy, happy puppies leave our shop.'],
  ['📜','Health certificate included','Certified health checks and records are handed over with every puppy.'],
  ['💰','Affordable, clear pricing','Top quality puppies at prices you can trust. Transparent from the first call.'],
  ['🏠','Visit before you buy','Meet the puppies at the shop and ask anything. No pressure, ever.'],
  ['📞','Support after you leave','Call or WhatsApp us whenever you have a question about your puppy.'],
];
$steps = [
  ['Choose your breed','Browse puppies here or visit the shop and meet them in person.'],
  ['Talk to our team','Tell us about your home and family. We suggest the breed that fits.'],
  ['Health check and papers','We share vaccination records and the vet health check before you pay.'],
  ['Take your puppy home','Get the feeding chart, starter guidance and our support number.'],
];
$kit = ['KCI registration details','Vaccination card with dates','Vet health certificate','Age-wise feeding chart','Training and care guide','Lifetime WhatsApp support'];
$compare = [['Vaccination records shared','✓','Often missing'],['Vet health check','✓','Not always'],['Visit puppies before buying','✓','Rarely allowed'],['Clear breed information','✓','Unclear'],['Support after purchase','✓','No follow-up'],['Real customer reviews and videos','✓','Hard to find']];
$faq = [
  ['What breeds of puppies do you have for sale?','Popular breeds like Labrador, Golden Retriever, German Shepherd, Pomeranian, Beagle, Shih Tzu, Pug, Toy Poodle and more.'],
  ['How much do your puppies cost?','Prices vary by breed, age and pedigree. We keep pricing affordable and transparent. Contact us for details.'],
  ['Are your puppies vaccinated?','Yes. Every puppy is vet checked and vaccinated for its age. You receive the vaccination card at pickup.'],
  ['Do you provide a health certificate?','Yes. A health certificate and records are included with every puppy.'],
  ['Can I visit before I decide?','Yes. Visit the shop, meet the puppies in person and ask anything you like.'],
  ['I am a first-time owner. Will you guide me?','Yes. We explain feeding, grooming, training and the vaccination schedule so you feel confident from day one.'],
  ['How do I choose the right breed for my family?','Tell us about your home, family and daily routine. We will suggest breeds that fit.'],
  ['Can I book or reserve a puppy in advance?','Yes. Send us an inquiry or WhatsApp message and we will share photos, videos and health details first.'],
];

// --- YAHAN BHI CHECK LAGA DO ---
if (!function_exists('h')) {
    function h($s){return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');}
}
if (!function_exists('gicon')) {
    function gicon($s=20){return '<svg width="'.$s.'" height="'.$s.'" viewBox="0 0 48 48" aria-hidden="true"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>';}
}
if (!function_exists('stars')) {
    function stars($n){return '<span class="stars" aria-label="'.$n.' out of 5 stars">'.str_repeat('★',$n).'</span>';}
}
// ---------------------------------

$tel=str_replace(' ','',$site['phone']);

/* ================= MULTI-PAGE DATA ================= */
// Puppies / breeds. 'img' => Unsplash photo id from $P, or a local path like 'assets/img/breeds/husky.jpg', or null (shows a placeholder)
$dogs = [
  ['slug'=>'labrador-retriever-male','name'=>'Healthy Labrador Retriever','breed'=>'Labrador','gender'=>'Male','age'=>'8 Weeks','size'=>'Large','temper'=>'Friendly, active, easy to train','img'=>$P['lab'],'desc'=>'Labradors are friendly, energetic and eager to please, which makes them one of the best first dogs for families with children.'],
  ['slug'=>'golden-retriever-male','name'=>'Premium Golden Retriever','breed'=>'Golden Retriever','gender'=>'Male','age'=>'8 Weeks','size'=>'Large','temper'=>'Gentle, loyal, loves people','img'=>$P['golden'],'desc'=>'Golden Retrievers are gentle, intelligent and devoted. They love company and need regular play, walks and brushing.'],
  ['slug'=>'chow-chow-female','name'=>'Chow Chow Puppy','breed'=>'Chow Chow','gender'=>'Female','age'=>'8 Weeks','size'=>'Medium to large','temper'=>'Calm, dignified, loyal','img'=>$P['chow'],'desc'=>'A fluffy, lion-like breed that is calm and loyal. Its thick coat needs regular grooming and a cool space in summer.'],
  ['slug'=>'pug-male','name'=>'Adorable Pug Puppy','breed'=>'Pug','gender'=>'Male','age'=>'8 Weeks','size'=>'Small','temper'=>'Playful, affectionate, funny','img'=>$P['pug'],'desc'=>'Pugs are small, playful companions that suit apartment life. They enjoy being close to their family.'],
  ['slug'=>'pug-female','name'=>'Sweet Pug Girl','breed'=>'Pug','gender'=>'Female','age'=>'8 Weeks','size'=>'Small','temper'=>'Playful, affectionate, funny','img'=>$P['pug2'],'desc'=>'A sweet, cuddly pug girl who loves attention and short play sessions.'],
  ['slug'=>'english-bulldog-male','name'=>'Purebred English Bulldog','breed'=>'Bulldog','gender'=>'Male','age'=>'8 Weeks','size'=>'Medium','temper'=>'Calm, gentle, relaxed','img'=>$P['bulldog'],'desc'=>'Bulldogs are calm, gentle and relaxed. They prefer a cool home and light exercise.'],
  ['slug'=>'shih-tzu-female','name'=>'Adorable Shih Tzu Toy Breed','breed'=>'Shih Tzu','gender'=>'Female','age'=>'8 Weeks','size'=>'Small','temper'=>'Sweet, playful, loves lap time','img'=>null,'desc'=>'Shih Tzus are small, sweet companions with a long, silky coat that needs regular grooming.'],
  ['slug'=>'beagle-male','name'=>'Playful Beagle Puppy','breed'=>'Beagle','gender'=>'Male','age'=>'8 Weeks','size'=>'Small to medium','temper'=>'Curious, merry, energetic','img'=>null,'desc'=>'Beagles are curious, merry and full of energy. They love walks, sniffing and family play.'],
  ['slug'=>'siberian-husky-male','name'=>'Purebred Siberian Husky','breed'=>'Siberian Husky','gender'=>'Male','age'=>'8 Weeks','size'=>'Medium to large','temper'=>'Energetic, friendly, independent','img'=>null,'desc'=>'Huskies are very energetic and friendly. They need lots of exercise and a cool, well-ventilated home.'],
  ['slug'=>'german-shepherd-male','name'=>'German Shepherd Puppy','breed'=>'German Shepherd','gender'=>'Male','age'=>'8 Weeks','size'=>'Large','temper'=>'Smart, loyal, protective','img'=>null,'desc'=>'German Shepherds are intelligent, loyal and protective. They thrive with training, exercise and a job to do.'],
  ['slug'=>'toy-poodle-female','name'=>'Toy Poodle Puppy','breed'=>'Poodle','gender'=>'Female','age'=>'8 Weeks','size'=>'Small','temper'=>'Clever, lively, affectionate','img'=>null,'desc'=>'Poodles are clever and lively with a curly coat that needs regular grooming.'],
  ['slug'=>'pomeranian-female','name'=>'Fluffy Pomeranian Puppy','breed'=>'Pomeranian','gender'=>'Female','age'=>'8 Weeks','size'=>'Small','temper'=>'Bold, lively, affectionate','img'=>null,'desc'=>'Pomeranians are tiny, fluffy and full of personality. They love attention and regular brushing.'],
];
foreach($dogs as $i=>$d){ $dogs[$i]['n']=$i; }

// Blog. Body format: string = paragraph, '# text' = heading, array = bullet list
$blog = [
 ['slug'=>'5-essential-diet-tips-for-your-growing-puppy','title'=>'5 Essential Diet Tips for Your Growing Puppy','cat'=>'Care','date'=>'Oct 08, 2026','read'=>'4 min read','img'=>$P['golden'],
  'excerpt'=>'Simple feeding habits that help your puppy grow strong, healthy and happy.',
  'body'=>['A puppy grows very fast in the first year, and what you feed it matters. Here are five easy habits we share with every family.',
   '# 1. Choose a puppy-specific food','Puppies need more protein, fat and calcium than adult dogs. Pick a good quality puppy food that suits your puppy\'s expected adult size.',
   '# 2. Feed small meals on a fixed schedule','Young puppies usually do best with three to four small meals a day. As they grow, you can move to two meals. Regular timing also makes potty training easier.',
   '# 3. Measure the portions','Follow the feeding chart on the pack for your puppy\'s weight. Overfeeding puts stress on growing joints, especially in large breeds like Labradors and Golden Retrievers.',
   '# 4. Always keep fresh water available','Clean water should be within reach all day. Wash the bowl daily.',
   '# 5. Know what to avoid',['Chocolate','Grapes and raisins','Onion and garlic','Sweeteners containing xylitol','Cooked bones and oily table scraps'],
   'Every puppy at Kyra Pet Shop goes home with a feeding chart. If you are unsure about anything, call us or ask your vet.']],
 ['slug'=>'how-to-potty-train-your-new-dog','title'=>'How to Potty Train Your New Dog: A 7-Day Starter Plan','cat'=>'Training','date'=>'Sep 26, 2026','read'=>'5 min read','img'=>$P['grass'],
  'excerpt'=>'Build strong potty habits in the first week with a calm, positive routine.',
  'body'=>['You can build strong foundations in the first week. Full reliability takes a few weeks, so stay patient and consistent.',
   '# Pick one spot','Choose one place outside or on a pad and always take your puppy there. The smell helps them understand what to do.',
   '# Take them out at the right times',['After waking up','After eating or drinking','After playing','Before bed and every couple of hours in between'],
   '# Reward right away','The moment your puppy finishes, praise gently and give a small treat. Timing is everything.',
   '# Supervise indoors','Keep your puppy where you can see them. If they start sniffing and circling, take them out quickly.',
   '# Handle accidents calmly','Never scold or punish. Clean the area well so the smell does not invite a repeat, and go back to the routine.',
   'Need help with a specific problem? Message us on WhatsApp and our team will guide you.']],
 ['slug'=>'golden-retriever-vs-siberian-husky','title'=>'Golden Retriever vs Siberian Husky: Which Breed Is Right for You?','cat'=>'Breeds','date'=>'Sep 12, 2026','read'=>'5 min read','img'=>$P['sweater'],
  'excerpt'=>'Two loved breeds, two very different personalities. Here is how to choose.',
  'body'=>['Both breeds are beautiful and friendly, but they suit different homes. Here is a quick comparison.',
   '# Temperament','Golden Retrievers are eager to please and easy to train. Huskies are friendly but independent, and they can be more stubborn.',
   '# Energy and exercise','Both need regular exercise. Huskies are especially energetic and need lots of activity to stay happy.',
   '# Heat and climate','Huskies have a thick double coat and need a cool, well-ventilated space in Delhi summers. Goldens also feel the heat, so plan walks for mornings and evenings.',
   '# Grooming','Both shed. Expect regular brushing for either breed, and more during seasonal shedding.',
   '# Our advice','If you want an easy-going family dog and a first dog, a Golden Retriever is usually simpler. If you are active and experienced, a Husky can be a wonderful companion. Visit us and meet both before deciding.']],
 ['slug'=>'puppy-vaccination-schedule-what-to-expect','title'=>'Puppy Vaccination Schedule: What to Expect','cat'=>'Health','date'=>'Aug 30, 2026','read'=>'4 min read','img'=>$P['tan'],
  'excerpt'=>'A simple overview of puppy vaccines. Always confirm the exact schedule with your vet.',
  'body'=>['Vaccines protect your puppy from serious diseases. Schedules can vary, so use this as a general guide and follow your vet\'s advice.',
   '# The basic pattern',['First vaccines usually start at around 6 to 8 weeks','Booster doses follow every few weeks until the puppy is around 16 weeks','Rabies vaccination is given at the age your vet recommends','Yearly boosters keep protection going'],
   'Ask your vet when your puppy can safely meet other dogs and go to public places. Until the course is complete, it is wise to avoid crowded spaces.',
   '# Keep the records','Always keep the vaccination card safe. Every Kyra puppy comes with its card, so your vet knows exactly what has been given.']],
 ['slug'=>'how-to-choose-the-right-breed-for-your-family','title'=>'How to Choose the Right Breed for Your Family','cat'=>'Breeds','date'=>'Aug 14, 2026','read'=>'4 min read','img'=>$P['lab'],
  'excerpt'=>'Space, time, kids, climate and budget: ask these questions before you fall in love.',
  'body'=>['It is easy to fall for a cute face. A few honest questions will help you pick a breed that fits your life for the next ten years or more.',
   '# Questions to ask yourself',['How much space do I have?','How much time can I give each day for walks and play?','Are there small children or elderly people at home?','Can I handle grooming and shedding?','Is my home comfortable for the breed in summer?'],
   '# Match energy to lifestyle','Active families enjoy Labradors and Beagles. A calmer home may prefer a Pug, Shih Tzu or Bulldog.',
   '# Meet the puppy','Nothing replaces meeting the puppy. Visit us, ask questions and let our team suggest breeds that match your answers.']],
 ['slug'=>'your-puppys-first-week-at-home','title'=>'Your Puppy\'s First Week at Home: A Simple Checklist','cat'=>'Care','date'=>'Jul 21, 2026','read'=>'3 min read','img'=>$P['pug'],
  'excerpt'=>'Everything to prepare so the first days are calm and happy for you and your puppy.',
  'body'=>['The first week sets the tone. Keep things calm, safe and predictable.',
   '# Before you bring the puppy home',['A quiet, safe resting space with a soft bed','Food and water bowls','The same food the puppy is already eating','A collar, lead and a few safe toys'],
   '# Settling in','Keep the same food for the first few days to avoid an upset stomach. Offer short play sessions and plenty of naps. Puppies need a lot of sleep.',
   '# Routine and vet','Start a simple routine for meals, potty breaks and sleep. Book a vet visit in the first week and show the vaccination card.',
   '# Need help?','Our team is only a call away. Message us any time with questions.']],
];

// Gallery: [unsplash id, category, alt text]
$gal = [
 [$P['sweater'],'Puppies','Puppy resting on a sweater'],[$P['golden'],'Puppies','Golden Retriever puppy'],[$P['pug'],'Puppies','Fawn pug puppy'],
 [$P['lab'],'Puppies','Labrador retriever'],[$P['pug2'],'Puppies','Pug lying down'],[$P['grass'],'Dogs','Brown dog on grass'],
 [$P['tongue'],'Dogs','Happy dog'],[$P['tan'],'Dogs','White and tan dog'],[$P['chow'],'Dogs','Chow Chow'],
 [$P['bulldog'],'Dogs','English Bulldog'],[$P['black'],'Dogs','Black dog'],[$P['grey'],'Dogs','Grey dog'],
];

/* ================= HELPERS ================= */
if (!function_exists('dog_card')) {
function dog_card($d,$site){ $t=rawurlencode("Hi Kyra Pet Shop, I am interested in: ".$d['name']); $u='breed.php?slug='.urlencode($d['slug']); ?>
<article class="card pitem rv" data-cat="<?=h($d['breed'])?>" data-gender="<?=h($d['gender'])?>" data-text="<?=h(strtolower($d['name'].' '.$d['breed']))?>" data-order="<?=(int)$d['n']?>">
  <a class="im" href="<?=$u?>"><?php if($d['img']): ?><img src="<?=h(strpos($d['img'],'/')!==false?$d['img']:ph($d['img'],700))?>" alt="<?=h($d['name'])?>" loading="lazy"><?php else: ?><span class="noimg"><b>🐾</b><?=h($d['breed'])?></span><?php endif; ?><span class="ok">✓ Vaccinated</span></a>
  <div class="bd"><h3><a href="<?=$u?>"><?=h($d['name'])?></a></h3><div class="meta"><?=h($d['age'])?> &nbsp;|&nbsp; <?=h($d['gender'])?> &nbsp;|&nbsp; <?=h($d['breed'])?></div>
    <div class="row"><a class="btn" href="<?=$u?>">Details</a><a class="btn btn-wa" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'])?>?text=<?=$t?>">Inquire</a></div></div>
</article><?php }
}

if (!function_exists('blog_card')) {
    function blog_card($b){ $u='blog-post.php?slug='.urlencode($b['slug']); ?>
    <article class="bcard rv" data-cat="<?=h($b['cat'])?>">
      <a class="bim" href="<?=$u?>">
        <img src="<?=h(strpos($b['img'],'/')!==false ? $b['img'] : ph($b['img'],700))?>" alt="<?=h($b['title'])?>" loading="lazy">
        <span class="bc"><?=h($b['cat'])?></span>
      </a>
      <div class="bb">
        <div class="bmeta"><?=h($b['date'])?> · <?=h($b['read'])?></div>
        <h3><a href="<?=$u?>"><?=h($b['title'])?></a></h3>
        <p><?=h($b['excerpt'])?></p>
        <a class="more" href="<?=$u?>">Read article →</a>
      </div>
    </article><?php }
}

if (!function_exists('banner')) {
function banner($h1,$crumb,$sub=''){ ?>
<section class="pbanner"><div class="blob b1"></div><div class="blob b2"></div>
  <?php for($k=0;$k<7;$k++): ?><span class="paw" style="left:<?=($k*14+8)%96?>%;animation-delay:<?=$k*1.6?>s;font-size:<?=16+($k%3)*8?>px">🐾</span><?php endfor; ?>
  <div class="wrap"><nav class="crumb" aria-label="Breadcrumb"><a href="index.php">Home</a><span>/</span><b><?=h($crumb)?></b></nav>
    <h1 class="split"><?=h($h1)?></h1><?php if($sub): ?><p class="seq"><?=h($sub)?></p><?php endif; ?></div></section><?php }
}

if (!function_exists('cta_band')) {
function cta_band($title='Ready to meet your new best friend?',$text=''){ global $site; ?>
<div class="band"><div class="wrap"><div class="in rv">
  <div><h2><?=h($title)?></h2><p><?=h($text?:'Visit us '.$site['hours'].' or message us on WhatsApp.')?></p></div>
  <a class="btn" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'])?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I would like to visit and see the puppies.')?>">Chat on WhatsApp</a>
</div></div></div><?php }
}