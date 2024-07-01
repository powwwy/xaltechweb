<?php
session_start();
include "connect.php"; // Ensure you have a connect.php file to establish database connection

// Load Composer's autoloader or include PHPMailer files manually
require 'vendor/autoload.php'; // If using Composer
// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Validate email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if the email exists in the database
        $sql = "SELECT user_id FROM user_details WHERE email = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                // Email exists, generate a unique token
                $token = bin2hex(random_bytes(16));
                $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

                // Save the token in the database with the expiry time
                $updateSql = "UPDATE user_details SET reset_token = ?, reset_token_expiry = ? WHERE email = ?";
                $updateStmt = $conn->prepare($updateSql);
                if ($updateStmt) {
                    $updateStmt->bind_param("sss", $token, $expiry, $email);
                    $updateStmt->execute();

                    // Send the reset link via email using PHPMailer
                    $mail = new PHPMailer(true);
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'your_email@gmail.com';
                        $mail->Password = 'your_password';
                        $mail->SMTPSecure = 'tls';
                        $mail->Port = 587;

                        // Recipients
                        $mail->setFrom('your_email@gmail.com', 'Mailer');
                        $mail->addAddress($email);

                        // Content
                        $resetLink = "http://localhost/reset_password.php?token=$token";
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        $mail->Body    = "Click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

                        $mail->send();
                        echo "An email with a reset link has been sent to your email address.";
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                } else {
                    echo "Failed to prepare the update statement.";
                }
            } else {
                echo "Email address not found.";
            }

            $stmt->close();
        } else {
            echo "Failed to prepare the select statement.";
        }
    } else {
        echo "Invalid email address.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>





<!DOCTYPE html>
<html lang="en">
<?php include "head.php"; ?>
<body>
    <div class="container">
        <div class="content">
            <h1>Forgot Password</h1>
            <form action="forgot_password.php" method="post">
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
                <?php endif; ?>
                <div>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div>
                    <input type="submit" name="reset_request" value="Send Reset Link">
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
