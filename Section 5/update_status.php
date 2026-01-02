<?php
//  Order Status Controller - update_status.php
//  Handles the administrative transition of order states (e.g., from Pending to Preparing).
//  Implements strict role-based access control and input sanitization.
session_start();

// PATH CONFIGURATION: Accesses global constants and database connectivity.
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  AUTHORIZATION & REQUEST VALIDATION:
//  1. Verifies that the user has 'admin' privileges.
//  2. Ensures the request is a valid POST submission with required identifiers.
if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && isset($_POST['order_id'])) {
    
    // SECURITY: Sanitize the order identifier and the new status string.
    $id = mysqli_real_escape_string($conn, $_POST['order_id']);
    $status = mysqli_real_escape_string($conn, $_POST['new_status']);
    
    //  DATABASE UPDATE:
    //  Commits the status change to the 'orders' table for the specific record.
    $update_query = "UPDATE orders SET order_status = '$status' WHERE order_id = '$id'";
    
    if (mysqli_query($conn, $update_query)) {
        // Success: The status has been updated in the database.
    }
}

//  REDIRECTION:
//  Regardless of outcome, return the administrator to the Order Management dashboard.
header("Location: admin_orders.php");   
exit();
?>