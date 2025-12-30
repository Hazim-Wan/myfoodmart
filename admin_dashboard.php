<?php
session_start();
include 'db_connect.php';

// Security: Redirect if not logged in OR not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Logic to delete a product
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE product_id = '$id'");
    header("Location: admin_dashboard.php?msg=deleted");
    exit();
}

$result = mysqli_query($conn, "SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id = c.category_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="bg-white p-4 rounded shadow-sm border">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">📦 Product Management</h2>
                <a href="add_product.php" class="btn btn-success fw-bold">+ Add New Product</a>
            </div>

            <?php if(isset($_GET['msg'])): ?>
                <div class="alert alert-success alert-dismissible fade show small py-2" role="alert">
                    Operation completed successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price (RM)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?php echo $row['product_id']; ?></td>
                            <td class="fw-bold"><?php echo $row['name']; ?></td>
                            <td><span class="badge bg-info text-dark px-2"><?php echo $row['cat_name']; ?></span></td>
                            <td class="fw-bold text-success">RM <?php echo number_format($row['price'], 2); ?></td>
                            <td>
                                <?php if($row['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" 
                                       class="btn btn-primary btn-sm px-3 shadow-sm">Edit</a>
                                    
                                    <a href="?delete=<?php echo $row['product_id']; ?>" 
                                       class="btn btn-danger btn-sm px-3 shadow-sm" 
                                       onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
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