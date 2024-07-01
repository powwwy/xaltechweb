<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['id'];
    $status = $_POST['status'];

    // Update website orders
    $sql_website = "UPDATE website_orders SET status = ? WHERE id = ?";
    $stmt_website = $conn->prepare($sql_website);
    if ($stmt_website) {
        $stmt_website->bind_param("si", $status, $order_id);
        if ($stmt_website->execute()) {
            echo "Website order status updated successfully.";
            header("Location: adminhome.php");
            exit();
        } else {
            echo "Error updating website order status.";
        }
        $stmt_website->close();
    } else {
        echo "Error preparing website statement.";
    }
}

$conn->close();
