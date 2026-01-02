<?php
//  Administrative Product Creation Module - add_product.php
//  Facilitates the addition of new food items to the database.
//  Handles secure file uploads for images and maintains data integrity 
//  through input sanitization.

session_start();

// PATH CONFIGURATION: Accesses the global configuration and path constants.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 


//  SECURITY GUARD:
//  Restricts access solely to users with the 'admin' role. 
//  Redirects unauthorized users back to the storefront.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

//  FORM PROCESSING LOGIC:
//  Handles the POST request for new product details and file uploads.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DATA CAPTURE: Sanitize text inputs to prevent SQL injection.
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);
    $price    = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category_id']);
    
    
    //  FILE UPLOAD HANDLING:
    //  1. Defines the target directory within the Root folder.
    //  2. Generates a unique filename using time() to prevent overwriting existing files.
    //  3. Moves the uploaded file from temporary storage to the permanent images folder.
    $target_dir = dirname(__DIR__) . "/images/";
    if (!is_dir($target_dir)) { 
        mkdir($target_dir, 0777, true); 
    }
    
    $file_name       = time() . "_" . basename($_FILES["image"]["name"]);
    $relative_path   = "images/" . $file_name; // Store this path in the database.
    $absolute_target = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $absolute_target)) {
        
        //  DATABASE INSERTION:
        //  Commits the new product record to the 'products' table.
        //  Default status is set to 1 (active).
        $sql = "INSERT INTO products (name, description, price, category_id, image_url, is_active) 
                VALUES ('$name', '$desc', '$price', '$category', '$relative_path', 1)";
        
        if (mysqli_query($conn, $sql)) {
            // REDIRECTION: Route back to dashboard with success status.
            header("Location: admin_dashboard.php?success=1");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Product - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5">
        <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px; border-radius: 15px;">
            <div class="card-header bg-dark text-white fw-bold py-3">➕ Add New Meal</div>
            <div class="card-body p-4">
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Food Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Laksa Sarawak" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select" required>
                            <?php 
                            // DYNAMIC CATEGORY LOADING: Fetches available food categories from DB.
                            $cats = mysqli_query($conn, "SELECT * FROM categories");
                            while($c = mysqli_fetch_assoc($cats)) {
                                echo "<option value='".$c['category_id']."'>".$c['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Price (RM)</label>
                        <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Describe the ingredients or taste..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">UPLOAD TO MENU</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>