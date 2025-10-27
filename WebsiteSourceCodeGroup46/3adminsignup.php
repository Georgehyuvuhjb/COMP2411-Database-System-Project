<?php

require_once "config.php";
require_once "session_admin_log_in.php";

if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $dateOfBirth = ($_POST['birthdate']);
    $password = trim($_POST['pwd']);

    // Prepare the query to check the admin credentials
    $query = "SELECT * FROM Admin WHERE email_address = :email";

    // Prepare the statement
    $stmt = oci_parse($conn, $query);

    // Bind the parameters
    oci_bind_by_name($stmt, ':email', $email);

    // Execute the statement
    oci_execute($stmt);
    
    // Check if a row is not returned (meaning the email address is not used by other Admins)
    $row = oci_fetch_assoc($stmt);
    echo $row;
    if (is_null($row['email_address'])) {
        //Insert all the new admin information
        $update_user = "INSERT INTO Admin(actual_name, email_address, date_of_birth, telephone_number, password) 
                        VALUES(:name,:email,TO_DATE(:date_of_birth,'YYYY/MM/DD'),:tel,:password)";

        //parse the query
        $stmt = oci_parse($conn, $update_user);

        //binding all the information
        oci_bind_by_name($stmt, ':name', $name);
        oci_bind_by_name($stmt, ':email', $email);
        oci_bind_by_name($stmt, ':tel', $tel);
        oci_bind_by_name($stmt, ':date_of_birth', $dateOfBirth);
        oci_bind_by_name($stmt, ':password', $password);

        // Execute the statement
        oci_execute($stmt);

        //Choose the Admin ID
        $select_Admin_ID = "SELECT * FROM Admin WHERE email_address = :email";

        //Parse the statement
        $select_ID_stmt = oci_parse($conn, $select_Admin_ID);

        //Bind the email address
        oci_bind_by_name($select_ID_stmt, ':email', $email);

        // Execute the statement
        oci_execute($select_ID_stmt);

        $row_AdminID = oci_fetch_assoc($select_ID_stmt);
        // Retrieve the store name from the Store table using the Admin_ID
        $adminId = $row_AdminID['ADMIN_ID'];

        //add Admin_ID to the session
        $_SESSION["adminId"] = $adminId;
        // Initialize Store Information

        $storeName = trim($_POST['store']);
        $storeDescription = trim($_POST['desc']);
        $storeQuery = "INSERT INTO Store (store_description, store_name , Admin_ID) 
                      VALUES(:store_description, :Admin_enter_store_name,:This_Admin_ID)";

        $storeStmt = oci_parse($conn, $storeQuery);

        oci_bind_by_name($storeStmt, ':Admin_enter_store_name', $storeName);
        oci_bind_by_name($storeStmt, ':store_description', $storeDescription);
        oci_bind_by_name($storeStmt, ':This_Admin_ID', $adminId);

        oci_execute($storeStmt);

	// print_r($_SESSION);
        //go the the correct page
        header("Location: 2adminlogin.php");
        exit;
    } 
else {
        // Invalid credentials, redirect back to the login page
        echo "<div class='centered'><h1>Email address is being used for a different account.</h1>
        <p>Please enter a different email address.
        Click <a href='3adminsignup.php'>here</a> to try again.</p></div>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Sign Up - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body{
            background-image: url("images/Nightsky.jpeg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <div class ="logo">
            <a href="1firstpage.html" ><img src="images/logo_smaller.jpg" width="80"></a>
        </div>
    </div>

  <div class="centered" style="color: white">
    <h1>Sign Up as Admin</h1>
    <form action = "" method= "post">
        <b>Your Information</b><br>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="tel">Phone number:</label>
        <input type="tel" id="tel" name="tel" required><br>
        <label for="birthdate">Date of birth:</label>
        <input type="date" id="birthdate" name="birthdate" required><br>
        <label for="pwd">Password:</label>
        <input type="password" id="pwd" name="pwd" required><br>
        <br>
        <b>Store Information</b><br>
        <label for="store">Name:</label>
        <input type="text" id="store" name="store" required><br>
        <label for="desc">Description:</label><br>
        <textarea id="desc" name="desc" rows="4" cols="50"></textarea><br><br>
        <button name="signup" type="submit"><h3>Sign up</h3></button><br><br>
    </form>
    <small>To sign up as a customer, click <a href="4customersignup.php">here</a>.</small>
</div>

</body>
</html>
