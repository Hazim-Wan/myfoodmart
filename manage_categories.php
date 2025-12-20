<?php
session_start();
include 'db_connect.php';

// Access Control: Ensure only Admins can access this page [cite: 318, 405]
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Access Denied: You do not have permission to view this page.");
}

$message = "";

// Logic to Add a Category [cite: 403, 407]
if (isset($_POST['add_category'])) {
    $cat_name = mysqli_real_escape_string($conn, $_POST['cat_name']);
    $cat_desc = mysqli_real_escape_string($conn, $_POST['cat_desc']);
    
    $query = "INSERT INTO categories (name, description) VALUES ('$cat_name', '$cat_desc')";
    if (mysqli_query($conn, $query)) {
        $message = "Category added successfully!";
    }
}

// Logic to Delete a Category [cite: 403]
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE category_id = $id");
    header("Location: manage_categories.php");
    exit();
}

// Fetch all categories [cite: 409]
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories - MyFoodMart Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Category Management</h2>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Products</a>
        </div>

        <?php if($message) echo "<div class='alert alert-success'>$message</div>"; ?>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">Add New Category</div>
            <div class="card-body">
                <form action="manage_categories.php" method="POST" class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="cat_name" class="form-control" placeholder="Category Name (e.g., Drinks)" required>
                    </div>
                    <div class="col-md-5">
                        <input type="text" name="cat_desc" class="form-control" placeholder="Description">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" name="add_category" class="btn btn-success w-100">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped shadow-sm bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                <tr>
                    <td><?php echo $cat['category_id']; ?></td>
                    <td><?php echo $cat['name']; ?></td>
                    <td><?php echo $cat['description']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $cat['category_id']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Delete this category? Products in this category may become orphaned.')">
                           Delete
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>