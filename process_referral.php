<?php
// Start session and include database connection
session_start();
include 'connect.php'; // Replace with your actual connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have stored user_id in session after login
    $user_id = $_SESSION['user_id']; // Replace with your session variable name

    // Get referral code from form submission
    $referral_code = $_POST['referral_code'];

    // Check if user is trying to refer themselves
    $sql_check_self_referral = "SELECT user_id FROM user_details WHERE referral_code = ?";
    $stmt_check_self_referral = $conn->prepare($sql_check_self_referral);
    if ($stmt_check_self_referral) {
        $stmt_check_self_referral->bind_param("s", $referral_code);
        $stmt_check_self_referral->execute();
        $stmt_check_self_referral->store_result();
        $stmt_check_self_referral->bind_result($referring_user_id);
        $stmt_check_self_referral->fetch();

        if ($stmt_check_self_referral->num_rows > 0 && $referring_user_id == $user_id) {
            // User is attempting to refer themselves
            $updateError = "You cannot refer yourself.";
        } else {
            // Validate referral code
            $sql_validate = "SELECT code_id, usage_count FROM referral_codes WHERE referral_code = ? LIMIT 1";
            $stmt_validate = $conn->prepare($sql_validate);
            if ($stmt_validate) {
                $stmt_validate->bind_param("s", $referral_code );
                $stmt_validate->execute();
                $stmt_validate->store_result();

                if ($stmt_validate->num_rows > 0) {
                    // Referral code exists, fetch its details
                    $stmt_validate->bind_result($referral_id, $usage_count);
                    $stmt_validate->fetch();

                    if ($usage_count < 5) {
                        // Update usage count and record usage date
                        $new_usage_count = $usage_count + 1;
                        $usage_date = date('Y-m-d H:i:s'); // Current date and time
                        $status = ($new_usage_count >= 5) ? 'expired' : 'active';

                        // Update referral code usage count and date
                        $sql_update_referral = "UPDATE referral_codes SET usage_count = ?, dates_used = ?, status = ? WHERE code_id = ?";
                        $stmt_update_referral = $conn->prepare($sql_update_referral);
                        if ($stmt_update_referral) {
                            $stmt_update_referral->bind_param("issi", $new_usage_count, $usage_date, $status, $referral_id);
                            if ($stmt_update_referral->execute()) {
                                // Update user_details table to mark referral code as used
                                $sql_update_user = "UPDATE user_details SET used_code = 1 WHERE user_id = ?";
                                $stmt_update_user = $conn->prepare($sql_update_user);
                                if ($stmt_update_user) {
                                    $stmt_update_user->bind_param("i", $user_id);
                                    if ($stmt_update_user->execute()) {
                                        $_SESSION['referral_used'] = true; // Set session variable
                                        header("Location: profile.php");
                                        exit();
                                    } else {
                                        echo "Error updating user details.";
                                    }
                                    $stmt_update_user->close();
                                } else {
                                    echo "Error preparing user update statement.";
                                }
                            } else {
                                echo "Error updating referral code details.";
                            }
                            $stmt_update_referral->close();
                        } else {
                            echo "Error preparing referral code update statement.";
                        }
                    } else {
                        echo "Referral code has expired.";
                    }
                } else {
                    echo "Invalid referral code.";
                }
                $stmt_validate->close();
            } else {
                echo "Error preparing statement.";
            }
        }
        $stmt_check_self_referral->close();
    } else {
        echo "Error preparing self-referral check statement.";
    }
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
