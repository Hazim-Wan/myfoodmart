<?php
session_start();
include 'db_connect.php';

// Security check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Handle the update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $cat_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $status = isset($_POST['is_active']) ? 1 : 0;

    $update_query = "UPDATE products SET name='$name', price='$price', category_id='$cat_id', is_active='$status' WHERE product_id='$id'";
    
    if (mysqli_query($conn, $update_query)) {
        header("Location: admin_dashboard.php?msg=updated");
        exit();
    }
}

// Fetch current product data
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$id'"));
$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Food - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="card p-4 mx-auto shadow-sm border-0" style="max-width: 600px; border-top: 5px solid #0d6efd;">
            <h2 class="fw-bold mb-4">✏️ Edit: <?php echo $product['name']; ?></h2>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold">Food Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Price (RM)</label>
                        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category_id" class="form-select">
                            <?php while($cat = mysqli_fetch_assoc($categories)): ?>
                                <option value="<?php echo $cat['category_id']; ?>" <?php echo ($cat['category_id'] == $product['category_id']) ? 'selected' : ''; ?>>
                                    <?php echo $cat['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_active" <?php echo $product['is_active'] ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-bold">Active in Menu</label>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">SAVE CHANGES</button>
                    <a href="admin_dashboard.php" class="btn btn-outline-secondary w-100 fw-bold py-2">CANCEL</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>