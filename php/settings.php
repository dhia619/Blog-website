<?php
session_start();
if(isset($_COOKIE["user_id"])){
    $_SESSION["user_id"] = intval($_COOKIE["user_id"]);
}
# Connect to the database
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}
mysqli_select_db($conn, "blogger") or die("error connecting to db");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/settings.css">
    <link rel="icon" href="../assets/bebo-logo.png" type="image/x-icon">
    <title>blogger-Home</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/settings.js"></script>
</head>
<body>

<body>
    <header>
        <div class="right-head-child">
            <a href="home.php"><img src="../assets/bebo-logo.png"></a>
        </div>
    </header>

    <form id="settings-form">

        <h1>Account settings</h1>

        <div class="settings-container">

            <div class="navigation-menu">
                <ul>
                    
                    <li>General</li>
                    <div class="horz_line"></div>
                    <li>Change password</li>
                    <div class="horz_line"></div>
                    <li>Info</li>
                    <div class="horz_line"></div>
                    <li>Notifications</li>
                    <div class="horz_line"></div>
                </ul>
            </div>
            <div class="content-container">
                <div class="general-settings">
                    <div class="profile-image-panel">
                        <div class="left-child">
                            <img id="image-preview" src="../assets/user.png">
                        </div>
                        <div class="right-child">
                            <div class="upper-right-child">
                                <button type="button" id="upload-image-button">Upload new photo</button>
                                <button type="button" id="reset-preview-image">Reset</button>
                            </div>
                            <div class="lower-right-child">
                                Allowed JPG, GIF, PNG, Max size of 2MB
                            </div>
                        </div>
                    </div>
                    <div class="general-info-panel">
                        <label>Username</label>
                        <input type="text">
                    </div>
                </div>
                <div class="change-password-settings"></div>
                <div class="info-settings"></div>
                <div class="notifications-settings"></div>
            </div>
        </div>
        <div class="settings-validation-buttons">
            <button type="button">Save changes</button>
            <button >Cancel</button>
        </div>
        <input type="file" id="choose-image-dialog" accept="image/*" style="display:none">
    </form>


</body>
</html>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image = $_POST["image"];
    if(!empty($image)){
        echo json_encode(["message" => json_decode($image)]);
        $query = "UPDATE user SET user_profile_image = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $image, $_SESSION["user_id"]);
        if ($stmt->execute()) {
            echo json_encode(["message" => "Profile image updated successfully."]);
        } else {
            echo json_encode(["message" => "Database update failed: " . $stmt->error]);
        }

        $stmt->close();
    }

}


# Close the database connection
$conn->close();
?>
