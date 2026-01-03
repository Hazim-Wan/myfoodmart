<?php
// Global Header Component - Root/header.php
// Provides the primary navigation bar across all project sections.
// Implements role-based access control for administrative links and 
// dynamic session-based shopping cart counters.

// PATH CONFIGURATION: Ensures the global BASE_URL is available for all navigation links.
include_once __DIR__ . '/config.php'; 
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>../Section 2/index.php">
            <span class="me-2">🍽️</span> MyFoodMart
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <form action="<?php echo BASE_URL; ?>../Section 2/index.php" method="GET" class="d-flex mx-auto w-50">
                <input class="form-control me-2" type="search" name="search" placeholder="Search for food...">
                <button class="btn btn-success" type="submit">Search</button>
            </form>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="<?php echo BASE_URL; ?>../Section 3/view_cart.php">
                        🛒 Cart 
                        <span class="badge bg-success rounded-pill">
                            <?php 
                                echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; 
                            ?>
                        </span>
                    </a>
                </li>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="btn btn-warning btn-sm fw-bold me-2" href="<?php echo BASE_URL; ?>../Section 5/admin_dashboard.php">
                                ⚙️ Inventory
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-info btn-sm fw-bold me-2" href="<?php echo BASE_URL; ?>../Section 5/admin_orders.php">
                                📋 Orders
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>../Section 4/order_history.php">My Orders</a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link text-info fw-bold" href="<?php echo BASE_URL; ?>../Section 1/profile.php">👤 Profile</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm ms-2" href="<?php echo BASE_URL; ?>../Section 1/logout.php">Logout</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>../Section 1/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-success btn-sm ms-2" href="<?php echo BASE_URL; ?>../Section 1/register.php">Sign Up</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>