<?php 
session_start();
include 'db_connect.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyFoodMart - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header.php'; ?>

<div class="container-fluid py-5 mb-5 shadow-sm" style="background-color: #e8f5e9; border-bottom: 2px solid #27ae60;">
    <div class="container text-center">
        <div class="mb-2" style="font-size: 4.5rem; filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));">🍔</div>
        <h1 class="display-4 fw-bold mb-4" style="color: #1b5e20; letter-spacing: -1.5px;">MyFoodMart</h1>
        
        <div class="mx-auto bg-white p-4 rounded-4 shadow-sm" style="max-width: 850px; border-left: 5px solid #27ae60;">
            <p class="lead text-dark mb-0" style="line-height: 1.8; font-size: 1.15rem;">
                <span class="fw-bold" style="color: #27ae60;">MyFoodMart</span> is an online food ordering website under the 
                <span class="badge bg-success px-2 py-1">Business to Consumer (B2C)</span> category. 
                It focuses on helping users order food from different vendors on one platform, 
                just like a simplified version of online food delivery services.
            </p>
        </div>
    </div>
</div>

<div class="container">
    <h2 class="mb-4 fw-bold text-dark border-start border-success border-4 ps-3">Our Menu</h2>
    <div class="row">
        <?php
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $query = "SELECT * FROM products WHERE is_active = 1";
        if ($search) { $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')"; }

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="transition: transform 0.2s; border-radius: 12px; overflow: hidden;">
                        <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="height: 220px; object-fit: cover;">
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
        }
        ?>

        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card h-100 border-0 shadow-sm d-flex align-items-center justify-content-center text-center p-4" 
                 style="background-color: #f1f8e9; border: 2px dashed #a5d6a7 !important; border-radius: 12px;">
                <div style="font-size: 3rem; opacity: 0.6;">🍕</div>
                <h5 class="fw-bold text-success mt-3">More selections coming soon</h5>
                <p class="text-muted small">We are constantly adding new vendors to provide you with the best variety!</p>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>