<?php
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Check if the item exists in the cart session
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]); // Removes the specific item
    }
}

// Redirect back to the cart page
header("Location: view_cart.php");
exit();
?>