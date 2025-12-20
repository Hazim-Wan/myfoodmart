<?php
session_start();
session_unset(); // Removes all session variables
session_destroy(); // Destroys the session itself
header("Location: index.php"); // Redirect back to homepage
exit();
?>
