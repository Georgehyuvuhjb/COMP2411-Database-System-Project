
<?php

require_once "config.php";
require_once "session_admin_log_in.php";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['pwd']);
    
    // Prepare the query to check the admin credentials
    $query = "SELECT * FROM Admin WHERE email_address = :email AND password = :password";

    // Prepare the statement
    $stmt = oci_parse($conn, $query);

    // Bind the parameters
    oci_bind_by_name($stmt, ':email', $email);
    oci_bind_by_name($stmt, ':password', $password);

    // Execute the statement
    oci_execute($stmt);

    // Check if a row is returned (credentials are valid)
    if ($row = oci_fetch_assoc($stmt)) {

        // Retrieve the store name from the Store table using the Admin_ID
        $adminId = $row['ADMIN_ID'];
   	$_SESSION["adminId"] = $adminId;
	header("Location: 14adminhome.php");
	exit;
    }  else {
        // Invalid credentials, redirect back to the login page
        echo "<h1>Oops... Your Login contains some errors.</h1>";
        echo "<a href='2adminlogin.php'><button><h3>Return to Log in Page</h3></button></a>";
        echo "<a href='2customerlogin.php'><button><h3>Want to Log in As Customer?</h3></button></a>";
        echo "<br><br><br><img src='images/cat.png' width='400'>";
        exit();
    }
}
?>


<head>
    <meta charset="UTF-8">
    <title>Admin Login - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body{
            background-image: url("images/2aPhoto.png");
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

    <div class="centered" style="color: white;">
        <h1>Log In as Admin</h1>
        <form action="" method="post">
            <label for="email">Email address:</label>
            <input type="text" id="email" name="email"><br>
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd"><br><br>
            <button name="login" type="submit"><h3>Log in</h3></button><br><br>
        </form>
        <small>To log in as a customer, click <a href="2customerlogin.php">here</a>.</small>
    </div>

</body>
</html>
