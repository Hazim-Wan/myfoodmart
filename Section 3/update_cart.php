<?php
//  Cart Quantity Management Logic - update_cart.php
//  Processes quantity adjustments from the view_cart page.
//  Maintains session consistency and handles item removal via zero-quantity validation.

session_start();

// REQUEST VALIDATION: Processes updates via POST to ensure session data integrity.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $new_qty = intval($_POST['quantity']);

    // SESSION CONSISTENCY LOGIC:
    // Checks if the item exists in the cart before applying the new quantity.
    if (isset($_SESSION['cart'][$product_id])) {
        if ($new_qty > 0) {
            // Apply new quantity count to the session array.
            $_SESSION['cart'][$product_id] = $new_qty;
        } else {
            // SECURITY/UX: Remove the item entirely if the quantity is reduced to zero.
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

// REDIRECTION: Returns the user to the cart view to see updated totals immediately.
header("Location: view_cart.php");
exit();
?>