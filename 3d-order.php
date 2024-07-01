<?php
include 'connect.php';
include 'success-result.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $model_name = $_POST['model_name'];
    $model_type = $_POST['model_type']; 
    $custom_model = isset($_POST['custom_model']) ? $_POST['custom_model'] : null;
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $additional_info = $_POST['additional_info'];

    // Insert data into database
    $sql = "INSERT INTO model_orders (user_id, model_name, model_type, custom_model, description, deadline, additional_info)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issssss", $user_id, $model_name, $model_type, $custom_model, $description, $deadline, $additional_info); 
        $stmt->execute();
        $stmt->close();

        // Redirect or display success message
        header("Location: profile.php#pending");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'head.php'; ?>
    <style>
        /* Style for form */
        #type2form {
            color: black;
            width: 80%;
            margin: auto;
        }

        /* Style for form elements */
        input[type="text"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            resize: vertical;
        }

        label {
            font-weight: bold;
        }

        /* Style for submit button */
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .error{
            color: red;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
        #additionalDomain {
            display: none;
        }
    </style>
</head>
<body>

<?php include 'header2.php'; ?>
    <main>
        <h2>3D Services Order</h2>
        <h3>What is entailed?</h3>
        <p>We can make you an advertisement of your product.</p>
        <p>You dont have a product? Check out our <a href="2d-order.php">Illustration</a> page <br> and we can make you a design and an ad for your future product</p>

        <h3>What if I'm a vtuber?</h3>
        <p>That's not a problem.</p>
        <p>We can make you a custpm vtube avatar for a fraction of the price elsewhere.</p>
        <p>Grow your audience today! </p>

        <h3>Can I get buildings?</h3>
        <p>Of course! Be it buildings or landscapes we got you covered.</p>

        <h3>Is there a discount?</h3>
        <h3>Refer a friend steps.</h3>
        <p>Go to your profile page.</p>
        <p>Check out your referral code or QRcode(coming soon).</p>
        <p>Share it with up to 5 friends and both of you get 15% off(websites and 3D services only)</p>
        <p>If you already paid for the site and refer a friend you can get the 15% cash back!</p>

        <h2>Your imagined world IS a reality with us!!</h2>
        <h1>If you do not want to order an illustration using the form below reach out to us at <a href="#" onclick="sendEmail();" target="_blank" class=" button">info@xalal.tech</a> </h1>  
            <script>
            function sendEmail(){
                var recipient = "info@xalal.tech";
                var subject ="I would like a Drawing/ Illustration.";
                var body = "Greetings, \n  My name is ......... \n I did not want to order a drawing/illustration using the form in your website because of XYZ but i am still willing to pay for your services. \n Thanks";
                var mailtoLink = "mailto: " + recipient + "?subject=" + subject +"&body="+ body;
                window.location.href = mailtoLink;
              }
              </script>

        <h2>What 3D Service would <?php echo $first_name ?> like?</h2>
        <div>
            <h1>3D Service Order Form</h1>
            <form action="3d-order.php" method="post" id="type2form" onsubmit="return validateForm()">

                <input type="text" name="model_name" placeholder="3D Order Name" required>
                
                <label for="model_type">3D Order Item:</label>
                <select name="model_type" id="model_type" onchange="toggleAdditionalDomain()" required>
                    <option value="Web Design">Advertisement</option>
                    <option value="Logo">Vtube Avatar</option>
                    <option value="Flyer">Building</option>
                    <option value="Animation">Animation</option>
                    <option value="other">Something else</option>
                </select>

                <div id="additionalDomain">
                    <label for="custom_model">Custom 3D Order:</label>
                    <input type="text" id="custom_model" name="custom_model" placeholder="Enter custom 3D Order">
                </div>
                
                <textarea name="description" placeholder="A small description"></textarea>
                
                <label for="deadline">Deadline for the 3D order:</label>
                <input type="date" name="deadline" id="deadline" required>
                <span class="error" id="deadlineError"></span>
                
                <input type="text" name="additional_info" placeholder="Anything else?">
                
                <input type="submit" value="Submit">
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="formplay3.js"></script>
</body>
</html>
