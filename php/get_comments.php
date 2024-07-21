<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "blogger");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}

$post_id = intval($_GET['post_id']);

$query = $conn->prepare("SELECT c.comment_content, c.comment_date, u.username 
                         FROM comment c 
                         JOIN user u ON c.user_id = u.id 
                         WHERE c.post_id = ?");
$query->bind_param("i", $post_id);
$query->execute();
$result = $query->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode($comments);

$conn->close();
?>
