document.addEventListener("DOMContentLoaded", () => {
    const commentForm = document.querySelector("#comment-form"); // Ensure form ID is correct

    if (commentForm) {
        commentForm.addEventListener("submit", (e) => {
            e.preventDefault(); // Prevent default form submission behavior

            const formData = new FormData(commentForm);
            const postId = document.querySelector(".inbox-post-header").dataset.postId;

            if (postId) {
                formData.append("post_id", postId);
            }

            fetch("php/insert_comment.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        console.log("Comment submitted successfully:", data.message);

                        // Reload comments after successful submission
                        loadComments(postId); 
                    } else {
                        console.error("Error submitting comment:", data.message);
                    }
                })
                .catch((error) => {
                    console.error("Error submitting comment:", error);
                });
        });
    }
});
