$(document).ready(function(){
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
                } else {
                    button.removeClass("liked"); // Remove 'liked' class
                    button.find(".button-text").text("Like");
                }
                if(response.likes>=1000){
                    likeCountElement.text(Math.floor(response.likes/1000)+" K");
                }
                else likeCountElement.text(response.likes);
                
            }
        });
    });

})