<?php
//  Administrative Order Management Module - admin_orders.php
//  Centralized dashboard for administrators to monitor sales performance and update fulfillment statuses.
//  Implements real-time revenue aggregation and secure status transitions.

session_start();

// PATH CONFIGURATION: Navigates up one level, then into the Root folder for core configuration.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  AUTHENTICATION GUARD:
//  Restricts access solely to administrators. Unauthorized users are rerouted to the public storefront.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . BASE_URL . "../Section 2/index.php");
    exit();
}

//  REVENUE AGGREGATION LOGIC:
//  Calculates the grand total for all 'Completed' transactions to provide a performance snapshot.
$rev_query = "SELECT SUM(total_amount) as total FROM orders WHERE order_status = 'Completed'";
$rev_data = mysqli_fetch_assoc(mysqli_query($conn, $rev_query));
$total_revenue = $rev_data['total'] ?? 0;

//  MASTER ORDER RETRIEVAL:
//  Joins the 'orders' and 'users' tables to provide customer context for each transaction.
$order_query = "SELECT o.*, u.name as customer_name 
                FROM orders o 
                JOIN users u ON o.user_id = u.user_id 
                ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $order_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
    
    <style>
        /* DASHBOARD UI ACCENTS: Custom styling for administrative clarity. */
        .admin-card { background-color: #ffffff; border-radius: 15px; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08); padding: 25px; }
        .rev-card { border-radius: 15px; background: linear-gradient(45deg, #27ae60, #2ecc71); box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3); }
        .table thead th { background-color: #f8f9fa; color: #495057; font-weight: 700; text-transform: uppercase; font-size: 0.8rem; }
        .status-select { font-weight: 600; border-radius: 50px; padding-left: 15px; font-size: 0.85rem; }
    </style>
</head>
<body style="background-color: #f4f7f6;"> <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-4 mb-5">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <div class="d-flex align-items-center border-start border-success border-4 ps-3">
                    <h2 class="fw-bold text-dark mb-0">📋 Order Management</h2>
                </div>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="card rev-card text-white d-inline-block text-start p-2 px-4 mt-3 mt-md-0">
                    <div class="small opacity-75 fw-bold text-uppercase">Total Revenue</div>
                    <h3 class="fw-bold mb-0">RM <?php echo number_format($total_revenue, 2); ?></h3>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-3">Order ID</th>
                            <th>Customer</th>
                            <th>Total Amount</th>
                            <th>Order Status</th>
                            <th class="text-center pe-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): 
                            
                            $status = $row['order_status'];
                            $borderColor = "#ffc107"; // Pending
                            if($status == "Completed") $borderColor = "#27ae60";
                            if($status == "Cancelled") $borderColor = "#dc3545";
                            if($status == "Preparing") $borderColor = "#0dcaf0";
                        ?>
                        <tr>
                            <td class="ps-3 fw-bold text-primary">#<?php echo $row['order_id']; ?></td>
                            <td>
                                <div class="fw-bold text-dark"><?php echo $row['customer_name']; ?></div>
                                <div class="small text-muted"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></div>
                            </td>
                            <td class="fw-bold text-success">RM <?php echo number_format($row['total_amount'], 2); ?></td>
                            <td style="min-width: 180px;">
                                <form action="update_status.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <select name="new_status" class="form-select form-select-sm status-select shadow-sm" 
                                            style="border-left: 4px solid <?php echo $borderColor; ?>;" 
                                            onchange="this.form.submit()">
                                        <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Preparing" <?php if($status == 'Preparing') echo 'selected'; ?>>Preparing</option>
                                        <option value="Completed" <?php if($status == 'Completed') echo 'selected'; ?>>Completed</option>
                                        <option value="Cancelled" <?php if($status == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                            <td class="text-center pe-3">
                                <a href="../Section 4/order_details.php?id=<?php echo $row['order_id']; ?>" 
                                   class="btn btn-sm btn-outline-primary fw-bold px-3 rounded-pill shadow-sm">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>