<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    $first_name = trim($_POST["first_name"] ?? '');
    $last_name = trim($_POST["last_name"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $phone = isset($_POST["phone"]) ? trim($_POST["phone"]) : ''; // Handling optional phone number
    $password = $_POST["password"] ?? '';

    // Validate form fields
    $errors = [];

    if (empty($first_name)) {
        $errors['error_first_name'] = "First name is required";
    }

    if (empty($email)) {
        $errors['error_email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['error_email'] = "Invalid email format";
    }

    if (!empty($phone)) {
        $phoneRegex = '/^\+\d{12}$/';
        if (!preg_match($phoneRegex, $phone)) {
            $errors['error_phone'] = "Invalid phone format (+254701234567)";
        }
    }

    $passRegex = '/^(?=.*\d)(?=.*[a-zA-Z]).{8,}$/';
    if (empty($password)) {
        $errors['error_password'] = "Password is required";
    } elseif (!preg_match($passRegex, $password)) {
        $errors['error_password'] = "Minimum 8 characters and at least 1 number required";
    }

    if (empty($errors)) {
        // Check for duplicate email
        $emailCheck = "SELECT * FROM user_details WHERE email = '$email'";
        $resultEmail = $conn->query($emailCheck);
        if ($resultEmail->num_rows > 0) {
            $errors['error_email'] = "Email already exists";
        }
    }
    // Check for duplicate phone number if it's not empty
    if (!empty($phone)) {
        $phoneCheck = "SELECT * FROM user_details WHERE phone = '$phone'";
        $resultPhone = $conn->query($phoneCheck);
        if ($resultPhone->num_rows > 0) {
            $errors['error_phone'] = "Phone number already exists";
        }
    }
    if (empty($errors)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL statement to insert data into the database using prepared statements
        $insert_sql = "INSERT INTO user_details (first_name, last_name, email, phone, password)
            VALUES (?, ?, ?, ?, ?)";

        // Prepare and bind the statement
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssss", $first_name, $last_name, $email, $phone, $hashedPassword);

        // Perform the SQL query to insert data
        if ($stmt->execute()) {
            $_SESSION["user_id"] = $row["user_id"];
            header("Location: profile.php");
            exit();
        } else {
            // Error inserting data into the database
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
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

<body id = "signBody">

    <div class="container">
        <div class="content">
            <div class="form-section" id="signupForm" style="display: block;">
                <h1>Create Account to continue to:</h1>
                <form class="form" id="createAccount" action="signup.php" method="post">
                    <a href="index.html"><img src="images/xr.png" alt="" width="100px"></a>

                    <div>
                        <input type="text" id="first_name" name="first_name" placeholder="First Name"><br>
                        <span class="error"
                            style="color: red;"><?php echo isset($errors['error_first_name']) ? $errors['error_first_name'] : ''; ?></span>
                    </div>

                    <div>
                        <input type="text" id="last_name" name="last_name" placeholder="Last Name (optional)"><br>
                        <span class="error"
                            style="color: red;"><?php echo isset($errors['error_last_name']) ? $errors['error_last_name'] : ''; ?></span>
                    </div>

                    <div>
                        <input type="tel" id="phone" name="phone"
                            placeholder="Phone Number (e.g +254701234567) (optional) (Kenya only)"><br>
                        <span class="error"
                            style="color: red;"><?php echo isset($errors['error_phone']) ? $errors['error_phone'] : ''; ?></span>
                    </div>

                    <div>
                        <input type="email" id="email" name="email" placeholder="Email"><br>
                        <span class="error"
                            style="color: red;"><?php echo isset($errors['error_email']) ? $errors['error_email'] : ''; ?></span>
                    </div>

                    <div class="password-container">
                        <div class = "password-checker">
                        <input type="password" id="password" name="password" placeholder="Password">
                        <span class="password-toggle" id="togglePassword">
                            <img src="images/eye-open.svg" alt="Toggle Password" width="20" height="20" style = "background-color: white; width: fit-content; padding: 0px;">
                        </span>
                     </div>
                        <span class="error"
                            style="color: red;"><?php echo isset($errors['error_password']) ? $errors['error_password'] : ''; ?></span>
                        
                    </div><br><br>

                    <div >
                        <input id= "sign-button" type="submit" value="Sign Up" name="signup">
                    </div>
                </form>
            </div>
            <div class="greeting-section">
                <h1>Hey....</h1>
                <p>Already have an account?<br>Sign in here</p>
                <a href="login.php" class="link">SIGN IN</a>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <script src="passwordChecker.js"></script>
</body>

</html>