<?php
//  Order Confirmation Module - order_confirmation.php
//  Displays a success message and summary for a specific transaction.

session_start();

// PATH CONFIGURATION
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// AUTHENTICATION GUARD
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "../Section 1/login.php");
    exit();
}

// DATA RETRIEVAL: Fetch the specific order details based on the URL ID
if (isset($_GET['id'])) {
    $order_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];
    
    // SECURITY: Ensure the order belongs to the logged-in user
    $query = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'";
    $result = mysqli_query($conn, $query);
    $order = mysqli_fetch_assoc($result);

    if (!$order) {
        header("Location: " . BASE_URL . "Section 2/index.php");
        exit();
    }
} else {
    header("Location: " . BASE_URL . "Section 2/index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body style="background-color: #f4f7f6;">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5 text-center">
        <div class="card shadow-sm p-5 border-0 rounded-4 mx-auto" style="max-width: 600px;">
            <div class="mb-4">
                <span class="display-1 text-success">✅</span>
            </div>
            <h1 class="fw-bold text-dark">Thank You!</h1>
            <p class="text-muted fs-5">Your order has been placed successfully.</p>
            
            <div class="bg-light p-4 rounded-3 my-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Order ID:</span>
                    <span class="fw-bold">#<?php echo $order['order_id']; ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Paid:</span>
                    <span class="fw-bold text-success">RM <?php echo number_format($order['total_amount'], 2); ?></span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Payment:</span>
                    <span class="fw-bold"><?php echo $order['payment_method']; ?></span>
                </div>
            </div>

            <p class="small text-muted mb-4">A confirmation has been sent to your account. You can track your order status in your history.</p>

            <div class="d-grid gap-2">
                <a href="order_history.php" class="btn btn-success btn-lg rounded-pill fw-bold">View Order History</a>
                <a href="../Section 2/index.php" class="btn btn-outline-secondary rounded-pill fw-bold">Return to Shop</a>
            </div>
        </div>
    </div>
</body>
</html>