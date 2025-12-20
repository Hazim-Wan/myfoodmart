<?php
session_start();
include 'db_connect.php';
$total_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Cart - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #ffffff;">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2 class="fw-bold mb-4">Your Shopping Cart</h2>
        
        <?php if (!empty($_SESSION['cart'])): ?>
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover align-middle bg-white mb-0">
                    <thead class="table-light">
                        <tr style="border-bottom: 2px solid #27ae60;">
                            <th>Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Subtotal</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($_SESSION['cart'] as $id => $qty): 
                            $safe_id = mysqli_real_escape_string($conn, $id);
                            $res = mysqli_query($conn, "SELECT * FROM products WHERE product_id = '$safe_id'");
                            $item = mysqli_fetch_assoc($res);
                            if($item):
                                $subtotal = $item['price'] * $qty;
                                $total_price += $subtotal;
                        ?>
                        <tr>
                            <td class="fw-bold"><?php echo $item['name']; ?></td>
                            <td class="text-center">RM <?php echo number_format($item['price'], 2); ?></td>
                            <td class="text-center"><?php echo $qty; ?></td>
                            <td class="text-center text-success fw-bold">RM <?php echo number_format($subtotal, 2); ?></td>
                            <td class="text-center">
                                <a href="remove_cart.php?id=<?php echo $id; ?>" class="btn btn-sm btn-outline-danger">Remove</a>
                            </td>
                        </tr>
                        <?php endif; endforeach; ?>
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="3" class="text-end py-3">Total Amount:</td>
                            <td colspan="2" class="text-center py-3 text-success" style="font-size: 1.2rem;">
                                RM <?php echo number_format($total_price, 2); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-outline-secondary px-4">Continue Shopping</a>
                <a href="checkout.php" class="btn btn-success px-5 fw-bold">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0 p-5 text-center">
                <h3 class="text-muted mb-4">Cart is currently empty</h3>
                <a href="index.php" class="btn btn-success px-4 fw-bold">BROWSE MENU</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>