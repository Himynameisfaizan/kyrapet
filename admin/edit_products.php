<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db-conn.php"; 
include "functions.php";

if (!isset($_GET['edit_product_details'])) {
    die("Breed ID is missing from the URL.");
}

$product_id = intval($_GET['edit_product_details']);

$query = "SELECT * FROM products WHERE pro_id = $product_id";
$result = mysqli_query($conn,$query);

if ($result && mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    die("Breed not found.");
}

$sql = "SELECT * FROM `categories` WHERE `status` = 1 ORDER BY id DESC";
$check = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kyra Admin - Edit Breed</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
</head>

<body class="crm_body_bg">

    <?php include "header.php"; ?>
    
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>

        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h2 class="m-0">Update Breed Details</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <br />
                                <div class="card-body">
                                    <form action="update-product.php" method="POST" enctype="multipart/form-data">
                                        
                                        <input type="hidden" name="pro_id" value="<?= $product['pro_id'] ?>" />
                                        <input type="hidden" name="id" value="<?= $product['id'] ?>" />

                                        <div class="row mb-3">
                                            <!-- Breed Name -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_name">Breed Title / Name</label>
                                                <input type="text" class="form-control" name="pro_name" id="pro_name" value="<?= htmlspecialchars($product['pro_name']) ?>" required />
                                            </div>

                                            <!-- Slug Field -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="slug_url">Custom Slug (SEO URL)</label>
                                                <div class="input-group">
                                                    <span class="input-group-text" style="background-color: #f1f3f5;">site.com/</span>
                                                    <input type="text" class="form-control" name="slug_url" id="slug_url" value="<?= htmlspecialchars($product['slug_url'] ?? '') ?>" required />
                                                </div>
                                            </div>

                                            <!-- Breed Brand Name -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="brand_name">Breed Type Name</label>
                                                <input type="text" class="form-control" name="brand_name" id="brand_name" value="<?= htmlspecialchars($product['brand_name'] ?? '') ?>" required />
                                            </div>

                                            <!-- Parent Category -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_cate">Parent Category</label>
                                                <select class="form-control" name="pro_cate" id="pro_cate" required>
                                                    <option value="">--Select Category--</option>
                                                    <?php foreach ($check as$val) { ?>
                                                        <option value="<?= $val['cate_id'] ?>" <?= ($product['pro_cate'] ==$val['cate_id']) ? 'selected' : '' ?>>
                                                            <?= ucwords(htmlspecialchars($val['categories'])) ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                                <input type="hidden" name="pro_sub_cate" value="0">
                                            </div>

                                            <!-- SPECS FIELDS: Gender, Age, Size, Temperament -->
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="gender">Gender</label>
                                                <input type="text" class="form-control" name="gender" id="gender" value="<?= htmlspecialchars($product['gender'] ?? 'Male / Female') ?>" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="age">Age</label>
                                                <input type="text" class="form-control" name="age" id="age" value="<?= htmlspecialchars($product['age'] ?? '8 Weeks') ?>" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="size">Adult Size</label>
                                                <input type="text" class="form-control" name="size" id="size" value="<?= htmlspecialchars($product['size'] ?? 'Medium to Large') ?>" required />
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label" for="temper">Temperament</label>
                                                <input type="text" class="form-control" name="temper" id="temper" value="<?= htmlspecialchars($product['temper'] ?? 'Friendly, playful, loyal') ?>" required />
                                            </div>

                                            <!-- Stock -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="stock">Stock Quantity</label>
                                                <input type="text" class="form-control" name="stock" id="stock" value="<?= $product['stock'] ?>" required />
                                            </div>

                                            <!-- Main Featured Image -->
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="pro_img">Main Featured Image</label>
                                                <input type="file" class="form-control" name="pro_img" id="pro_img" accept="image/*" />
                                                <input type="hidden" name="old_img" value="<?= $product['pro_img'] ?>">
                                                <?php if(!empty($product['pro_img'])): ?>
                                                <div class="mt-2">
                                                    <small class="text-muted">Current Image:</small>
                                                    <div class="mt-1">
                                                        <img src="assets/img/uploads/<?= trim($product['pro_img']) ?>" style="height: 80px; width: 80px; object-fit: contain; border-radius: 8px; border: 1px solid #ccc;" alt="Current Image">
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>

                                            <!-- MULTI-ANGLE GALLERY IMAGES UPLOAD -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold text-primary" for="gallery_images">Add More Gallery Images (Multi-Angle)</label>
                                                <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" multiple />
                                                <small class="text-muted">Nayi images select karne par yeh is breed ke gallery mein add ho jayengi.</small>
                                                
                                                <!-- Show existing multi-angle images from product_images table -->
                                                <?php
                                                $p_db_id = (int)$product['id'];
                                                $g_res = mysqli_query($conn, "SELECT * FROM product_images WHERE product_id = $p_db_id");
                                                if ($g_res && mysqli_num_rows($g_res) > 0) {
                                                    echo '<div class="d-flex flex-wrap gap-2 mt-2">';
                                                    while($g_row = mysqli_fetch_assoc($g_res)) {
                                                        $g_path =$g_row['image_path'];
                                                        $full_path = (strpos($g_path, 'uploads/') !== false) ? $g_path : 'assets/img/uploads/' .$g_path;
                                                        echo '<div style="position:relative; display:inline-block;"><img src="'.$full_path.'" style="height:60px;width:60px;object-fit:cover;border-radius:6px;border:1px solid #ddd;"></div>';
                                                    }
                                                    echo '</div>';
                                                }
                                                ?>
                                            </div>

                                            <!-- Short Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="short_desc">Short Overview</label>
                                                <textarea class="form-control" name="short_desc" id="short_desc" required><?= $product['short_desc'] ?></textarea>
                                            </div>

                                            <!-- Long Description -->
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="pro_desc">Detailed Long Description</label>
                                                <textarea class="form-control" name="pro_desc" id="pro_desc" required><?= $product['description'] ?></textarea>
                                            </div>

                                            <!-- Status -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label" for="status">Status</label>
                                                <select id="status" name="status" class="form-control" required>
                                                    <option value="1" <?= $product['status'] == 1 ? 'selected' : '' ?>>Active</option>
                                                    <option value="0" <?= $product['status'] == 0 ? 'selected' : '' ?>>Deactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- SEO Section -->
                                        <h5 class="mt-4 mb-3 border-bottom pb-2">SEO Details</h5>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input type="text" class="form-control" name="meta_title" id="meta_title" value="<?= htmlspecialchars($product['meta_title'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_key">Meta Keywords</label>
                                                <input type="text" class="form-control" name="meta_key" id="meta_key" value="<?= htmlspecialchars($product['meta_key'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label" for="meta_desc">Meta Description</label>
                                                <input type="text" class="form-control" name="meta_desc" id="meta_desc" value="<?= htmlspecialchars($product['meta_desc'] ?? '') ?>" />
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold" for="schema_markup">SEO Schema Markup (JSON-LD)</label>
                                                <textarea class="form-control" name="schema_markup" id="schema_markup" rows="4"><?= htmlspecialchars($product['schema_markup'] ?? '') ?></textarea>
                                            </div>
                                        </div>

                                        <button type="submit" name="update-product" class="btn btn-primary px-5 py-2">
                                            <i class="fas fa-save me-2"></i> Update Breed
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>
        <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
        <script>
            if (document.getElementById('pro_desc')) { CKEDITOR.replace('pro_desc'); }
            if (document.getElementById('short_desc')) { CKEDITOR.replace('short_desc'); }
        </script>
    </section>
</body>
</html>