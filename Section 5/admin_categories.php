<?php
//  Administrative Category Management - admin_categories.php
//  Provides an interface for managing food categories.
//  Supports dynamic retrieval and deletion of category records.

session_start();

// PATH CONFIGURATION: Accesses global constants and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// AUTHENTICATION GUARD: Restricts access solely to administrators.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

// DELETION LOGIC: Processes removal of categories based on ID.
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM categories WHERE category_id = '$id'";
    
    if (mysqli_query($conn, $delete_query)) {
        header("Location: admin_categories.php?msg=deleted");
        exit();
    }
}

// DATA RETRIEVAL: Fetches all categories for dynamic display.
$query = "SELECT * FROM categories ORDER BY category_id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="mb-3">
            <a href="admin_dashboard.php" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-bold">
                ← Back to Product Dashboard
            </a>
        </div>

        <div class="bg-white p-4 rounded-4 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h2 class="fw-bold text-dark mb-0">📁 Category Management</h2>
                <a href="add_category.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">+ Add New Category</a>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-success py-2 small">Category successfully removed.</div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-3 text-muted">#<?php echo $row['category_id']; ?></td>
                            <td class="fw-bold text-dark"><?php echo $row['name']; ?></td>
                            <td class="small text-muted"><?php echo $row['description']; ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_category.php?id=<?php echo $row['category_id']; ?>" 
                                       class="btn btn-outline-primary btn-sm px-3 fw-bold">Edit</a>
                                    <a href="?delete=<?php echo $row['category_id']; ?>" 
                                       class="btn btn-outline-danger btn-sm px-3 fw-bold" 
                                       onclick="return confirm('Deleting this category may affect products assigned to it. Continue?')">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>