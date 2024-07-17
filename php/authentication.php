<?php
session_start();

function login($conn, $email, $password) {
    $login_query = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $login_query->bind_param("s", $email);
    $login_query->execute();
    $login_result = $login_query->get_result();
    
    if ($login_result->num_rows == 1) {
        $user = $login_result->fetch_assoc();
        if (password_verify($password, $user['pwd'])) {
            //echo "Logged in successfully";
            $cookie_expiration = time() + (10 * 365 * 24 * 60 * 60); // 10 years
            if (isset($_POST["remember_me"])) {
                setcookie("remember_me", "yes", $cookie_expiration, "/", "", true, true);
                setcookie("user_id",$user["id"],$cookie_expiration, "/", "", true, true);
            } else {
                // Clear the cookie if "Remember me" is not checked
                setcookie("remember_me", "no", $cookie_expiration, "/", "", true, true);
                $_SESSION["user_id"] = $user["id"];
            }
            header("Location: home.php");
            exit();
        } else {
            $_SESSION['error'] = 'Verify your credentials';
            header("Location: ../index.php");
            exit();
        }
    } else {
        $_SESSION['error'] = 'Verify your credentials';
        header("Location: ../index.php");
        exit();
    }
}

function register($conn, $username, $email, $birthdate, $password) {
    $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $_SESSION['error'] = 'Email already used';
        header("Location: signup.php");
        exit();
    } else {
        $stmt = $conn->prepare("INSERT INTO user (username, email, pwd, birthdate) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, password_hash($password, PASSWORD_DEFAULT), $birthdate);
        
        if ($stmt->execute()) {
            header("Location: success.php");
            exit();
        } else {
            $_SESSION['error'] = 'Error: ' . $stmt->error;
            header("Location: signup.php");
            exit();
        }

        $stmt->close();
    }
}

# Connect to the database
$conn = mysqli_connect("localhost", "root", "");
if (!$conn) {
    die(mysqli_error($conn) . " connection failed");
}
mysqli_select_db($conn, "blogger") or die("error connecting to db");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['form_type'])) {
        $form_type = $_POST['form_type'];
        
        if ($form_type == 'signin') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            login($conn, $email, $password);
        } elseif ($form_type == 'signup') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $birthdate = $_POST['birthdate'];
            $password = $_POST['password'];
            register($conn, $username, $email, $birthdate, $password);
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
