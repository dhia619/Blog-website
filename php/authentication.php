
<link rel="icon" href="../bebo-logo.png" type="image/x-icon">
<?php

function login($conn,$email, $password) {

    $login_query = "SELECT * from user where email = '$email' and pwd = '$password'";
    $login_query_result = mysqli_query($conn,$login_query);
    if (mysqli_num_rows($login_query_result)==1){
        echo "logged in successfully";
    }
    else{
        header("Location: ../index.php?error=Email does not exist");
    }
}

function register($conn, $username, $email, $birthdate, $password) {
    // Check if the email is already used
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        header("Location: signup.php?error=Email already used");
    } else {
        // Insert the new user into the database
        $stmt = $conn->prepare("INSERT INTO user (username, email, pwd, birthdate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $birthdate);
        
        if ($stmt->execute()) {
            header("Location: success.php");
        } else {
            header("Location: signup.php?error=Error: " . urlencode($stmt->error));
        }

        $stmt->close();
    }
}

#connexion au base de données
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die(mysqli_error() . "connection failed");
}
mysqli_select_db($conn, "blogger") or die("error connecting to db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $form_type = $_POST['form_type'];
        
        if ($form_type == 'signin') {
            // Handle login form submission
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            login($conn,$email, $password);

        } elseif ($form_type == 'signup') {
            // Handle signup form submission
            $username = $_POST['username'];
            $email = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $password = $_POST['password'];

            register($conn,$username, $email, $birthdate, $password);

        } else {
            echo "Unknown form type!";
        }
    } else {
        echo "Form type not set!";
    }
} else {
    echo "Invalid request method!";
}
?>