<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger - Sign Up</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="../bebo-logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
        <div>
            <h1>Blogger</h1>
        </div>
        <form method="POST" action="authentication.php">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error"><?php echo htmlspecialchars($_SESSION['error']); ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <p>Create an account</p>
            <input type="hidden" name="form_type" value="signup">
            <input placeholder="Email" type="email" name="email" required>
            <input placeholder="Username" type="text" name="username" required>
            <div class="age-div">
                <label>Birthdate</label>
                <input type="date" name="birthdate" required>
            </div>
            <input placeholder="Password" type="password" name="password" required>
            <input placeholder="Retype password" type="password" required>
            <input type="submit" value="Sign up">
            <div class="no-account">Have an account already?<span><a href="../index.php">Sign in</a></span></div>
        </form>
        <footer>
        </footer>
    </div>
</body>
</html>
