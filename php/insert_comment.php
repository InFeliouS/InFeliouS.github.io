<?php
// Include the database connection file and the profanity library
require_once 'db_connect.php';
require_once 'vendor/autoload.php'; // Ensure the Profanity library is installed via Composer

use ConsoleTVs\Profanity\Builder;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize inputs
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : null;
    $comment_content = isset($_POST['comment_content']) ? trim($_POST['comment_content']) : null;

    // Check for missing input
    if (!$post_id || !$comment_content) {
        echo json_encode(['success' => false, 'message' => 'Post ID or comment content is missing.']);
        exit;
    }

    try {
        // Apply profanity filter
        $clean_comment_content = Builder::blocker($comment_content, languages: ['en', 'fil'])->filter();

        // Check if the post already has 3 comments
        $sql_check_count = "SELECT COUNT(*) AS comment_count FROM comments WHERE post_id = ?";
        $stmt = $conn->prepare($sql_check_count);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row['comment_count'] >= 3) {
            echo json_encode(['success' => false, 'message' => 'This post already has 3 comments.']);
            exit;
        }

        // Insert the new comment with the cleaned content
        $sql_insert = "INSERT INTO comments (post_id, comment_content) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("is", $post_id, $clean_comment_content);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Comment added successfully!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add comment.']);
        }
        $stmt->close();
    } catch (Exception $e) {
        // Generic error message for production
        echo json_encode(['success' => false, 'message' => 'An unexpected error occurred.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Close the database connection
$conn->close();
?>
