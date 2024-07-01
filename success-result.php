<?php 
session_start();
include 'connect.php'; // Ensure your database connection file is included

$user_id = $_SESSION['user_id'];
if (!isset($_SESSION["user_id"])) {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

// Fetch user details
$sql_user = "SELECT * FROM user_details WHERE user_id = '$user_id'";
$result_user = $conn->query($sql_user);
$user = $result_user->fetch_assoc();

$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];
$phone = $user['phone'];
$referral_code = $user['referral_code'];
$profile_picture = $user['profile_picture'] ?? 'images/placeholder.jpg';

$full_name = $first_name . " " . $last_name;

if ($phone == null) {
    $phone = "Add phone number";
}


// Fetch website orders
$sql_orders = "SELECT * FROM website_orders WHERE user_id = '$user_id'";
$result_orders = $conn->query($sql_orders);

$orders = [];
while ($row = $result_orders->fetch_assoc()) {
    $orders[] = $row;
}

//fetch design orders
$sql_designs = "SELECT * FROM design_orders WHERE user_id = '$user_id'";
$result_designs = $conn->query($sql_designs);

$designs = [];
while ($row = $result_designs->fetch_assoc()) {
    $designs[] = $row;
}
//fetch design orders
$sql_models = "SELECT * FROM model_orders WHERE user_id = '$user_id'";
$result_models = $conn->query($sql_models);

$models = [];
while ($row = $result_models->fetch_assoc()) {
    $models[] = $row;
}


$sql_pending_web_orders = "SELECT * FROM website_orders WHERE user_id = '$user_id' AND status = 'pending'";
$sql_completed_web_orders = "SELECT * FROM website_orders WHERE user_id = '$user_id' AND status = 'completed'";
$result_pending_web_orders = $conn->query($sql_pending_web_orders);
$result_completed_web_orders = $conn->query($sql_completed_web_orders);

// Fetch design orders
$sql_pending_design_orders = "SELECT * FROM design_orders WHERE user_id = '$user_id' AND status = 'pending'";
$sql_completed_design_orders = "SELECT * FROM design_orders WHERE user_id = '$user_id' AND status = 'completed'";
$result_pending_design_orders = $conn->query($sql_pending_design_orders);
$result_completed_design_orders = $conn->query($sql_completed_design_orders);

// Fetch 3D orders
$sql_pending_3d_orders = "SELECT * FROM model_orders WHERE user_id = '$user_id' AND status = 'pending'";
$sql_completed_3d_orders = "SELECT * FROM model_orders WHERE user_id = '$user_id' AND status = 'completed'";
$result_pending_3d_orders = $conn->query($sql_pending_3d_orders);
$result_completed_3d_orders = $conn->query($sql_completed_3d_orders);

