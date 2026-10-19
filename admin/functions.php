<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "db-conn.php";

// Helper function to extract 11-character YouTube Video Code from any URL/Shorts link
if (!function_exists('extractBreedYoutubeId')) {
    function extractBreedYoutubeId($url) {
        $url = trim($url ?? '');
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/',$url)) {
            return $url;
        }
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url,$matches)) {
            return $matches[1];
        }
        return '';
    }
}

if (isset($_POST["add-categories"])) {
    $fileName = '';
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {$fileTmpPath = $_FILES['imageUpload']['tmp_name'];$fileName = $_FILES['imageUpload']['name'];$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if (in_array($fileExtension,$allowedExtensions)) {
            $newFileName = uniqid('img_', true) . '.' .$fileExtension;
            $uploadDir = 'uploads/category/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            move_uploaded_file($fileTmpPath, $uploadDir .$newFileName);
            $fileName =$newFileName;
        }
    }

    $cate_id = mt_rand(11111, 99999);$cate_name = mysqli_real_escape_string($conn,$_POST["cate_name"]);
    $meta_title = mysqli_real_escape_string($conn,$_POST["meta_title"]);
    $meta_key = mysqli_real_escape_string($conn,$_POST["meta_key"]);
    $meta_desc = mysqli_real_escape_string($conn,$_POST["meta_desc"]);
    $schema_markup = mysqli_real_escape_string($conn, trim($_POST["schema_markup"] ?? ''));
    $status = mysqli_real_escape_string($conn,$_POST["status"] ?? '1');
    $slug_url = strtolower(str_replace(" ", "-", $cate_name));

    $sql = "INSERT INTO `categories` (`cate_id`, `categories`, `meta_title`, `meta_desc`, `meta_key`, `schema_markup`, `image`, `slug_url`, `status`, `added_on`) 
            VALUES ('$cate_id', '$cate_name', '$meta_title', '$meta_desc', '$meta_key', '$schema_markup', '$fileName', '$slug_url', '$status', NOW())";
    
    if (mysqli_query($conn,$sql)) {
        echo "<script>alert('Category Inserted Successfully!'); window.location.href = 'view-categories.php';</script>";
    } else {
        echo "SQL Error: " . mysqli_error($conn);
    }
}

if (isset($_POST["add-sub-categories"])) {
    $uploadedImage = ''; 
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] === UPLOAD_ERR_OK) {$fileTmpPath = $_FILES['imageUpload']['tmp_name'];$fileName = $_FILES['imageUpload']['name'];$fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));$allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        if (in_array($fileExtension,$allowedExtensions)) {
            $newFileName = uniqid('img_', true) . '.' .$fileExtension;
            $uploadDir = 'uploads/sub-category/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0777, true); }
            move_uploaded_file($fileTmpPath, $uploadDir .$newFileName);
            $uploadedImage =$newFileName;
        }
    }

    $cate_id       = mt_rand(11111, 99999);
    $parent_id     = mysqli_real_escape_string($conn, $_POST['parent_id']);$cate_name     = mysqli_real_escape_string($conn,$_POST["cate_name"]);
    $meta_title    = mysqli_real_escape_string($conn,$_POST["meta_title"]);
    $meta_key      = mysqli_real_escape_string($conn,$_POST["meta_key"]);
    $meta_desc     = mysqli_real_escape_string($conn,$_POST["meta_desc"]);
    $schema_markup = mysqli_real_escape_string($conn, trim($_POST["schema_markup"] ?? ''));
    $status        = mysqli_real_escape_string($conn,$_POST["status"] ?? '1');
    $added_on      = date('M d, Y');
    $slug_url      = strtolower(str_replace(" ", "-", $cate_name));

    $sql = "INSERT INTO `sub_categories` (`parent_id`, `cate_id`, `categories`, `meta_title`, `meta_desc`, `meta_key`, `schema_markup`, `sub_cat_img`, `slug_url`, `status`, `added_on`) 
            VALUES ('$parent_id', '$cate_id', '$cate_name', '$meta_title', '$meta_desc', '$meta_key', '$schema_markup', '$uploadedImage', '$slug_url', '$status', '$added_on')";

    if (mysqli_query($conn,$sql)) {
        echo "<script>alert('Sub-Category Inserted Successfully!'); window.location.href = 'view-sub-categories.php';</script>";
    } else {
        echo "Error inserting record: " . mysqli_error($conn);
    }
}

