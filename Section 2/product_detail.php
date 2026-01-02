<?php
//  Product Specification Module - product_detail.php
//  Responsible for displaying comprehensive information about a specific food item.
//  Includes category details, pricing, and the interface for cart additions.

session_start();

// PATH CONFIGURATION: Navigates to the Root folder for core configuration and database connection.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  DATA RETRIEVAL LOGIC:
//  Validates the presence of a product ID and fetches corresponding data via a SQL Join.
if (isset($_GET['id'])) {
    // SECURITY: Sanitize the GET parameter to prevent SQL Injection.
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // SQL JOIN: Retrieves product details along with the associated category name.
    $query = "SELECT p.*, c.name as cat_name FROM products p 
              JOIN categories c ON p.category_id = c.category_id 
              WHERE p.product_id = '$id'";
    
    $result = mysqli_query($conn, $query);
    $product = mysqli_fetch_assoc($result);
    
    // ERROR HANDLING: Terminates execution if the ID does not match any record.
    if (!$product) { 
        die("Product not found."); 
    }
} else { 
    // REDIRECTION: Returns to the storefront if no ID is provided in the URL.
    header("Location: index.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $product['name']; ?> - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row bg-white p-4 rounded-4 shadow-sm border mx-auto align-items-center" style="max-width: 900px;">
            
            <div class="col-md-6 text-center">
                <img src="<?php echo BASE_URL . $product['image_url']; ?>" 
                     class="img-fluid rounded-3 border shadow-sm" 
                     style="max-width: 100%; height: 400px; object-fit: cover;" 
                     alt="<?php echo $product['name']; ?>">
            </div>
            
            <div class="col-md-6 ps-md-5 mt-4 mt-md-0">
                <span class="badge bg-success mb-2 px-3 py-2"><?php echo $product['cat_name']; ?></span>
                <h1 class="fw-bold text-dark"><?php echo $product['name']; ?></h1>
                <p class="text-muted fs-5 lh-base"><?php echo $product['description']; ?></p>
                <h3 class="my-4 fw-bold text-success">RM <?php echo number_format($product['price'], 2); ?></h3>
                
                <form action="<?php echo BASE_URL; ?>Section 3/cart_action.php" method="POST" class="mt-4">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    
                    <div class="d-flex align-items-center mb-4">
                        <label class="me-3 fw-bold text-dark">Quantity:</label>
                        <input type="number" name="quantity" class="form-control border-success shadow-sm" 
                               value="1" min="1" style="max-width: 90px; height: 45px;">
                    </div>
                    
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold shadow-sm py-3 rounded-pill">
                        ADD TO CART
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>