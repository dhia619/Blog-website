$(document).ready(function() {
    const fileInput = $("#choose-image-dialog");
    const uploadButton = $("#upload-image-button");
    const imagePreview = $("#image-preview");
    const resetPreviewButton = $("#reset-preview-image");
    const saveChangesButton = $(".settings-validation-buttons button:nth-child(1)");
    let base64Image = ""; // Store base64 image data
    
    // Open the file dialog when the upload button is clicked
    uploadButton.click(function() {
        fileInput.click();
    });
    
    fileInput.change(function(event) {
        
        const files = fileInput[0].files;
        if (files.length > 0) {
            const file = fileInput[0].files[0];
            // Validate file type and size
            const validTypes = ["image/jpeg", "image/png", "image/gif"];
            if (!validTypes.includes(file.type)) {
                alert("Invalid file type. Please select a JPG, PNG, or GIF image.");
                return;
            }
            
            if (file.size > 2 * 1024 * 1024) { // Max size of 2MB
                alert("File is too large. Max size is 2MB.");
                return;
            }
            
            // Use FileReader to read the file and display it
            const reader = new FileReader();
            reader.onload = function(e) {
                base64Image = e.target.result; // Store the base64 string
                imagePreview.attr("src", base64Image); // Set image source to the result
            };
            reader.readAsDataURL(file); // Read the file as a data URL
        }
    });
    
    resetPreviewButton.click(function(){
        imagePreview.attr("src", "../assets/user.png"); // Reset to default image
        fileInput.val(''); // Clear the file input
    })
    
    
    //save changes
    saveChangesButton.click(function(){

        $.ajax({
            url: '../php/settings.php',
            type: 'POST',
            data: {image:base64Image},
            success:function(response){
                console.log(response);
            }
        })
    })

    
});
