<?php
include "db-conn.php";
include "functions.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $cat_id = mysqli_real_escape_string($conn, $_GET['id']);$sql = "SELECT * FROM `categories` WHERE cate_id = '$cat_id' OR id = '$cat_id' LIMIT 1";
    $result = mysqli_query($conn,$sql);
    $category = mysqli_fetch_assoc($result);
    if (!$category) {
        echo "<script>alert('Category not found.'); window.location.href='view-categories.php';</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid category ID.'); window.location.href='view-categories.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_category'])) {$cate_name     = mysqli_real_escape_string($conn,$_POST['cate_name']);
    $slug_url      = mysqli_real_escape_string($conn, $_POST['slug_url']);$status        = mysqli_real_escape_string($conn,$_POST['status']);
    $meta_title    = mysqli_real_escape_string($conn, $_POST['meta_title']);$meta_desc     = mysqli_real_escape_string($conn,$_POST['meta_desc']);
    $meta_key      = mysqli_real_escape_string($conn, $_POST['meta_key']);$schema_markup = mysqli_real_escape_string($conn, trim($_POST['schema_markup']));

    $imageName =$category['image'];
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {$fileTmpPath = $_FILES['imageUpload']['tmp_name'];$fileName = $_FILES['imageUpload']['name'];$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if (in_array($fileExtension,$allowedExtensions)) {
            $imageName = uniqid('img_', true) . '.' .$fileExtension;
            $uploadDir = 'uploads/category/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            move_uploaded_file($fileTmpPath, $uploadDir .$imageName);
        }
    }

    $update_sql = "UPDATE categories SET 
                    categories = '$cate_name', 
                    slug_url = '$slug_url', 
                    status = '$status', 
                    meta_title = '$meta_title', 
                    meta_desc = '$meta_desc', 
                    meta_key = '$meta_key', 
                    schema_markup = '$schema_markup', 
                    image = '$imageName' 
                  WHERE cate_id = '{$category['cate_id']}' OR id = '{$category['id']}'";
                  
    if (mysqli_query($conn,$update_sql)) {
        echo "<script>alert('Category Updated Successfully!'); window.location.href = 'view-categories.php';</script>";
        exit;
    } else {
        $error_msg = "Error updating category: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Edit Category | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
</head>
<body class="crm_body_bg">
    <?php include "header.php"; ?>
    <section class="main_content dashboard_part large_header_bg">
        <div class="container-fluid g-0">
            <div class="row"><div class="col-lg-12 p-0"><?php include "top_nav.php"; ?></div></div>
        </div>
        <div class="main_content_iner">
            <div class="container-fluid p-4">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-primary text-white"><h4 class="mb-0">Edit Category</h4></div>
                            <div class="card-body">
                                <?php if(isset($error_msg)) echo '<div class="alert alert-danger">'.$error_msg.'</div>'; ?>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="cate_name" class="form-label fw-bold">Category Name</label>
                                            <input type="text" id="cate_name" name="cate_name" value="<?= htmlspecialchars($category['categories']) ?>" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="slug_url" class="form-label fw-bold">Slug URL</label>
                                            <input type="text" id="slug_url" name="slug_url" value="<?= htmlspecialchars($category['slug_url']) ?>" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="meta_title" class="form-label fw-bold">Meta Title</label>
                                            <input type="text" id="meta_title" name="meta_title" value="<?= htmlspecialchars($category['meta_title'] ?? '') ?>" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="meta_key" class="form-label fw-bold">Meta Keywords</label>
                                            <input type="text" id="meta_key" name="meta_key" value="<?= htmlspecialchars($category['meta_key'] ?? '') ?>" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_desc" class="form-label fw-bold">Meta Description</label>
                                        <textarea id="meta_desc" name="meta_desc" class="form-control" rows="2"><?= htmlspecialchars($category['meta_desc'] ?? '') ?></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="schema_markup" class="form-label fw-bold">SEO Schema Markup (JSON-LD)</label>
                                        <textarea id="schema_markup" name="schema_markup" class="form-control" rows="4"><?= htmlspecialchars($category['schema_markup'] ?? '') ?></textarea>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="status" class="form-label fw-bold">Status</label>
                                            <select id="status" name="status" class="form-select" required>
                                                <option value="1" <?= ($category['status'] == '1' || $category['status'] == 'Active') ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= ($category['status'] == '0' || $category['status'] == 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Current Image</label><br>
                                            <?php if (!empty($category['image'])): ?>
                                                <img src="uploads/category/<?= htmlspecialchars($category['image']) ?>" class="img-thumbnail mb-2" style="max-height:80px;">
                                            <?php else: ?>
                                                <p class="text-muted">No image uploaded.</p>
                                            <?php endif; ?>
                                            <input type="file" id="imageUpload" name="imageUpload" class="form-control mt-1">
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="view-categories.php" class="btn btn-outline-secondary">Cancel</a>
                                        <button type="submit" name="update_category" class="btn btn-primary">Update Category</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
    </section>
</body>
</html>