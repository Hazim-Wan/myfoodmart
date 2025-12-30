<?php
session_start();
include 'db_connect.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    $query = "SELECT p.*, c.name as cat_name FROM products p 
              JOIN categories c ON p.category_id = c.category_id 
              WHERE p.product_id = '$id'";
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    if (!$product) { die("Product not found."); }
} else { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #ffffff;">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="row bg-white p-4 rounded shadow-sm border mx-auto align-items-center" style="max-width: 900px;">
            <div class="col-md-6 text-center">
                <img src="<?php echo $product['image_url']; ?>" 
                     class="img-fluid rounded border shadow-sm" 
                     alt="Product Image"
                     style="width: 100%; height: 400px; object-fit: cover;">
            </div>
            <div class="col-md-6">
                <span class="badge bg-success mb-2"><?php echo $product['cat_name']; ?></span>
                <h1 class="fw-bold" style="color: #2c3e50;"><?php echo $product['name']; ?></h1>
                <p class="text-muted fs-5"><?php echo $product['description']; ?></p>
                <h3 class="my-4 fw-bold text-success">RM <?php echo number_format($product['price'], 2); ?></h3>
                
                <form action="cart_action.php" method="POST" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <div class="d-flex align-items-center mb-3">
                        <label class="me-3 fw-bold">Quantity:</label>
                        <input type="number" name="quantity" class="form-control border-success" value="1" min="1" style="max-width: 80px;">
                    </div>
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm">ADD TO CART</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>