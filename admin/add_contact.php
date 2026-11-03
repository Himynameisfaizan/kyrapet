<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "db-conn.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form values
    $address        = mysqli_real_escape_string($conn, $_POST['address']);
    $address2       = mysqli_real_escape_string($conn, $_POST['address2']);
    $phone          = mysqli_real_escape_string($conn, $_POST['phone']);
    $wp_number      = mysqli_real_escape_string($conn, $_POST['wp_number']);
    $telephone      = mysqli_real_escape_string($conn, $_POST['telephone']);
    $email          = mysqli_real_escape_string($conn, $_POST['email']);
    $working_hours  = mysqli_real_escape_string($conn, $_POST['working_hours'] ?? null);
    $facebook       = mysqli_real_escape_string($conn, $_POST['facebook'] ?? null);
    $instagram      = mysqli_real_escape_string($conn, $_POST['instagram'] ?? null);
    $youtube        = mysqli_real_escape_string($conn, $_POST['youtube'] ?? null); // YOUTUBE ADDED
    $twitter        = mysqli_real_escape_string($conn, $_POST['twitter'] ?? null);
    $linkdin        = mysqli_real_escape_string($conn, $_POST['linkdin'] ?? null);
    $map            = mysqli_real_escape_string($conn, $_POST['map'] ?? null);
    $contact_email  = mysqli_real_escape_string($conn, $_POST['contact_email'] ?? null);

    // Fetch the existing contact record
    $check = $conn->query("SELECT id FROM contacts LIMIT 1");

    if ($check && $check->num_rows > 0) {
        $row = $check->fetch_assoc();
        $contact_id = $row['id'];

        // Update query (Youtube added)
        $stmt = $conn->prepare("UPDATE contacts 
            SET address = ?, 
                address2 = ?,
                phone = ?, 
                wp_number = ?,
                telephone = ?,
                email = ?, 
                working_hours = ?, 
                facebook = ?, 
                instagram = ?, 
                youtube = ?, 
                twitter = ?, 
                linkdin = ?,
                map = ?, 
                contact_email = ?,
                updated_at = NOW()
            WHERE id = ?");

        $stmt->bind_param(
            'ssssssssssssssi', 
            $address, $address2, $phone, $wp_number, $telephone, $email, 
            $working_hours, $facebook, $instagram, $youtube, $twitter, $linkdin,
            $map, $contact_email, $contact_id
        );
    } else {
        // Insert new record (Youtube added)
        $stmt = $conn->prepare("INSERT INTO contacts 
            (address, address2, phone, wp_number, telephone, email, working_hours, facebook, instagram, youtube, twitter, linkdin, map, contact_email) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            'ssssssssssssss', 
            $address, $address2, $phone, $wp_number, $telephone, $email, 
            $working_hours, $facebook, $instagram, $youtube, $twitter, $linkdin,
            $map, $contact_email
        );
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Contact information updated successfully!";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating information: " . $stmt->error;
    }
}

