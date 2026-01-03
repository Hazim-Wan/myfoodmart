<?php
// Administrative Product Dashboard - admin_dashboard.php
// Provides a centralized interface for managing the food catalog.
// Supports CRUD operations (Create, Read, Update, Delete) for products.

session_start();

// PATH CONFIGURATION: Accesses global constants and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// AUTHENTICATION GUARD:
// Restricts access solely to administrators. Unauthorized users are 
// rerouted to the storefront home page.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

// DELETION LOGIC:
// Processes GET requests to remove products from the inventory.
// SECURITY: Sanitizes the ID parameter to prevent SQL Injection.
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $delete_query = "DELETE FROM products WHERE product_id = '$id'";
    
    if (mysqli_query($conn, $delete_query)) {
        header("Location: admin_dashboard.php?msg=deleted");
        exit();
    }
}

// DATA RETRIEVAL:
// Fetches all products joined with their category names for better display.
$query = "SELECT p.*, c.name as cat_name 
          FROM products p 
          JOIN categories c ON p.category_id = c.category_id 
          ORDER BY p.product_id DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="bg-white p-4 rounded-4 shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                <h2 class="fw-bold text-dark mb-0">📦 Product Management</h2>
                <div class="d-flex gap-2">
                    <a href="admin_categories.php" class="btn btn-outline-dark fw-bold rounded-pill px-4">📁 Manage Categories</a>
                    <a href="add_product.php" class="btn btn-success fw-bold rounded-pill px-4 shadow-sm">+ Add New Product</a>
                </div>
            </div>

            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert alert-success py-2 small">Product removed successfully.</div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="ps-3 text-muted">#<?php echo $row['product_id']; ?></td>
                            <td>
                                <img src="<?php echo BASE_URL . $row['image_url']; ?>" 
                                     alt="Food" 
                                     style="width: 55px; height: 55px; object-fit: cover;" 
                                     class="rounded border shadow-sm">
                            </td>
                            <td class="fw-bold text-dark"><?php echo $row['name']; ?></td>
                            <td><span class="badge bg-info text-dark px-3 py-2"><?php echo $row['cat_name']; ?></span></td>
                            <td class="text-success fw-bold">RM <?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <span class="badge rounded-pill <?php echo $row['is_active'] ? 'bg-success' : 'bg-secondary'; ?> px-3">
                                    <?php echo $row['is_active'] ? 'ACTIVE' : 'INACTIVE'; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" 
                                       class="btn btn-outline-primary btn-sm px-3 fw-bold">Edit</a>
                                    <a href="?delete=<?php echo $row['product_id']; ?>" 
                                       class="btn btn-outline-danger btn-sm px-3 fw-bold" 
                                       onclick="return confirm('Are you sure you want to delete this meal?')">Delete</a>
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