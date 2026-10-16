<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db-conn.php";

$error = '';
$success = '';
$deleted = '';

// Helper function: Kisi bhi YouTube URL (Shorts, Watch, Embed, youtu.be) se 11-char video_code nikalne ke liye
function extractYoutubeId($url) {
    $url = trim($url);
    // Agar direct 11 character ka code hi daala hai
    if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
        return $url;
    }
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
    if (preg_match($pattern, $url, $matches)) {
        return $matches[1];
    }
    return '';
}

// ==========================================
// 1. DELETE VIDEO
// ==========================================
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    if (mysqli_query($conn, "DELETE FROM videos WHERE video_id = $del_id")) {
        $deleted = "Video deleted successfully!";
    } else {
        $error = "Error deleting video: " . mysqli_error($conn);
    }
}

// ==========================================
// 2. ADD NEW VIDEO
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_video'])) {
    $title        = trim($_POST['title'] ?? '');
    $youtube_url  = trim($_POST['youtube_url'] ?? '');
    $section_type = $_POST['section_type'] ?? 'forever_homes';
    $status       = isset($_POST['status']) ? (int)$_POST['status'] : 1;

    $video_code = extractYoutubeId($youtube_url);

    if (empty($title) || empty($youtube_url)) {
        $error = "Please enter both Video Title and YouTube URL.";
    } elseif (empty($video_code)) {
        $error = "Invalid YouTube URL! Please paste a valid YouTube video or Shorts link.";
    } else {
        $stmt = $conn->prepare("INSERT INTO videos (title, youtube_url, video_code, section_type, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        if ($stmt) {
            $stmt->bind_param("ssssi", $title, $youtube_url, $video_code, $section_type, $status);
            if ($stmt->execute()) {
                $success = "Video added successfully!";
            } else {
                $error = "Database error: " . $conn->error;
            }
            $stmt->close();
        }
    }
}

// ==========================================
// 3. EDIT / UPDATE VIDEO (MODAL)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_video'])) {
    $edit_id      = (int)$_POST['edit_video_id'];
    $title        = trim($_POST['edit_title'] ?? '');
    $youtube_url  = trim($_POST['edit_youtube_url'] ?? '');
    $section_type = $_POST['edit_section_type'] ?? 'forever_homes';
    $status       = (int)$_POST['edit_status'];

    $video_code = extractYoutubeId($youtube_url);

    if ($edit_id <= 0 || empty($title) || empty($youtube_url)) {
        $error = "Please fill all required fields.";
    } elseif (empty($video_code)) {
        $error = "Invalid YouTube URL! Could not extract video ID.";
    } else {
        $up_stmt = $conn->prepare("UPDATE videos SET title = ?, youtube_url = ?, video_code = ?, section_type = ?, status = ? WHERE video_id = ?");
        if ($up_stmt) {
            $up_stmt->bind_param("ssssii", $title, $youtube_url, $video_code, $section_type, $status, $edit_id);
            if ($up_stmt->execute()) {
                $success = "Video updated successfully!";
            } else {
                $error = "Error updating video: " . $conn->error;
            }
            $up_stmt->close();
        }
    }
}

// Fetch all videos
$videos_result = mysqli_query($conn, "SELECT * FROM videos ORDER BY video_id DESC");

