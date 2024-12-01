document.addEventListener("DOMContentLoaded", () => {
    const commentForm = document.querySelector("#comment-form"); // Ensure form ID is correct

    if (commentForm) {
        commentForm.addEventListener("submit", (e) => {
            e.preventDefault(); // Prevent default form submission behavior

            const formData = new FormData(commentForm);
            const postId = document.querySelector(".inbox-post-header").dataset.postId;

            if (postId) {
                formData.append("post_id", postId);
            } else {
                alert("Post ID is missing. Unable to submit comment.");
                return;
            }

            fetch("php/insert_comment.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        alert("Comment submitted successfully!");
                        loadComments(postId); // Reload comments after successful submission
                        commentForm.reset(); // Clear the form
                    } else {
                        alert(data.message || "Error submitting comment.");
                    }
                })
                .catch(() => {
                    alert("Error submitting your comment. Please try again.");
                });
        });
    }
});

// Function to reload comments
function loadComments(postId) {
    const commentsContainer = document.querySelector(".inbox-comments");
    commentsContainer.innerHTML = "<p>Loading comments...</p>";

    fetch(`php/get_comment.php?post_id=${encodeURIComponent(postId)}`)
        .then((response) => {
            if (!response.ok) throw new Error("Failed to load comments.");
            return response.json();
        })
        .then((data) => {
            commentsContainer.innerHTML = "";

            if (data.comments && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentElement = document.createElement("div");
                    commentElement.classList.add("inbox-comment");
                    commentElement.innerHTML = `
                        <p>${sanitizeHtml(comment.comment_content)}</p>
                        <span class="inbox-comment-time">${new Date(comment.comment_date).toLocaleString()}</span>
                    `;
                    commentsContainer.appendChild(commentElement);
                });
            } else {
                commentsContainer.innerHTML = "<p>No comments yet. Be the first to comment!</p>";
            }
        })
        .catch(() => {
            commentsContainer.innerHTML = "<p>Error loading comments. Please try again later.</p>";
        });
}

// Function to sanitize HTML input/output
function sanitizeHtml(input) {
    const div = document.createElement("div");
    div.textContent = input;
    return div.innerHTML;
}