$result = $conn->query("SELECT * FROM contacts LIMIT 1");
$data = $result ? $result->fetch_assoc() : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Contact Information | Admin Panel</title>
    <link rel="icon" href="assets/img/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include "links.php"; ?>
    <style>
    .contact-form-container {
        background: #fff;
        border-radius: 10px;
        padding: 30px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .section-title {
        font-weight: 600;
        color: #4e73df;
        margin-bottom: 25px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        padding: 12px 15px;
        border: 1px solid #ddd;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }

    .social-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .input-with-icon {
        position: relative;
    }

    .input-with-icon input {
        padding-left: 40px;
    }

    .preview-map iframe {
        width: 100%;
        height: 300px;
        border: none;
        border-radius: 5px;
    }
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
                    <div class="col-12">
                        <div class="white_card card_height_100 mb_30">
                            <div class="white_card_header">
                                <div class="box_header m-0">
                                    <div class="main-title">
                                        <h2 class="m-0">Contact Information Management</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="white_card_body">
                                <?php if(isset($_SESSION['success_message'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                                <?php endif; ?>

                                <div class="contact-form-container">
                                    <form action="" method="post">

                                        <!-- Address, Phone, Email fields remain same as your original code... -->
                                        <!-- Copying them for completeness -->
                                        <h4 class="section-title"><i class="fas fa-map-marker-alt me-2"></i> Address
                                            Information</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label for="address" class="form-label">Primary Address</label>
                                                <textarea name="address" class="form-control" rows="3"
                                                    required><?php echo htmlspecialchars($data['address'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="address2" class="form-label">Secondary Address
                                                    (Optional)</label>
                                                <textarea name="address2" class="form-control"
                                                    rows="3"><?php echo htmlspecialchars($data['address2'] ?? ''); ?></textarea>
                                            </div>
                                        </div>

                                        <h4 class="section-title"><i class="fas fa-phone me-2"></i> Contact Numbers</h4>
                                        <div class="row mb-4">
                                            <div class="col-md-4 mb-3">
                                                <label for="phone" class="form-label">Primary Phone</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-mobile-alt social-icon text-primary"></i>
                                                    <input type="text" class="form-control" name="phone"
                                                        value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="wp_number" class="form-label">WhatsApp Number</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-whatsapp social-icon text-success"></i>
                                                    <input type="text" class="form-control" name="wp_number"
                                                        value="<?php echo htmlspecialchars($data['wp_number'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="telephone" class="form-label">Telephone Number</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-phone social-icon text-danger"></i>
                                                    <input type="text" class="form-control" name="telephone"
                                                        value="<?php echo htmlspecialchars($data['telephone'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <h4 class="section-title"><i class="fas fa-envelope me-2"></i> Email Information
                                        </h4>
                                        <div class="row mb-4">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Public Email Address</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-at social-icon text-success"></i>
                                                    <input type="email" class="form-control" name="email"
                                                        value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="contact_email" class="form-label">Contact Form Receiver
                                                    Email</label>
                                                <div class="input-with-icon">
                                                    <i class="fas fa-mail-bulk social-icon text-success"></i>
                                                    <input type="email" class="form-control" name="contact_email"
                                                        value="<?php echo htmlspecialchars($data['contact_email'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <h4 class="section-title"><i class="fas fa-clock me-2"></i> Business Hours</h4>
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <label for="working_hours" class="form-label">Working Hours</label>
                                                <input type="text" class="form-control" name="working_hours"
                                                    value="<?php echo htmlspecialchars($data['working_hours'] ?? ''); ?>">
                                            </div>
                                        </div>

                                        <h4 class="section-title"><i class="fas fa-share-alt me-2"></i> Social Media
                                            Links</h4>
                                        <div class="row mb-4">
                                            <!-- FACEBOOK -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Facebook</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-facebook-f social-icon text-primary"></i>
                                                    <input type="url" class="form-control" name="facebook"
                                                        value="<?php echo htmlspecialchars($data['facebook'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <!-- INSTAGRAM -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Instagram</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-instagram social-icon text-danger"></i>
                                                    <input type="url" class="form-control" name="instagram"
                                                        value="<?php echo htmlspecialchars($data['instagram'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <!-- YOUTUBE ADDED HERE -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">YouTube</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-youtube social-icon text-danger"></i>
                                                    <input type="url" class="form-control" name="youtube"
                                                        placeholder="https://youtube.com/..."
                                                        value="<?php echo htmlspecialchars($data['youtube'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <!-- TWITTER -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Twitter / X</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-twitter social-icon text-info"></i>
                                                    <input type="url" class="form-control" name="twitter"
                                                        value="<?php echo htmlspecialchars($data['twitter'] ?? ''); ?>">
                                                </div>
                                            </div>
                                            <!-- LINKEDIN -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">LinkedIn</label>
                                                <div class="input-with-icon">
                                                    <i class="fab fa-linkedin social-icon text-primary"></i>
                                                    <input type="url" class="form-control" name="linkdin"
                                                        value="<?php echo htmlspecialchars($data['linkdin'] ?? ''); ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <h4 class="section-title"><i class="fas fa-map-marked-alt me-2"></i> Location
                                            Map</h4>
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <label for="map" class="form-label">Google Map Embed Code</label>
                                                <textarea name="map" class="form-control"
                                                    rows="4"><?php echo htmlspecialchars($data['map'] ?? ''); ?></textarea>
                                            </div>
                                        </div>

                                        <div class="text-end mt-4">
                                            <button type="submit" class="btn btn-primary"
                                                style="background:#4e73df; padding:12px 25px;">
                                                <i class="fas fa-save me-2"></i> Update Contact Information
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
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            new bootstrap.Alert(alert).close();
        });
    }, 5000);
    </script>
</body>

</html>