<?php
session_start();
include 'db_connect.php';

// Security check: Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fixed Query: Using 'created_at' as shown in your phpMyAdmin
$query = "SELECT o.*, u.name as customer_name 
          FROM orders o 
          JOIN users u ON o.user_id = u.user_id 
          ORDER BY o.created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Management - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container mt-4">
        <div class="bg-white p-4 rounded shadow-sm border">
            <h2 class="fw-bold text-dark mb-4">📋 Customer Order Overview</h2>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date & Time</th>
                            <th>Total (RM)</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td>#<?php echo $row['order_id']; ?></td>
                            <td class="fw-bold"><?php echo $row['customer_name']; ?></td>
                            <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                            <td class="fw-bold text-success">RM <?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td>
                                <?php 
                                    $status = $row['order_status'];
                                    $badgeClass = ($status == 'Completed') ? 'bg-success' : 'bg-warning text-dark';
                                ?>
                                <span class="badge <?php echo $badgeClass; ?> shadow-sm">
                                    <?php echo $status; ?>
                                </span>
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