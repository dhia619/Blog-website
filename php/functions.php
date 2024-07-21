<?php

// Function to see if the current user liked a post or not
function userHasLiked($conn, $post_id, $user_id) {
    $query = $conn->prepare("SELECT * FROM user_likes WHERE post_id = ? AND user_id = ?");
    if (!$query) {
        die("Prepare failed: " . $conn->error);
    }
    $query->bind_param("ii", $post_id, $user_id);
    $query->execute();
    return $query->get_result()->num_rows > 0;
}

// Function to get the author of a post
function get_username($conn, $user_id) {
    $query = $conn->prepare("SELECT * FROM user WHERE id= ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $user_data = $query->get_result();
    $user_data = $user_data->fetch_assoc();
    return $user_data["username"];
}
?>
