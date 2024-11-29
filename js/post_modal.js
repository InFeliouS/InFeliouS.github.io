// Function to handle form submission via AJAX
document.querySelector(".post-form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent the default form submission
  
    const formData = new FormData(event.target);
  
    fetch("php/insert_post.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        const modal = document.getElementById("modal");
        const modalMessage = document.getElementById("modal-message");
  
        if (data.success) {
          // Show success message with post ID
          modalMessage.textContent = `${data.message} Your Post ID: ${data.post_id}`;
          modal.classList.add("active");
        } else {
          // Show error message
          modalMessage.textContent = data.message;
          modal.classList.add("active");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("An error occurred while submitting your post.");
      });
  });
  
  // Function to close the modal and redirect
  function closeModal() {
    document.getElementById("modal").classList.remove("active");
    // Redirect to inbox.html
    window.location.href = "inbox.html";
  }
  