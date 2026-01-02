<?php
//  Order Detailed View Module - order_details.php
//  Displays comprehensive receipt data for a specific transaction.
//  Implements security checks to ensure users only view their own orders.

session_start();

// PATH CONFIGURATION: Accesses global constants and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

// VALIDATION: Ensures a valid Order ID is provided; otherwise, redirects to storefront.
if (!isset($_GET['id'])) { 
    header("Location: " . BASE_URL . "../Section 2/index.php"); 
    exit(); 
}

// DATA CAPTURE: Sanitize input and retrieve session state.
$order_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];
$is_admin = (isset($_SESSION['role']) && $_SESSION['role'] == 'admin');

// MASTER DATA RETRIEVAL:
// Fetches primary order info joined with customer name for administrative clarity.
$order_query = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id 
                WHERE o.order_id = '$order_id'";
$order_res = mysqli_query($conn, $order_query);
$order_data = mysqli_fetch_assoc($order_res);

// SECURITY GUARD:
// Prevents unauthorized users from viewing orders that do not belong to their account.
// Administrators bypass this check to facilitate management.
if (!$is_admin && $order_data['user_id'] != $user_id) { 
    die("Unauthorized access."); 
}

// LINE ITEM RETRIEVAL:
// Fetches all specific products associated with this order ID.
$items_query = "SELECT oi.*, p.name, p.image_url 
                FROM order_items oi 
                JOIN products p ON oi.product_id = p.product_id 
                WHERE oi.order_id = '$order_id'";
$items_res = mysqli_query($conn, $items_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
    <style>
        /* COMPONENT STYLING: High-contrast layout for receipt data. */
        .details-container { background-color: #ffffff; border-radius: 15px; box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08); padding: 30px; }
        .product-img { width: 70px; height: 70px; object-fit: cover; border-radius: 10px; border: 1px solid #eee; }
        .status-pill { padding: 0.5em 1.2em; border-radius: 50px; font-weight: 600; font-size: 0.85rem; }
        .info-label { color: #6c757d; font-size: 0.85rem; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; }
    </style>
</head>
<body style="background-color: #f4f7f6;"> <?php include BASE_PATH . 'header.php'; ?>
    
    <div class="container mt-5 mb-5">
        <div class="mb-4">
            <?php 
            // DYNAMIC NAVIGATION: Redirects back to the appropriate dashboard based on user role.
            $back_url = $is_admin ? "../Section 5/admin_orders.php" : "order_history.php";
            ?>
            <a href="<?php echo $back_url; ?>" class="btn btn-sm btn-outline-secondary px-3 rounded-pill fw-bold">
                ← Back to <?php echo $is_admin ? 'Admin Orders' : 'Order History'; ?>
            </a>
        </div>

        <div class="details-container">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
                <h2 class="fw-bold text-dark mb-0">Order Summary #<?php echo $order_id; ?></h2>
                <?php 
                    $status = $order_data['order_status'];
                    $badgeClass = 'bg-warning text-dark'; 
                    if ($status == 'Completed') $badgeClass = 'bg-success text-white';
                    elseif ($status == 'Cancelled') $badgeClass = 'bg-danger text-white';
                    elseif ($status == 'Preparing') $badgeClass = 'bg-info text-white';
                ?>
                <span class="status-pill <?php echo $badgeClass; ?>"><?php echo strtoupper($status); ?></span>
            </div>

            <div class="row mb-5">
                <div class="col-md-4">
                    <p class="info-label mb-1">Customer</p>
                    <p class="fw-bold text-dark"><?php echo $order_data['customer_name']; ?></p>
                </div>
                <div class="col-md-4">
                    <p class="info-label mb-1">Order Date</p>
                    <p class="fw-bold text-dark"><?php echo date('d M Y, h:i A', strtotime($order_data['created_at'])); ?></p>
                </div>
                <div class="col-md-4">
                    <p class="info-label mb-1">Payment Method</p>
                    <p class="fw-bold text-dark"><?php echo $order_data['payment_method']; ?></p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th colspan="2" class="ps-3">Product</th>
                            <th class="text-center">Price</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end pe-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($item = mysqli_fetch_assoc($items_res)): 
                            $subtotal = $item['price'] * $item['quantity'];
                        ?>
                        <tr>
                            <td style="width: 80px;" class="ps-3">
                                <img src="<?php echo BASE_URL . $item['image_url']; ?>" class="product-img">
                            </td>
                            <td><span class="fw-bold text-dark"><?php echo $item['name']; ?></span></td>
                            <td class="text-center">RM <?php echo number_format($item['price'], 2); ?></td>
                            <td class="text-center text-muted">x <?php echo $item['quantity']; ?></td>
                            <td class="text-end pe-3 fw-bold text-dark">RM <?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot class="border-top-0">
                        <tr>
                            <td colspan="4" class="text-end pt-4 fs-5 fw-bold">Total Amount:</td>
                            <td class="text-end pt-4 pe-3 fs-5 fw-bold text-success">
                                RM <?php echo number_format($order_data['total_amount'], 2); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mt-5 p-4 rounded-3 bg-light border-start border-success border-4">
                <h6 class="fw-bold text-dark mb-2">Delivery Address:</h6>
                <p class="text-muted mb-0"><?php echo $order_data['shipping_address']; ?></p>
            </div>
        </div>
    </div>
</body>
</html>