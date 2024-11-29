<?php
// Include the database connection file
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate incoming data
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
    $comment_content = isset($_POST['comment_content']) ? trim($_POST['comment_content']) : null;

    // Check for missing input
    if (empty($post_id) || empty($comment_content)) {
        echo json_encode(['success' => false, 'message' => 'Post ID or comment content is missing.']);
        exit;
    }

    try {
        // Check if there are already 3 comments for this post
        $sql_check_count = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = ?";
        $stmt = $conn->prepare($sql_check_count);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $comment_count = $row['comment_count'];
        $stmt->close();

        if ($comment_count >= 3) {
            echo json_encode(['success' => false, 'message' => 'This post already has 3 comments.']);
            exit;
        }

        // Insert the new comment
        $sql_insert = "INSERT INTO comments (post_id, comment_content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("is", $post_id, $comment_content);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Comment added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to insert comment.']);
        }
        $stmt->close();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
$conn->close();
?>
