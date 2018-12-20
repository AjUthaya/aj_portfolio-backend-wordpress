document.addEventListener("DOMContentLoaded", function() {
  // Check if admin bar is present
  var adminBarElement = document.getElementById("wpadminbar");
  if (adminBarElement) {
    var contentElement = document.getElementById("content");
    contentElement.classList.add("content--is_logged_in");
  }
});
