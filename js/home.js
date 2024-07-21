
$(document).ready(function(){
    
    //create a post

    $(".create-a-post").click(function(){
        $(".create-post-popup").fadeIn("slow");
    })


    //hiding any popup when page first loads or when close button is pressed
    $(".popup").hide();
    
    $(".close").click(function(){
        $(".popup").hide();
    })
    
    //comments section
    $(".comment-button").click(function(){
        $(".comments-popup").fadeIn("slow");
        
        var postId = $(this).data("post-id");

        $.ajax({
            url: "get_comments.php",
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
        
                
        
    })
    
    

    //profile mini panel logic
    $(".user_mini_panel").hide();
    $("#user_mini_img").click(function(){
        $(".user_mini_panel").toggle("slow");
    })

    //like button logic
    $(".like-button").click(function(e) {
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
                    button.find(".button-image").attr("src", "../assets/liked.png");;
                } else {
                    button.removeClass("liked"); // Remove 'liked' class
                    button.find(".button-text").text("Like");
                    button.find(".button-image").attr("src", "../assets/like.png");;
                }
                if(response.likes>=1000){
                    likeCountElement.text(Math.floor(response.likes/1000)+" K");
                }
                else likeCountElement.text(response.likes);
                
            }
        });
    });

})