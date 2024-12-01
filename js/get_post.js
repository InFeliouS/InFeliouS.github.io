document.addEventListener("DOMContentLoaded", () => {
    const randomPostButton = document.querySelector("#randomPostBtn");
    const postSearchButton = document.querySelector("#searchPostBtn");
    const inboxPostHeader = document.querySelector(".inbox-post-header");
    const inboxPostBody = document.querySelector(".inbox-post-body");
    const inboxComments = document.querySelector(".inbox-comments");

    // Fetch a post (random or by ID)
    function fetchPost(action, postId = null) {
        const url = action === "search" ? `php/get_post.php?action=search&post_id=${encodeURIComponent(postId)}` : `php/get_post.php?action=random`;

        fetch(url)
            .then(response => {
                if (!response.ok) throw new Error("Failed to fetch post.");
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    displayError("Post not found or invalid request.");
                    return;
                }

                // Update post content
                inboxPostHeader.textContent = `#${data.post_id} - ${sanitizeHtml(data.post_name)} (${new Date(data.post_datetime).toLocaleString()})`;
                inboxPostHeader.dataset.postId = data.post_id;
                inboxPostBody.textContent = sanitizeHtml(data.post_content);

                // Fetch comments
                fetchComments(data.post_id);
            })
            .catch(() => displayError("Error loading post. Please try again later."));
    }

    // Fetch comments for a post
    function fetchComments(postId) {
        inboxComments.innerHTML = "<p>Loading comments...</p>";

        fetch(`php/get_comment.php?post_id=${postId}`)
            .then(response => {
                if (!response.ok) throw new Error("Failed to fetch comments.");
                return response.json();
            })
            .then(data => {
                inboxComments.innerHTML = "";

                if (data.comments && data.comments.length > 0) {
                    data.comments.forEach(comment => {
                        const commentElement = document.createElement("div");
                        commentElement.classList.add("inbox-comment");
                        commentElement.innerHTML = `
                            <p>${sanitizeHtml(comment.comment_content)}</p>
                            <span class="inbox-comment-time">${new Date(comment.comment_date).toLocaleString()}</span>
                        `;
                        inboxComments.appendChild(commentElement);
                    });
                } else {
                    inboxComments.innerHTML = "<p>No comments yet. Be the first to comment!</p>";
                }

                // Add comment form
                inboxComments.insertAdjacentHTML(
                    "beforeend",
                    `
                    <div class="inbox-add-comment">
                        <form>
                            <input type="hidden" name="post_id" value="${postId}">
                            <input type="text" name="comment_content" placeholder="Add comment here..." maxlength="100" required>
                            <button type="submit">Send</button>
                        </form>
                    </div>
                    `
                );
                attachCommentFormListener(postId);
            })
            .catch(() => {
                inboxComments.innerHTML = "<p>Error loading comments. Please try again later.</p>";
            });
    }

    // Handle comment form submission
    function attachCommentFormListener(postId) {
        const commentForm = document.querySelector(".inbox-add-comment form");
        if (!commentForm) return;

        commentForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(commentForm);

            fetch("php/insert_comment.php", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        fetchComments(postId); // Reload comments
                    } else {
                        alert(data.message || "Failed to add comment.");
                    }
                })
                .catch(() => alert("Error adding comment. Please try again later."));
        });
    }

    // Display error message
    function displayError(message) {
        inboxPostHeader.textContent = message;
        inboxPostBody.textContent = "";
        inboxComments.innerHTML = "<p>No content available.</p>";
    }

    // Sanitize user input or server response
    function sanitizeHtml(input) {
        const div = document.createElement("div");
        div.textContent = input;
        return div.innerHTML;
    }

    // Event Listeners
    randomPostButton.addEventListener("click", () => fetchPost("random"));
    postSearchButton.addEventListener("click", () => {
        const postId = prompt("Enter the Post ID:");
        if (postId) fetchPost("search", postId);
    });

    // Fetch a random post on page load
    fetchPost("random");
});
