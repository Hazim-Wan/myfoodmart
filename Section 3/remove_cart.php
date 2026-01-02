<?php
//  Shopping Cart Removal Module - remove_cart.php
//  Handles the logic for deleting a specific product from the active user session.
//  This file processes GET requests from view_cart.php to update the cart state.

session_start();

//  PATH CONFIGURATION: 
//  Navigates to the Root directory to access global configuration constants.
include_once __DIR__ . '/../Root/config.php'; 

// ITEM REMOVAL LOGIC:
// Validates the presence of a product ID and modifies the 'cart' session array.
if (isset($_GET['id'])) {
    // DATA CAPTURE: Retrieve the unique product identifier to be removed.
    $id = $_GET['id'];
      
    //  SESSION MANIPULATION:
    //  1. Check if the 'cart' array exists and contains the specified item key.
    //  2. Use unset() to completely remove the item entry from the associative array.
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]); 
    }
}
//  REDIRECTION: 
//  Returns the user to the cart summary view after the session update is complete.
 
header("Location: view_cart.php");
exit();
?>