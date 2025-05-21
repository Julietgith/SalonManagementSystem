<?php
session_start(); // Start the session

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page (or any other page you want after logout)
header("Location: login.php"); // Assuming your login page is index.php in the root directory
exit(); // Ensure that no further code is executed after the redirect
?>
