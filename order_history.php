<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-color: #ffffff;">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <h2 class="fw-bold mb-4" style="color: #2c3e50;">Your Order History</h2>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive shadow-sm rounded">
                <table class="table table-hover align-middle bg-white mb-0">
                    <thead class="table-light">
                        <tr style="border-bottom: 2px solid #27ae60;">
                            <th>Order ID</th>
                            <th>Date & Time</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $row['order_id']; ?></td>
                            <td><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                            <td class="text-success fw-bold">RM <?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo $row['payment_method']; ?></td>
                            <td class="text-center">
                                <span class="badge <?php 
                                    echo ($row['order_status'] == 'Completed') ? 'bg-success' : 'bg-warning text-dark'; 
                                ?>">
                                    <?php echo $row['order_status']; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="card shadow-sm border-0 p-5 text-center">
                <h3 class="text-muted mb-4">You haven't placed any orders yet</h3>
                <a href="index.php" class="btn btn-success px-4 fw-bold">START ORDERING</a>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>