<?php
include 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['id'];
    $status = $_POST['status'];
    // Update model orders
    $sql_model = "UPDATE model_orders SET status = ? WHERE id = ?";
    $stmt_model = $conn->prepare($sql_model);
    if ($stmt_model) {
        $stmt_model->bind_param("si", $status, $order_id);
        if ($stmt_model->execute()) {
            echo "Model order status updated successfully.";
            header("Location: adminhome.php");
            exit();
        } else {
            echo "Error updating model order status.";
        }
        $stmt_model->close();
    } else {
        echo "Error preparing model statement.";
    }
}
$conn->close();