<?php
session_start();
include "db-conn.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Add Category | Admin Panel</title>
    <link rel="icon" href="img/logo.png" type="image/png">

    <?php include "links.php"; ?>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4cc9f0;
            --dark-color: #1a1a2e;
            --light-color: #f8f9fa;
            --success-color: #4bb543;
        }
        .category-card { background: white; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); padding: 2rem; }
        .section-title { font-weight: 600; color: var(--dark-color); margin-bottom: 1.5rem; position: relative; padding-bottom: 0.75rem; }
        .section-title:after { content: ''; position: absolute; left: 0; bottom: 0; width: 50px; height: 3px; background: var(--primary-color); }
        .form-label { font-weight: 500; color: var(--dark-color); margin-bottom: 0.5rem; }
        .form-control { border-radius: 8px; padding: 0.75rem 1rem; border: 1px solid #e0e0e0; }
        .btn-submit { background: linear-gradient(to right, var(--primary-color), var(--secondary-color)); border: none; padding: 0.75rem 2rem; border-radius: 8px; font-weight: 600; }
        .image-preview-container { display: none; margin-top: 1rem; text-align: center; }
        .image-preview { max-width: 200px; max-height: 150px; border-radius: 8px; border: 1px solid #eee; }
        .file-upload-wrapper { position: relative; margin-bottom: 1rem; }
        .file-upload-label { display: block; padding: 0.75rem 1rem; border: 1px dashed #ddd; border-radius: 8px; text-align: center; cursor: pointer; }
    </style>
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

        <div class="main_content_iner">
            <div class="container-fluid p-0 sm_padding_15px">
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h2 class="m-0">Add New Category</h2>
                                    </div>
                                    <div class="add_button ms-2">
                                        <a href="view-categories.php" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-list me-1"></i> View Categories
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <div class="category-card">
                                    <h3 class="section-title">Category Details & SEO</h3>
                                    <form id="categoryForm" action="functions.php" method="post" enctype="multipart/form-data">
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="cate_name">Category Name</label>
                                                <input type="text" class="form-control" name="cate_name" id="cate_name" placeholder="Enter category name" required />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_title">Meta Title</label>
                                                <input type="text" class="form-control" name="meta_title" id="meta_title" placeholder="Enter meta title for SEO" required />
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_key">Meta Keywords</label>
                                                <input type="text" class="form-control" name="meta_key" id="meta_key" placeholder="Comma separated keywords" required />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="meta_desc">Meta Description</label>
                                                <textarea class="form-control" name="meta_desc" id="meta_desc" rows="1" placeholder="Brief description for SEO" required></textarea>
                                            </div>
                                        </div>

                                        <!-- SCHEMA MARKUP SECTION -->
                                        <div class="row mb-4">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-bold" for="schema_markup">SEO Schema Markup (JSON-LD)</label>
                                                <textarea class="form-control" name="schema_markup" id="schema_markup" rows="4" placeholder="Paste full <script type='application/ld+json'>...</script> here..."></textarea>
                                            </div>
                                        </div>

                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label" for="status">Status</label>
                                                <select id="status" name="status" class="form-control" required>
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Category Image</label>
                                                <div class="file-upload-wrapper">
                                                    <label for="imageUpload" class="file-upload-label">
                                                        <i class="fas fa-cloud-upload-alt"></i>
                                                        <p class="mb-0">Click to upload or drag and drop</p>
                                                    </label>
                                                    <input type="file" class="form-control d-none" name="imageUpload" id="imageUpload" accept="image/*" onchange="previewImage(this)" />
                                                </div>
                                                <div class="image-preview-container" id="imagePreviewContainer">
                                                    <img id="imagePreview" class="image-preview" />
                                                    <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                                        <i class="fas fa-trash me-1"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" class="btn btn-submit" name="add-categories">
                                                <i class="fas fa-save me-1"></i> Save Category
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
        <script>
            function previewImage(input) {
                const previewContainer = document.getElementById('imagePreviewContainer');
                const preview = document.getElementById('imagePreview');
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            function removeImage() {
                document.getElementById('imageUpload').value = '';
                document.getElementById('imagePreviewContainer').style.display = 'none';
            }
        </script>
    </section>
</body>
</html>