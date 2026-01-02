<?php
//  Administrative Product Editing Module - edit_product.php
//  Allows administrators to modify existing food item details, 
//  including pricing, category assignment, and image replacement.

session_start();

// PATH CONFIGURATION: Navigates to the Root folder for core configuration and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  SECURITY GUARD:
//  Restricts access to administrators only. Unauthorized access attempts 
//  are redirected to the storefront.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

// DATA INITIALIZATION: Sanitize the target product identifier from the GET request.
$id = mysqli_real_escape_string($conn, $_GET['id']);

//  FORM PROCESSING LOGIC:
//  Handles the update of product details and conditional image replacement.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SECURITY: Sanitize all text-based inputs.
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $price  = mysqli_real_escape_string($conn, $_POST['price']);
    $cat_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $status = isset($_POST['is_active']) ? 1 : 0;

    //  CONDITIONAL IMAGE LOGIC:
    //  If a new file is uploaded, process it and update the image_url field.
    //  Otherwise, preserve the existing image reference.
    if (!empty($_FILES['image']['name'])) {
        $target_dir = BASE_PATH . "images/";
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $relative_path = "images/" . $file_name;
        $absolute_target = $target_dir . $file_name;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $absolute_target)) {
            $update_query = "UPDATE products SET name='$name', price='$price', 
                             category_id='$cat_id', is_active='$status', 
                             image_url='$relative_path' WHERE product_id='$id'";
        }
    } else {
        $update_query = "UPDATE products SET name='$name', price='$price', 
                         category_id='$cat_id', is_active='$status' WHERE product_id='$id'";
    }
    
    // EXECUTION: Commit changes and return to the dashboard with feedback.
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_dashboard.php?msg=updated");
        exit();
    }
}

//  PRE-POPULATION DATA:
//  Fetches existing product values and available categories to populate the form.
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$id'"));
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Food - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
    <style>
        /* COMPONENT STYLING: Balanced margins and modern container UI. */
        .edit-container { 
            background-color: #ffffff; 
            border-radius: 15px; 
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08); 
            padding: 35px;
            max-width: 650px;
        }
        .current-img { 
            width: 100px; height: 100px; object-fit: cover; 
            border-radius: 12px; border: 2px solid #eee;
        }
        .form-label { color: #495057; font-weight: 700; font-size: 0.9rem; }
    </style>
</head>
<body style="background-color: #f4f7f6;">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="edit-container mx-auto">
            <h2 class="fw-bold mb-4 d-flex align-items-center">
                <span class="me-2">📝</span> Edit: <?php echo $product['name']; ?>
            </h2>
            
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="form-label">Food Name</label>
                    <input type="text" name="name" class="form-control py-2 shadow-sm" 
                           value="<?php echo $product['name']; ?>" required>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Price (RM)</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-white">RM</span>
                            <input type="number" step="0.01" name="price" class="form-control py-2" 
                                   value="<?php echo $product['price']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3 mt-md-0">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select py-2 shadow-sm">
                            <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['category_id']; ?>" 
                                    <?php echo ($cat['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="mb-4 p-3 rounded-3 bg-light border">
                    <label class="form-label d-block mb-3">Product Image Management</label>
                    <div class="d-flex align-items-center gap-4">
                        <div class="text-center">
                            <img src="<?php echo BASE_URL . $product['image_url']; ?>" class="current-img shadow-sm mb-1">
                            <div class="small text-muted fw-bold">Current</div>
                        </div>
                        <div class="flex-grow-1">
                            <label class="small text-muted mb-2 fw-bold">Replace Image (Optional)</label>
                            <input type="file" name="image" class="form-control shadow-sm" accept="image/*">
                        </div>
                    </div>
                </div>

                <div class="form-check form-switch mb-5 d-flex align-items-center gap-2">
                    <input class="form-check-input mt-0" type="checkbox" role="switch" id="activeSwitch" 
                           name="is_active" <?php echo $product['is_active'] ? 'checked' : ''; ?> 
                           style="width: 45px; height: 23px; cursor: pointer;">
                    <label class="form-check-label fw-bold text-dark pt-1" for="activeSwitch">Active in Menu</label>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-primary w-100 py-3 shadow fw-bold">SAVE CHANGES</button>
                    </div>
                    <div class="col-sm-6">
                        <a href="admin_dashboard.php" class="btn btn-outline-secondary w-100 py-3 fw-bold">CANCEL</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>