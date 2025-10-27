<?php
require_once "config.php";
require_once "session_admin_log_out.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Account Info - OSS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap">
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
    </style>
</head>
<body>

<!--Nav bar-->
    <div class="navbar">
        <div class ="logo">
            <a href="14adminhome.php" ><img src="images/logo_smaller.jpg" width="80"></a>
        </div>
        <div class="search-container">
            <form action="15InventorySearch.php" method="GET">
                <input type="text" placeholder="Search your products..." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </div>
        <div class="dropdown">
            <button class="dropbtn"><i class="fa fa-user-o"></i></button>
            <div class="dropdown-content">
                <a href="20adminacctinfo.php">View Account Information</a>
                <a href="logout.php">Sign Out</a>
            </div>
        </div>
    </div>

<div class="content">
    <h1>Your Account</h1>
    <div class="box" style="background-color: tan;">
        <a href="20editadminacctinfo.php"><i class="fa fa-pencil-square-o"></i></a>
        <b>Your Information</b><br><br>
        <?php

        $adminId = $_SESSION["adminId"];
        $adminQuery = "SELECT * FROM Admin WHERE Admin_ID = :adminId";
        $adminStmt = oci_parse($conn, $adminQuery);
        oci_bind_by_name($adminStmt, ':adminId', $adminId);
        oci_execute($adminStmt);
        $adminRow = oci_fetch_assoc($adminStmt);
        echo "Name: ". $adminRow['ACTUAL_NAME']. "<br>";
        echo "Email Address: ". $adminRow['EMAIL_ADDRESS']. "<br>";
        echo "Phone number: ". $adminRow['TELEPHONE_NUMBER']. "<br>";
        echo "Password: ". $adminRow['PASSWORD']. "<br>";

      	$oldDate = $adminRow['DATE_OF_BIRTH'];
      	$newDate = date("Y-m-d", strtotime($oldDate));
      	echo "Date of birth: ". $newDate. "<br>";
        $storeName = "SELECT * FROM Store WHERE Store.Admin_ID = :adminId";
        $store_stmt = oci_parse($conn, $storeName);
        oci_bind_by_name($store_stmt, ':adminId', $adminId);
        oci_execute($store_stmt);
        $storeRow = oci_fetch_assoc($store_stmt);
        echo "Store Name: ". $storeRow['STORE_NAME']. "<br>";
        echo "Store Description: ". $storeRow['STORE_DESCRIPTION']. "<br>";

        ?>
    </div>
</div>
</body>
</html>
