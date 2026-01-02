<?php
//  User Profile Management Module - profile.php
//  Allows authenticated users to view and update their personal account information.
//  Ensures data persistence through secure SQL update operations.

session_start();

// PATH CONFIGURATION: Navigates to the Root folder for core configuration and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  AUTHENTICATION GUARD:
//  Ensures only logged-in users can access the profile dashboard.
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
$success_msg = "";
$error_msg = "";

//  UPDATE LOGIC:
//  Processes POST requests to modify user records in the database.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = mysqli_real_escape_string($conn, $_POST['name']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);

    $update_query = "UPDATE users SET name = '$new_name', email = '$new_email' WHERE user_id = '$user_id'";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['user_name'] = $new_name; // Update active session name
        $success_msg = "Profile updated successfully!";
    } else {
        $error_msg = "Error updating profile: " . mysqli_error($conn);
    }
}

//  DATA RETRIEVAL:
//  Fetches the latest user information to populate the form fields.
$query = "SELECT * FROM users WHERE user_id = '$user_id'";
$user = mysqli_fetch_assoc(mysqli_query($conn, $query));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - MyFoodMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>style.css">
</head>
<body class="bg-light">
    <?php include BASE_PATH . 'header.php'; ?>

    <div class="container mt-5">
        <div class="card p-4 mx-auto shadow-sm border-0" style="max-width: 500px; border-top: 5px solid #27ae60 !important;">
            <div class="text-center mb-4">
                <div class="display-4">👤</div>
                <h2 class="fw-bold">Profile Dashboard</h2>
                <p class="text-muted small">Manage your personal information</p>
            </div>
            
            <?php if($success_msg): ?>
                <div class="alert alert-success py-2 small"><?php echo $success_msg; ?></div>
            <?php endif; ?>

            <?php if($error_msg): ?>
                <div class="alert alert-danger py-2 small"><?php echo $error_msg; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-bold small">Full Name</label>
                    <input type="text" name="name" class="form-control border-success" 
                           value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold small">Email Address</label>
                    <input type="email" name="email" class="form-control border-success" 
                           value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold small text-muted">Account Role</label>
                    <input type="text" class="form-control bg-light" value="<?php echo strtoupper($user['role']); ?>" readonly>
                    <div class="form-text italic small text-muted">Role cannot be changed by user.</div>
                </div>
                
                <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm">UPDATE PROFILE</button>
            </form>
        </div>
    </div>
</body>
</html>