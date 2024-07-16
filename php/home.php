<?php

# Connect to the database
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}
mysqli_select_db($conn, "blogger") or die("error connecting to db");

# Prepare and execute the query
$retrieve_posts_query = $conn->prepare("SELECT * FROM post");
$retrieve_posts_query->execute();
$posts = $retrieve_posts_query->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="icon" href="../assets/bebo-logo.png" type="image/x-icon">
    <title>blogger-Home</title>
</head>
<body>

<header>
    <div class="right-head-child">
        <img src="../assets/bebo-logo.png">
    </div>
    <nav class="center-head-child">
        <ul>
            <li><img src="../assets/"></li>
            <li><img src="../assets/"></li>
            <li><img src="../assets/"></li>
        </ul>
    </nav>
    <div class="left-head-child">
        <img src="../assets/avatar.png" width="32px">
    </div>
</header>

<div class="home-container">
    <div class="right-child"></div>
    <div class="posts-container">
        <?php if ($posts->num_rows >= 1): ?>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="post-card">
                    <div class="post-user_name"><?php echo htmlspecialchars($post["user_id"]); ?></div>
                    <div class="post-date"><?php echo htmlspecialchars($post["posting_date"]); ?></div>
                    <div class="post-content"><?php echo nl2br(htmlspecialchars($post["post_content"])); ?></div>
                    <div class="post-stats">
                        <diV><span><?php echo htmlspecialchars($post["post_likes"]); ?></span> Likes</div>
                        <div><span>0</span> Comments</div>
                    </div>
                    <div class="post-buttons">
                        <button>
                            <img src="../assets/like.png" width="32px">
                            Like
                        </button>
                        <button>
                            <img src="../assets/comment.png" width="32px">
                            Comments
                        </button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No posts available.</p>
        <?php endif; ?>
    </div>
    <div class="left-child"></div>
</div>
</body>
</html>

<?php
# Close the database connection
$conn->close();
?>
