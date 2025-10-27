<?php
require_once "config.php";
require_once "session_customer_log_out.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Account Info - OSS</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!--external stylesheets-->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        .box {
            display: inline-block;
            padding: 10px;
            font-size: 16px;
            line-height: 1.2;
            width: 98.2%;
            word-wrap: break-word;
        }

        .box i {
            float: right;
        }

        body {
            background-image: url("images/Nightsky2.jpeg");
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

<div class="content">
    <h1>Your Account</h1>
    <div class="box" style="background-color: tan;">
        <a href="5editcustomeracctinfo.php"><i class="fa fa-pencil-square-o"></i></a>
        <?php
        $userName= $_SESSION["username"];
        $query = "SELECT * FROM Users WHERE Users.User_Name = :userName";
        $stmt = oci_parse($conn, $query);

        // Bind the parameters
        oci_bind_by_name($stmt, ':userName', $userName);

        oci_execute($stmt);

        $row = oci_fetch_assoc($stmt);
        //$password = $row['password'];
        //$actual_name = $row['actual_name'];
        //$email = $row['email_address'];
        //$dob = $row['data_of_birth'];
        //$tel = $row['telephone_number'];
        echo "Username: ".$userName."<br>";
        echo "Password: ".$row['PASSWORD']. "<br>";
        echo "Name: ".$row['ACTUAL_NAME']."<br>";
        echo "Email: ".$row['EMAIL_ADDRESS']. "<br>";
        echo "Phone number: ".$tel = $row['TELEPHONE_NUMBER']."<br>";
        echo "Date of birth: ".$row['DATE_OF_BIRTH']."<br>";
        ?>
        <!--Password: 123456(re)<br>
        Name: Adam(re)<br>
        Email: adamgoodman@email.com(re)<br>
        Phone number: 123456(re)<br>
        Date of birth: 1/1/12023(re) <br>-->
    </div>
    <br><br>

    <div class="box" style="background-color: lightgray">
        <a href="6editaddresses.php"><i class="fa fa-pencil-square-o"></i></a>
        <b>Addresses</b><br>
        <ol>
            <?php
            $userName= $_SESSION["username"];
            $selectAddress = "SELECT ShAdd.address_name FROM Users U, Shipment_Address ShAdd 
                                WHERE U.User_Name = :userName
                                AND U.User_Name = ShAdd.User_Name AND ShAdd.is_active = 1";

            $address_stmt = oci_parse($conn,$selectAddress);

            oci_bind_by_name($address_stmt, ':userName', $userName);

            oci_execute($address_stmt);

            while($row = oci_fetch_assoc($address_stmt)){
                echo "<li>".$row['ADDRESS_NAME']."</li>";
            }
            ?>
            <!--<li>Address 1 Interesting Street(re)</li>
            <li>Address 2 Not Interesting Street(re)</li>-->
        </ol>
    </div>
</div>
</body>
</html>
