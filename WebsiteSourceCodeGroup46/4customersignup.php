<?php
require_once "config.php";
require_once "session_customer_log_in.php";


if (isset($_POST['signup'])) {
    $name = trim($_POST['name']);
    $dateOfBirth = trim($_POST['dob']);
    $email = trim($_POST['email']);
    $tel = trim($_POST['tel']);
    $userName = trim($_POST['username']);
    $password = trim($_POST['pwd']);

    // Prepare the query to check the admin credentials
    $query = "SELECT * FROM Users WHERE User_Name = :userName";

    // Prepare the statement
    $stmt = oci_parse($conn, $query);

    // Bind the parameters
    oci_bind_by_name($stmt, ':userName', $userName);

    // Execute the statement
    oci_execute($stmt);

    // Check if a row is not returned (meaning the userName is not used by other Users)
    $row = oci_fetch_assoc($stmt);
    if (is_null($row['User_Name'])) {
        //Insert all the new admin information
        $update_user = "INSERT INTO Users(User_Name, actual_name, email_address, date_of_birth, telephone_number, password) 
                        VALUES(:userName,:name,:email,TO_DATE(:date_of_birth,'YYYY-MM-DD'),:tel,:password)";

        //parse the query
        $user_stmt = oci_parse($conn, $update_user);

        //binding all the information
        oci_bind_by_name($user_stmt, ':userName', $userName);
        oci_bind_by_name($user_stmt, ':name', $name);
        oci_bind_by_name($user_stmt, ':email', $email);
        oci_bind_by_name($user_stmt, ':tel', $tel);
        oci_bind_by_name($user_stmt, ':date_of_birth', $dateOfBirth);
        oci_bind_by_name($user_stmt, ':password', $password);

        // Execute the statement
        oci_execute($user_stmt);

        // $_SESSION["username"] = $userName;

        //go the the correct page
        header("Location: 2customerlogin.php");
        exit;
    } else {
        // Invalid credentials, redirect back to the login page
        echo "<div class='centered'><h1>Username already exists.</h1>
        <p>Please enter a different username.
        Click <a href='4customersignup.php'>here</a> to try again.</p></div>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Sign Up - OSS</title>
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

<div class="centered" style="color:white">
    <h1>Sign Up as Customer</h1>
    <form action = "" method = "post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        <!--<label for="lname">Last name:</label>
        <input type="text" id="lname" name="lname" required><br>-->
        <label for="dob">Date of birth:</label>
        <input type="date" id="dob" name="dob" required><br>
        <label for="email">Email address:</label>
        <input type="email" id="email" name="email" required><br>
        <label for="tel">Phone number:</label>
        <input type="tel" id="tel" name="tel" required><br>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        <label for="pwd">Password:</label>
        <input type="password" id="pwd" name="pwd" required><br><br>
        <button name = "signup" type="submit"><h3>Sign up</h3></button><br><br>
    </form>
    <small>To sign up as an admin to manage a store, click <a href="3adminsignup.php">here</a>.</small>
</div>

</body>
</html>
