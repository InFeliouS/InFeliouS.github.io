<?php
// Include the database connection file
require_once 'db_connect.php';


// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data and sanitize inputs
    $post_name = $conn->real_escape_string(trim($_POST['post_name']));
    $post_content = $conn->real_escape_string(trim($_POST['post_content']));

    // Validate inputs
    if (empty($post_name) || empty($post_content)) {
        echo json_encode(['success' => false, 'message' => 'Both fields are required.']);
        exit;
    } elseif (strlen($post_name) > 25 || strlen($post_content) > 100) {
        echo json_encode(['success' => false, 'message' => 'Input exceeds character limits.']);
        exit;
    } else {
        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO post (post_name, post_content) VALUES (?, ?)");
        $stmt->bind_param("ss", $post_name, $post_content);

        // Execute the query and check for success
        if ($stmt->execute()) {
            $new_post_id = $stmt->insert_id; // Get the ID of the newly created post
            echo json_encode(['success' => true, 'message' => 'Post created successfully!', 'post_id' => $new_post_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error creating post.']);
        }

        // Close the statement
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
