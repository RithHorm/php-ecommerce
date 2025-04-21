<?php
// Start the session
session_start();

// Unset the session variable (JWT token) and destroy the session
unset($_SESSION['token']);
session_destroy();

// Redirect the user to the sign-in page
header("Location: sign-in.php");
exit();
?>
