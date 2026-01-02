<?php
//  User Authentication Module - login.php
//  Handles user sign-in by verifying credentials against the database.
//  Implements session management and secure redirection logic.

session_start();

// PATH CONFIGURATION: Navigates up one level, then into the Root directory for configuration.
include_once __DIR__ . '/../Root/config.php'; 

// DATABASE CONNECTION: Utilizes absolute path constant for reliability.
include_once BASE_PATH . 'db_connect.php'; 

$error = "";

// LOGIN PROCESSING LOGIC
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SECURITY: Sanitize user input to prevent SQL injection vulnerabilities.
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // QUERY EXECUTION: Retrieve user record associated with the provided email.
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        // CREDENTIAL VERIFICATION: Compare submitted password with database record.
        if ($password === $user['password']) {
            // SESSION INITIALIZATION: Store essential user identity and role data.
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role']; 
            
            header("Location: " . BASE_URL . "../Section 2/index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No account found with that email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5">
        <div class="card p-4 mx-auto shadow-sm border-0" style="max-width: 400px; border-top: 5px solid #27ae60 !important;">
            <h2 class="text-center fw-bold mb-4">Login</h2>
            
            <?php if($error): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email Address</label>
                    <input type="email" name="email" class="form-control border-success" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Password</label>
                    <input type="password" name="password" class="form-control border-success" required>
                </div>
                
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">SIGN IN</button>
            </form>
            
            <p class="mt-4 text-center small text-muted">
                New to MyFoodMart? <a href="register.php" class="text-success fw-bold">Create an account</a>
            </p>
        </div>
    </div>
</body>
</html>