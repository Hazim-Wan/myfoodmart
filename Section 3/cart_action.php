<?php
//  Shopping Cart Controller - cart_action.php
//  Handles the logic for adding products to the persistent user session.
//  This file processes data from product_detail.php and manages cart quantities.

session_start();

// PATH CONFIGURATION: 
// Navigates to the Root directory to access global constants.
include_once __DIR__ . '/../Root/config.php'; 

//  REQUEST PROCESSING:
//  Validates the POST submission and updates the 'cart' session array.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // DATA CAPTURE: Retrieve the product identifier and desired quantity.
    $id = $_POST['product_id'];
    $qty = (int)$_POST['quantity'];
    
    //  SESSION MANAGEMENT:
    //  1. Check if the 'cart' array exists in the current session.
    //  2. Use the Null Coalescing Operator to handle first-time additions (default to 0).
    //  3. Increment the existing quantity by the new quantity provided.
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + $qty;
    
    //  REDIRECTION: 
    //  Direct the user to the cart summary page after successful processing.
    header("Location: view_cart.php");
    exit();
} else {
    // SECURITY: Prevent direct browser access to this logic-only file.
    header("Location: " . BASE_URL . "Section 2/index.php");
    exit();
}
?>