function get_Category()
{
    include "db-conn.php";

    $sql = "SELECT * FROM `categories` ORDER BY id DESC";
    $check = mysqli_query($conn, $sql);$sno = 1;
    while ($result = mysqli_fetch_assoc($check)) {
        echo $output = "<tr>
        <td>" . $sno++ . "</td>
        <td>" . $result['cate_id'] . "</td>
        <td>" . $result['categories'] . "</td>
        <td>" . $result['slug_url'] . "</td>
        <td>" . $result['status'] . "</td>
        <td><a href='delete-category.php?id=" . $result['cate_id'] . "' onclick='return confirm(\"Are you sure you want to delete this category?\")'><i class='fa-solid fa-trash text-danger fs-4'></i></a></td>
        <td><a href='edit_category.php?id=" . $result['cate_id'] . "'><i class='fa-solid fa-file-pen fs-4'></i></a></td>
        <td>" . $result['added_on'] . "</td>
        </tr>";
    }
}

if (isset($_POST["add-product"])) {
    $pro_id        = mt_rand(11111, 99999);
    $pro_name      = mysqli_real_escape_string($conn, $_POST['pro_name']);$brand_name    = mysqli_real_escape_string($conn,$_POST['brand_name']);
    $pro_cate      = mysqli_real_escape_string($conn, $_POST['pro_cate']);$pro_sub_cate  = '0';
    $short_desc    = mysqli_real_escape_string($conn, $_POST['short_desc']);$description   = mysqli_real_escape_string($conn,$_POST['pro_desc']);
    $gender        = mysqli_real_escape_string($conn, $_POST['gender'] ?? 'Male / Female');$age           = mysqli_real_escape_string($conn,$_POST['age'] ?? '8 Weeks');
    $size          = mysqli_real_escape_string($conn, $_POST['size'] ?? 'Medium to Large');$temper        = mysqli_real_escape_string($conn,$_POST['temper'] ?? 'Friendly, playful, loyal');
    $new_arrival   = '0';$trending      = '0';
    $mrp           = '0';$selling_price = '0';
    $stock         = mysqli_real_escape_string($conn, $_POST['stock']);$status        = mysqli_real_escape_string($conn,$_POST['status']);

    $folder = 'assets/img/uploads/';
    if (!is_dir($folder)) { mkdir($folder, 0755, true); }

    $filename = '';
    if (isset($_FILES['pro_img']['name'][0]) && !empty($_FILES['pro_img']['name'][0])) {
        $filename =$_FILES['pro_img']['name'][0];
        $tempname =$_FILES['pro_img']['tmp_name'][0];
        move_uploaded_file($tempname, $folder .$filename);
    }

    $meta_title    = mysqli_real_escape_string($conn, $_POST['meta_title']);$meta_key      = mysqli_real_escape_string($conn,$_POST["meta_key"]);
    $meta_desc     = mysqli_real_escape_string($conn,$_POST["meta_desc"]);
    $schema_markup = mysqli_real_escape_string($conn, trim($_POST['schema_markup']));
    $added_on      = date('M d, Y');$slug_url      = mysqli_real_escape_string($conn,$_POST['slug_url']);

    $sql = "INSERT INTO `products` (`pro_id`, `pro_name`, `brand_name`, `pro_cate`, `pro_sub_cate`, `short_desc`, `description`, `gender`, `age`, `size`, `temper`, `new_arrival`, `trending`, `mrp`, `selling_price`, `stock`, `pro_img`, `status`, `slug_url`, `meta_title`, `meta_desc`, `meta_key`, `schema_markup`, `added_on`) 
            VALUES ('$pro_id', '$pro_name', '$brand_name', '$pro_cate', '$pro_sub_cate', '$short_desc', '$description', '$gender', '$age', '$size', '$temper', '$new_arrival', '$trending', '$mrp', '$selling_price', '$stock', '$filename', '$status', '$slug_url', '$meta_title', '$meta_desc', '$meta_key', '$schema_markup', '$added_on')";

    $check = mysqli_query($conn,$sql);
    
    if ($check) {
        $last_db_id = mysqli_insert_id($conn);
        
        // 1. Handle Multi-Angle Gallery Images Upload
        if (isset($_FILES['gallery_images']['name']) && !empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as$gk => $g_tmp) {$g_name = $_FILES['gallery_images']['name'][$gk];
                if (!empty($g_name)) {
                    move_uploaded_file($g_tmp, $folder .$g_name);
                    mysqli_query($conn, "INSERT INTO `product_images` (`product_id`, `image_path`) VALUES ('$last_db_id', '$g_name')");
                }
            }
        }

        // 2. Handle Breed-Specific Video Reviews
        mysqli_query($conn, "CREATE TABLE IF NOT EXISTS `product_videos` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `product_id` int(11) NOT NULL,
          `video_title` varchar(255) NOT NULL,
          `youtube_url` varchar(255) NOT NULL,
          `video_code` varchar(50) NOT NULL,
          `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
          PRIMARY KEY (`id`),
          KEY `product_id` (`product_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        if (!empty($_POST['video_urls']) && is_array($_POST['video_urls'])) {
            foreach ($_POST['video_urls'] as$vk => $v_url) {$v_url = trim($v_url);$v_title = trim($_POST['video_titles'][$vk] ?? '');
                if ($v_url !== '') {
                    $v_code = extractBreedYoutubeId($v_url);
                    if ($v_code !== '') {
                        if ($v_title === '')$v_title = $pro_name . ' Customer Review';$v_title_esc = mysqli_real_escape_string($conn,$v_title);
                        $v_url_esc   = mysqli_real_escape_string($conn, $v_url);$v_code_esc  = mysqli_real_escape_string($conn,$v_code);
                        mysqli_query($conn, "INSERT INTO `product_videos` (`product_id`, `video_title`, `youtube_url`, `video_code`) VALUES ('$last_db_id', '$v_title_esc', '$v_url_esc', '$v_code_esc')");
                    }
                }
            }
        }

        echo "<script>alert('Breed Added Successfully!'); window.location.href = 'show-products.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

function get_Sub_Category()
{
    include "db-conn.php";

    $searchQuery = "";
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);$searchQuery = " WHERE `categories` LIKE '%$search%' OR `slug_url` LIKE '%$search%' ";
    }

    $sql = "SELECT * FROM `sub_categories` $searchQuery ORDER BY id DESC";
    $check = mysqli_query($conn, $sql);$sno = 1;

    if ($check && mysqli_num_rows($check) > 0) {
        while ($result = mysqli_fetch_assoc($check)) {$parent_id = $result['parent_id'];$sql2 = "SELECT `categories` FROM `categories` WHERE `cate_id` = $parent_id";
            $check2 = mysqli_query($conn, $sql2);$parent_cate = "";

            if ($check2 && mysqli_num_rows($check2) > 0) {
                $parent = mysqli_fetch_assoc($check2);
                $parent_cate =$parent['categories'];
            }

            echo "<tr>
                    <td>" . $sno++ . "</td>
                    <td>" . htmlspecialchars($result['cate_id']) . "</td>
                    <td>" . htmlspecialchars(ucwords($result['categories'])) . "</td>
                    <td>" . htmlspecialchars($parent_cate) . "</td>
                    <td>" . htmlspecialchars($result['slug_url']) . "</td>
                    <td>" . htmlspecialchars($result['status']) . "</td>
                    <td>
                            <a href='delete_sub_category.php?id=" . $result['cate_id'] . "' 
                            onclick='return confirm(\"Are you sure you want to delete this sub-category?\")' 
                            class='btn btn-danger'>Delete</a>
                    </td>
                    <td><a href='edit_sub_category.php?id=" . $result['cate_id'] . "'><i class='fa-solid fa-file-pen fs-4'></i></a></td>
                    <td>" . htmlspecialchars($result['added_on']) . "</td>
                </tr>";
        }
    } else {
        echo "<tr><td colspan='9' class='text-center'>No subcategories found</td></tr>";
    }
}

