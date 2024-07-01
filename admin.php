<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php'; // Ensure this file correctly sets up your $conn database connection

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $admin_name= $_POST["admin_name"];
    $password = $_POST["password"];

    // Basic validation
    if (empty($admin_name)) {
        $errors[] = "Enter your number you silly goose";
    }
    if (empty($password)){
        $errors[] = "Enter the password you silly goose";
    }

    // Proceed if no validation errors
    if (empty($errors)) {
        $sql = "SELECT * FROM administrator WHERE admin_name= '$admin_name'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row["password"];

            if ($password == $password) {
                $_SESSION["admin_id"] = $row["admin_id"];
                header("Location: adminhome.php");
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
    header("Location: admin.php");
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
                <form class="form" id="login" action="admin.php" method="post">
                    <a href="admin.php"><img src="images/xr.png" alt="" width="100px"></a>
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
                        <input type="text" id="loginNumber" name="admin_name" placeholder="Your Serial Number:" required><br>
                        <span id="error_email" class="error" style="color: red;"></span>
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
    <script>
        function changeStatus(orderId, status) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "update_order_status.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    alert(xhr.responseText);
                }
            };
            xhr.send("order_id=" + orderId + "&status=" + status);
        }

        function deleteOrder(orderId) {
            if (confirm("Are you sure you want to delete this order?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_order.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText);
                        location.reload();
                    }
                };
                xhr.send("order_id=" + orderId);
            }
        }
    </script>
</body>
</html>
