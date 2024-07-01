<?php
session_start(); // Start the session
session_unset();
session_destroy(); // Destroy the session
header("Location: login.php"); // Redirect to the login page
exit();
?>