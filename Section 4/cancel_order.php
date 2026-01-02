<?php
//  Order Cancellation Controller - cancel_order.php
//  Handles user-initiated cancellation requests for orders.
//  Implements strict security checks to ensure only 'Pending' orders owned 
//  by the current user can be modified.
 

session_start();

//  PATH CONFIGURATION: 
//  Navigates to the Root directory to access global configuration and database connectivity.
 
include_once __DIR__ . '/../Root/config.php'; 
include_once BASE_PATH . 'db_connect.php'; 

//  CANCELLATION PROCESSING LOGIC:
//  Validates session state and request method before modifying the database.
 
if (isset($_SESSION['user_id']) && $_SERVER["REQUEST_METHOD"] == "POST") {
    
    // DATA CAPTURE: Retrieve identity and sanitize the target order identifier.
    $user_id = $_SESSION['user_id'];
    $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
    
    
    //  CONDITIONAL UPDATE QUERY:
    //  1. Updates order_status to 'Cancelled'.
    //  2. SECURITY: Filters by user_id to prevent unauthorized cross-user cancellations.
    //  3. BUSINESS LOGIC: Restricts cancellation to 'Pending' orders only.
     
    $cancel_sql = "UPDATE orders SET order_status = 'Cancelled' 
                   WHERE order_id = '$order_id' 
                   AND user_id = '$user_id' 
                   AND order_status = 'Pending'";
    
    if (mysqli_query($conn, $cancel_sql)) {
        // SUCCESS: Feedback redirected back to the history interface.
        header("Location: order_history.php?cancel=success");
    } else {
        // FAILURE: Redirects with error flag for user notification.
        header("Location: order_history.php?cancel=error");
    }
    exit();

} else {
    //  FALLBACK REDIRECTION: 
    //  Handles unauthorized access attempts or non-POST requests by routing to home.
     
    header("Location: " . BASE_URL . "Section 2/index.php");
    exit();
}
?>