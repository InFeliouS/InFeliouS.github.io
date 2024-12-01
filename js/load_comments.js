document.addEventListener("DOMContentLoaded", () => {
    // Function to load comments for a specific post
    function loadComments(postId) {
        const commentsContainer = document.querySelector(".inbox-comments");

        if (!commentsContainer) {
            console.error("Comments container not found.");
            return;
        }

        commentsContainer.innerHTML = "<p>Loading comments...</p>"; // Show a loading message

        // Fetch comments for the given `postId`
        fetch(`php/get_comment.php?post_id=${encodeURIComponent(postId)}`)
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.json(); // Parse JSON response
            })
            .then((data) => {
                commentsContainer.innerHTML = ""; // Clear any existing content

                // Check and display comments if available
                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach((comment) => {
                        const commentElement = document.createElement("div");
                        commentElement.classList.add("inbox-comment");

                        // Add sanitized content
                        commentElement.innerHTML = `
                            <p>${sanitizeHtml(comment.comment_content)}</p>
                            <span class="inbox-comment-time">Posted on: ${new Date(comment.comment_date).toLocaleString()}</span>
                        `;
                        commentsContainer.appendChild(commentElement);
                    });
                } else {
                    commentsContainer.innerHTML = "<p>No comments available for this post.</p>";
                }
            })
            .catch((error) => {
                console.error("Error loading comments:", error);
                commentsContainer.innerHTML = "<p>Failed to load comments. Please try again later.</p>";
            });
    }

    // Sanitize HTML to prevent XSS
    function sanitizeHtml(html) {
        const tempDiv = document.createElement("div");
        tempDiv.textContent = html;
        return tempDiv.innerHTML;
    }

    // Get the post ID from the `.inbox-post-header` element
    const postHeader = document.querySelector(".inbox-post-header");
    if (postHeader) {
        const postId = postHeader.dataset.postId;
        if (postId) {
            loadComments(postId); // Load comments for the provided post ID
        } else {
            console.error("Post ID not found in the `.inbox-post-header` element.");
        }
    } else {
        console.error("Post header element `.inbox-post-header` not found.");
    }
});
