document.addEventListener("DOMContentLoaded", () => {
    const randomPostButton = document.querySelector("#randomPostBtn");
    const postSearchButton = document.querySelector("#searchPostBtn");
    const inboxPostHeader = document.querySelector(".inbox-post-header");
    const inboxPostBody = document.querySelector(".inbox-post-body");
    const inboxComments = document.querySelector(".inbox-comments");

    // Fetch a post (random or by ID)
    function fetchPost(action, postId = null) {
        const url = action === "search" ? `php/get_post.php?action=${action}&post_id=${postId}` : `php/get_post.php?action=random`;

        fetch(url)
            .then((response) => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.json();
            })
            .then((data) => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Update the post display
                inboxPostHeader.textContent = `#${data.post_id} - ${data.post_name} (${new Date(data.post_datetime).toLocaleString()})`;
                inboxPostHeader.dataset.postId = data.post_id; // Update postId for reference
                inboxPostBody.textContent = data.post_content;

                // Fetch and display comments
                fetchComments(data.post_id);
            })
            .catch((error) => console.error("Error fetching post:", error));
    }

    // Fetch comments for a post
    function fetchComments(postId) {
        console.log("Fetching comments for post ID:", postId);

        fetch(`php/get_comment.php?post_id=${postId}`)
            .then((response) => {
                if (!response.ok) throw new Error("Failed to fetch comments");
                return response.json();
            })
            .then((data) => {
                // Check the fetched comments data
                console.log("Fetched comments:", data);
                const comments = data.comments;
                inboxComments.innerHTML = "";  // Clear loading text

                // Insert comments into the DOM
                if (comments.length > 0) {
                    comments.forEach((comment) => {
                        inboxComments.insertAdjacentHTML(
                            "beforeend",
                            `
                            <div class="inbox-comment">
                                <p>${comment.comment_content}</p>
                                <span class="inbox-comment-time">${new Date(comment.comment_date).toLocaleString()}</span>
                            </div>
                            `
                        );
                    });
                } else {
                    inboxComments.innerHTML = "<p>No comments yet. Be the first to comment!</p>";
                }

                // Insert the comment form
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

                // Attach event listener to handle new comments
                attachCommentFormListener(postId);
            })
            .catch((error) => {
                console.error("Error fetching comments:", error);
                inboxComments.innerHTML = "<p>Failed to load comments. Please try again later.</p>";
            });
    }

    // Attach comment form listener
    function attachCommentFormListener(postId) {
        const commentForm = document.querySelector(".inbox-add-comment form");
        if (!commentForm) {
            console.error("Error: Comment form not found.");
            return;
        }

        // Prevent default form submission
        commentForm.addEventListener("submit", function (event) {
            event.preventDefault();

            const formData = new FormData(commentForm);

            fetch("php/insert_comment.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Comment added successfully!");
                        fetchComments(postId); // Refresh comments
                    } else {
                        alert(data.message || "An error occurred while adding your comment.");
                    }
                })
                .catch((error) => {
                    console.error("Error submitting comment:", error);
                    alert("Failed to submit your comment. Please try again.");
                });
        });
    }

    // Random Post Button
    randomPostButton.addEventListener("click", () => fetchPost("random"));

    // Post Search Button
    postSearchButton.addEventListener("click", () => {
        const postId = prompt("Enter the 6-digit Post ID:");
        if (postId) fetchPost("search", postId);
    });

    // Automatically fetch a random post on page load
    fetchPost("random");
});
