<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db-conn.php";

// Ensure table exists taaki kabhi error na aaye
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `happy_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_path` varchar(255) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$error = '';
$success = '';$deleted = '';

// ==========================================
// 1. DELETE CUSTOMER PHOTO
// ==========================================
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    
    // Pehle image path nikal kar server se file delete karenge
    $img_q = mysqli_query($conn, "SELECT image_path FROM happy_customers WHERE id = $del_id");
    if ($img_q && mysqli_num_rows($img_q) > 0) {
        $img_row = mysqli_fetch_assoc($img_q);
        $old_file =$img_row['image_path'];
        if (!empty($old_file) && file_exists($old_file)) {
            @unlink($old_file);
        }
    }

    if (mysqli_query($conn, "DELETE FROM happy_customers WHERE id = $del_id")) {
        $deleted = "Customer photo deleted successfully!";
    } else {
        $error = "Error deleting photo: " . mysqli_error($conn);
    }
}

// ==========================================
// 2. ADD NEW CUSTOMER PHOTOS (SINGLE OR MULTIPLE)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_customer_photo'])) {$status = isset($_POST['status']) ? (int)$_POST['status'] : 1;
    $upload_dir = "uploads/happy_customers/";
    
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $allowed_types = ['jpg', 'jpeg', 'png', 'webp', 'gif'];$uploaded_count = 0;

    if (!empty($_FILES['customer_images']['name'][0])) {
        foreach ($_FILES['customer_images']['tmp_name'] as $key =>$tmp_name) {
            if ($_FILES['customer_images']['error'][$key] === UPLOAD_ERR_OK) {$file_name = $_FILES['customer_images']['name'][$key];
                $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                if (in_array($ext,$allowed_types)) {
                    $new_name = 'hc_' . uniqid() . '_' . time() . '.' . $ext;
                    $target_path = $upload_dir .$new_name;

                    if (move_uploaded_file($tmp_name, $target_path)) {$path_esc = mysqli_real_escape_string($conn,$target_path);
                        mysqli_query($conn, "INSERT INTO happy_customers (image_path, status) VALUES ('$path_esc',$status)");
                        $uploaded_count++;
                    }
                } else {
                    $error = "Only JPG, JPEG, PNG, WEBP & GIF files are allowed.";
                }
            }
        }
        if ($uploaded_count > 0) {
            $success = "$uploaded_count Happy Customer photo(s) added successfully!";
        }
    } else {
        $error = "Please select at least one image to upload.";
    }
}

// ==========================================
// 3. EDIT / UPDATE CUSTOMER PHOTO (MODAL)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer_photo'])) {$edit_id = (int)$_POST['edit_id'];$edit_status = (int)$_POST['edit_status'];$upload_dir = "uploads/happy_customers/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Fetch current image
    $cur_q = mysqli_query($conn, "SELECT image_path FROM happy_customers WHERE id = $edit_id");
    $cur_row = mysqli_fetch_assoc($cur_q);
    $final_path =$cur_row['image_path'] ?? '';

    // Check if new image is uploaded
    if (isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK) {$file_name = $_FILES['edit_image']['name'];$ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));$allowed_types = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($ext,$allowed_types)) {
            $new_name = 'hc_' . uniqid() . '_' . time() . '.' . $ext;
            $target_path = $upload_dir .$new_name;

            if (move_uploaded_file($_FILES['edit_image']['tmp_name'],$target_path)) {
                // Delete old file if exists
                if (!empty($final_path) && file_exists($final_path)) {
                    @unlink($final_path);
                }
                $final_path =$target_path;
            }
        } else {
            $error = "Invalid image format. Only JPG, PNG, WEBP, GIF allowed.";
        }
    }

    if (empty($error) &&$edit_id > 0) {
        $path_esc = mysqli_real_escape_string($conn, $final_path);$up_sql = "UPDATE happy_customers SET image_path = '$path_esc', status = $edit_status WHERE id =$edit_id";
        if (mysqli_query($conn, $up_sql)) {$success = "Customer photo updated successfully!";
        } else {
            $error = "Database error: " . mysqli_error($conn);
        }
    }
}

