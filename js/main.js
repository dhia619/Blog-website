function validateForm(event) {
    console.log("validateForm called");  // Debug message

    var email = document.getElementById("email");
    var password = document.getElementById("password");
    var errorMessage = "";
    var error_zone = document.getElementsByClassName("error")[0];

    // Clear previous error messages
    error_zone.innerHTML = "";

    // Check if email is valid
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email.value)) {
        errorMessage += "Please enter a valid email address.<br>";
    }

    // Check if password is valid (at least 8 characters)
    if (password.value.length < 8) {
        errorMessage += "Password must be at least 8 characters long.<br>";
    }

    if (errorMessage) {
        error_zone.innerHTML = errorMessage;
        console.log("Form validation failed:", errorMessage);  // Debug message
        event.preventDefault(); // Prevent form submission
        return false;
    }
    console.log("Form validation passed");  // Debug message
    return true;
}

document.addEventListener("DOMContentLoaded", function() {
    console.log("DOM fully loaded and parsed");  // Debug message

    var form = document.getElementById("login-form");
    form.addEventListener("submit", validateForm);
});
