<?php
// logout.php
session_start();
session_unset(); // Removes all session variables
session_destroy(); // Destroys the session
header("Location: login.php"); // Redirect to login page
exit();
?>