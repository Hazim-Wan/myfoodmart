<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
    .navbar-brand { font-weight: 700 !important; display: flex; align-items: center; }
    .brand-emoji { font-size: 1.8rem; margin-right: 8px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); }
</style>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3" style="background-color: #343a40; border-bottom: 3px solid #27ae60;">
    <div class="container">
        <a class="navbar-brand" href="index.php" style="color: #2ecc71; letter-spacing: 1px; font-size: 1.5rem;">
            <span class="brand-emoji">🍽️</span> MyFoodMart
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex mx-auto w-50" method="GET" action="index.php">
                <input class="form-control me-2 border-0 shadow-sm" type="search" name="search" placeholder="Search for food..." style="background-color: #f8f9fa;">
                <button class="btn btn-success fw-bold" type="submit">Search</button>
            </form>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link fw-bold text-white" href="view_cart.php">
                        🛒 Cart <span class="badge bg-success">
                            <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        </span>
                    </a>
                </li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php if(isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item me-2">
                            <a class="btn btn-warning btn-sm fw-bold text-dark shadow-sm" href="admin_dashboard.php">⚙️ Products</a>
                        </li>
                        <li class="nav-item me-2">
                            <a class="btn btn-info btn-sm fw-bold text-dark shadow-sm" href="admin_orders.php">📋 Orders</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="nav-link text-white" href="order_history.php">My Orders</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm ms-2 fw-bold" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link text-white" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-success btn-sm ms-2 fw-bold shadow-sm" href="register.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>