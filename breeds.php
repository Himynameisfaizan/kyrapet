<?php
require __DIR__.'/config/connect.php'; 
require __DIR__.'/includes/config.php';

$page = 'breeds';

// ==========================================
// 1. DYNAMIC META & SCHEMA FETCHING
// ==========================================
$meta_q = mysqli_query($conn, "SELECT * FROM meta WHERE page_url='breeds.php'");
$meta_data = mysqli_fetch_assoc($meta_q);
$title = $meta_data['meta_title'] ?? 'Our Premium Breeds | Puppies for Sale in New Delhi | Kyra Pet Shop';
$desc = $meta_data['meta_desc'] ?? 'Browse healthy, vaccinated puppies: Labrador, Golden Retriever, Pug, Shih Tzu, Beagle, German Shepherd, Husky, Poodle and more.';

$schema_q = mysqli_query($conn, "SELECT * FROM page_schemas WHERE page_url='breeds.php'");
$schema_data = mysqli_fetch_assoc($schema_q);

// ==========================================
// 2. FETCH DYNAMIC CATEGORIES (BREEDS)
// ==========================================
$breedNames = [];
$breed_q = mysqli_query($conn, "SELECT DISTINCT brand_name FROM products WHERE status=1 AND brand_name IS NOT NULL AND brand_name != ''");
if($breed_q) {
    while($b_row = mysqli_fetch_assoc($breed_q)){
        $breedNames[] = $b_row['brand_name'];
    }
}

// ==========================================
// 3. PAGINATION & FILTER LOGIC
// ==========================================
$limit = 12; 
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $limit;

// Fixed: Purani hardcoded pro_cate=34158 hata di hai taaki saari categories ke products show ho sakein
$where = ["status=1"];

// Search Box Filter
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where[] = "(pro_name LIKE '%$search%' OR brand_name LIKE '%$search%')";
}

// Breed Button Filter
if (!empty($_GET['breed'])) {
    $breed = mysqli_real_escape_string($conn, $_GET['breed']);
    $where[] = "brand_name = '$breed'";
}

// Sorting Logic
$order_by = "ORDER BY id DESC"; // Default: Featured (Latest)
if (isset($_GET['sort']) && $_GET['sort'] == 'az') {
    $order_by = "ORDER BY pro_name ASC";
}

$where_sql = implode(' AND ', $where);

// Count total items for Pagination
$total_rows = 0;
$count_q = mysqli_query($conn, "SELECT COUNT(*) as total FROM products WHERE $where_sql");
if ($count_q) {
    $total_rows = mysqli_fetch_assoc($count_q)['total'];
}
$total_pages = ceil($total_rows / $limit);

// Fetch Products from Database - Array renamed to $db_dogs to prevent overwrite
$db_dogs = [];
$prod_q = mysqli_query($conn, "SELECT * FROM products WHERE $where_sql $order_by LIMIT $offset, $limit");
if ($prod_q) {
    while ($row = mysqli_fetch_assoc($prod_q)) {
        $db_dogs[] = [
            'slug'   => $row['slug_url'],
            'name'   => $row['pro_name'],
            'breed'  => $row['brand_name'] ?? 'Dog',
            'gender' => 'Male/Female', 
            'age'    => '8 Weeks',
            'img'    => !empty($row['pro_img']) ? 'admin/assets/img/uploads/' . $row['pro_img'] : null,
            'n'      => $row['id']
        ];
    }
}

