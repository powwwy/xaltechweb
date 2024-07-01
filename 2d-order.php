<?php
include 'connect.php';
include 'success-result.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    $design_name = $_POST['design_name'];
    $design_type = $_POST['design_type']; 
    $custom_design = isset($_POST['custom_design']) ? $_POST['custom_design'] : null;
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $additional_info = $_POST['additional_info'];

    // Insert data into database
    $sql = "INSERT INTO design_orders (user_id, design_name, design_type, custom_design, description, deadline, additional_info)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("issssss", $user_id, $design_name, $design_type, $custom_design, $description, $deadline, $additional_info); 
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
        <h2>Illustration Order</h2>
        <h3>What is entailed?</h3>
        <p>We can make you a logo, drawing, fanart, commission etc</p>

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

        <h2>What Illustration would <?php echo $first_name ?> like?</h2>
        <div>
            <h1>Illustration Order Form</h1>
            <form action="2d-order.php" method="post" id="type2form" onsubmit="return validateForm()">

                <input type="text" name="design_name" placeholder="Illustration Name" required>
                
                <label for="design_type">Illustration Item:</label>
                <select name="design_type" id="design_type" onchange="toggleAdditionalDomain()" required>
                    <option value="Web Design">Web Design</option>
                    <option value="Logo">Logo</option>
                    <option value="Flyer">Flyer</option>
                    <option value="Poster">Poster</option>
                    <option value="other">Something else</option>
                </select>

                <div id="additionalDomain">
                    <label for="custom_design">Custom Illustration:</label>
                    <input type="text" id="custom_design" name="custom_design" placeholder="Enter custom illustration">
                </div>
                
                <textarea name="description" placeholder="A small description"></textarea>
                
                <label for="deadline">Deadline for the illustration:</label>
                <input type="date" name="deadline" id="deadline" required>
                <span class="error" id="deadlineError"></span>
                
                <input type="text" name="additional_info" placeholder="Anything else?">
                
                <input type="submit" value="Submit">
            </form>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="formplay2.js"></script>
</body>
</html>
