<?php
session_start();
if (isset($_COOKIE["remember_me"])) {
    if ($_COOKIE["remember_me"] == "yes") {
        header("Location: php/home.php");
        exit;
    } else {
        header("Location: php/login.php");
        exit;
    }
} else {
    // Calculate expiration time (10 years from now)
    $cookie_expiration = time() + (10 * 365 * 24 * 60 * 60); // 10 years
    setcookie("remember_me", "no", $cookie_expiration, "/", "", true, true);
    header("Location: php/login.php");
    exit;
}
?>
