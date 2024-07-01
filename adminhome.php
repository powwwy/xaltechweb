<?php
include 'connect.php';
session_start();

// Fetch all users
$sql_users = "SELECT * FROM user_details";
$result_users = $conn->query($sql_users);

// Fetch all website orders
$sql_website_orders = "SELECT * FROM website_orders";
$result_website_orders = $conn->query($sql_website_orders);

// Fetch all design orders
$sql_design_orders = "SELECT * FROM design_orders";
$result_design_orders = $conn->query($sql_design_orders);

// Fetch all model orders
$sql_model_orders = "SELECT * FROM model_orders";
$result_model_orders = $conn->query($sql_model_orders);

$sql_referral_codes= "SELECT * FROM referral_codes";
$result_referral_codes = $conn->query($sql_referral_codes);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        button {
            padding: 5px;
        }
    </style>
</head>
<body>
    <main>
        <h1>Admin Dashboard</h1>

        <h2>Users</h2>
        <table>
            <tr>
                <th>User ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Referral Code</th>
                <th>Used Code?</th>
                <!-- Add more fields as needed -->
            </tr>
            <?php while ($user = $result_users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $user['user_id']; ?></td>
                    <td><?php echo $user['first_name']; ?></td>
                    <td><?php echo $user['last_name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['referral_code']; ?></td>
                    <td><?php echo $user['used_code'] ? "true" : "false" ; ?></td>
                    <!-- Display more fields as needed -->
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Website Orders</h2>
        <table>
            <tr>
                <th>Web Order ID</th>
                <th>User ID</th>
                <th>Website Name</th>
                <th>Domain Type</th>
                <th>Custom Domain</th>
                <th>Description</th>
                <th>Deadline</th>
                <th>Additional Info</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $result_website_orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo $order['website_name']; ?></td>
                    <td><?php echo $order['domain_type']; ?></td>
                    <td><?php echo $order['custom_domain']; ?></td>
                    <td><?php echo $order['description']; ?></td>
                    <td><?php echo $order['deadline']; ?></td>
                    <td><?php echo $order['additional_info']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <form action="update_order_status.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                            <select name="status">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Design Orders</h2>
        <table>
            <tr>
                <th> 2D Order ID</th>
                <th>User ID</th>
                <th>Design Name</th>
                <th>Design Type</th>
                <th>Custom Design</th>
                <th>Description</th>
                <th>Deadline</th>
                <th>Additional Info</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $result_design_orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo $order['design_name']; ?></td>
                    <td><?php echo $order['design_type']; ?></td>
                    <td><?php echo $order['custom_design']; ?></td>
                    <td><?php echo $order['description']; ?></td>
                    <td><?php echo $order['deadline']; ?></td>
                    <td><?php echo $order['additional_info']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <form action="update_design_status.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                            <select name="status">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>

        <h2>Model Orders</h2>
        <table>
            <tr>
                <th>3D Order ID</th>
                <th>User ID</th>
                <th>Model Name</th>
                <th>Model Type</th>
                <th>Custom Model</th>
                <th>Description</th>
                <th>Deadline</th>
                <th>Additional Info</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($order = $result_model_orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo $order['model_name']; ?></td>
                    <td><?php echo $order['model_type']; ?></td>
                    <td><?php echo $order['custom_model']; ?></td>
                    <td><?php echo $order['description']; ?></td>
                    <td><?php echo $order['deadline']; ?></td>
                    <td><?php echo $order['additional_info']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td>
                        <form action="update_3d_status.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                            <select name="status">
                                <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Completed</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>   
        
        <h2>Referral Codes</h2>
    <table>
        <tr>
            <th>Referral ID</th>
            <th>Code</th>
            <th>Usage Count</th>
            <th>Last Date Used</th>
            <th>Status</th>
        </tr>
        <?php while ($referral = $result_referral_codes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $referral['code_id']; ?></td>
                    <td><?php echo $referral['referral_code']; ?></td>
                    <td><?php echo $referral['usage_count']; ?></td>
                    <td><?php echo $referral['dates_used']; ?></td>
                    <td><?php echo $referral['status']; ?></td>
                </tr>
            <?php endwhile; ?>
    </table>
    </main>
    <?php include 'footer.php'; ?>
</body>
</html>
