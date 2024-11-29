<?php
require_once 'db_connect.php'; // Include the database connection

header('Content-Type: application/json'); // Ensure proper JSON response headers

$post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : null;

$response = []; // Initialize response array

if ($post_id) {
    $sql = "SELECT comment_content, comment_date FROM comments WHERE post_id = ? ORDER BY comment_date ASC";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = [
                'comment_content' => htmlspecialchars($row['comment_content']),
                'comment_date' => $row['comment_date'],
            ];
        }

        $response['comments'] = $comments;
    } else {
        $response['error'] = 'Failed to prepare the statement.';
    }
    $stmt->close();
} else {
    $response['error'] = 'No post ID provided.';
}

echo json_encode($response); // Send JSON response
?>