require __DIR__.'/includes/header.php';
if (function_exists('banner')) banner('Our Premium Breeds','Breeds','Healthy, vaccinated and raised with care. Pick a puppy and message us on WhatsApp.');
?>
<main>
<section style="padding-top:30px"><div class="wrap">
  
  <!-- DYNAMIC SEARCH & FILTER FORM -->
  <form id="filterForm" method="GET" action="breeds.php">
    <div class="tools">
      <div class="sbox">
        <input name="search" id="q" type="search" placeholder="Search breed or name..." value="<?= h($_GET['search'] ?? '') ?>" onsearch="document.getElementById('filterForm').submit();" onkeypress="if(event.keyCode == 13) document.getElementById('filterForm').submit();">
      </div>
      <div class="sel">
        <select name="gender" id="fGender" aria-label="Filter by gender" onchange="document.getElementById('filterForm').submit();">
          <option value="all">All genders</option>
          <option value="Male" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Male') ? 'selected' : '' ?>>Male</option>
          <option value="Female" <?= (isset($_GET['gender']) && $_GET['gender'] == 'Female') ? 'selected' : '' ?>>Female</option>
        </select>
        <select name="sort" id="fSort" aria-label="Sort puppies" onchange="document.getElementById('filterForm').submit();">
          <option value="order">Featured</option>
          <option value="az" <?= (isset($_GET['sort']) && $_GET['sort'] == 'az') ? 'selected' : '' ?>>Name A to Z</option>
        </select>
      </div>
    </div>
    <!-- Hidden input to track breed selection -->
    <input type="hidden" name="breed" id="breedInput" value="<?= h($_GET['breed'] ?? '') ?>">
  </form>

  <!-- DYNAMIC CATEGORIES (BREEDS) -->
  <div class="fchips" role="group" aria-label="Filter by breed">
    <button type="button" class="<?= empty($_GET['breed']) ? 'active' : '' ?>" onclick="document.getElementById('breedInput').value=''; document.getElementById('filterForm').submit();">All breeds</button>
    <?php foreach($breedNames as $b): ?>
      <button type="button" class="<?= (isset($_GET['breed']) && $_GET['breed'] == $b) ? 'active' : '' ?>" onclick="document.getElementById('breedInput').value='<?= h($b) ?>'; document.getElementById('filterForm').submit();"><?= h($b) ?></button>
    <?php endforeach; ?>
  </div>
  
  <!-- RESULT COUNT -->
  <div class="count" id="cnt">Showing <?= count($db_dogs) ?> of <?= $total_rows ?> puppies</div>
  
  <!-- PRODUCTS GRID USING $db_dogs -->
  <div class="grid4" id="grid">
    <?php 
    if (!empty($db_dogs)) {
        foreach($db_dogs as $d) {
            if (function_exists('dog_card')) dog_card($d, $site); 
        }
    } 
    ?>
  </div>
  
  <!-- EMPTY STATE -->
  <?php if (empty($db_dogs)): ?>
    <div class="empty" id="empty" style="display:block;">
        <b>No puppies match your search</b><br>
        Try another breed, or message us. New puppies arrive often.
    </div>
  <?php endif; ?>

  <!-- DYNAMIC PAGINATION -->
  <?php if ($total_pages > 1): ?>
  <div class="pagination" style="display: flex; justify-content: center; gap: 10px; margin-top: 40px; flex-wrap: wrap;">
      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
          <?php 
              $query = $_GET;
              $query['page'] = $i;
              $query_string = http_build_query($query);
          ?>
          <a href="?<?= $query_string ?>" class="btn <?= ($current_page == $i) ? 'btn-teal' : 'btn-line' ?>" style="padding: 10px 20px; border-radius: 8px; text-decoration: none;">
              <?= $i ?>
          </a>
      <?php endfor; ?>
  </div>
  <?php endif; ?>

  <div class="leave rv" style="margin-top:64px">
    <div><h3>Cannot see your dream breed?</h3><p>Tell us the breed, gender and age you want. We will share photos, videos and health details as soon as we have a match.</p></div>
    <div class="cta" style="margin:0"><a class="btn btn-teal" target="_blank" rel="noopener" href="https://wa.me/<?=h($site['wa'] ?? '')?>?text=<?=rawurlencode('Hi Kyra Pet Shop, I am looking for a specific breed.')?>">Ask on WhatsApp</a><a class="btn btn-line" href="tel:<?=h($tel ?? '')?>">Call us</a></div>
  </div>
</div></section>
<?php if(function_exists('cta_band')) cta_band(); ?>
</main>
<?php require __DIR__.'/includes/footer.php'; ?>