<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogger-Sign up</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="icon" href="../bebo-logo.png" type="image/x-icon">
</head>
<body>
    <div class="container">
    <div>
        <h1>Blogger</h1>
    </div>
    <form method="POST" action="authentication.php">
        <?php if (isset($_GET['error'])): ?>
            <div class="error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        <p>Create an account</p>
        <input type="hidden" name="form_type" value="signup">
        <input placeholder="Email" type="email" name="email">
        <input placeholder="Username" type="text" name="username">
        <div class="age-div">
            <label>Birthdate</label>
            <input type="date" name="birthdate">
        </div>
        <input placeholder="Password" type="password" name="password">
        <input placeholder="Retype password" type="password">
        <input type="submit" value="Sign up">
        <div class="no-account">have an account already ?<span><a href="../index.php">Sign in</a></span></div>

    </form>
    <footer>

    </footer>
    </div>
    
</body>
</html>