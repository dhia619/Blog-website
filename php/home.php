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

include "functions.php";

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
    <!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
    <script src="../js/jquery-3.7.0.min.js"></script> 
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
    <div class="feed-container">
        <div class="create-a-post post-card">
            <div class="upper-child">
                Post Something
                <div class="horz_line"></div>
            </div>
            <div class="lower-child">
                <input type="text" placeholder="What's on your mind ...">
                <div class="attach-buttons">
                    <button><img src="../assets/photo.png" width="40px"></button>
                    <button><img src="../assets/video.png" width="40px"></button>
                    <button><img src="../assets/music.png" width="40px"></button>
                </div>
            </div>
        </div>
        <div class="posts-container">
            <?php if ($posts->num_rows >= 1): ?>
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="post-card">
                        <div style="display:flex;justify-content:center;align-items:center;gap:10px">
                            <div class="post-user_profile_picture"><img src="../assets/user.png" width=40px></div>
                            <div class="post-user_name"><?php echo htmlspecialchars(get_username($conn,$post["user_id"])); ?></div>
                        </div>    
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
                        <button class="comment-button" data-post-id="<?php echo $post['post_id']; ?>">
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
    </div>
    <div class="left-child"></div>
</div>

<div class="comments-popup popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <div id="write-comment">
            <input type="text" placeholder="write a comment">
            <button type="button"><img src="../assets/paper-plane.png"></button>
        </div>
        <div id="comments-container"></div>
    </div>
</div>

<div class="create-post-popup popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <div id="create-container">
            <div class="popup-header">
                Post
                <div class="horz_line"></div>
            </div>
            <div class="create-space" id="create-post-form">
                <textarea id="postContent" name="postContent" rows="8" cols="50" placeholder="What's on your mind?"></textarea>
                <div class="create-a-post-buttons">
                    <div class="attach-buttons">
                        <button><img src="../assets/photo.png" width="40px"></button>
                        <button><img src="../assets/video.png" width="40px"></button>
                        <button><img src="../assets/music.png" width="40px"></button>
                    </div>
                    <button class="post-button">Post</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="message"></div> <!-- To display the success or error message -->

</body>
</html>

<?php

# Close the database connection
$conn->close();
?>
