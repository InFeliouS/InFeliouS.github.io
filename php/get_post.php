<?php
require_once 'db_connect.php'; // Include your database connection

$action = $_GET['action'] ?? null;

try {
    if ($action === 'random') {
        // Fetch a random post
        $stmt = $conn->query("SELECT * FROM post ORDER BY RAND() LIMIT 1");
    } elseif ($action === 'search' && isset($_GET['post_id'])) {
        // Fetch a specific post by ID
        $postId = intval($_GET['post_id']);
        $stmt = $conn->prepare("SELECT * FROM post WHERE post_id = ?");
        $stmt->bind_param("i", $postId);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Invalid request
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Check if a post was found
    if ($stmt && $post = ($action === 'random' ? $stmt->fetch_assoc() : $result->fetch_assoc())) {
        $postId = $post['post_id'];

        // Fetch associated comments
        $commentsStmt = $conn->prepare("SELECT * FROM comments WHERE post_id = ?");
        $commentsStmt->bind_param("i", $postId);
        $commentsStmt->execute();
        $commentsResult = $commentsStmt->get_result();
        $post['comments'] = $commentsResult->fetch_all(MYSQLI_ASSOC);

        // Return the post data as JSON
        echo json_encode($post); // Includes post_name, post_content, and post_datetime
    } else {
        echo json_encode(['error' => 'Post not found']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred. Please try again later.']);
} finally {
    // Close database connections
    if (isset($stmt)) $stmt->close();
    if (isset($commentsStmt)) $commentsStmt->close();
    $conn->close();
}
?>
