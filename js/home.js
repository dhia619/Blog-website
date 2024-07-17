$(document).ready(function(){
    $(".user_mini_panel").hide();
    $("#user_mini_img").click(function(){
        $(".user_mini_panel").toggle("slow");
    })
})