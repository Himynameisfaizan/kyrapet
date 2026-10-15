<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "db-conn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-product'])) {
    // Get all POST data
    $pro_id = intval($_POST['pro_id']);
    $db_id = intval($_POST['id'] ?? 0); // Primary key id for product_images relation
    $pro_name = mysqli_real_escape_string($conn, $_POST['pro_name'] ?? '');$brand_name = mysqli_real_escape_string($conn,$_POST['brand_name'] ?? '');
    $pro_cate = intval($_POST['pro_cate'] ?? 0);
    $pro_sub_cate = intval($_POST['pro_sub_cate'] ?? 0);
    $short_desc = mysqli_real_escape_string($conn, $_POST['short_desc'] ?? '');$description = mysqli_real_escape_string($conn,$_POST['pro_desc'] ?? '');
    
    // Breed Specs Fields
    $gender = mysqli_real_escape_string($conn, $_POST['gender'] ?? 'Male / Female');$age = mysqli_real_escape_string($conn,$_POST['age'] ?? '8 Weeks');
    $size = mysqli_real_escape_string($conn, $_POST['size'] ?? 'Medium to Large');$temper = mysqli_real_escape_string($conn,$_POST['temper'] ?? 'Friendly, playful, loyal');

    $stock = mysqli_real_escape_string($conn, $_POST['stock'] ?? '');$meta_title = mysqli_real_escape_string($conn,$_POST['meta_title'] ?? '');
    $meta_desc = mysqli_real_escape_string($conn, $_POST['meta_desc'] ?? '');$meta_key = mysqli_real_escape_string($conn,$_POST['meta_key'] ?? '');
    
    // Schema Variable
    $schema_markup = mysqli_real_escape_string($conn, trim($_POST['schema_markup'] ?? ''));$status = intval($_POST['status'] ?? 0);$slug_url = mysqli_real_escape_string($conn,$_POST['slug_url'] ?? '');

    // Handle Main Featured Image
    $upload_dir = "assets/img/uploads/";
    $pro_img = trim($_POST['old_img'] ?? '');

    if (!empty($_FILES['pro_img']['name']) &&$_FILES['pro_img']['error'] === 0) {
        $file_name =$_FILES['pro_img']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
        $destination = $upload_dir .$new_file_name;

        if (move_uploaded_file($_FILES['pro_img']['tmp_name'],$destination)) {
            $pro_img =$new_file_name;
        }
    }

    // Update query with Breed Specs (gender, age, size, temper)
    $query = "UPDATE products SET 
                pro_name = '$pro_name',
                brand_name = '$brand_name',
                pro_cate = '$pro_cate',
                pro_sub_cate = '$pro_sub_cate',
                short_desc = '$short_desc',
                description = '$description',
                gender = '$gender',
                age = '$age',
                size = '$size',
                temper = '$temper',
                stock = '$stock',
                pro_img = '$pro_img',
                meta_title = '$meta_title',
                meta_desc = '$meta_desc',
                meta_key = '$meta_key',
                schema_markup = '$schema_markup',
                status = '$status',
                slug_url = '$slug_url'
              WHERE pro_id = '$pro_id'";

    if (mysqli_query($conn,$query)) {
        // Handle Multi-Angle Gallery Images Upload if added during edit
        if (isset($_FILES['gallery_images']['name']) && !empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as$gk => $g_tmp) {$g_name = $_FILES['gallery_images']['name'][$gk];
                if (!empty($g_name)) {
                    $g_ext = pathinfo($g_name, PATHINFO_EXTENSION);
                    $g_new_name = time() . '_gal_' . uniqid() . '.' . $g_ext;
                    if (move_uploaded_file($g_tmp, $upload_dir .$g_new_name)) {
                        mysqli_query($conn, "INSERT INTO `product_images` (`product_id`, `image_path`) VALUES ('$db_id', '$g_new_name')");
                    }
                }
            }
        }

        // Success redirect
        header("Location: edit_products.php?edit_product_details=$pro_id&success=1");
        exit();
    } else {
        // Error redirect
        header("Location: edit_products.php?edit_product_details=$pro_id&error=1");
        exit();
    }
} else {
    header("Location: show-products.php");
    exit();
}
?>