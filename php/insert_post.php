<?php
// Include the database connection file and the profanity filter library
require_once 'db_connect.php';
require_once 'vendor/autoload.php';

use ConsoleTVs\Profanity\Builder as Profanity;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $post_name = $conn->real_escape_string(trim($_POST['post_name']));
    $post_content = $conn->real_escape_string(trim($_POST['post_content']));

    if (empty($post_name) || empty($post_content) || strlen($post_name) > 25 || strlen($post_content) > 100) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    // Apply profanity filter
    $clean_post_name = Profanity::blocker($post_name, languages: ['en', 'fil'])->filter();
    $clean_post_content = Profanity::blocker($post_content, languages: ['en', 'fil'])->filter();

    // Prepare and execute SQL statement
    $stmt = $conn->prepare("INSERT INTO post (post_name, post_content) VALUES (?, ?)");
    $stmt->bind_param("ss", $clean_post_name, $clean_post_content);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Post created successfully!', 'post_id' => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Error creating post.']);
    }

    $stmt->close();
}

$conn->close();
?>
