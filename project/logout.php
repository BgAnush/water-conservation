<?php
// Start the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// JavaScript alert
echo '<script>alert("Thank you for choosing Drops Of Life. You are a very special person to our team."); window.location.href="home.html";</script>';
?>
