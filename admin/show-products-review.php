<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "db-conn.php";

// Delete Review Logic
if (isset($_GET['delete_id'])) {$review_id = (int) $_GET['delete_id'];$sql_del = "DELETE FROM `product_reviews` WHERE `id` = $review_id";
    $res = mysqli_query($conn,$sql_del);

    if ($res) {$deleted = "Review deleted successfully.";
    } else {
        echo "Error deleting review: " . mysqli_error($conn);
    }
}

// Initialize variables
$error = '';$success = '';

// Handle Manual Review Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {
    $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);$rating = filter_input(INPUT_POST, 'rating', FILTER_SANITIZE_NUMBER_INT);
    $review_text = trim($_POST['review_text'] ?? '');
    $reviewer_name = trim($_POST['reviewer_name'] ?? '');
    $reviewer_email = filter_input(INPUT_POST, 'reviewer_email', FILTER_SANITIZE_EMAIL);$status = 1;

    if (empty($product_id)) {$error = "Please select a breed/product.";
    } elseif ($rating < 1 || $rating > 5) {$error = "Please select a rating between 1 and 5 stars.";
    } elseif (empty($review_text) || empty($reviewer_name)) {$error = "Please fill in all required fields.";
    }

    if (empty($error)) {
        $stmt =$conn->prepare("INSERT INTO product_reviews (product_id, reviewer_name, reviewer_email, rating, review_text, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        if ($stmt) {$stmt->bind_param("issisi", $product_id,$reviewer_name, $reviewer_email,$rating, $review_text,$status);
            if ($stmt->execute()) {$success = "Review added successfully!";
                $_POST = array();
            } else {
                $error = "Database error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// Handle Review Update (Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_review'])) {
    $edit_id = (int)$_POST['edit_id'];
    $up_rating = (int)$_POST['edit_rating'];
    $up_name = trim($_POST['edit_reviewer_name'] ?? '');
    $up_text = trim($_POST['edit_review_text'] ?? '');

    if ($edit_id > 0 && !empty($up_name) && !empty($up_text)) {
        $up_stmt =$conn->prepare("UPDATE product_reviews SET reviewer_name = ?, rating = ?, review_text = ? WHERE id = ?");
        if ($up_stmt) {$up_stmt->bind_param("sisi", $up_name,$up_rating, $up_text,$edit_id);
            if ($up_stmt->execute()) {$success = "Review updated successfully!";
            } else {
                $error = "Error updating review: " . $conn->error;
            }
            $up_stmt->close();
        }
    } else {
        $error = "Please fill out all required fields for editing.";
    }
}

// Fetch existing reviews for display joining with products table
$reviews_query = "SELECT r.*, p.pro_name FROM product_reviews r LEFT JOIN products p ON r.product_id = p.id ORDER BY r.created_at DESC";
$reviews_result = mysqli_query($conn,$reviews_query);
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Breed Reviews Management | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .rating-stars { color: #ffc107; font-size: 1.2em; }
        .review-card { border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 6px rgba(0,0,0,0.04); background: #fff; }
        .review-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .reviewer-name { font-weight: bold; font-size: 16px; color: #1e293b; }
        .review-date { color: #64748b; font-size: 0.85em; }
        .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; font-size: 1.5em; }
        .star-rating input { display: none; }
        .star-rating label { color: #ddd; cursor: pointer; transition: color 0.2s; }
        .star-rating label:hover, .star-rating label:hover ~ label, .star-rating input:checked ~ label { color: #ffc107; }
        .form-container { padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.04); margin-bottom: 25px; }
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
                                    <h2 class="mb-0 fw-bold">Breed Reviews Management</h2>
                                    <p class="text-muted mb-0 small">Manage customer reviews and feedback submitted on breed pages</p>
                                </div>
                            </div>
                            
                            <div class="white_card_body">
                                <!-- Notifications -->
                                <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error; ?></div><?php endif; ?>
                                <?php if (!empty($deleted)): ?><div class="alert alert-warning"><?= $deleted; ?></div><?php endif; ?>
                                <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success; ?></div><?php endif; ?>
                                
                                <!-- Add Review Form -->
                                <div class="form-container mb-4">
                                    <h4 class="mb-3">Add Manual Review</h4>
                                    <form method="post">
                                        <div class="mb-3">
                                            <label for="product_id" class="form-label">Select Breed</label>
                                            <select class="form-control" id="product_id" name="product_id" required>
                                                <option value="">-- SELECT BREED --</option>
                                                <?php
                                                $sql_pro = "SELECT id, pro_name FROM products WHERE status=1";
                                                $res_pro = mysqli_query($conn,$sql_pro);
                                                while($row_pro = mysqli_fetch_assoc($res_pro)){
                                                    echo '<option value="'.$row_pro['id'].'">'.$row_pro['pro_name'].'</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Rating</label>
                                            <div class="star-rating">
                                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo ($i == 5) ? 'checked' : ''; ?>>
                                                    <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars"><i class="fas fa-star"></i></label>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="review_text" class="form-label">Review Message</label>
                                            <textarea class="form-control" id="review_text" name="review_text" rows="3" required></textarea>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="reviewer_name" class="form-label">Reviewer Name</label>
                                                <input type="text" class="form-control" id="reviewer_name" name="reviewer_name" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="reviewer_email" class="form-label">Reviewer Email (Optional)</label>
                                                <input type="email" class="form-control" id="reviewer_email" name="reviewer_email">
                                            </div>
                                        </div>
                                        
                                        <button type="submit" name="submit_review" class="btn btn-primary">Post Review</button>
                                    </form>
                                </div>
                                
                                <h4 class="mb-3">All Customer Reviews</h4>
                                <?php if ($reviews_result && mysqli_num_rows($reviews_result) > 0): ?>
                                    <?php while ($review = mysqli_fetch_assoc($reviews_result)): ?>
                                        <div class="review-card">
                                            <div class="review-header">
                                                <div>
                                                    <span class="reviewer-name"><?= htmlspecialchars($review['reviewer_name']); ?></span>
                                                    <span class="text-muted small ms-2">(Breed: <b><?= htmlspecialchars($review['pro_name'] ?? 'N/A'); ?></b>)</span>
                                                    <div class="review-date"><?= date('F j, Y, g:i a', strtotime($review['created_at'])); ?></div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rating-stars me-2">
                                                        <?= str_repeat('★', (int)$review['rating']) . str_repeat('☆', 5 - (int)$review['rating']); ?>
                                                    </div>
                                                    <!-- Edit Button triggers modal -->
                                                    <button type="button" class="btn btn-outline-info btn-sm" onclick="openEditModal('<?= $review['id']; ?>', '<?= htmlspecialchars($review['reviewer_name'], ENT_QUOTES); ?>', '<?= $review['rating']; ?>', '<?= htmlspecialchars($review['review_text'], ENT_QUOTES); ?>')">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <a href="?delete_id=<?= $review['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this review?');">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <p class="mb-0 text-secondary"><?= nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center py-4 text-muted">No reviews found in the database.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
    </section>

    <!-- EDIT REVIEW MODAL POPUP -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form method="POST">
            <div class="modal-header">
              <h5 class="modal-title" id="editReviewModalLabel">Edit Customer Review</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <input type="hidden" name="edit_id" id="edit_id">
              
              <div class="mb-3">
                  <label for="edit_reviewer_name" class="form-label">Reviewer Name</label>
                  <input type="text" class="form-control" id="edit_reviewer_name" name="edit_reviewer_name" required>
              </div>

              <div class="mb-3">
                  <label for="edit_rating" class="form-label">Rating (1 to 5)</label>
                  <select class="form-control" id="edit_rating" name="edit_rating" required>
                      <option value="5">5 Stars</option>
                      <option value="4">4 Stars</option>
                      <option value="3">3 Stars</option>
                      <option value="2">2 Stars</option>
                      <option value="1">1 Star</option>
                  </select>
              </div>

              <div class="mb-3">
                  <label for="edit_review_text" class="form-label">Review Message</label>
                  <textarea class="form-control" id="edit_review_text" name="edit_review_text" rows="4" required></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="update_review" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS (Required for Modal) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditModal(id, name, rating, text) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_reviewer_name').value = name;
            document.getElementById('edit_rating').value = rating;
            document.getElementById('edit_review_text').value = text;
            
            var myModal = new bootstrap.Modal(document.getElementById('editReviewModal'));
            myModal.show();
        }
    </script>
</body>
</html>