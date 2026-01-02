<?php
//  Shopping Cart View Module - view_cart.php
//  Displays the current items stored in the user's session.
//  Features real-time total calculation and automatic quantity updates.

session_start();

// PATH CONFIGURATION: Navigates up one level, then into Root for core configuration.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// INITIALIZATION: Setup counters for the cart summary.
$total_price = 0;
$total_quantity = 0; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
    <style>
        /* COMPONENT STYLING: Card-based container for readability. */
        .cart-container { background-color: #ffffff; border-radius: 15px; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08); padding: 30px; }
        .product-img { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; }
        .table thead th { background-color: #f8f9fa; color: #495057; font-weight: 700; text-transform: uppercase; font-size: 0.85rem; }
    </style>
</head>
<body style="background-color: #f4f7f6;"> 
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-start border-success border-4 ps-3">
            <h2 class="fw-bold text-dark mb-0">Your Shopping Cart</h2>
        </div>
        
        <?php 
        // CONDITIONAL RENDERING: Checks if the cart session exists and contains items.
        if (!empty($_SESSION['cart'])): ?>
            <div class="cart-container shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white mb-0">
                        <thead>
                            <tr>
                                <th colspan="2" class="ps-3">Product</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center pe-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // DYNAMIC CART PROCESSING: Iterates through session data and merges it with database product info.
                            foreach ($_SESSION['cart'] as $id => $qty): 
                                $safe_id = mysqli_real_escape_string($conn, $id);
                                $res = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$safe_id'");
                                $item = mysqli_fetch_assoc($res);
                                
                                if($item):
                                    $subtotal = $item['price'] * $qty;
                                    $total_price += $subtotal;
                                    $total_quantity += $qty; 
                            ?>
                            <tr>
                                <td style="width: 80px;" class="ps-3">
                                    <img src="<?php echo BASE_URL . $item['image_url']; ?>" class="product-img border">
                                </td>
                                <td><span class="fw-bold text-dark"><?php echo $item['name']; ?></span></td>
                                <td class="text-center">RM <?php echo number_format($item['price'], 2); ?></td>
                                
                                <td class="text-center">
                                    <form action="update_cart.php" method="POST" class="d-flex justify-content-center">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="number" name="quantity" value="<?php echo $qty; ?>" min="1" 
                                               class="form-control form-control-sm text-center border-success" 
                                               style="width: 80px;" onchange="this.form.submit()">
                                    </form>
                                </td>

                                <td class="text-center text-success fw-bold">RM <?php echo number_format($subtotal, 2); ?></td>
                                <td class="text-center pe-3">
                                    <a href="remove_cart.php?id=<?php echo $id; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3">Remove</a>
                                </td>
                            </tr>
                            <?php endif; endforeach; ?>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td colspan="3" class="text-end py-4 ps-3">Total Items: <?php echo $total_quantity; ?></td>
                                <td class="text-end py-4">Total Amount:</td>
                                <td colspan="2" class="text-center py-4 text-success pe-3" style="font-size: 1.4rem;">
                                    RM <?php echo number_format($total_price, 2); ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="../Section 2/index.php" class="btn btn-outline-secondary rounded-pill px-4 fw-bold">← Continue Shopping</a>
                <a href="../Section 4/checkout.php" class="btn btn-success rounded-pill px-5 fw-bold shadow">Proceed to Checkout →</a>
            </div>
        <?php else: ?>
            <div class="cart-container text-center py-5 shadow-sm">
                <div style="font-size: 4rem;" class="mb-3">🛒</div>
                <h3 class="text-muted mb-4">Your cart is currently empty</h3>
                <a href="../Section 2/index.php" class="btn btn-success rounded-pill px-5 py-2 fw-bold shadow-sm">BROWSE MENU</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>