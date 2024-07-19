<?php
session_start();
if(isset($_COOKIE["user_id"])){
    $_SESSION["user_id"] = intval($_COOKIE["user_id"]);
}
# Connect to the database
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}
mysqli_select_db($conn, "blogger") or die("error connecting to db");

//function to see if the current user liked a post or not
function userHasLiked($conn, $post_id, $user_id) {
    $query = $conn->prepare("SELECT * FROM user_likes WHERE post_id = ? AND user_id = ?");
    if (!$query) {
        die("Prepare failed: " . $conn->error);
    }
    $query->bind_param("ii", $post_id, $user_id);
    $query->execute();
    return $query->get_result()->num_rows > 0;
}

//function to get the author of a post
function get_username($conn,$user_id){
    $query = $conn->prepare("SELECT * FROM user WHERE id= ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $user_data = $query->get_result();
    $user_data = $user_data->fetch_assoc();
    return $user_data["username"];
}

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/home.js"></script>
</head>
<body>

<header>
    <div class="right-head-child">
        <a href="home.php"><img src="../assets/bebo-logo.png"></a>
    </div>
    <nav class="center-head-child">
        <ul>
            <li><a href="home.php"><img src="../assets/home.png" width="32px"></a></li>
            <li><img src="../assets/chat.png" width="32px"></li>
            <li><img src="../assets/groups.png" width="32px"></li>
        </ul>
    </nav>
    <div class="left-head-child">
        <img id="user_mini_img" src="../assets/user.png" width="40px">
        <div class="user_mini_panel">
        <div class="mini_panel_username"><?php echo get_username($conn, $_SESSION["user_id"]); ?></div>
        <div class="horz_line"></div>
        <ul>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    </div>
</header>

<div class="home-container">
    <div class="right-child"></div>
    <div class="posts-container">
        <?php if ($posts->num_rows >= 1): ?>
            <?php while ($post = $posts->fetch_assoc()): ?>
                <div class="post-card">
                    <div class="post-user_name"><?php echo htmlspecialchars(get_username($conn,$post["user_id"])); ?></div>
                    <div class="post-date"><?php echo htmlspecialchars($post["posting_date"]); ?></div>
                    <div class="post-content"><?php echo nl2br(htmlspecialchars($post["post_content"])); ?></div>
                    <div class="post-stats">
                        <div><span class="like-count">
                            <?php 
                            if (intval($post["post_likes"])>=1000){
                                echo htmlspecialchars(floor(intval($post["post_likes"])/1000)." K");
                            }
                            else
                                echo htmlspecialchars($post["post_likes"]);
                            ?></span> Likes
                        </div>
                        <div><span>0</span> Comments</div>
                    </div>
                    <div class="horz_line"></div>
                    <div class="post-buttons">
                    <?php 
                    if(userHasLiked($conn, intval($post["post_id"]), intval($_SESSION["user_id"]))) { ?>
                        <button class="like-button liked" data-post-id="<?php echo $post['post_id']; ?>">
                            <img class="button-image" src="../assets/liked.png" width="32px">
                            <span class="button-text">Liked</span>
                        </button>
                    <?php } else { ?>
                        <button class="like-button" data-post-id="<?php echo $post['post_id']; ?>">
                            <img class="button-image" src="../assets/like.png" width="32px">
                            <span class="button-text">Like</span>
                        </button>
                    <?php } ?>
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
