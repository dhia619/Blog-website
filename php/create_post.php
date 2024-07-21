<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "blogger");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}

include "functions.php";

function get_new_post_id($conn){
    $query = $conn->prepare("SELECT MAX(post_id) AS max_post_id FROM post");
    $query->execute();
    $result = $query->get_result();
    $last_id = $result->fetch_assoc();
    return intval($last_id["max_post_id"]) + 1;
}

$content = $_POST["postContent"];
$new_post_id = get_new_post_id($conn);
$user_id = $_SESSION["user_id"];
$posting_date = date("Y-m-d H:i:s");
$likes = 0;

$query = $conn->prepare("INSERT INTO post (post_id, user_id, post_content, post_likes, posting_date) VALUES (?, ?, ?, ?, ?)");
$query->bind_param("iisis", $new_post_id, $user_id, $content, $likes, $posting_date);
if ($query->execute()) {
    // Return the new post data as a JSON response
    $response = [
        'post_id' => $new_post_id,
        'username' => get_username($conn, $_SESSION["user_id"]),
        'posting_date' => date(time()),
        'post_content' => htmlspecialchars($content),
        'post_likes' => 0
    ];
    echo json_encode($response);
} else {
    // Return an error message
    echo json_encode(['error' => 'Failed to create post']);
}

$conn->close();
?>
