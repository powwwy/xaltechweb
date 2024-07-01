<?php
include 'connect.php';
include 'success-result.php';
//include 'referral_code.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["editing"])) {
    $editing = true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateUser"])) {
    $newName = $conn->real_escape_string($_POST["newName"]);
    $newLast = $conn->real_escape_string($_POST["newLast"]);
    $newEmail = $conn->real_escape_string($_POST["newEmail"]);
    $newPhone = $conn->real_escape_string($_POST["newPhone"]);
    $newProfile = $conn->real_escape_string($_POST["newProfile"]);

    // Update user details in the database
    $updateSql = "UPDATE user_details SET first_name = '$newName', last_name = '$newLast', email = '$newEmail', phone = '$newPhone', profile_picture = '$newProfile' WHERE user_id = '$user_id'";
    if ($conn->query($updateSql) === TRUE) {
        // Update successful, reload the page to show changes
        header("Location: profile.php");
        exit();
    } else {
        $updateError = "Error updating user details: " . $conn->error;
    }
}

$updateError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["updateProfilePic"])) {
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === 0) {
        // Define the directory where profile pictures will be stored
        $uploadDirectory = 'profiles/';

        // Generate a unique filename to avoid overwriting existing files
        $uniqueFilename = uniqid() . '_' . basename($_FILES['profilePicture']['name']);

        // Move the uploaded file to the specified directory
        $targetPath = $uploadDirectory . $uniqueFilename;

        if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $targetPath)) {
            // Update the profile picture path in the 'user_details' table
            $updateProfilePicSql = "UPDATE user_details SET profile_picture = '$targetPath' WHERE user_id = '$user_id'";
            if ($conn->query($updateProfilePicSql) === TRUE) {
                // Redirect back to profile page after successful update
                header("Location: profile.php");
                exit();
            } else {
                $updateError = "Error updating profile picture: " . $conn->error;
            }
        } else {
            $updateError = "Error moving uploaded file to destination.";
        }
    } else {
        $updateError = "No file uploaded or file upload error.";
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_webOrder"])) {
    $getwebOrderIDSql = "SELECT id FROM website_orders WHERE user_id = '$user_id'";
    $result = $conn->query($getwebOrderIDSql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];

        // Delete the vehicle from the database
        $deleteWebOrderSql = "DELETE FROM website_orders WHERE id = '$id'";
        if ($conn->query($deleteWebOrderSql) === TRUE) {
            // Deletion and update successful, reload the page to show changes
            header("Location: profile.php");
            exit();
        } else {
            $deleteVehicleError = "Error deleting vehicle: " . $conn->error;
        }
    } else {
        $deleteVehicleError = "Error deleting vehicle: " . $conn->error;
    }
} else {
    $deleteVehicleError = "Error finding vehicle associated with the student.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_2dOrder"])) {
    $get2dOrderIDSql = "SELECT id FROM design_orders WHERE user_id = '$user_id'";
    $result = $conn->query($get2dOrderIDSql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];

        // Delete the vehicle from the database
        $delete2dOrderSql = "DELETE FROM design_orders WHERE id = '$id'";
        if ($conn->query($delete2dOrderSql) === TRUE) {
            // Deletion and update successful, reload the page to show changes
            header("Location: profile.php#pending");
            exit();
        } else {
            $deleteVehicleError = "Error deleting vehicle: " . $conn->error;
        }
    } else {
        $deleteVehicleError = "Error deleting vehicle: " . $conn->error;
    }
} else {
    $deleteVehicleError = "Error finding vehicle associated with the student.";
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_3dOrder"])) {
    $get3dOrderIDSql = "SELECT id FROM model_orders WHERE user_id = '$user_id'";
    $result = $conn->query($get3dOrderIDSql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id'];

        // Delete the vehicle from the database
        $delete3dOrderSql = "DELETE FROM model_orders WHERE id = '$id'";
        if ($conn->query($delete3dOrderSql) === TRUE) {
            // Deletion and update successful, reload the page to show changes
            header("Location: profile.php#pending");
            exit();
        } else {
            $deleteVehicleError = "Error deleting vehicle: " . $conn->error;
        }
    } else {
        $deleteVehicleError = "Error deleting vehicle: " . $conn->error;
    }
} else {
    $deleteVehicleError = "Error finding vehicle associated with the student.";
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'head.php'; ?>

<body>
    <?php include 'header2.php'; ?>
    <a href="logout.php" id="logout">Logout</a>
    <main>
        <h1>Welcome, <?php echo $first_name ?>!</h1>
        <?php if (isset($editing) && $editing): ?>
            <h2>Personal Details</h2>
            <section class="editing-details">
                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <div class="profile-pic">
                        <input type="file" id="profilePicture" name="profilePicture" accept="image/*">
                        <img src="<?php echo $profile_picture; ?>" alt="">
                        <button type="submit" name="updateProfilePic" class="btn">Update Profile Picture</button>
                        <h3><?php echo $full_name ?></h3>
                    </div>
                    <div class="sub-deets">
                        <h3>First Name:</h3>
                        <input type="text" id="newName" name="newName" value="<?php echo $first_name; ?>">

                        <h3>Last Name:</h3>
                        <input type="text" id="newLast" name="newLast" value="<?php echo $last_name; ?>">

                        <h3>Phone Number:</h3>
                        <input type="phone" id="newPhone" name="newPhone" value="<?php echo $phone; ?>">

                        <h3>Email:</h3>
                        <input type="email" id="newEmail" name="newEmail" value="<?php echo $email; ?>">
                        <br>
                        <button type="submit" name="updateUser" class="btn">Save Details</button>
                    </div>
                </form>
            </section>
        <?php else: ?>
            <h2>Personal Details</h2>
            <section class="details">
                <div class="profile-pic">
                    <img src="<?php echo $profile_picture; ?>" alt="" style="border-radius: 50%;">
                </div>
                <div class="sub-deets">
                    <h3><?php echo $full_name ?></h3>
                    <h3>Phone Number:</h3>
                    <p><?php echo $phone ?></p>

                    <h3>Email:</h3>
                    <p><?php echo $email ?></p>
                    <br>
                    <form action="" method="post">
                        <input type="hidden" name="editing" value="1">
                        <button type="submit" class="btn">Edit Your Details</button>
                    </form>
                </div>
            </section>
        <?php endif; ?>

        <h2>Your Orders</h2>
        <div class="pending" id="pending">
            <h2>Pending Orders</h2>
                <section class="web_orders">
                <h4>Website Orders: </h4>
                <div class="pending-website-orders">
            <?php if ($result_pending_web_orders->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Website Name</th>
                            <th>Domain Type</th>
                            <th>Description</th>
                            <th>Deadline</th>
                            <th>Additional Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_pending_web_orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['website_name']; ?></td>
                                <td><?php echo $row['domain_type']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['deadline']; ?></td>
                                <td><?php echo $row['additional_info']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pending website orders found.</p>
            <?php endif; ?>
        </div>
</section>

            <section class="2d-orders">
                <h4>2D Orders: </h4>
                <div class="pending-design-orders">
            <?php if ($result_pending_design_orders->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Illustration Name</th>
                            <th>Illustration Type</th>
                            <th>Description</th>
                            <th>Deadline</th>
                            <th>Additional Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_pending_design_orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['design_name']; ?></td>
                                <td><?php echo $row['design_type']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['deadline']; ?></td>
                                <td><?php echo $row['additional_info']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pending Illustration orders found.</p>
            <?php endif; ?>
        </div>
            </section>

            <section class="3d-orders">
                <h4>3D Orders: </h4>
                <div class="pending-model-orders">
            <?php if ($result_pending_3d_orders->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Model Name</th>
                            <th>Model Type</th>
                            <th>Description</th>
                            <th>Deadline</th>
                            <th>Additional Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_pending_3d_orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['model_name']; ?></td>
                                <td><?php echo $row['model_type']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['deadline']; ?></td>
                                <td><?php echo $row['additional_info']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No pending 3D orders found.</p>
            <?php endif; ?>
        </div>
            </section>
        </div>
        
        <div class="past">
            <h2>Past Orders</h2>      
<section>
        <!-- Display completed website orders -->
        <h1>Website Orders:</h1>
        <div class="completed-website-orders">
            <?php if ($result_completed_web_orders->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Website Name</th>
                            <th>Domain Type</th>
                            <th>Description</th>
                            <th>Deadline</th>
                            <th>Additional Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result_completed_web_orders->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['website_name']; ?></td>
                                <td><?php echo $row['domain_type']; ?></td>
                                <td><?php echo $row['description']; ?></td>
                                <td><?php echo $row['deadline']; ?></td>
                                <td><?php echo $row['additional_info']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No completed website orders found.</p>
            <?php endif; ?>
        </div>
</section>

            <section class="2d-orders">
                <h4>2D Orders: </h4>
                <?php if ($result_completed_design_orders->num_rows > 0): ?>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Design Name</th>
                                <th>Design Type</th>
                                <th>Custom Design</th>
                                <th>Description</th>
                                <th>Deadline</th>
                                <th>Additional Info</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result_completed_design_orders->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['design_name']; ?></td>
                                    <td><?php echo $row['design_type']; ?></td>
                                    <td><?php echo $row['custom_design']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['deadline']; ?></td>
                                    <td><?php echo $row['additional_info']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No completed 2D orders found.</p>
                <?php endif; ?>
            </section>

            <section class="3d-orders">
                <h4>3D Orders: </h4>
                <?php if ($result_completed_3d_orders->num_rows > 0): ?>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Model Name</th>
                                <th>Model Type</th>
                                <th>Custom Model</th>
                                <th>Description</th>
                                <th>Deadline</th>
                                <th>Additional Info</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result_completed_3d_orders->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['model_name']; ?></td>
                                    <td><?php echo $row['model_type']; ?></td>
                                    <td><?php echo $row['custom_model']; ?></td>
                                    <td><?php echo $row['description']; ?></td>
                                    <td><?php echo $row['deadline']; ?></td>
                                    <td><?php echo $row['additional_info']; ?></td>
                                </tr>
                                <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No completed 3D orders found.</p>
                <?php endif; ?>
            </section>
        </div>
        
        <?php
include 'connect.php'; // Include your database connection script

// Assuming you have stored user_id in session after login
$user_id = $_SESSION['user_id']; // Replace with your session variable name

// Fetch user details including referral code status
$sql_user = "SELECT referral_code, referral_code_generated FROM user_details WHERE user_id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($referral_code, $referral_code_generated);
$stmt_user->fetch();
$stmt_user->close();
?>

<section class="referral">
    <h3>Refer A Friend</h3>
    <?php if ($referral_code_generated == 0): ?>
        <form action="referral_code.php" method="post">
            <button class="btn" type="submit" name="generateReferralCode">Generate Referral Code</button>
        </form>
    <?php else: ?>
        <button class="btn" id="toggleReferralCode">Show Referral Code</button>
        <p id="referralCode" style="display: none;"><?php echo $referral_code; ?></p>
        <script>
            document.getElementById('toggleReferralCode').addEventListener('click', function() {
                var referralCode = document.getElementById('referralCode');
                if (referralCode.style.display === 'none') {
                    referralCode.style.display = 'block';
                    this.textContent = 'Hide Referral Code';
                } else {
                    referralCode.style.display = 'none';
                    this.textContent = 'Show Referral Code';
                }
            });
        </script>
    <?php endif; ?>
</section>
<section class="referral">
        <h3>Referred by a friend?</h3>
        <?php if (!isset($_SESSION['referral_used']) || $_SESSION['referral_used'] == false): ?>
            <form method="post" action="process_referral.php">
                <label for="referral_code"></label>
                <input type="text" id="referral_code" name="referral_code" placeholder="Enter Referral Code:" required>
                <button type="submit" class="btn">Apply Code</button>
            </form>
            <?php if (isset($_SESSION['updateError'])): ?>
                <p><?php echo $_SESSION['updateError']; unset($_SESSION['updateError']); ?></p>
            <?php endif; ?>
        <?php else: ?>
            <p>You have already used a referral code.</p>
        <?php endif; ?>
    </section>


    </main>
    <?php include 'footer.php'; ?>
</body>
</html>