<?php
//  User Session Termination Module - logout.php
//  Responsible for securely clearing all session data and 
//  redirecting the user back to the public storefront.

// PATH CONFIGURATION: Navigates to the Root folder to access global constants.
include_once __DIR__ . '/../Root/config.php'; 

// SESSION INITIALIZATION: Access the current active session.
session_start();

//  SECURITY CLEANUP:
//  1. session_unset() removes all internal session variables.
//  2. session_destroy() terminates the session record on the server.
session_unset(); 
session_destroy(); 

// REDIRECTION: Route the user back to the storefront home page using BASE_URL.
header("Location: " . BASE_URL . "Section 2/index.php"); 
exit();
?>