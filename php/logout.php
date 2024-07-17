<?php
session_start();
// Unset all of the session variables
$_SESSION = array();

// If the user is logged in using cookies, delete the cookie as well
if (isset($_COOKIE["user_id"])) {
    setcookie("user_id", "", time() - 3600, "/");
    setcookie("remember_me","no",time() - 3600, "/");
}

// Destroy the session
session_destroy();

// Redirect to login page or homepage
header("Location: login.php"); // change to your login page
exit;
?>
