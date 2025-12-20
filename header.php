<nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom border-success shadow-sm py-3 mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php" style="color: #27ae60; font-size: 1.5rem;">MyFoodMart</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="d-flex mx-auto w-50" method="GET" action="index.php">
                <input class="form-control me-2 border-success" type="search" name="search" placeholder="Search for food...">
                <button class="btn btn-success" type="submit">Search</button>
            </form>

            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link fw-bold" href="view_cart.php">
                        🛒 Cart <span class="badge bg-success">
                            <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                        </span>
                    </a>
                </li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="order_history.php">Orders</a></li>
                    <li class="nav-item"><a class="btn btn-outline-success btn-sm ms-2" href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-success btn-sm ms-2" href="register.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>