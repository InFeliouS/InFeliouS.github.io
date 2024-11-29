<?php
require_once 'db_connect.php'; // Ensure you include your database connection

$action = $_GET['action'] ?? null;

if ($action === 'random') {
    $result = $conn->query("SELECT * FROM post ORDER BY RAND() LIMIT 1");
} elseif ($action === 'search' && isset($_GET['post_id'])) {
    $postId = $conn->real_escape_string($_GET['post_id']);
    $result = $conn->query("SELECT * FROM post WHERE post_id = '$postId'");
} else {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

if ($result && $post = $result->fetch_assoc()) {
    $postId = $post['post_id'];
    $commentsResult = $conn->query("SELECT * FROM comments WHERE post_id = '$postId'");
    $post['comments'] = $commentsResult ? $commentsResult->fetch_all(MYSQLI_ASSOC) : [];
    echo json_encode($post); // Includes post_name, post_content, and post_datetime
} else {
    echo json_encode(['error' => 'Post not found']);
}

$conn->close();
?>