<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['id'];
    $status = $_POST['status'];
    // Update design orders
    $sql_design = "UPDATE design_orders SET status = ? WHERE id = ?";
    $stmt_design = $conn->prepare($sql_design);
    if ($stmt_design) {
        $stmt_design->bind_param("si", $status, $order_id);
        if ($stmt_design->execute()) {
            echo "Design order status updated successfully.";
            header("Location: adminhome.php");
            exit();
        } else {
            echo "Error updating design order status.";
        }
        $stmt_design->close();
    } else {
        echo "Error preparing design statement.";
    }
}

$conn->close();