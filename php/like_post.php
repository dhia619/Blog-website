<?php
session_start(); // Start the session to access user ID
header('Content-Type: application/json'); // Set header for JSON response
# Connect to the database
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}
mysqli_select_db($conn, "blogger") or die("error connecting to db");

# Check if the post_id is set and user_id is available in session
if (isset($_POST['post_id']) && isset($_SESSION['user_id'])) {
    $post_id = intval($_POST['post_id']);
    $user_id = intval($_SESSION['user_id']);

    # Check if the user has already liked the post
    $check_like_query = $conn->prepare("SELECT * FROM user_likes WHERE post_id = ? AND user_id = ?");
    $check_like_query->bind_param("ii", $post_id, $user_id);
    $check_like_query->execute();
    $like_result = $check_like_query->get_result();

    if ($like_result->num_rows > 0) {
        # User has already liked the post, so remove the like
        $delete_like_query = $conn->prepare("DELETE FROM user_likes WHERE post_id = ? AND user_id = ?");
        $delete_like_query->bind_param("ii", $post_id, $user_id);
        $delete_like_query->execute();

        # Decrement the like count
        $update_query = $conn->prepare("UPDATE post SET post_likes = post_likes - 1 WHERE post_id = ?");
        $update_query->bind_param("i", $post_id);
        $update_query->execute();

        # Get the new like count
        $like_count_query = $conn->prepare("SELECT post_likes FROM post WHERE post_id = ?");
        $like_count_query->bind_param("i", $post_id);
        $like_count_query->execute();
        $like_count_result = $like_count_query->get_result();
        $like_count_row = $like_count_result->fetch_assoc();

        echo json_encode(['liked' => false, 'likes' => $like_count_row["post_likes"]]);
    } else {
        # User has not liked the post, so add the like
        $insert_like_query = $conn->prepare("INSERT INTO user_likes (user_id, post_id) VALUES (?, ?)");
        $insert_like_query->bind_param("ii", $user_id, $post_id);
        $insert_like_query->execute();

        # Increment the like count
        $update_query = $conn->prepare("UPDATE post SET post_likes = post_likes + 1 WHERE post_id = ?");
        $update_query->bind_param("i", $post_id);
        $update_query->execute();

        # Get the new like count
        $like_count_query = $conn->prepare("SELECT post_likes FROM post WHERE post_id = ?");
        $like_count_query->bind_param("i", $post_id);
        $like_count_query->execute();
        $like_count_result = $like_count_query->get_result();
        $like_count_row = $like_count_result->fetch_assoc();

        echo json_encode(['liked' => true, 'likes' => $like_count_row["post_likes"]]);
    }
}

# Close the database connection
$conn->close();
?>
