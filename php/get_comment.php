<?php
require_once 'db_connect.php'; // Include the database connection

header('Content-Type: application/json'); // Set response header for JSON output

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : null; // Validate and sanitize post_id
$response = []; // Initialize response array

if ($post_id) {
    try {
        $sql = "SELECT comment_content, comment_date FROM comments WHERE post_id = ? ORDER BY comment_date ASC";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $post_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $comments = [];
            while ($row = $result->fetch_assoc()) {
                $comments[] = [
                    'comment_content' => htmlspecialchars($row['comment_content']), // Sanitize output
                    'comment_date' => $row['comment_date'],
                ];
            }

            $response['comments'] = $comments;
        } else {
            $response['error'] = 'Failed to prepare the statement.';
        }
    } catch (Exception $e) {
        $response['error'] = 'An unexpected error occurred.';
    } finally {
        if (isset($stmt)) {
            $stmt->close(); // Close the prepared statement
        }
        $conn->close(); // Close the database connection
    }
} else {
    $response['error'] = 'No post ID provided.';
}

echo json_encode($response); // Send JSON response
?>
