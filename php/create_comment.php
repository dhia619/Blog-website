<?php 

session_start();
$conn = mysqli_connect("localhost", "root", "", "blogger");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}

include "functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_content = $_POST["comment_content"];
    $post_id = $_POST["post_id"];
    if (isset($comment_content) && isset($post_id)){
        $comment_date = date("Y-m-d H:i:s");
        $query = "INSERT INTO comment (post_id,user_id,comment_date,comment_content)
        VALUES(?,?,?,?);";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiss",$_POST["post_id"],$_SESSION["user_id"],$comment_date,$comment_content);
        if($stmt->execute()){
            echo json_encode(["username"=>get_username($conn,$_SESSION["user_id"]),"comment_date"=>$comment_date,"comment_content"=>$comment_content]);
        }
    }
}

?>