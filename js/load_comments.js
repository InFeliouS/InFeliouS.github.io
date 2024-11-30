document.addEventListener("DOMContentLoaded", () => {
    // Function to load comments for the post
    function loadComments(postId) {
        const commentsContainer = document.querySelector(".inbox-comments");
        commentsContainer.innerHTML = "<p>Loading comments...</p>"; // Show loading message

        // Fetch comments using the provided `postId`
        fetch(`php/get_comment.php?post_id=${postId}`)
            .then(response => response.json()) // Parse response as JSON
            .then(data => {
                commentsContainer.innerHTML = ""; // Clear previous content

                // Check if comments are available
                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        // Create and append a comment element
                        const commentElement = document.createElement("div");
                        commentElement.classList.add("inbox-comment");
                        commentElement.innerHTML = `
                            <p>${comment.comment_content}</p>
                            <span class="inbox-comment-time">Posted on: ${new Date(comment.comment_date).toLocaleString()}</span>
                        `;
                        commentsContainer.appendChild(commentElement);
                    });
                } 
            })
            .catch(error => {
                console.error("Error loading comments:", error);
                commentsContainer.innerHTML = "<p>Failed to load comments. Please try again later.</p>";
            });
    }

    // Get post ID from the `.inbox-post-header` element
    const postHeader = document.querySelector(".inbox-post-header");
    if (postHeader) {
        const postId = postHeader.dataset.postId;
        if (postId) {
            loadComments(postId); // Load comments for the given post
        } else {
            console.error("Post ID not found in the `.inbox-post-header` element.");
        }
    }
});