if (isset($_POST['cate_id'])) {$p_id = $_POST['cate_id'];$sql = "SELECT * FROM `sub_categories` where `parent_id` = '$p_id' ORDER BY id DESC";
    $check = mysqli_query($conn,$sql);
    ?>
    <option value="">Select</option>
<?php
    while ($result = mysqli_fetch_assoc($check)) {
        echo "<option value=" . $result['cate_id'] . ">" . $result['categories'] . "</option>";
    }
}

function SlugUrl($string)
{
    $slug = preg_replace('/[^a-zA-Z0-9 -]/', '',$string);
    $slug = str_replace('', '-',$slug);
    $slug = strtolower($slug);
    return ($slug);
}

function get_category_by_id($cat_id)
{
    global $conn;
    $cat_id = mysqli_real_escape_string($conn, $cat_id);$sql = "SELECT * FROM `categories` WHERE cate_id = '$cat_id' LIMIT 1";
    $result = mysqli_query($conn,$sql);
    return mysqli_fetch_assoc($result);
}

function get_sub_category_by_id($cat_id)
{
    global $conn;
    $cat_id = mysqli_real_escape_string($conn, $cat_id);$sql = "SELECT * FROM `sub_categories` WHERE cate_id = '$cat_id' LIMIT 1";
    $result = mysqli_query($conn,$sql);
    return mysqli_fetch_assoc($result);
}
?>