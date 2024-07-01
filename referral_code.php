<?php
include 'connect.php'; // Include your database connection file

function generateReferralCode() {
    // Generate a random alphanumeric code
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code_length = 10;
    $referral_code = '';

    for ($i = 0; $i < $code_length; $i++) {
        $referral_code .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $referral_code;
}

function generateReferralCodeForUser($conn, $user_id) {
    // Initialize referral_code_generated variable
    $referral_code_generated = 0;

    // Check if referral code already generated for this user
    $sql_check = "SELECT referral_code_generated FROM user_details WHERE user_id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $stmt_check->bind_result($referral_code_generated);
    $stmt_check->fetch();
    $stmt_check->close();

    if ($referral_code_generated == 1) {
        return "Referral code already generated for this user.";
    }

    // Generate a new referral code
    $referral_code = generateReferralCode();

    // Update user_details table with generated referral code
    $sql_update_user = "UPDATE user_details SET referral_code = ?, referral_code_generated = 1 WHERE user_id = ?";
    $stmt_update_user = $conn->prepare($sql_update_user);
    $stmt_update_user->bind_param("si", $referral_code, $user_id);
    
    if ($stmt_update_user->execute()) {
        // Insert into referral_codes table
        $sql_insert_referral = "INSERT INTO referral_codes (user_id, referral_code) VALUES (?, ?)";
        $stmt_insert_referral = $conn->prepare($sql_insert_referral);
        $stmt_insert_referral->bind_param("is", $user_id, $referral_code);
        
        if ($stmt_insert_referral->execute()) {
            // Redirect to profile.php after successful update and insert
            header("Location: profile.php");
            exit();
        } else {
            return "Error inserting referral code into referral_codes table.";
        }
    } else {
        return "Error generating referral code.";
    }
}

// Example usage
session_start();
$user_id = $_SESSION['user_id'] ?? null;
if ($user_id) {
    $result = generateReferralCodeForUser($conn, $user_id);
    echo $result; // Output any result message (you can handle this as needed)
} else {
    echo "User not logged in."; // Handle case where user is not logged in
}
