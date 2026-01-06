<?php
// Administrative Product Creation Module - add_product.php
// Facilitates the addition of new food items to the database.
// Handles secure file uploads for images and maintains data integrity 
// through input sanitization and standardized pathing.

session_start();

// PATH CONFIGURATION: Accesses the global configuration and path constants.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// SECURITY GUARD:
// Restricts access solely to users with the 'admin' role. 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

// FORM PROCESSING LOGIC:
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DATA CAPTURE: Sanitize text inputs.
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);
    $price    = mysqli_real_escape_string($conn, $_POST['price']);
    $category = mysqli_real_escape_string($conn, $_POST['category_id']);
    
    // FILE UPLOAD HANDLING:
    // 1. Uses BASE_PATH to ensure images land in the shared 'Root/images' folder.
    // 2. This ensures the homepage (index.php) can find the file.
    $target_dir = BASE_PATH . "images/";
    
    if (!is_dir($target_dir)) { 
        mkdir($target_dir, 0777, true); 
    }
    
    // Generate a unique name to prevent overwriting files with the same name.
    $file_ext        = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $file_name       = time() . "_" . uniqid() . "." . $file_ext;
    
    // Path for database: relative to the Root.
    $relative_path   = "images/" . $file_name; 
    
    // Path for moving the file: absolute server path.
    $absolute_target = $target_dir . $file_name;

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $absolute_target)) {
        
        // DATABASE INSERTION:
        // Stores the $relative_path so the homepage can append it to BASE_URL.
        $sql = "INSERT INTO products (name, description, price, category_id, image_url, is_active) 
                VALUES ('$name', '$desc', '$price', '$category', '$relative_path', 1)";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: admin_dashboard.php?success=1");
            exit();
        }
    } else {
        $error = "File upload failed. Check folder permissions.";
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
        <div class="mb-4">
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-bold">
                ← Back to Dashboard
            </a>
        </div>

        <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px; border-radius: 15px;">
            <div class="card-header bg-dark text-white fw-bold py-3 text-center">➕ Add New Meal</div>
            <div class="card-body p-4">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Food Name</label>
                        <input type="text" name="name" class="form-control border-success" placeholder="e.g. Laksa Sarawak" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select border-success" required>
                            <?php 
                            $cats = mysqli_query($conn, "SELECT * FROM categories");
                            while($c = mysqli_fetch_assoc($cats)) {
                                echo "<option value='".$c['category_id']."'>".$c['name']."</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Price (RM)</label>
                        <input type="number" step="0.01" name="price" class="form-control border-success" placeholder="0.00" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control border-success" rows="3" placeholder="Describe the ingredients..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Product Image</label>
                        <input type="file" name="image" class="form-control border-success" accept="image/*" required>
                        <div class="form-text text-muted">Image will be saved to Root/images/ automatically.</div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm rounded-pill">
                            UPLOAD TO MENU
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>