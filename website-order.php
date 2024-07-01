<?php
include 'connect.php';
include 'success-result.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $website_name = $_POST['website_name'];
    $domain_type = $_POST['domain_type'];
    $custom_domain = isset($_POST['custom_domain']) ? $_POST['custom_domain'] : null;
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $additional_info = $_POST['additional_info'];

    // Insert data into database
    $sql = "INSERT INTO website_orders (user_id, website_name, domain_type, custom_domain, description, deadline, additional_info)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issssss", $user_id, $website_name, $domain_type, $custom_domain, $description, $deadline, $additional_info);
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
        <h2>Website Order</h2>
        <h3>What is entailed?</h3>
        <p>If you have a domain (eg .com) - We can simply build, test and deploy the site for you.</p>
        <p>If you do not have a domain - We can set up the domain for you, build, test and deploy the site for you.</p>
        <p>If you do not want a domain (eg for a project) - We can build and test the site for you. For projects put project in the field for ccustom domain</p>
       
        <h2>Wait, what about maintenance?</h2>
        <h3>Not a problem!</h3>
        <p>We can maintain your site for you and add any additional features.</p>

        <h2>So, how do I get a discount?</h2>
        <h3>Refer a friend steps.</h3>
        <p>Go to your profile page.</p>
        <p>Check out your referral code or QRcode(coming soon).</p>
        <p>Share it with up to 5 friends and both of you get 15% off(websites and 3D services only)</p>
        <p>If you already paid for the site and refer a friend you can get the 15% cash back!</p>

        <h2>Your imagined world IS a reality with us!!</h2>
        <h2>If you do not want to order a website using the form below reach out to us at <a href="#" onclick="sendEmail();" target="_blank" class=" button">info@xalal.tech</a> </h2>  
            <script>
            function sendEmail(){
                var recipient = "info@xalal.tech";
                var subject ="I would like a webiste.";
                var body = "Greetings, \n  My name is ......... \n I did not want to order a site using the form in your website because of XYZ but i am still willing to pay for your services. \n Thanks";
                var mailtoLink = "mailto: " + recipient + "?subject=" + subject +"&body="+ body;
                window.location.href = mailtoLink;
              }
              </script>

        <h1>What website would <?php echo $first_name ?> like?</h1>
        <div>
            <h2>Website Order Form</h2>
            <form action="website-order.php" method="post" id="type2form" onsubmit="return validateForm()">
                <input type="text" name="website_name" placeholder="Website Name" required>
                
                <label for="domain_type">Domain Type:</label>
                <select name="domain_type" id="domain_type" onchange="toggleAdditionalDomain()" required>
                    <option value=".com">.com</option>
                    <option value=".net">.net</option>
                    <option value=".io">.io</option>
                    <option value=".tech">.tech (like us)</option>
                    <option value="other">Something else</option>
                </select>

                <div id="additionalDomain">
                    <label for="custom_domain">Custom Domain:</label>
                    <input type="text" id="custom_domain" name="custom_domain" placeholder="Enter custom domain">
                </div>
                
                <textarea name="description" placeholder="A small description"></textarea>
                
                <label for="deadline">Deadline for the website:</label>
                <input type="date" name="deadline" id="deadline" required>
                <span class="error" id="deadlineError"></span>
                
                <input type="text" name="additional_info" placeholder="Anything else?">
                
                <input type="submit" value="Submit">
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src = "formplay.js"> </script>
</body>
</html>