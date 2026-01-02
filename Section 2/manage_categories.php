<?php
//  Administrative Category Management - manage_categories.php
//  Allows administrators to create and remove logical food groupings (categories).
//  Implements strict role-based access control and input sanitization.

session_start();

// PATH CONFIGURATION: Navigates to the Root folder for core configuration and database connection.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// ACCESS CONTROL: Restricts page access to users with administrative privileges.
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied.");
}

$message = "";

//  CATEGORY CREATION LOGIC
//  Processes POST requests to add new categories to the database.
if (isset($_POST['add_category'])) {
    // SECURITY: Sanitize user input to prevent SQL Injection.
    $cat_name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    $cat_desc = mysqli_real_escape_string($conn, $_POST['cat_desc']);
    
    $insert_query = "INSERT INTO categories (name, description) VALUES ('$cat_name', '$cat_desc')";
    
    if (mysqli_query($conn, $insert_query)) {
        $message = "Category added successfully!";
    }
}

/**
 * CATEGORY DELETION LOGIC
 * Processes GET requests to remove categories based on a specific ID.
 */
if (isset($_GET['delete'])) {
    // SECURITY: Cast ID to integer to prevent malicious input.
    $id = (int)$_GET['delete'];
    
    $delete_query = "DELETE FROM categories WHERE category_id = $id";
    mysqli_query($conn, $delete_query);
    
    // REDIRECTION: Refresh the page to update the displayed list.
    header("Location: manage_categories.php");
    exit();
}

/**
 * DATA RETRIEVAL
 * Fetches all existing categories sorted alphabetically by name.
 */
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-start border-success border-4 ps-3">
            <h2 class="fw-bold text-dark mb-0">Category Management</h2>
            <a href="<?php echo BASE_URL; ?>Section 5/admin_dashboard.php" class="btn btn-secondary rounded-pill px-4 fw-bold shadow-sm">
                Back to Products
            </a>
        </div>

        <?php if($message): ?>
            <div class="alert alert-success shadow-sm"><?php echo $message; ?></div>
        <?php endif; ?>

        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>