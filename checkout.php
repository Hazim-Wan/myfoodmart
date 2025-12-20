<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$total_price = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $id = mysqli_real_escape_string($conn, $id);
    $res = mysqli_query($conn, "SELECT price FROM products WHERE product_id = '$id'");
    $item = mysqli_fetch_assoc($res);
    $total_price += ($item['price'] * $qty);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    $order_sql = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method) 
                  VALUES ('$user_id', '$total_price', '$address', '$payment')";
    
    if (mysqli_query($conn, $order_sql)) {
        unset($_SESSION['cart']); // Clear cart after order
        header("Location: order_history.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #f8f9fa;">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2 class="fw-bold mb-4">Complete Your Order</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm p-4 border-0 mb-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Delivery Address</label>
                            <textarea name="address" class="form-control border-success" rows="3" placeholder="Enter your address..." required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select name="payment" class="form-select border-success" required>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="S Pay Global">S Pay Global (Sarawak Pay)</option>
                                <option value="Online Banking">Online Banking</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">PLACE ORDER NOW</button>
                    </form>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white fw-bold">Order Summary</div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>RM <?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee</span>
                            <span class="text-success">FREE</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold fs-5">
                            <span>Total</span>
                            <span class="text-success">RM <?php echo number_format($total_price, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>