<?php
session_start();
include 'db_connect.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // In a real app, use password_hash()

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Error: Email already exists.";
    } else {
        $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'user')";
        if (mysqli_query($conn, $sql)) {
            $message = "Registration successful! <a href='login.php' class='text-success'>Login here</a>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="card p-4 mx-auto shadow-sm border-0" style="max-width: 450px; border-top: 5px solid #27ae60 !important;">
            <h2 class="text-center fw-bold mb-4">Create Account</h2>
            
            <?php if($message) echo "<div class='alert alert-info py-2 small'>$message</div>"; ?>
            
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Full Name</label>
                    <input type="text" name="name" class="form-control border-success" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email Address</label>
                    <input type="email" name="email" class="form-control border-success" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Password</label>
                    <input type="password" name="password" class="form-control border-success" required>
                </div>
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 mt-2 shadow-sm">SIGN UP</button>
            </form>
            <p class="mt-4 text-center small text-muted">
                Already have an account? <a href="login.php" class="text-success fw-bold">Login</a>
            </p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>