// Fetch all Happy Customer images
$hc_result = mysqli_query($conn, "SELECT * FROM happy_customers ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Happy Customers Management | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .form-container { padding: 22px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #eee; }
        .hc-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(210px, 1fr)); gap: 20px; }
        .hc-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.04); transition: transform 0.25s ease; }
        .hc-card:hover { transform: translateY(-4px); }
        .hc-card-img { width: 100%; height: 240px; object-fit: cover; background: #f8fafc; display: block; }
        .hc-card-body { padding: 12px 14px; display: flex; justify-content: space-between; align-items: center; }
        .badge-active { background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .badge-inactive { background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
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
            <div class="container-fluid p-3">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="white_card mb_30">
                            <div class="white_card_header bg-white border-0 py-3">
                                <div class="main-title">
                                    <h2 class="mb-0 fw-bold">Our Happy Customers Management</h2>
                                    <p class="text-muted mb-0 small">Add, edit, or delete customer photos for the Happy Customers slider</p>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                                <?php if (!empty($deleted)): ?><div class="alert alert-warning"><?= $deleted ?></div><?php endif; ?>
                                <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

                                <!-- Add Customer Photos Form -->
                                <div class="form-container">
                                    <h4 class="mb-3">Upload Happy Customer Photos</h4>
                                    <form method="POST" enctype="multipart/form-data">
                                        <div class="row align-items-end">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">Select Photo(s) <span class="text-danger">*</span></label>
                                                <input type="file" name="customer_images[]" class="form-control" accept="image/*" multiple required>
                                                <small class="text-muted">Aap ek sath multiple photos bhi select kar sakte hain (Portrait 3:4 recommended).</small>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-bold">Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" selected>Active (Visible)</option>
                                                    <option value="0">Inactive (Hidden)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <button type="submit" name="add_customer_photo" class="btn btn-primary w-100 py-2">
                                                    <i class="fas fa-upload me-2"></i>Upload Photo(s)
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Existing Customer Photos Grid -->
                                <h4 class="mb-3">All Happy Customer Photos</h4>
                                <?php if ($hc_result && mysqli_num_rows($hc_result) > 0): ?>
                                    <div class="hc-grid">
                                        <?php while ($row = mysqli_fetch_assoc($hc_result)): ?>
                                            <div class="hc-card">
                                                <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Happy Customer" class="hc-card-img">
                                                <div class="hc-card-body">
                                                    <div>
                                                        <?php if ($row['status'] == 1): ?>
                                                            <span class="badge-active">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge-inactive">Inactive</span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button type="button" class="btn btn-outline-info btn-sm" 
                                                            onclick="openEditCustomerModal('<?= $row['id'] ?>', '<?= htmlspecialchars($row['image_path'], ENT_QUOTES) ?>', '<?=$row['status'] ?>')">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-outline-danger btn-sm" 
                                                           onclick="return confirm('Are you sure you want to delete this customer photo?');">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-5 text-muted">No customer photos uploaded yet.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
    </section>

    <!-- EDIT CUSTOMER PHOTO MODAL -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Customer Photo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        
                        <div class="text-center mb-3">
                            <img id="current_img_preview" src="" alt="Preview" style="max-height: 180px; border-radius: 10px; border: 1px solid #ddd;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Replace Photo (Optional)</label>
                            <input type="file" name="edit_image" class="form-control" accept="image/*">
                            <small class="text-muted">Agar photo change nahi karni toh isse blank chhod dein.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="edit_status" id="edit_status" class="form-control">
                                <option value="1">Active (Visible)</option>
                                <option value="0">Inactive (Hidden)</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_customer_photo" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditCustomerModal(id, imgPath, status) {
            document.getElementById('edit_id').value = id;
            document.getElementById('current_img_preview').src = imgPath;
            document.getElementById('edit_status').value = status;
            var modal = new bootstrap.Modal(document.getElementById('editCustomerModal'));
            modal.show();
        }
    </script>
</body>
</html>