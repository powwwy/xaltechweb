<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(EALL);

session_start();
include 'connect.php';

$errors = array();
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token is valid and not expired
    $sql = "SELECT * FROM user_details WHERE reset_token = '$token' AND reset_token_expiry > NOW()";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_password"])) {
            $new_password = $_POST["new_password"];
            $confirm_password = $_POST["confirm_password"];

            // Basic validation
            if (empty($new_password) || empty($confirm_password)) {
                $errors[] = "All fields are required";
            } elseif ($new_password !== $confirm_password) {
                $errors[] = "Passwords do not match";
            } else {
                // Update the password in the database
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE user_details SET password = '$hashedPassword', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
                if ($conn->query($sql) === TRUE) {
                    $success = "Your password has been reset successfully.";
                } else {
                    $errors[] = "Error: " . $conn->error;
                }
            }
        }
    } else {
        $errors[] = "Invalid or expired reset token.";
    }
} else {
    $errors[] = "No reset token provided.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include "head.php"; ?>
<body>
    <div class="container">
        <div class="content">
            <h1>Reset Password</h1>
            <form action="reset_password.php?token=<?php echo htmlspecialchars($token); ?>" method="post">
                <div id="error-messages" style="color: red;">
                    <?php
                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<p>$error</p>";
                        }
                    }
                    ?>
                </div>
                <?php if (!empty($success)): ?>
                    <div style="color: green;">
                        <p><?php echo $success; ?></p>
                    </div>
                <?php else: ?>
                    <div>
                        <input type="password" name="new_password" placeholder="New Password" required>
                    </div>
                    <div>
                        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                    </div>
                    <div>
                        <input type="submit" name="reset_password" value="Reset Password">
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
