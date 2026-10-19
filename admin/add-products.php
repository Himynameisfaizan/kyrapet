<?php
include "db-conn.php";

$sql = "SELECT * FROM `categories` ORDER BY id DESC";
$check = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Kyra Admin - Add Breed</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
</head>

<body class="crm_body_bg">

    <?php include "header.php"; ?>
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row">
                <div class="col-lg-12 p-0 ">
                    <?php include "top_nav.php"; ?>
                </div>
            </div>
        </div>

        <div class="main_content_iner ">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="main_content_iner">
                            <div class="container-fluid p-0 sm_padding_15px">
                                <div class="row justify-content-center">

                                    <div class="col-lg-12">
                                        <div class="white_card card_height_100 mb_30">
                                            <div class="white_card_header">
                                                <div class="box_header m-0">
                                                    <div class="main-title">
                                                        <h3 class="m-0">Fill the Breed Details</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="white_card_body">
                                                <div class="card-body">
                                                    <form id="myForm" action="functions.php" method="post" enctype="multipart/form-data">
                                                        <div class="row mb-3">
                                                            
                                                            <!-- Breed Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="pro_name">Breed Title / Name</label>
                                                                <input type="text" class="form-control" name="pro_name" id="pro_name" placeholder="e.g. Healthy Labrador Retriever" required />
                                                            </div>

                                                            <!-- Custom Slug Field -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="slug_url">Custom Slug (SEO URL)</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text" style="background-color: #f1f3f5;">site.com/</span>
                                                                    <input type="text" class="form-control" name="slug_url" id="slug_url" placeholder="e.g. labrador-retriever-male" required />
                                                                </div>
                                                            </div>

                                                            <!-- Breed Sub-type / Brand Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="brand_name">Breed Type Name</label>
                                                                <input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="e.g. Labrador" required />
                                                            </div>

                                                            <!-- Parent Category Name -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="pro_cate">Parent Category Name</label>
                                                                <select class="form-control" name="pro_cate" id="pro_cate" required onchange="get_subcategory(this.value)">
                                                                    <option value="">--select--</option>
                                                                    <?php foreach ($check as$val) { ?>
                                                                        <option value="<?= $val['cate_id'] ?>"><?= ucwords(htmlspecialchars($val['categories'])) ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6 mb-3" style="display:none;">
                                                                <input type="hidden" name="pro_sub_cate" value="0">
                                                            </div>

                                                            <!-- SPECS FIELDS: Gender, Age, Size, Temperament -->
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label" for="gender">Gender</label>
                                                                <input type="text" class="form-control" name="gender" id="gender" value="Male / Female" placeholder="e.g. Male" required />
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label" for="age">Age</label>
                                                                <input type="text" class="form-control" name="age" id="age" value="8 Weeks" placeholder="e.g. 8 Weeks" required />
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label" for="size">Adult Size</label>
                                                                <input type="text" class="form-control" name="size" id="size" value="Medium to Large" placeholder="e.g. Large" required />
                                                            </div>
                                                            <div class="col-md-3 mb-3">
                                                                <label class="form-label" for="temper">Temperament</label>
                                                                <input type="text" class="form-control" name="temper" id="temper" value="Friendly, active, easy to train" placeholder="e.g. Friendly, loyal" required />
                                                            </div>

                                                            <!-- Stock / Availability Count -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="stock">Available Count / Stock</label>
                                                                <input type="text" class="form-control" name="stock" id="stock" value="10" placeholder="Stock" required />
                                                            </div>

                                                            <!-- Main Featured Image -->
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="pro_img">Main Featured Image</label>
                                                                <input type="file" class="form-control" name="pro_img[]" id="pro_img" required />
                                                            </div>

                                                            <!-- MULTI-ANGLE GALLERY IMAGES -->
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label fw-bold text-primary" for="gallery_images">Multi-Angle Gallery Images (For Slider & Thumbnails)</label>
                                                                <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" multiple />
                                                                <small class="text-muted">Aap ek sath multiple photos select kar sakte hain jo frontend breed detail page par slider mein dikhengi.</small>
                                                            </div>

                                                            <!-- NEW: BREED SPECIFIC CUSTOMER VIDEO REVIEWS -->
                                                            <div class="col-md-12 mb-4 p-3 rounded" style="background:#f8fafc; border:1px dashed #cbd5e1;">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <div>
                                                                        <label class="form-label fw-bold text-success mb-0">Customer Video Reviews for this Breed (YouTube / Shorts)</label>
                                                                        <div class="small text-muted">Yahan daali gayi videos seedha is breed ke detail page par "Ask them, not us" section mein dikhengi.</div>
                                                                    </div>
                                                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addVideoRow()">
                                                                        <i class="fas fa-plus me-1"></i> Add More Video
                                                                    </button>
                                                                </div>
                                                                <div id="videoRowsContainer">
                                                                    <div class="row g-2 mb-2 video-row-item">
                                                                        <div class="col-md-5">
                                                                            <input type="text" class="form-control" name="video_titles[]" placeholder="Video Title / Customer Name (e.g. Happy Labrador Family)">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <input type="text" class="form-control" name="video_urls[]" placeholder="YouTube or Shorts URL (e.g. https://youtube.com/shorts/...)">
                                                                        </div>
                                                                        <div class="col-md-1 d-grid">
                                                                            <button type="button" class="btn btn-outline-danger" onclick="this.closest('.video-row-item').remove()">✕</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6 mb-3" style="display:none;">
                                                                <input type="hidden" name="new_arrival" value="0">
                                                            </div>
                                                            <div class="col-md-6 mb-3" style="display:none;">
                                                                <input type="hidden" name="trending" value="0">
                                                            </div>
                                                            
                                                            <!-- Short Description -->
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label" for="short_desc">Short Overview (Lead Paragraph)</label>
                                                                <textarea class="form-control" name="short_desc" required ></textarea>
                                                            </div>
                                                            
                                                            <!-- Long Description -->
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label" for="pro_desc">Detailed Long Description (Overview & Care section)</label>
                                                                <textarea class="form-control" name="pro_desc" required ></textarea>
                                                            </div>

                                                            <div class="col-md-6 mb-3" style="display:none;">
                                                                <input type="hidden" name="mrp" value="0">
                                                            </div>
                                                            <div class="col-md-6 mb-3" style="display:none;">
                                                                <input type="hidden" name="selling_price" value="0">
                                                            </div>
                                                        </div>

                                                        <!-- SEO SECTION -->
                                                        <div class="row mb-3">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                                <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Meta Title" />
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label" for="meta_key">Meta Keyword</label>
                                                                <input type="text" class="form-control" name="meta_key" id="meta_key" placeholder="Meta Keyword" />
                                                            </div>
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label" for="meta_desc">Meta Description</label>
                                                                <input type="text" class="form-control" name="meta_desc" id="meta_desc" placeholder="Meta Description" />
                                                            </div>
                                                            
                                                            <!-- Schema Markup Box -->
                                                            <div class="col-md-12 mb-3">
                                                                <label class="form-label fw-bold" for="schema_markup">SEO Schema Markup (JSON-LD)</label>
                                                                <textarea class="form-control" name="schema_markup" id="schema_markup" rows="4" placeholder="Paste full <script type='application/ld+json'>...</script> here..."></textarea>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label" for="status">Status</label>
                                                                <select id="status" name="status" class="form-control" required>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">Deactive</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <button type="submit" class="btn btn-primary px-5" name="add-product">
                                                            Save Breed
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include "footer.php"; ?>

        <script>
            function addVideoRow() {
                const container = document.getElementById('videoRowsContainer');
                const row = document.createElement('div');
                row.className = 'row g-2 mb-2 video-row-item';
                row.innerHTML = `
                    <div class="col-md-5">
                        <input type="text" class="form-control" name="video_titles[]" placeholder="Video Title / Customer Name">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="video_urls[]" placeholder="YouTube or Shorts URL">
                    </div>
                    <div class="col-md-1 d-grid">
                        <button type="button" class="btn btn-outline-danger" onclick="this.closest('.video-row-item').remove()">✕</button>
                    </div>
                `;
                container.appendChild(row);
            }

            const form = document.getElementById('myForm');
            form.addEventListener('submit', function(event) {
                const select = document.getElementById('pro_cate');
                if (!select.value) {
                    alert('Please select a valid category.');
                    event.preventDefault(); 
                }
            });
        </script>
        
        <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>
        <script>
            CKEDITOR.replace('pro_desc');
            CKEDITOR.replace('short_desc');

            function convertToSlug(text) {
                return text.toLowerCase().replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');
            }
            document.getElementById('pro_name').addEventListener('input', function() {
                document.getElementById('slug_url').value = convertToSlug(this.value);
            });
        </script>
    </section>
</body>
</html>