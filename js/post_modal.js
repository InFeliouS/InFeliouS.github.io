// Handle form submission via AJAX
document.querySelector(".post-form").addEventListener("submit", function (event) {
  event.preventDefault(); // Prevent default form submission

  const formData = new FormData(event.target);
  const submitButton = event.target.querySelector("button[type='submit']");
  submitButton.disabled = true; // Disable submit button to prevent multiple submissions

  fetch("php/insert_post.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network error occurred.");
      }
      return response.json(); // Parse JSON response
    })
    .then((data) => {
      showModal(data.message, data.success ? `Your Post ID: ${data.post_id}` : null);
      submitButton.disabled = false; // Re-enable submit button
    })
    .catch(() => {
      alert("An error occurred while submitting your post.");
      submitButton.disabled = false; // Re-enable submit button in case of an error
    });
});

// Function to show a modal with a message
function showModal(message, additionalInfo = null) {
  const modal = document.getElementById("modal");
  const modalMessage = document.getElementById("modal-message");

  modalMessage.textContent = additionalInfo ? `${message} ${additionalInfo}` : message;
  modal.classList.add("active");
}

// Function to close the modal and redirect to inbox.html
function closeModal() {
  const modal = document.getElementById("modal");
  modal.classList.remove("active");
  window.location.href = "inbox.html";
}

// Prevent repeated actions on Enter key
document.addEventListener("keydown", function (event) {
  const modal = document.getElementById("modal");
  const isEnterPressed = event.key === "Enter";

  if (isEnterPressed) {
    if (modal.classList.contains("active")) {
      event.preventDefault();
      closeModal(); // Close modal on Enter key when active
    } else {
      const activeElement = document.activeElement;
      if (["TEXTAREA", "INPUT"].includes(activeElement.tagName)) {
        event.preventDefault(); // Prevent form submission on Enter key
      }
    }
  }
});
