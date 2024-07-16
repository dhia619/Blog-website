<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="../assets/bebo-logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div>
            <h1>Blogger</h1>
            <p>A place where you can post blogs without any restrictions</p>
        </div>
        <form method="POST" action="authentication.php">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <input type="hidden" name="form_type" value="signin">
            <input placeholder="Email" type="email" name="email" required>
            <input placeholder="Password" type="password" name="password" required>
            <div class="side-to-side-only">
                <span style="display:flex;gap:10px;">
                    <input type="checkbox" id="remember-me" name="remember_me">
                    <label for="remember-me" style="cursor:pointer;">Remember me</label>
                </span>
                <span><a href="resetpwd.php">Forgot password?</a></span>
            </div>
            <input type="submit" value="Sign in">
            <div class="no-account">don't have an account?<span><a href="signup.php">Sign up</a></span></div>
        </form>
        <footer>
        </footer>
    </div>
</body>
</html>
