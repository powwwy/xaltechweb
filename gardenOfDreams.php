<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php'; // Ensure this file correctly sets up your $conn database connection

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $password_protect = $_POST["password"];

    // Basic validation
    if (empty($password_protect)) {
        $errors[] = "Enter the password you silly goose";
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        $sql = "SELECT * FROM administrator WHERE password_protect = '$password_protect'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row["password_protect"];

            if ($password == $password) {
                $_SESSION["admin_id"] = $row["admin_id"];
                header("Location: admin.php");
                exit();
            } else {
                $errors[] = "Invalid email or password or are you just braindead?";
            }
        } else {
            $errors[] = "Invalid email or password or are you just braindead?";
        }
    }
}

// Close the database connection
$conn->close();

// Store errors in session and redirect back to login page if any errors
if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    header("Location: gardenOfDreams.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xalal Technologies</title>

    <link rel="stylesheet" href="xal.css">
    <link rel="stylesheet" href="login.css">
    <!-- Link to phones.css for phones -->
    <link rel="stylesheet" media="only screen and (max-width: 767px)" href="phones.css">
    <!-- Link to tablets.css for tablets and small laptops -->
    <link rel="stylesheet" media="only screen and (min-width: 768px) and (max-width: 1080px)" href="tablets.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&display=swap" rel="stylesheet">
<body id = "logBody">
    <div class="container">
        <div class="content" id="content">
            <div class="form-section" id="log" style="display: block;">
                <h1>Welcome back, Admin! <br> Login to continue to:</h1>
                <form class="form" id="login" action="gardenOfDreams.php" method="post">
                    <a href="gardenOfDreams.php"><img src="images/xr.png" alt="" width="100px"></a>
                    <div id="error-messages" style="color: red;">
                        <?php
                        if (isset($_SESSION['login_errors']) && !empty($_SESSION['login_errors'])) {
                            $errors = $_SESSION['login_errors'];
                            foreach ($errors as $error) {
                                echo "<p>$error</p>";
                            }
                            unset($_SESSION['login_errors']);
                        }
                        ?>
                    </div>
                    
                    <div>
                        <div class="password-checker">
                            <input type="password" id="password" name="password" placeholder="What's the Password?" required>
                            <span class="password-toggle" id="togglePassword">
                                <img src="images/eye-open.svg" alt="Toggle Password" width="20" height="20" style="background-color: white; width: fit-content; padding: 0px;">
                            </span>
                        </div>
                        <span class="error" style="color: red;"></span>
                    </div>
                    <div>
                        <input type="submit" id="logButton" value="Login" name="login">
                    </div>
                    <!--<a href="forgot_password.php " style = "text-decoration:none;"> Forgot Password?</a>-->
                </form>
            </div>
        </div>
    </div>
    <script src="passwordChecker.js"></script>
</body>
</html>
