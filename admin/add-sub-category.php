<?php
include "db-conn.php";
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$sql = "SELECT * FROM `categories` ORDER BY id DESC";
$check = mysqli_query($conn,$sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Add Sub Category | Admin Panel</title>
    <link rel="icon" href="img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .form-section { background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 30px; }
        .form-label { font-weight: 500; color: #495057; margin-bottom: 8px; }
        .form-control, .form-select { border-radius: 6px; padding: 10px 15px; border: 1px solid #e0e0e0; }
        .preview-image { max-width: 150px; max-height: 150px; margin-top: 15px; border-radius: 4px; border: 1px dashed #ddd; padding: 5px; display: none; }
    </style>
</head>
<body class="crm_body_bg">
    <?php include "header.php"; ?>
    <section class="main_content dashboard_part">
        <div class="container-fluid g-0"><div class="row"><div class="col-lg-12 p-0"><?php include "top_nav.php"; ?></div></div></div>
        <div class="main_content_iner">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="form-section">
                            <h2 class="mb-4">Add Sub Category</h2>
                            <form id="subCategoryForm" action="functions.php" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Parent Category <span class="text-danger">*</span></label>
                                        <select class="form-select" name="parent_id" required>
                                            <option value="" selected disabled>Select Parent Category</option>
                                            <?php while ($row = mysqli_fetch_assoc($check)): ?>
                                                <option value="<?= $row['cate_id'] ?>"><?= htmlspecialchars($row['categories']) ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Sub Category Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="cate_name" placeholder="Enter sub category name" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" name="meta_title" placeholder="Meta title for SEO">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" name="meta_key" placeholder="Comma separated keywords">
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label">Meta Description</label>
                                        <textarea class="form-control" name="meta_desc" rows="2" placeholder="Meta description for SEO"></textarea>
                                    </div>
                                    <div class="col-md-12 mb-4">
                                        <label class="form-label fw-bold">SEO Schema Markup (JSON-LD)</label>
                                        <textarea class="form-control" name="schema_markup" rows="4" placeholder="Paste schema markup here..."></textarea>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Category Image</label>
                                        <input type="file" class="form-control" name="imageUpload" id="imageUpload" accept="image/*">
                                        <img id="imagePreview" src="#" alt="Preview" class="preview-image">
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Status</label>
                                        <select class="form-select" name="status">
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary" name="add-sub-categories">
                                            <i class="fas fa-plus me-2"></i> Add Sub Category
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
        <script>
            document.getElementById('imageUpload').addEventListener('change', function(e) {
                const preview = document.getElementById('imagePreview');
                const file = e.target.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.style.display = 'block';
                    preview.src = e.target.result;
                }
                if (file) { reader.readAsDataURL(file); }
            });
        </script>
    </section>
</body>
</html>