<?php
require_once "session_customer_log_out.php";
require_once "config.php";


if (isset($_POST["customeracct"])) {
$userName= $_SESSION["username"];

$name = trim($_POST['name']);
$dateOfBirth = trim($_POST['dob']);
$email = trim($_POST['email']);
$tel = trim($_POST['tel']);
$password = trim($_POST['pwd']);

$updateUser = "UPDATE Users SET Users.actual_name = :name, Users.date_of_birth = TO_DATE(:dob, 'YYYY/MM/DD'), Users.email_address = :email,
               Users.telephone_number = :tel, Users.password = :password WHERE Users.User_Name = :userName";

$updateStmt = oci_parse($conn, $updateUser);

oci_bind_by_name($updateStmt, ':userName', $userName);
oci_bind_by_name($updateStmt, ':name', $name);
oci_bind_by_name($updateStmt, ':dob', $dateOfBirth);
oci_bind_by_name($updateStmt, ':email', $email);
oci_bind_by_name($updateStmt, ':tel', $tel);
oci_bind_by_name($updateStmt, ':password', $password);

oci_execute($updateStmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Account Info - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
    body {
        background-image: url("images/Nightsky3.jpg");
        background-size: cover;
        background-repeat: no-repeat;
        background-position: top;
    }
    </style>
</head>
<body>

<!--Nav bar-->
<div class="navbar">
        <div class ="logo">
            <a href="13customerhome.php"><img src="images/logo_smaller.jpg" width="80"></a>
        </div>
        <div class="search-container">
            <form action="7productlist.php" method="GET">
                <input type="text" placeholder="Search..." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="dropdown">
            <button class="dropbtn"><i class="fa fa-user-o"></i></button>
            <div class="dropdown-content">
                <a href="4customeracctinfo.php">View Account Information</a>
                <a href="logout.php">Sign Out</a>
            </div>
        </div>
        <div class="cart-button">
            <a href="10cart.php"><i class="fa fa-shopping-cart"></i></a>
        </div>
    </div>

<div class="content" style="color:white">
    <div class = "centered">
        <h1>Edit account info</h1>
        <?php
        require_once "config.php";
        $userName= $_SESSION["username"];
        $selectUser = "SELECT * FROM Users WHERE Users.User_Name = :userName";

        $userStmt = oci_parse($conn, $selectUser);

        oci_bind_by_name($userStmt, ':userName', $userName);

        oci_execute($userStmt);

        $userRow = oci_fetch_assoc($userStmt);
	$sqldate = $userRow['DATE_OF_BIRTH'];	
	$newdate = date("Y-m-d", strtotime($sqldate));
           echo"<form action='' method='post'>";
                echo "<label for='"."name"."'>"."Name: </label>";
                echo "<input type='text' id='name' name='name' value='".$userRow['ACTUAL_NAME']."'><br>";
                echo "<label for='"."dob"."'>"."Date of birth:</label>";
                echo "<input type='date' id='dob' name='dob' value='".$newdate."'><br>";
                echo "<label for='"."email"."'>"."Email address:</label>";
                echo "<input type='email' id='email' name='email' value='".$userRow['EMAIL_ADDRESS']."'><br>";
                echo "<label for='"."tel"."'>"."Phone number:</label>";
                echo "<input type='tel' id='tel' name='tel' value='".$userRow['TELEPHONE_NUMBER']."'><br>";
                echo "<label for='"."pwd"."'>"."Password:</label>";
                echo "<input type='password' id='pwd' name='pwd' value='".$userRow['PASSWORD']."'><br>";
        ?>
        <a href="4customeracctinfo.php"><button type="button">Cancel</button></a>
        <button name="customeracct" type="submit">Confirm</button><br>
	</form>
    </div>
</div>

</body>
</html>
