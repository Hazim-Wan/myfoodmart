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

<div class="container mt-5">
    <h2 class="mb-4 fw-bold">Our Menu</h2>
    <div class="row">
        <?php
        $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
        $query = "SELECT * FROM products WHERE is_active = 1";
        
        if ($search) {
            $query .= " AND (name LIKE '%$search%' OR description LIKE '%$search%')";
        }

        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card product-card h-100 shadow-sm border-0">
                        <img src="<?php echo $row['image_url']; ?>" class="card-img-top p-2 rounded" alt="<?php echo $row['name']; ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?php echo $row['name']; ?></h5>
                            <p class="card-text text-muted small"><?php echo substr($row['description'], 0, 80); ?>...</p>
                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-success">RM <?php echo number_format($row['price'], 2); ?></span>
                                <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-success btn-sm px-3">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p class='text-center'>No meals found matching your search.</p>";
        }
        ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>