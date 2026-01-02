<?php
//  User Order History Module - order_history.php
//  Displays a historical log of all transactions made by the authenticated user.
//  Features dynamic status color-coding and responsive table layouts.

session_start();

// PATH CONFIGURATION: Navigates up one level, then into Root for config and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  AUTHENTICATION GUARD:
//  Ensures only logged-in users can access their transaction history.
//  Redirects guests to the login module in Section 1 using BASE_URL.
if (!isset($_SESSION['user_id'])) { 
    header("Location: " . BASE_URL . "../Section 1/login.php"); 
    exit(); 
}

//  DATA RETRIEVAL LOGIC:
//  Fetches all orders associated with the active session user, sorted by most recent first.
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
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
    
    <style>
        .order-container { 
            background-color: #ffffff; 
            border-radius: 15px; 
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08); 
            padding: 20px;
        }
        .table thead th { 
            background-color: #f8f9fa; 
            color: #495057; 
            font-weight: 700; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
        }
        .status-badge { padding: 0.6em 1.2em; border-radius: 50px; font-weight: 600; font-size: 0.75rem; }
    </style>
</head>
<body style="background-color: #f4f7f6;"> 
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-start border-success border-4 ps-3">
            <h2 class="fw-bold text-dark mb-0">Your Order History</h2>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="order-container shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ORDER ID</th>
                                <th>PLACED ON</th>
                                <th>TOTAL AMOUNT</th>
                                <th>STATUS</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($result)): 
                                $status = $row['order_status'];
                                $badgeClass = 'bg-warning text-dark'; 
                                if ($status == 'Completed') $badgeClass = 'bg-success text-white';
                                elseif ($status == 'Cancelled') $badgeClass = 'bg-danger text-white';
                                elseif ($status == 'Preparing') $badgeClass = 'bg-info text-white';
                            ?>
                            <tr>
                                <td class="fw-bold text-primary">#<?php echo $row['order_id']; ?></td>
                                <td class="text-muted"><?php echo date('d M Y, h:i A', strtotime($row['created_at'])); ?></td>
                                <td class="fw-bold text-success">RM <?php echo number_format($row['total_amount'], 2); ?></td>
                                <td>
                                    <span class="badge status-badge <?php echo $badgeClass; ?> shadow-sm">
                                        <?php echo strtoupper($status); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="order_details.php?id=<?php echo $row['order_id']; ?>" 
                                       class="btn btn-sm btn-light fw-bold border shadow-sm">
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="order-container text-center py-5 shadow-sm">
                <div style="font-size: 4rem;">📦</div>
                <h3 class="text-muted mt-3">You haven't placed any orders yet.</h3>
                <a href="<?php echo BASE_URL; ?>../Section 2/index.php" class="btn btn-success rounded-pill px-4 mt-3 fw-bold shadow-sm">
                    Browse Menu
                </a>
            </div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>