// Section labels mapping for clean UI
$section_labels = [
    'forever_homes'    => 'Where our puppies go next (Forever Homes)',
    'customer_stories' => 'Customer Stories (Real families. Real smiles.)',
    'ask_them'         => 'Ask them, not us (Puppy Parents / Breed Page)'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Video Reviews Management | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <?php include "links.php"; ?>
    <style>
        .form-container { padding: 22px; background: #fff; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #eee; }
        .yt-thumb { width: 110px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
        .table thead th { background-color: #2c3e50; color: white; font-weight: 500; }
        .sec-badge { background: #e0f2fe; color: #0369a1; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; display: inline-block; }
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
                                    <h2 class="mb-0 fw-bold">Customer Video Reviews & Stories</h2>
                                    <p class="text-muted mb-0 small">Add, edit, or delete YouTube videos for Home Page, Breed Page & Reviews Page</p>
                                </div>
                            </div>

                            <div class="white_card_body">
                                <?php if (!empty($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                                <?php if (!empty($deleted)): ?><div class="alert alert-warning"><?= $deleted ?></div><?php endif; ?>
                                <?php if (!empty($success)): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>

                                <!-- Add Video Form -->
                                <div class="form-container">
                                    <h4 class="mb-3">Add New YouTube Video</h4>
                                    <form method="POST">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">Video Title / Caption <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" placeholder="e.g. Happy family with Golden Retriever" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">YouTube Video / Shorts URL <span class="text-danger">*</span></label>
                                                <input type="text" name="youtube_url" class="form-control" placeholder="https://youtube.com/shorts/..." required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold">Display Section</label>
                                                <select name="section_type" class="form-control" required>
                                                    <option value="forever_homes">Where our puppies go next (Forever Homes)</option>
                                                    <option value="customer_stories">Customer Stories (Real families. Real smiles.)</option>
                                                    <option value="ask_them">Ask them, not us (Puppy Parents / Breed Page)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-bold">Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3 mb-3 d-flex align-items-end">
                                                <button type="submit" name="add_video" class="btn btn-primary w-100 py-2">
                                                    <i class="fas fa-plus-circle me-2"></i>Add Video
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Videos List Table -->
                                <h4 class="mb-3">All Uploaded Videos</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered align-middle text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Thumbnail</th>
                                                <th>Title / Caption</th>
                                                <th>Video Code</th>
                                                <th>Section</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if ($videos_result && mysqli_num_rows($videos_result) > 0): 
                                                $sno = 1;
                                                while ($v = mysqli_fetch_assoc($videos_result)): 
                                                    $sec_key = $v['section_type'] ?? 'forever_homes';
                                                    $sec_name = $section_labels[$sec_key] ?? $sec_key;
                                            ?>
                                                <tr>
                                                    <td><?= $sno++ ?></td>
                                                    <td>
                                                        <a href="https://www.youtube.com/watch?v=<?= htmlspecialchars($v['video_code']) ?>" target="_blank">
                                                            <img src="https://i.ytimg.com/vi/<?= htmlspecialchars($v['video_code']) ?>/hqdefault.jpg" class="yt-thumb" alt="Thumbnail">
                                                        </a>
                                                    </td>
                                                    <td class="text-start fw-semibold"><?= htmlspecialchars($v['title']) ?></td>
                                                    <td><code><?= htmlspecialchars($v['video_code']) ?></code></td>
                                                    <td><span class="sec-badge"><?= htmlspecialchars($sec_name) ?></span></td>
                                                    <td>
                                                        <?php if ($v['status'] == 1): ?>
                                                            <span class="text-success fw-bold">Active</span>
                                                        <?php else: ?>
                                                            <span class="text-danger fw-bold">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-center gap-2">
                                                            <button type="button" class="btn btn-outline-info btn-sm"
                                                                onclick="openEditVideoModal(
                                                                    '<?= $v['video_id'] ?>',
                                                                    '<?= htmlspecialchars($v['title'], ENT_QUOTES) ?>',
                                                                    '<?= htmlspecialchars($v['youtube_url'], ENT_QUOTES) ?>',
                                                                    '<?= htmlspecialchars($sec_key, ENT_QUOTES) ?>',
                                                                    '<?= $v['status'] ?>'
                                                                )">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <a href="?delete_id=<?= $v['video_id'] ?>" class="btn btn-outline-danger btn-sm"
                                                               onclick="return confirm('Are you sure you want to delete this video?');">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endwhile; else: ?>
                                                <tr><td colspan="7" class="text-center text-muted py-4">No videos found.</td></tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php"; ?>
    </section>

    <!-- EDIT VIDEO MODAL POPUP -->
    <div class="modal fade" id="editVideoModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit YouTube Video</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="edit_video_id" id="edit_video_id">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Video Title / Caption</label>
                            <input type="text" name="edit_title" id="edit_title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">YouTube URL</label>
                            <input type="text" name="edit_youtube_url" id="edit_youtube_url" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Display Section</label>
                            <select name="edit_section_type" id="edit_section_type" class="form-control" required>
                                <option value="forever_homes">Where our puppies go next (Forever Homes)</option>
                                <option value="customer_stories">Customer Stories (Real families. Real smiles.)</option>
                                <option value="ask_them">Ask them, not us (Puppy Parents / Breed Page)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="edit_status" id="edit_status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="update_video" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function openEditVideoModal(id, title, url, section, status) {
            document.getElementById('edit_video_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_youtube_url').value = url;
            document.getElementById('edit_section_type').value = section;
            document.getElementById('edit_status').value = status;
            var modal = new bootstrap.Modal(document.getElementById('editVideoModal'));
            modal.show();
        }
    </script>
</body>
</html>