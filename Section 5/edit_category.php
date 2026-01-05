<?php
//  Administrative Category Creator - add_category.php
//  Enables administrators to expand the food catalog by adding new categories.
//  Implements data sanitization and modular pathing for system reliability.

session_start();

// PATH CONFIGURATION: Navigates to the Root directory for core configuration.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// AUTHENTICATION GUARD: Restricts access solely to administrators.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

$error = "";

// CATEGORY PROCESSING LOGIC: Executes when the administrator submits the form.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SECURITY: Sanitize inputs to prevent SQL Injection vulnerabilities.
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // DATABASE INSERTION: Creates a new record in the categories table.
    $query = "INSERT INTO categories (name, description, created_at) 
              VALUES ('$name', '$description', NOW())";

    if (mysqli_query($conn, $query)) {
        // SUCCESS: Redirects back to the Category Management list.
        header("Location: admin_categories.php?msg=added");
        exit();
    } else {
        $error = "Database Error: Could not add category.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Category - MyFoodMart Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5">
        <div class="mb-4">
            <a href="admin_categories.php" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-bold">
                ← Back to Categories
            </a>
        </div>

        <div class="card p-4 mx-auto shadow-sm border-0 rounded-4" style="max-width: 600px;">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dark">➕ Add New Category</h2>
                <p class="text-muted">Create a new group for your food products.</p>
            </div>

            <?php if($error): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Category Name</label>
                    <input type="text" name="name" class="form-control border-primary" 
                           placeholder="e.g., Traditional Desserts" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" class="form-control border-primary" rows="3" 
                              placeholder="Briefly describe what this category includes..." required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm rounded-pill">
                    SAVE CATEGORY
                </button>
            </form>
        </div>
    </div>
</body>
</html>