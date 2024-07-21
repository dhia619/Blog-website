$(document).ready(function() {

    // Create a post
    $(".create-a-post").click(function() {
        $(".create-post-popup").fadeIn("slow");
    });

    $(".post-button").click(function() {
        var postContent = $('#postContent').val();

        $.ajax({
            url: 'create_post.php',
            type: 'POST',
            data: {
                postContent: postContent
            },
            dataType: 'json', // Ensure the response is interpreted as JSON
            success: function(response) {
                if (response.error) {
                    $('#message').html('<p>Error: ' + response.error + '</p>');
                } else {
                    console.log("Post author:", response.username); // Log specific properties
                    $(".create-post-popup").hide();
                    
                    $('.posts-container').prepend(`
                        <div class="post-card">
                            <div class="post-user_name">${response.username}</div>
                            <div class="post-date">${response.posting_date}</div>
                            <div class="post-content">${response.post_content}</div>
                            <div class="post-stats">
                                <div><span class="like-count">${response.post_likes}</span> Likes</div>
                                <div><span>0</span> Comments</div>
                            </div>
                            <div class="horz_line"></div>
                            <div class="post-buttons">
                                <button class="like-button" data-post-id="${response.post_id}">
                                    <img class="button-image" src="../assets/like.png" width="32px">
                                    <span class="button-text">Like</span>
                                </button>
                                <button class="comment-button" data-post-id="${response.post_id}">
                                    <img src="../assets/comment.png" width="32px">
                                    Comments
                                </button>
                            </div>
                        </div>
                    `);

                    $('#postContent').val(''); // Clear textarea
                }
            },
            error: function(xhr, status, error) {
                $('#message').html('<p>Error creating post: ' + error + '</p>');
            }
        });
    });

    // Hide any popup when page first loads or when close button is pressed
    $(".popup").hide();
    
    $(".close").click(function() {
        $(this).closest(".popup").fadeOut("slow");
    });

    // Comments section
    $(document).on("click", ".comment-button", function() {
        $(".comments-popup").fadeIn("slow");
        
        var postId = $(this).data("post-id");

        $.ajax({
            url: "../php/get_comments.php",
            type: "GET",
            data: { post_id: postId },
            success: function(data) {
                console.log("Response from server:", data); // Log the response
                try {
                    var comments = JSON.parse(data);
                    var commentsHtml = "";
        
                    comments.forEach(function(comment) {
                        commentsHtml += `
                            <div class="comment">
                                <div class="comment-user">${comment.username}</div>
                                <div class="comment-date">${comment.comment_date}</div>
                                <div class="comment-content">${comment.comment_content}</div>
                            </div>
                        `;
                    });
        
                    $("#comments-container").html(commentsHtml);
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });

    // Profile mini panel logic
    $(".user_mini_panel").hide();
    $("#user_mini_img").click(function() {
        $(".user_mini_panel").toggle("slow");
    });

    // Like button logic
    $(document).on("click", ".like-button", function(e) {
        e.preventDefault();

        var postId = $(this).data("post-id");
        var button = $(this);
        var likeCountElement = $(this).closest(".post-card").find(".like-count");

        $.ajax({
            type: "POST",
            url: "../php/like_post.php",
            data: { post_id: postId },
            success: function(response) {
                console.log(response); // Log response for debugging
                if (response.liked) {
                    button.addClass("liked"); // Add 'liked' class
                    button.find(".button-text").text("Liked");
                    button.find(".button-image").attr("src", "../assets/liked.png");
                } else {
                    button.removeClass("liked"); // Remove 'liked' class
                    button.find(".button-text").text("Like");
                    button.find(".button-image").attr("src", "../assets/like.png");
                }
                if (response.likes >= 1000) {
                    likeCountElement.text(Math.floor(response.likes / 1000) + " K");
                } else {
                    likeCountElement.text(response.likes);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
            }
        });
    });
});
