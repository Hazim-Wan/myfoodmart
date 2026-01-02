<?php 
//  Main Storefront Home Page - index.php
//  Displays the product catalog with dynamic category filtering and search functionality.
//  Implements a responsive grid layout for food items

session_start();

// PATH CONFIGURATION: Navigates to the Root folder for core configuration constants.
include_once __DIR__ . '/../Root/config.php'; 

// DATABASE CONNECTION: Established using the absolute BASE_PATH.
include_once BASE_PATH . 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyFoodMart - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body>

<?php include BASE_PATH . 'header.php'; ?>

<div class="container-fluid py-5 mb-5 shadow-sm" style="background-color: #e8f5e9; border-bottom: 2px solid #27ae60;">
    <div class="container text-center">
        <div class="mb-2" style="font-size: 4.5rem; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">🍔</div>
        <h1 class="display-4 fw-bold mb-4" style="color: #1b5e20; letter-spacing: -1.5px;">MyFoodMart</h1>
        
        <div class="mx-auto bg-white p-4 rounded-4 shadow-sm" style="max-width: 850px; border-left: 5px solid #27ae60;">
            <p class="lead text-dark mb-0" style="line-height: 1.8; font-size: 1.15rem;">
                <span class="fw-bold" style="color: #27ae60;">MyFoodMart</span> is an online food ordering website that falls under the 
                <span class="badge bg-success px-2 py-1">Business-to-Consumer (B2C)</span> category. 
                It focuses on helping users order food from different vendors on one platform, 
                just like a simplified version of online food delivery services.
            </p>
        </div>
    </div>
</div>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 border-start border-success border-4 ps-3">
        <h2 class="fw-bold text-dark mb-0">Our Menu</h2>
    </div>

    <div class="mb-5 text-center">
        <a href="index.php" class="btn btn-outline-success rounded-pill px-4 me-2 mb-2 fw-bold shadow-sm">All Items</a>
        <?php 
        $cat_result = mysqli_query($conn, "SELECT * FROM categories");
        while($cat = mysqli_fetch_assoc($cat_result)): 
        ?>
            <a href="index.php?cat=<?php echo $cat['category_id']; ?>" 
               class="btn btn-outline-success rounded-pill px-4 me-2 mb-2 fw-bold shadow-sm">
               <?php echo $cat['name']; ?>
            </a>
        <?php endwhile; ?>
    </div>

    <div class="row">
        <?php
        /**
         * SEARCH & FILTER LOGIC:
         * Captures GET parameters for search terms and category IDs.
         * Sanitizes input to prevent SQL Injection.
         */
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $cat_filter = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';

        $query = "SELECT * FROM products WHERE is_active = 1";
        if ($search) { $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')"; }
        if ($cat_filter) { $query .= " AND category_id = '$cat_filter'"; }

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        <img src="<?php echo BASE_URL . $row['image_url']; ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-1"><?php echo $row['name']; ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?php echo substr($row['description'], 0, 75); ?>...</p>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <span class="fs-5 fw-bold text-success">RM <?php echo number_format($row['price'], 2); ?></span>
                                <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-success btn-sm px-3 fw-bold rounded-pill shadow-sm">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            // FEEDBACK: Error handling for empty result sets.
            echo "<div class='col-12 text-center py-5'><p class='text-muted fs-4'>No meals found.</p></div>";
        }
        ?>  

        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 shadow-sm border-0 text-center p-4" 
                 style="border-radius: 12px; background-color: #f1fcf1; border: 2px dashed #b7e4b7 !important;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <div class="mb-3" style="font-size: 3rem;">🍕</div>
                    <h5 class="fw-bold" style="color: #27ae60;">More selections coming soon</h5>
                    <p class="text-muted small">We are constantly adding new vendors to provide you with the best variety!</p>
                </div>
            </div>
        </div>
    </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>