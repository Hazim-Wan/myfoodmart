<?php

//  Checkout Processing Module - checkout.php
//  Finalizes the transaction by converting session-based cart items into 
//  permanent database records (orders and order_items).

session_start();

// PATH CONFIGURATION: Accesses the global configuration and path constants.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// AUTHENTICATION GUARD: Ensures guests are redirected to login before checkout.
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "../Section 1/login.php");
    exit();
}

// CART VALIDATION: Prevents access to checkout if the shopping cart session is empty.
if (empty($_SESSION['cart'])) {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

//  PRE-PROCESSING: TOTAL CALCULATION
//  Computes the final total price based on the current database prices to ensure accuracy.
 
$total_price = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $id = mysqli_real_escape_string($conn, $id);
    $res = mysqli_query($conn, "SELECT price FROM products WHERE product_id = '$id'");
    $item = mysqli_fetch_assoc($res);
    if ($item) {
        $total_price += ($item['price'] * $qty);
    }
}


//  TRANSACTION LOGIC:
//  Processes the POST submission to create the order and line items.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    // SECURITY: Sanitize user-provided shipping and payment data.
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment = mysqli_real_escape_string($conn, $_POST['payment']);

    // 1. INSERT MASTER ORDER RECORD: Creates the primary order entry.
    $order_sql = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_method, order_status, created_at) 
                  VALUES ('$user_id', '$total_price', '$address', '$payment', 'Pending', NOW())";
    
    if (mysqli_query($conn, $order_sql)) {
        // Retrieve the auto-generated ID for the newly created order.
        $new_order_id = mysqli_insert_id($conn);
        
        // 2. INSERT LINE ITEMS: Transfer each session item into the 'order_items' table.
        foreach ($_SESSION['cart'] as $id => $qty) {
            $id = mysqli_real_escape_string($conn, $id);
            $price_res = mysqli_query($conn, "SELECT price FROM products WHERE product_id = '$id'");
            $price_item = mysqli_fetch_assoc($price_res);
            $current_price = $price_item['price'];

            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$new_order_id', '$id', '$qty', '$current_price')";
            mysqli_query($conn, $item_sql);
        }
        
        // SESSION CLEANUP: Empty the cart after successful transaction.
        unset($_SESSION['cart']); 
        header("Location: order_confirmation.php?id=" . $new_order_id);
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body style="background-color: #f8f9fa;">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5 mb-5">
        <h2 class="fw-bold mb-4">Complete Your Order</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm p-4 border-0 mb-4 rounded-4">
                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label fw-bold">Delivery Address</label>
                            <textarea name="address" class="form-control border-success rounded-3" rows="3" 
                                      placeholder="Enter your full campus or delivery address" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Payment Method</label>
                            <select name="payment" class="form-select border-success rounded-3" required>
                                <option value="Cash on Delivery">Cash on Delivery</option>
                                <option value="Online Banking">Online Banking</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill py-3 shadow">
                            PLACE ORDER NOW
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-success text-white fw-bold py-3 text-center">Order Summary</div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal</span>
                            <span class="fw-bold">RM <?php echo number_format($total_price, 2); ?></span>
                        </div>
                        <hr class="my-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold fs-5">Total</span>
                            <span class="text-success fw-bold fs-4">RM <?php echo number_format($total_price, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>